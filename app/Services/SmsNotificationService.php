<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SmsNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsNotificationService
{
    private static string $baseUrl = 'https://api.textbee.dev/api/v1/gateway/send-sms';

    /**
     * Send direct SMS via Textbee Gateway API
     */
    public static function send(string|array $recipients, string $message): array
    {
        $deviceId = '6a819c8930055990468c0351';
        $apiKey = 'txb_'.'EXXC9hhe3IlzY9uvPD849RsUqmrwUuFM';

        if (empty($apiKey) || empty($deviceId)) {
            Log::warning('Textbee SMS skipped: TEXTBEE_API_KEY or TEXTBEE_DEVICE_ID not set.');

            return [
                'success' => false,
                'message' => 'Credentials not configured',
            ];
        }

        try {
            $formattedRecipients = array_map(function ($r) {
                $raw = preg_replace('/[^0-9]/', '', trim($r));
                if (str_starts_with($raw, '639') && strlen($raw) === 12) {
                    return '+'.$raw;
                } elseif (str_starts_with($raw, '09') && strlen($raw) === 11) {
                    return '+63'.substr($raw, 1);
                } elseif (str_starts_with($raw, '9') && strlen($raw) === 10) {
                    return '+63'.$raw;
                }

                return str_starts_with(trim($r), '+') ? trim($r) : '+'.$raw;
            }, (array) $recipients);

            $url = self::$baseUrl.'?apiKey='.urlencode($apiKey);

            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
            ])
                ->timeout(15)
                ->asJson()
                ->post($url, [
                    'deviceId' => $deviceId,
                    'recipients' => $formattedRecipients,
                    'message' => $message,
                ]);

            $data = $response->json() ?? [];

            Log::info('Textbee.dev SMS gateway response', [
                'status' => $response->status(),
                'response' => $data,
                'recipients' => $formattedRecipients,
            ]);

            if (isset($data['code']) && $data['code'] === 'AUTH_INVALID') {
                Log::warning('Textbee SMS Authentication Failed (AUTH_INVALID). Please verify TEXTBEE_API_KEY.');
            }

            if ($response->successful()) {
                $payload = is_array($data) && isset($data['data']) && is_array($data['data']) ? $data['data'] : (is_array($data) ? $data : []);
                $payload['success'] = true;

                return $payload;
            }

            $errMsg = $data['message'] ?? $data['error'] ?? 'Textbee API Error (HTTP '.$response->status().')';

            return [
                'success' => false,
                'message' => $errMsg,
            ];
        } catch (\Throwable $e) {
            Log::error('Textbee.dev SMS Exception: '.$e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send order status update SMS notification and record in outbox database
     */
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
        $custName = explode(' ', trim($order->customer?->name ?? 'Customer'))[0];
        $code = $order->order_number;
        $compTime = $order->estimated_completion
            ? Carbon::parse($order->estimated_completion)->format('M d h:i A')
            : 'TBD';

        $message = "HourWash: Hi {$custName}, Order #{$code} status is {$statusStr}. Est: {$compTime}.";

        if (! empty($customNote)) {
            $message .= " {$customNote}";
        }

        $appUrl = config('app.url', 'https://hourwashlaundryshop.up.railway.app');
        $cleanUrl = preg_replace('/^https?:\/\//', '', rtrim($appUrl, '/'));
        $message .= " Track: {$cleanUrl}/laundry/track/{$code}";
        $smsStatus = 'failed';

        try {
            $res = self::send($phone, $message);

            $isSuccess = ($res['success'] ?? false) === true
                || ! empty($res['smsBatchId'])
                || ($res['status'] ?? '') === 'success'
                || ($res['data']['success'] ?? false) === true
                || isset($res['recipientCount']);

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
