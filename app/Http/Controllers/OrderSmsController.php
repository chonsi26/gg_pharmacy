<?php
// app/Http/Controllers/OrderSmsController.php — thin: check access, validate, call the service, answer.
namespace App\Http\Controllers;

use App\Exceptions\RelayRequestException;
use App\Models\Order;
use App\Services\RelaySmsClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class OrderSmsController extends Controller
{
    /** Staff send a short note about an order to the customer's stored phone number. */
    public function store(Request $request, Order $order, RelaySmsClient $relay): JsonResponse
    {
        Gate::authorize('send-sms', $order); // your own rule: who may text this customer
        $validated = $request->validate([
            'note' => ['required', 'string', 'max:300'],
            'request_id' => ['required', 'uuid'], // created once by the form, re-sent unchanged on a double click or retry
        ]);

        try {
            $sms = $relay->sendSms(
                to: $order->phone, // validated when the order was saved, never taken from this request
                message: "Order {$order->id}: {$validated['note']}",
                idempotencyKey: "order-{$order->id}-note-{$validated['request_id']}",
                reference: "order-{$order->id}",
            );
        } catch (RelayRequestException $e) {
            // Log the code and request ID only: no key, no phone number, no message text.
            Log::warning('Relay rejected an SMS', ['code' => $e->errorCode, 'status' => $e->status, 'requestId' => $e->requestId]);

            return response()->json(['message' => 'The SMS could not be sent.'], $e->isRetryable() ? 503 : 422);
        } catch (ConnectionException) {
            return response()->json(['message' => 'The SMS service did not answer. Please try again.'], 503);
        }

        $order->update(['sms_message_id' => $sms['message']['id']]);

        // 202: Relay accepted it for sending. It is not delivered yet.
        return response()->json(['queued' => true, 'smsMessageId' => $sms['message']['id']], 202);
    }

    /** Ask Relay what happened to the last SMS for this order. */
    public function show(Order $order, RelaySmsClient $relay): JsonResponse
    {
        Gate::authorize('send-sms', $order);
        abort_if($order->sms_message_id === null, 404);

        $message = $relay->getMessage($order->sms_message_id);

        return response()->json([
            'status' => $message['status'],           // for example SENT_TO_NETWORK or FAILED
            'label' => $message['statusLabel'],       // human-friendly
            'errorCode' => $message['error']['code'] ?? null,
        ]);
    }
}