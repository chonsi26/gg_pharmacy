<?php
// app/Services/RelaySmsClient.php — every request to Relay goes through this class.
namespace App\Services;

use App\Exceptions\RelayRequestException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class RelaySmsClient
{
    private string $baseUrl;

    private string $apiKey;

    public function __construct()
    {
        $baseUrl = config('services.relay.base_url');
        $apiKey = config('services.relay.api_key');
        if (! is_string($baseUrl) || $baseUrl === '' || ! is_string($apiKey) || $apiKey === '') {
            // Names the settings, never prints their values.
            throw new RuntimeException('Relay is not configured: set RELAY_BASE_URL and RELAY_API_KEY in .env, then see config/services.php.');
        }
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
    }

    /**
     * Submit one SMS. $idempotencyKey must come from YOUR business event (for example
     * "order-1001-confirmation") so a retry after a timeout never sends twice.
     *
     * @return array the "data" object: batchId, mode, status, message { id, to, status, segments, credits }
     *
     * @throws RelayRequestException when Relay answers 4xx/5xx
     * @throws \Illuminate\Http\Client\ConnectionException on a timeout or network error (retry with the same key)
     */
    public function sendSms(string $to, string $message, string $idempotencyKey, ?string $reference = null): array
    {
        $body = ['to' => $to, 'message' => $message];
        if ($reference !== null) {
            $body['metadata'] = ['reference' => $reference];
        }

        return $this->data($this->http()->withHeaders(['Idempotency-Key' => $idempotencyKey])->post('/api/v1/messages', $body));
    }

    /**
     * One text to many numbers. Use one key for the whole batch.
     *
     * @param  list<string>  $recipients
     */
    public function sendBulkSms(array $recipients, string $message, string $idempotencyKey): array
    {
        return $this->data($this->http()->withHeaders(['Idempotency-Key' => $idempotencyKey])->post('/api/v1/messages/bulk', [
            'recipients' => $recipients,
            'message' => $message,
        ]));
    }

    /** @return array id, mode, status, statusLabel, error, history, ... */
    public function getMessage(string $id): array
    {
        return $this->data($this->http()->get('/api/v1/messages/'.rawurlencode($id)));
    }

    private function http(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->withToken($this->apiKey)
            ->acceptJson()
            ->connectTimeout(5) // seconds to connect
            ->timeout(15);      // seconds for the whole request
    }

    private function data(Response $response): array
    {
        // 202 Accepted (send) and 200 OK (lookups) are both "successful".
        if ($response->failed()) {
            throw RelayRequestException::fromResponse($response);
        }

        return $response->json('data'); // Relay wraps every success in { data, requestId }
    }
}