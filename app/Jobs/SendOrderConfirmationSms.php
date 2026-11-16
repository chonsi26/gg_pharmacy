<?php
// app/Jobs/SendOrderConfirmationSms.php — create it with: php artisan make:job SendOrderConfirmationSms
// Dispatch it with: SendOrderConfirmationSms::dispatch($order);
namespace App\Jobs;

use App\Exceptions\RelayRequestException;
use App\Models\Order;
use App\Services\RelaySmsClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Backoff;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Support\Facades\Log;
use Throwable;

#[Tries(5)]
#[Backoff([10, 30, 60, 300])] // seconds before retry 1, 2, 3 and 4
class SendOrderConfirmationSms implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function handle(RelaySmsClient $relay): void
    {
        try {
            $sms = $relay->sendSms(
                to: $this->order->phone,
                message: "Your order {$this->order->id} is confirmed.",
                // Derived from the order, so EVERY retry of this job sends the same key and Relay never sends twice.
                idempotencyKey: "order-{$this->order->id}-confirmation",
                reference: "order-{$this->order->id}",
            );
        } catch (RelayRequestException $e) {
            if ($e->status === 429 && $e->retryAfterSeconds !== null) {
                $this->release($e->retryAfterSeconds); // Relay said how long to wait

                return;
            }
            if (! $e->isRetryable()) {
                $this->fail($e); // 402 out of credits, 422 bad number or text, 401/403 key problems

                return;
            }
            throw $e; // 5xx: the queue retries after the backoff
        }
        // A timeout or network error throws ConnectionException, which the queue also retries.

        // 202 Accepted: queued, NOT yet delivered. Store the ID to check the status later.
        $this->order->update(['sms_message_id' => $sms['message']['id']]);
    }

    /** Runs once, after the last attempt failed. */
    public function failed(?Throwable $exception): void
    {
        Log::error('Order confirmation SMS failed', ['order' => $this->order->id, 'error' => $exception?->getMessage()]);
    }
}