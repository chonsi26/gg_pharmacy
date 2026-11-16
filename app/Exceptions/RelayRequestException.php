<?php
// app/Exceptions/RelayRequestException.php — Relay answered with an error status.
namespace App\Exceptions;

use Illuminate\Http\Client\Response;
use RuntimeException;

class RelayRequestException extends RuntimeException
{
    public function __construct(
        public readonly int $status,
        public readonly string $errorCode,
        string $errorMessage,
        public readonly ?string $requestId = null,
        public readonly ?int $retryAfterSeconds = null,
        public readonly array $details = [],
    ) {
        // Safe to log: an error code, Relay's message and the request ID. Never the key.
        parent::__construct("{$errorCode}: {$errorMessage} (requestId ".($requestId ?? 'n/a').')');
    }

    public static function fromResponse(Response $response): self
    {
        $retryAfter = $response->header('Retry-After');

        return new self(
            $response->status(),
            $response->json('error.code') ?? 'UNKNOWN',
            $response->json('error.message') ?? $response->reason(),
            $response->json('requestId') ?? ($response->header('X-Request-Id') ?: null),
            is_numeric($retryAfter) ? (int) $retryAfter : null,
            $response->json('error.details') ?? [],
        );
    }

    /** 429 and 5xx: send the SAME request again later. Any other 4xx: fix the request, retrying will fail the same way. */
    public function isRetryable(): bool
    {
        return $this->status === 429 || $this->status >= 500;
    }
}