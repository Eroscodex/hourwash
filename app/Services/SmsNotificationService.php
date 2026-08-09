<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SmsNotification;
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
        $compTime = $order->estimated_completion ? \Carbon\Carbon::parse($order->estimated_completion)->format('M d, Y h:i A') : 'TBD';

        $message = "HourWash Alert: Hi {$custName}, your laundry Order #{$code} status is now {$statusStr}. Est Completion: {$compTime}.";

        if (! empty($customNote)) {
            $message .= " {$customNote}";
        }

        $message .= ' Track live: '.url("/laundry/track/{$code}");

        $smsStatus = 'sent';

        // 1. Send Real Physical SMS via Semaphore API if SEMAPHORE_API_KEY is configured in .env
        $apiKey = env('SEMAPHORE_API_KEY');
        if (! empty($apiKey)) {
            try {
                $response = Http::post('https://api.semaphore.co/api/v4/messages', [
                    'apikey' => $apiKey,
                    'number' => $phone,
                    'message' => $message,
                    'sendername' => env('SEMAPHORE_SENDER_NAME', 'HourWash'),
                ]);

                if ($response->failed()) {
                    Log::error("Semaphore SMS API failed for {$phone}: ".$response->body());
                    $smsStatus = 'failed';
                } else {
                    Log::info("Semaphore SMS sent to {$phone}: ".$response->body());
                }
            } catch (\Throwable $e) {
                Log::error("Semaphore SMS Exception for {$phone}: ".$e->getMessage());
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
            Log::error("SMS Notification Log Error for {$phone}: ".$e->getMessage());

            return null;
        }
    }
}
