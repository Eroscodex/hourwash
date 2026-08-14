<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SmsNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsNotificationService
{
    public static function sendOrderStatusSms(
        Order $order,
        string $customNote = ''
    ): ?SmsNotification {

        $phone = $order->customer?->phone;

        if (empty($phone)) {
            return null;
        }

        $statusStr = strtoupper(
            str_replace('_', ' ', $order->order_status)
        );

        $custName = $order->customer?->name ?? 'Customer';
        $code = $order->order_number;

        $compTime = $order->estimated_completion
            ? Carbon::parse($order->estimated_completion)
                ->format('M d, Y h:i A')
            : 'TBD';

        $message = "HourWash Alert: Hi {$custName}, your laundry Order #{$code} status is now {$statusStr}. Est Completion: {$compTime}.";

        if (!empty($customNote)) {
            $message .= " {$customNote}";
        }

        $message .= ' Track live: ' . url("/laundry/track/{$code}");

        $smsStatus = 'failed';

        /*
        |--------------------------------------------------------------------------
        | PhilSMS
        |--------------------------------------------------------------------------
        */

        $apiToken = config('services.philsms.api_token');
        $senderId = config('services.philsms.sender_id', 'PhilSMS');

        if (!empty($apiToken)) {
            try {

                $recipient = self::normalizePhPhone($phone);

                if ($recipient === null) {
                    throw new \RuntimeException("Unable to normalize phone number: {$phone}");
                }

                Log::info('Sending SMS through PhilSMS', [
                    'recipient' => $recipient,
                    'sender_id' => $senderId,
                ]);

                $response = Http::timeout(10)
                    ->withToken($apiToken)
                    ->acceptJson()
                    ->asJson()
                    ->post(
                        'https://app.philsms.com/api/v3/sms/send',
                        [
                            'recipient' => $recipient,
                            'sender_id' => $senderId,
                            'type' => 'plain',
                            'message' => $message,
                        ]
                    );

                /*
                 * Log complete PhilSMS response
                 */
                Log::info('PhilSMS API response', [
                    'http_status' => $response->status(),
                    'response' => $response->json(),
                ]);

                $data = $response->json();

                if (
                    $response->successful() &&
                    isset($data['status']) &&
                    $data['status'] === 'success'
                ) {
                    $smsStatus = 'sent';

                    Log::info('PhilSMS SMS successfully dispatched', [
                        'recipient' => $recipient,
                        'data' => $data['data'] ?? null,
                    ]);
                } else {
                    $smsStatus = 'failed';

                    Log::error('PhilSMS SMS failed', [
                        'recipient' => $recipient,
                        'http_status' => $response->status(),
                        'response' => $response->body(),
                    ]);
                }

            } catch (\Throwable $e) {

                $smsStatus = 'failed';

                Log::error('PhilSMS exception', [
                    'phone' => $phone,
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            Log::error('PHILSMS_API_TOKEN is not configured.');
        }

        /*
        |--------------------------------------------------------------------------
        | Save SMS Outbox
        |--------------------------------------------------------------------------
        */

        try {

            return SmsNotification::create([
                'order_id' => $order->id,
                'user_id' => $order->customer_id,
                'phone' => $phone,
                'message' => $message,
                'status' => $smsStatus,
            ]);

        } catch (\Throwable $e) {

            Log::error('SMS Notification database error', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Normalize a Philippine mobile number to PhilSMS's expected
     * 63XXXXXXXXXX format (12 digits, no '+', no leading '0').
     *
     * Accepts input in any of these forms (spaces/dashes/parens allowed):
     *   09175012581     (11 digits, local format)
     *   +639175012581   (with country code and plus sign)
     *   639175012581    (already correct)
     *   9175012581      (10 digits, no leading 0 or 63)
     *
     * Returns null if the input doesn't match any recognized PH mobile format.
     */
    public static function normalizePhPhone(string $phone): ?string
    {
        // Strip everything except digits (this also removes any '+').
        $digits = preg_replace('/\D+/', '', $phone);

        // Already in correct format: 63 + 10 digits = 12 digits total.
        if (strlen($digits) === 12 && str_starts_with($digits, '63')) {
            return $digits;
        }

        // Local format with leading 0: 0 + 10 digits = 11 digits total.
        if (strlen($digits) === 11 && str_starts_with($digits, '09')) {
            return '63' . substr($digits, 1);
        }

        // Bare 10-digit mobile number without prefix, e.g. 9175012581.
        if (strlen($digits) === 10 && str_starts_with($digits, '9')) {
            return '63' . $digits;
        }

        // Doesn't match any known PH mobile format.
        return null;
    }
}