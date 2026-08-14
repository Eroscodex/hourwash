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
        $compTime = $order->estimated_completion ? Carbon::parse($order->estimated_completion)->format('M d, Y h:i A') : 'TBD';

        $message = "HourWash Alert: Hi {$custName}, your laundry Order #{$code} status is now {$statusStr}. Est Completion: {$compTime}.";

        if (! empty($customNote)) {
            $message .= " {$customNote}";
        }

        $message .= ' Track live: '.url("/laundry/track/{$code}");

        $smsStatus = 'sent';

        // 1. Send Real Physical SMS via PhilSMS API if PHILSMS_API_KEY is configured in .env
        // Send SMS via PhilSMS API
        $apiToken = config('services.philsms.api_token');

        if (!empty($apiToken)) {
            try {
            // Convert Philippine number to international format
            $recipient = preg_replace('/\D+/', '', $phone);

            if (str_starts_with($recipient, '09')) {
                $recipient = '63' . substr($recipient, 1);
            } elseif (str_starts_with($recipient, '+63')) {
                $recipient = substr($recipient, 1);
            }

            $response = Http::timeout(10)
                ->withToken($apiToken)
                ->acceptJson()
                ->post('https://app.philsms.com/api/v3/sms/send', [
                    'recipient' => $recipient,
                    'sender_id' => config('services.philsms.sender_id', 'HourWash'),
                    'type' => 'plain',
                    'message' => $message,
                ]);

            if ($response->successful()) {
                Log::info('PhilSMS sent successfully', [
                    'phone' => $recipient,
                    'response' => $response->json(),
                ]);

                $smsStatus = 'sent';
            } else {
                Log::error('PhilSMS API failed', [
                    'phone' => $recipient,
                    'status' => $response->status(),
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

        // 2. Save SMS Notification to Database
        $smsNotification = SmsNotification::create([
            'order_id' => $order->id,
            'recipient_phone' => $phone,
            'message' => $message,
            'status' => $smsStatus,
        ]);

        return $smsNotification;
    }
}
