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
            Log::warning('SMS not sent: customer has no phone number.', [
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

        $philsmsToken = config('services.philsms.api_token');
        $philsmsSenderId = config('services.philsms.sender_id', 'PhilSMS');

        $textbeeApiKey = config('services.textbee.api_key');
        $textbeeDeviceId = config('services.textbee.device_id');

        if (empty($philsmsToken) && (empty($textbeeApiKey) || empty($textbeeDeviceId))) {
            Log::error('No SMS gateway provider (Textbee.dev or PhilSMS) is configured in environment.');
        } else {
            try {
                $recipient = trim($phone);
                $recipient = preg_replace('/[^0-9+]/', '', $recipient);

                if (str_starts_with($recipient, '09')) {
                    $recipient = '63'.substr($recipient, 1);
                } elseif (str_starts_with($recipient, '+639')) {
                    $recipient = substr($recipient, 1);
                } elseif (str_starts_with($recipient, '639') && strlen($recipient) === 12) {
                    $recipient = $recipient;
                } elseif (str_starts_with($recipient, '9') && strlen($recipient) === 10) {
                    $recipient = '63'.$recipient;
                } else {
                    Log::error('Invalid Philippine mobile number format.', [
                        'original_phone' => $phone,
                        'normalized_phone' => $recipient,
                        'order_id' => $order->id,
                    ]);
                    $recipient = null;
                }

                if (! empty($recipient) && preg_match('/^639[0-9]{9}$/', $recipient)) {

                    // 1. Textbee.dev Gateway Provider (Primary if configured)
                    if (! empty($textbeeApiKey) && ! empty($textbeeDeviceId)) {
                        Log::info('Sending SMS through Textbee.dev Gateway.', [
                            'recipient' => '+'.$recipient,
                            'device_id' => $textbeeDeviceId,
                            'order_id' => $order->id,
                        ]);

                        $response = Http::timeout(20)
                            ->withHeaders([
                                'x-api-key' => $textbeeApiKey,
                            ])
                            ->acceptJson()
                            ->asJson()
                            ->post("https://api.textbee.dev/api/v1/gateway/devices/{$textbeeDeviceId}/send-sms", [
                                'recipients' => ['+'.$recipient],
                                'message' => $message,
                            ]);

                        $data = $response->json();

                        Log::info('Textbee.dev API response.', [
                            'http_status' => $response->status(),
                            'response' => $data,
                            'order_id' => $order->id,
                        ]);

                        $isTextbeeSuccess = $response->successful() && (
                            ($data['data']['success'] ?? false) === true ||
                            ($data['success'] ?? false) === true ||
                            ! empty($data['data']['smsBatchId']) ||
                            ($data['status'] ?? '') === 'success'
                        );

                        if ($isTextbeeSuccess) {
                            $smsStatus = 'sent';
                            Log::info('Textbee.dev SMS successfully dispatched.', ['order_id' => $order->id]);
                        } else {
                            $smsStatus = 'failed';
                            Log::error('Textbee.dev SMS failed.', ['response' => $response->body(), 'order_id' => $order->id]);
                        }

                        // 2. PhilSMS Gateway Provider
                    } elseif (! empty($philsmsToken)) {
                        Log::info('Sending SMS through PhilSMS.', [
                            'recipient' => $recipient,
                            'sender_id' => $philsmsSenderId,
                            'order_id' => $order->id,
                        ]);

                        $response = Http::timeout(20)
                            ->withToken($philsmsToken)
                            ->acceptJson()
                            ->asJson()
                            ->post('https://dashboard.philsms.com/api/v3/sms/send', [
                                'recipient' => $recipient,
                                'sender_id' => $philsmsSenderId,
                                'type' => 'plain',
                                'message' => $message,
                            ]);

                        $data = $response->json();

                        Log::info('PhilSMS API response.', [
                            'http_status' => $response->status(),
                            'response' => $data,
                            'order_id' => $order->id,
                        ]);

                        if ($response->successful() && ($data['status'] ?? null) === 'success') {
                            $smsStatus = 'sent';
                            Log::info('PhilSMS SMS successfully dispatched.', ['order_id' => $order->id]);
                        } else {
                            $smsStatus = 'failed';
                            Log::error('PhilSMS SMS failed.', ['response' => $response->body(), 'order_id' => $order->id]);
                        }
                    }
                } else {
                    $smsStatus = 'failed';
                    Log::error('SMS failed because mobile number is invalid.', [
                        'phone' => $phone,
                        'order_id' => $order->id,
                    ]);
                }
            } catch (\Throwable $e) {
                $smsStatus = 'failed';
                Log::error('SMS exception.', [
                    'phone' => $phone,
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
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
            Log::error('SMS Notification database record error.', [
                'phone' => $phone,
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
