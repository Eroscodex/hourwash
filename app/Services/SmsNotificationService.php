<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SmsNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsNotificationService
{
    public static function sendOrderStatusSms(Order $order, string $customNote = ''): ?SmsNotification
    {
        $phone = $order->customer?->phone;

        if (empty($phone)) {
            return null;
        }

        $statusStr = strtoupper(str_replace('_', ' ', $order->order_status));
        $custName = $order->customer?->name ?? 'Customer';
        $code = $order->order_number;
        $compTime = $order->estimated_completion
            ? Carbon::parse($order->estimated_completion)->format('M d, Y h:i A')
            : 'TBD';

        $message = "HourWash Alert: Hi {$custName}, your laundry Order #{$code} status is now {$statusStr}. Est Completion: {$compTime}.";

        if (!empty($customNote)) {
            $message .= " {$customNote}";
        }

        $message .= ' Track live: ' . url("/laundry/track/{$code}");

        $smsStatus = 'sent';

        // Send SMS via PhilSMS API
        $apiKey = env('PHILSMS_API_TOKEN');

        if (!empty($apiKey)) {
            try {
                $recipient = preg_replace('/\D+/', '', $phone);

                // Convert 09XXXXXXXXX to 639XXXXXXXXX
                if (str_starts_with($recipient, '09')) {
                    $recipient = '63' . substr($recipient, 1);
                }

                $response = Http::timeout(10)
                    ->withToken($apiKey)
                    ->acceptJson()
                    ->post('https://dashboard.philsms.com/api/v3/sms/send', [
                        'recipient' => $recipient,
                        'sender_id' => env('PHILSMS_SENDER_ID', 'PhilSMS'),
                        'type' => 'plain',
                        'message' => $message,
                    ]);

                if ($response->failed()) {
                    Log::error("PhilSMS SMS API failed for {$recipient}: " . $response->body());
                    $smsStatus = 'failed';
                } else {
                    Log::info("PhilSMS SMS sent to {$recipient}: " . $response->body());
                }

            } catch (\Throwable $e) {
                Log::error("PhilSMS SMS Exception for {$phone}: " . $e->getMessage());
                $smsStatus = 'failed';
            }
        }

        try {
            // Record in Database Outbox Log
            $sms = SmsNotification::create([
                'order_id' => $order->id,
                'user_id' => $order->customer_id,
                'phone' => $phone,
                'message' => $message,
                'status' => $smsStatus,
            ]);

            return $sms;

        } catch (\Throwable $e) {
            Log::error("SMS Notification Log Error for {$phone}: " . $e->getMessage());

            return null;
        }
    }
}