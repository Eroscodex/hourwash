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

                // Remove spaces, -, parentheses, etc.
                $recipient = preg_replace('/\D+/', '', $phone);

                /*
                 * Philippine number conversion
                 *
                 * 09175012581
                 *       ↓
                 * 639175012581
                 */
                if (str_starts_with($recipient, '09')) {
                    $recipient = '63' . substr($recipient, 1);
                } elseif (str_starts_with($recipient, '+63')) {
                    $recipient = substr($recipient, 1);
                } elseif (
                    str_starts_with($recipient, '9') &&
                    strlen($recipient) === 10
                ) {
                    $recipient = '63' . $recipient;
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
                        'https://dashboard.philsms.com/api/v3/sms/send',
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
}