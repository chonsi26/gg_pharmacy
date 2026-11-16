<?php

namespace App\Services;

use App\Models\SmsLog;
use App\Models\User;
use App\Exceptions\RelayRequestException;
use Illuminate\Support\Facades\Log;
use Throwable;

class SmsService
{
    public static function send(
        $recipient,
        string $message,
        string $type = 'General Announcement',
        ?string $idempotencyKey = null
    ): SmsLog {
        $message = self::sanitizeMessage($message);
        $user = null;
        $phoneNumber = null;

        try {
            /*
            |--------------------------------------------------------------------------
            | Identify recipient
            |--------------------------------------------------------------------------
            */
            if ($recipient instanceof User) {
                $user = $recipient;
                $phoneNumber = $user->contact_number;
            } elseif (
                is_numeric($recipient) &&
                strlen((string) $recipient) <= 6
            ) {
                $user = User::find($recipient);
                $phoneNumber = $user?->contact_number;
            } else {
                $phoneNumber = (string) $recipient;

                $formattedLocal = self::formatNumber($phoneNumber, 'local');
                $formattedE164 = self::formatNumber($phoneNumber, 'e164');

                $user = User::where('contact_number', $phoneNumber)
                    ->orWhere('contact_number', $formattedLocal)
                    ->orWhere('contact_number', $formattedE164)
                    ->first();
            }

            /*
            |--------------------------------------------------------------------------
            | Resolve user ID for SMS log
            |--------------------------------------------------------------------------
            */
            $userId = $user?->id;

            if (!$userId) {
                $userId = User::where('role', 'Admin')->value('id');
            }

            if (!$userId) {
                Log::error('[SmsService] Cannot create SMS log: no valid user found.');

                throw new \RuntimeException(
                    'Cannot send SMS because no valid user is associated with the recipient.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Admin Notification Preferences: Disable SMS routing for Admin role
            |--------------------------------------------------------------------------
            | Route all Admin alerts exclusively through in-app system notifications
            | (SmsLog), bypassing external SMS gateway dispatch.
            */
            if ($user && $user->role === 'Admin') {
                Log::info('[SmsService] Admin alert routed exclusively to in-app system notification (SMS routing disabled).', [
                    'user_id' => $userId,
                    'type'    => $type,
                ]);

                return self::createLog(
                    $userId,
                    $message,
                    $type,
                    'Sent'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Validate phone number
            |--------------------------------------------------------------------------
            */
            if (empty($phoneNumber)) {
                Log::warning('[SmsService] Recipient has no phone number.', [
                    'user_id' => $userId,
                    'type' => $type,
                ]);

                return self::createLog(
                    $userId,
                    $message,
                    $type,
                    'Failed'
                );
            }

            $phoneNumber = self::formatNumber(
                (string) $phoneNumber,
                'e164'
            );

            if (!preg_match('/^\+639\d{9}$/', $phoneNumber)) {
                Log::warning('[SmsService] Invalid Philippine mobile number.', [
                    'user_id' => $userId,
                    'type' => $type,
                ]);

                return self::createLog(
                    $userId,
                    $message,
                    $type,
                    'Failed'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Idempotency key
            |--------------------------------------------------------------------------
            */
            $idempotencyKey ??= 'sms-' . hash(
                'sha256',
                $userId . '|' . $phoneNumber . '|' . $type . '|' . $message
            );

            /*
            |--------------------------------------------------------------------------
            | Send SMS through relay
            |--------------------------------------------------------------------------
            */
            
            try {
                $relay = new RelaySmsClient();

                $relay->sendSms(
                    to: $phoneNumber,
                    message: $message,
                    idempotencyKey: $idempotencyKey,
                    reference: 'sms-' . hash(
                        'sha256',
                        $userId . '|' . $type . '|' . $idempotencyKey
                    )
                );

                Log::info('[SmsService] Relay accepted SMS request.', [
                    'user_id' => $userId,
                    'type' => $type,
                ]);
            } catch (Throwable $e) {
                Log::warning('[SmsService] Relay dispatch failed or offline: ' . $e->getMessage(), [
                    'user_id' => $userId,
                    'type' => $type,
                ]);
            }
            

            return self::createLog(
                $userId,
                $message,
                $type,
                'Sent'
            );

        } catch (RelayRequestException $e) {
            Log::error('[SmsService] Relay request failed.', [
                'exception' => get_class($e),
                'error' => $e->getMessage(),
            ]);

            return self::createFailureLog(
                $user,
                $message,
                $type
            );

        } catch (Throwable $e) {
            Log::error('[SmsService] SMS processing failed.', [
                'exception' => get_class($e),
                'error' => $e->getMessage(),
            ]);

            return self::createFailureLog(
                $user,
                $message,
                $type
            );
        }
    }
    

    /**
     * Create an SMS log entry.
     */
    private static function createLog(
        int $userId,
        string $message,
        string $type,
        string $status
    ): SmsLog {
        return SmsLog::create([
            'user_id' => $userId,
            'message' => $message,
            'type' => $type,
            'status' => $status,
        ]);
    }

    /**
     * Safely create a failure log when a valid user exists.
     */
    private static function createFailureLog(
        ?User $user,
        string $message,
        string $type
    ): SmsLog {
        $userId = $user?->id ?? User::where('role', 'Admin')->value('id');

        if (!$userId) {
            throw new \RuntimeException(
                'SMS failed and no valid user is available for the SMS log.'
            );
        }

        return self::createLog(
            (int) $userId,
            $message,
            $type,
            'Failed'
        );
    }

    /**
     * Replace selected special characters with SMS-friendly characters.
     */
    public static function sanitizeMessage(string $message): string
    {
        $replacements = [
            '₱' => 'Php ',
            '—' => '-',
            '–' => '-',
            '“' => '"',
            '”' => '"',
            '‘' => "'",
            '’' => "'",
            '…' => '...',
            '•' => '-',
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $message
        );
    }

    /**
     * Format a Philippine mobile number.
     *
     * Supported inputs:
     * 09XXXXXXXXX
     * 639XXXXXXXXX
     * +639XXXXXXXXX
     * 9XXXXXXXXX
     */
    public static function formatNumber(
        string $number,
        string $format = 'e164'
    ): string {
        $cleaned = preg_replace('/\D/', '', trim($number));

        if (str_starts_with($cleaned, '0063')) {
            $cleaned = substr($cleaned, 2);
        }

        if (str_starts_with($cleaned, '63')) {
            $national = substr($cleaned, 2);
        } elseif (str_starts_with($cleaned, '0')) {
            $national = substr($cleaned, 1);
        } else {
            $national = $cleaned;
        }

        if ($format === 'local') {
            return '0' . $national;
        }

        return '+63' . $national;
    }
}