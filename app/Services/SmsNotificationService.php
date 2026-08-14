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

        /*
        |--------------------------------------------------------------------------
        | Get Customer Phone
        |--------------------------------------------------------------------------
        */

        $phone = $order->customer?->phone;

        if (empty($phone)) {
            Log::warning('SMS not sent: customer has no phone number.', [
                'order_id' => $order->id,
            ]);

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Order Information
        |--------------------------------------------------------------------------
        */

        $statusStr = strtoupper(
            str_replace('_', ' ', $order->order_status)
        );

        $custName = $order->customer?->name ?? 'Customer';
        $code = $order->order_number;

        $compTime = $order->estimated_completion
            ? Carbon::parse($order->estimated_completion)
                ->format('M d, Y h:i A')
            : 'TBD';

        /*
        |--------------------------------------------------------------------------
        | SMS Message
        |--------------------------------------------------------------------------
        */

        $message = "HourWash Alert: Hi {$custName}, your laundry Order #{$code} status is now {$statusStr}. Est Completion: {$compTime}.";

        if (!empty($customNote)) {
            $message .= " {$customNote}";
        }

        $message .= ' Track live: ' . url("/laundry/track/{$code}");

        /*
        |--------------------------------------------------------------------------
        | Default SMS Status
        |--------------------------------------------------------------------------
        */

        $smsStatus = 'failed';

        /*
        |--------------------------------------------------------------------------
        | PhilSMS Configuration
        |--------------------------------------------------------------------------
        */

        $apiToken = config('services.philsms.api_token');
        $senderId = config(
            'services.philsms.sender_id',
            'PhilSMS'
        );

        /*
        |--------------------------------------------------------------------------
        | Check API Token
        |--------------------------------------------------------------------------
        */

        if (empty($apiToken)) {

            Log::error('PhilSMS API token is not configured.');

        } else {

            try {

                /*
                |--------------------------------------------------------------------------
                | Normalize Philippine Phone Number
                |--------------------------------------------------------------------------
                |
                | Supported:
                |
                | 09171234567
                | 639171234567
                | +639171234567
                | 9171234567
                |
                | Result:
                |
                | +639171234567
                |
                */

                $recipient = trim($phone);

                // Remove spaces, hyphens, parentheses, etc.
                $recipient = preg_replace('/[^0-9+]/', '', $recipient);

                if (str_starts_with($recipient, '09')) {

                    // 09171234567
                    // ↓
                    // +639171234567

                    $recipient = '+63' . substr($recipient, 1);

                } elseif (str_starts_with($recipient, '639')) {

                    // 639171234567
                    // ↓
                    // +639171234567

                    $recipient = '+' . $recipient;

                } elseif (
                    str_starts_with($recipient, '+639')
                ) {

                    // Already +63 format
                    // +639171234567

                    $recipient = $recipient;

                } elseif (
                    str_starts_with($recipient, '9') &&
                    strlen($recipient) === 10
                ) {

                    // 9171234567
                    // ↓
                    // +639171234567

                    $recipient = '+63' . $recipient;

                } else {

                    Log::error('Invalid Philippine mobile number.', [
                        'original_phone' => $phone,
                        'normalized_phone' => $recipient,
                        'order_id' => $order->id,
                    ]);

                    $recipient = null;
                }

                /*
                |--------------------------------------------------------------------------
                | Validate Final Number
                |--------------------------------------------------------------------------
                */

                if (
                    !empty($recipient) &&
                    preg_match('/^\+639[0-9]{9}$/', $recipient)
                ) {

                    Log::info('Sending SMS through PhilSMS.', [
                        'recipient' => $recipient,
                        'sender_id' => $senderId,
                        'order_id' => $order->id,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | PhilSMS API Request
                    |--------------------------------------------------------------------------
                    */

                    $response = Http::timeout(20)
                        ->withHeaders([
                            'Authorization' => 'Bearer ' . $apiToken,
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ])
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
                    |--------------------------------------------------------------------------
                    | API Response Logging
                    |--------------------------------------------------------------------------
                    */

                    $data = $response->json();

                    Log::info('PhilSMS API response.', [
                        'http_status' => $response->status(),
                        'response' => $data,
                        'body' => $response->body(),
                        'recipient' => $recipient,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Check API Result
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $response->successful() &&
                        ($data['status'] ?? null) === 'success'
                    ) {

                        $smsStatus = 'sent';

                        Log::info(
                            'PhilSMS SMS successfully dispatched.',
                            [
                                'recipient' => $recipient,
                                'order_id' => $order->id,
                                'message_id' =>
                                    $data['data']['uid'] ?? null,
                            ]
                        );

                    } else {

                        $smsStatus = 'failed';

                        Log::error(
                            'PhilSMS SMS failed.',
                            [
                                'recipient' => $recipient,
                                'http_status' => $response->status(),
                                'message' =>
                                    $data['message'] ??
                                    'Unknown PhilSMS error',
                                'response' => $response->body(),
                                'order_id' => $order->id,
                            ]
                        );
                    }

                } else {

                    $smsStatus = 'failed';

                    Log::error(
                        'PhilSMS SMS failed because phone number is invalid.',
                        [
                            'original_phone' => $phone,
                            'normalized_phone' => $recipient,
                            'order_id' => $order->id,
                        ]
                    );
                }

            } catch (\Throwable $e) {

                $smsStatus = 'failed';

                Log::error('PhilSMS exception.', [
                    'phone' => $phone,
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Save SMS Notification / Outbox
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

            Log::error(
                'SMS Notification database error.',
                [
                    'phone' => $phone,
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]
            );

            return null;
        }
    }
}
