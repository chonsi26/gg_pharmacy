<?php
// tests/Feature/RelaySmsClientTest.php — run with: php artisan test
// Http::fake() answers instead of Relay and preventStrayRequests() blocks everything else,
// so these tests never reach the network and never send an SMS.
namespace Tests\Feature;

use App\Exceptions\RelayRequestException;
use App\Services\RelaySmsClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RelaySmsClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // A fake key: never put a real key in tests.
        config(['services.relay.base_url' => 'https://relay.test', 'services.relay.api_key' => 'relay_test_fake_key']);
        Http::preventStrayRequests();
    }

    public function test_it_sends_the_request_relay_expects(): void
    {
        Http::fake(['relay.test/*' => Http::response(['data' => ['message' => ['id' => 'msg_1', 'status' => 'ACCEPTED']], 'requestId' => 'req_1'], 202)]);

        $data = app(RelaySmsClient::class)->sendSms('09170000000', 'Hi', 'order-1-confirmation');

        $this->assertSame('msg_1', $data['message']['id']);
        // assertSent only reports true/false, so a failure never prints the key.
        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && $request->url() === 'https://relay.test/api/v1/messages'
            && $request->hasHeader('Authorization', 'Bearer relay_test_fake_key')
            && $request->hasHeader('Accept', 'application/json')
            && $request->hasHeader('Idempotency-Key', 'order-1-confirmation')
            && $request->data() === ['to' => '09170000000', 'message' => 'Hi']);
    }

    public function test_a_validation_error_becomes_a_relay_exception(): void
    {
        Http::fake(['relay.test/*' => Http::response(['error' => ['code' => 'INVALID_PHONE_NUMBER', 'message' => 'Invalid number', 'details' => ['errors' => []]], 'requestId' => 'req_2'], 422)]);

        try {
            app(RelaySmsClient::class)->sendSms('123', 'Hi', 'order-1-confirmation');
            $this->fail('Expected a RelayRequestException');
        } catch (RelayRequestException $e) {
            $this->assertSame(422, $e->status);
            $this->assertSame('INVALID_PHONE_NUMBER', $e->errorCode);
            $this->assertSame('req_2', $e->requestId);
            $this->assertFalse($e->isRetryable());
        }
    }

    public function test_insufficient_credits_is_not_retryable(): void
    {
        Http::fake(['relay.test/*' => Http::response(['error' => ['code' => 'INSUFFICIENT_CREDITS', 'message' => 'Not enough credits'], 'requestId' => 'req_3'], 402)]);

        try {
            app(RelaySmsClient::class)->sendSms('09170000000', 'Hi', 'order-1-confirmation');
            $this->fail('Expected a RelayRequestException');
        } catch (RelayRequestException $e) {
            $this->assertSame('INSUFFICIENT_CREDITS', $e->errorCode);
            $this->assertFalse($e->isRetryable());
        }
    }

    public function test_a_timeout_throws_a_connection_exception(): void
    {
        Http::fake(['relay.test/*' => Http::failedConnection()]);

        $this->expectException(ConnectionException::class);
        app(RelaySmsClient::class)->sendSms('09170000000', 'Hi', 'order-1-confirmation');
    }

    public function test_missing_configuration_fails_without_revealing_values(): void
    {
        config(['services.relay.api_key' => null]);

        $this->expectExceptionMessage('Relay is not configured');
        app(RelaySmsClient::class);
    }
}