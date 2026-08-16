<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SmsNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SmsNotificationService
{
    public static function sendOrderStatusSms(
        Order $order,
        string $customNote = ''
    ): ?SmsNotification {

        $phone = $order->customer?->phone ?? $order->customer?->customerProfile?->phone;

        if (empty($phone) && $order->customer_id) {
            $phone = User::find($order->customer_id)?->phone;
        }

        if (empty($phone)) {
            Log::warning('SMS skipped: Customer has no phone number.', [
                'order_id' => $order->id,
            ]);

            return null;
        }

        $statusStr = strtoupper(str_replace('_', ' ', $order->order_status));
        $custName = $order->customer?->name ?? 'Customer';
        $code = $order->order_number;
        $compTime = $order->estimated_completion
            ? Carbon::parse($order->estimated_completion)->format('M d, Y h:i A')
            : 'TBD';

        $message = "HourWash Alert: Hi {$custName}, your laundry Order #{$code} status is now {$statusStr}. Est Completion: {$compTime}.";

        if (! empty($customNote)) {
            $message .= " {$customNote}";
        }

        $message .= ' Track live: '.url("/laundry/track/{$code}");
        $smsStatus = 'failed';

        try {
            $smsService = app(SmsService::class);
            $res = $smsService->send($phone, $message);

            $isSuccess = ($res['success'] ?? false) === true || ! empty($res['smsBatchId']) || ($res['status'] ?? '') === 'success';

            if ($isSuccess) {
                $smsStatus = 'sent';
                Log::info('Textbee SMS dispatched successfully.', [
                    'order_id' => $order->id,
                    'batch_id' => $res['smsBatchId'] ?? null,
                ]);
            } else {
                $smsStatus = 'failed';
                Log::error('Textbee SMS dispatch failed.', [
                    'response' => $res,
                    'order_id' => $order->id,
                ]);
            }
        } catch (\Throwable $e) {
            $smsStatus = 'failed';
            Log::error('SMS Exception: '.$e->getMessage(), [
                'phone' => $phone,
                'order_id' => $order->id,
            ]);
        }

        try {
            return SmsNotification::create([
                'order_id' => $order->id,
                'user_id' => $order->customer_id,
                'phone' => $phone,
                'message' => $message,
                'status' => $smsStatus,
            ]);
        } catch (\Throwable $e) {
            Log::error('SMS Notification database record error: '.$e->getMessage(), [
                'phone' => $phone,
                'order_id' => $order->id,
            ]);

            return null;
        }
    }
}
