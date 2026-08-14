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

        $smsStatus = 'failed';

        // Get PhilSMS API token
        $apiToken = config('services.philsms.api_token');

        if (empty($apiToken)) {
            Log::error('PhilSMS API token is missing.');

        } else {

            try {

                // Normalize Philippine phone number
                $recipient = preg_replace('/\D+/', '', $phone);

                // 09171234567 -> 639171234567
                if (str_starts_with($recipient, '09')) {
                    $recipient = '63' . substr($recipient, 1);
                }

                // 9171234567 -> 639171234567
                elseif (
                    str_starts_with($recipient, '9')
                    && strlen($recipient) === 10
                ) {
                    $recipient = '63' . $recipient;
                }

                $response = Http::timeout(10)
                    ->withToken($apiToken)
                    ->acceptJson()
                    ->post(
                        'https://app.philsms.com/api/v3/sms/send',
                        [
                            'recipient' => $recipient,
                            'sender_id' => config(
                                'services.philsms.sender_id',
                                'HourWash'
                            ),
                            'type' => 'plain',
                            'message' => $message,
                        ]
                    );

                if ($response->successful()) {

                    Log::info('PhilSMS sent successfully', [
                        'phone' => $recipient,
                        'response' => $response->json(),
                    ]);

                    $smsStatus = 'sent';

                } else {

                    Log::error('PhilSMS API failed', [
                        'phone' => $recipient,
                        'http_status' => $response->status(),
                        'response' => $response->body(),
                    ]);

                    $smsStatus = 'failed';
                }

            } catch (\Throwable $e) {

                Log::error('PhilSMS exception', [
                    'phone' => $phone,
                    'error' => $e->getMessage(),
                ]);

                $smsStatus = 'failed';
            }
        }

        // Save SMS notification
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
                'order_id' => $order->id,
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}