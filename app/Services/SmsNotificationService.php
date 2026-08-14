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
            Log::warning('SMS not sent: customer has no phone number', [
                'order_id' => $order->id,
            ]);

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

        // Default to failed. Only mark as sent after PhilSMS confirms success.
        $smsStatus = 'failed';

        $apiToken = config('services.philsms.api_token');

        if (empty($apiToken)) {
            Log::error('PhilSMS API token is missing.', [
                'order_id' => $order->id,
            ]);
        } else {
            try {
                /*
                 * Convert Philippine phone number to international format.
                 *
                 * 09171234567  -> 639171234567
                 * 9171234567   -> 639171234567
                 * +639171234567 -> 639171234567
                 */

                $recipient = preg_replace('/\D+/', '', $phone);

                if (str_starts_with($recipient, '09')) {
                    $recipient = '63' . substr($recipient, 1);
                } elseif (
                    str_starts_with($recipient, '9')
                    && strlen($recipient) === 10
                ) {
                    $recipient = '63' . $recipient;
                }

                // Validate Philippine mobile number
                if (
                    !str_starts_with($recipient, '639')
                    || strlen($recipient) !== 12
                ) {
                    Log::error('Invalid Philippine phone number for PhilSMS.', [
                        'phone' => $phone,
                        'recipient' => $recipient,
                        'order_id' => $order->id,
                    ]);
                } else {
                    $senderId = config(
                        'services.philsms.sender_id',
                        'PhilSMS'
                    );

                    $response = Http::timeout(10)
                        ->withToken($apiToken)
                        ->acceptJson()
                        ->post(
                            'https://app.philsms.com/api/v3/sms/send',
                            [
                                'recipient' => $recipient,
                                'sender_id' => $senderId,
                                'type' => 'plain',
                                'message' => $message,
                            ]
                        );

                    if ($response->successful()) {
                        Log::info('PhilSMS SMS sent successfully.', [
                            'order_id' => $order->id,
                            'phone' => $recipient,
                            'sender_id' => $senderId,
                            'response' => $response->json(),
                        ]);

                        $smsStatus = 'sent';
                    } else {
                        Log::error('PhilSMS API failed.', [
                            'order_id' => $order->id,
                            'phone' => $recipient,
                            'sender_id' => $senderId,
                            'http_status' => $response->status(),
                            'response' => $response->body(),
                        ]);

                        $smsStatus = 'failed';
                    }
                }
            } catch (\Throwable $e) {
                Log::error('PhilSMS exception.', [
                    'order_id' => $order->id,
                    'phone' => $phone,
                    'error' => $e->getMessage(),
                ]);

                $smsStatus = 'failed';
            }
        }

        /*
         * Save SMS notification to database.
         *
         * IMPORTANT:
         * Your database column is "phone", NOT "recipient_phone".
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
            Log::error('SMS notification database error.', [
                'order_id' => $order->id,
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}