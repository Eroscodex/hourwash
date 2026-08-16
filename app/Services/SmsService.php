<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private string $baseUrl = 'https://api.textbee.dev/api/v1/gateway/send-sms';

    public function send(string|array $recipients, string $message): array
    {
        $deviceId = config('textbee.device_id');
        $apiKey = config('textbee.api_key');

        if (empty($apiKey) || empty($deviceId)) {
            Log::warning('Textbee SMS skipped: TEXTBEE_API_KEY or TEXTBEE_DEVICE_ID not set.');

            return [
                'success' => false,
                'message' => 'Credentials not configured',
            ];
        }

        try {
            $formattedRecipients = array_map(function ($r) {
                $num = preg_replace('/[^0-9+]/', '', trim($r));
                if (str_starts_with($num, '+639')) {
                    return '09'.substr($num, 4);
                } elseif (str_starts_with($num, '639')) {
                    return '09'.substr($num, 3);
                } elseif (str_starts_with($num, '9') && strlen($num) === 10) {
                    return '09'.substr($num, 1);
                } elseif (str_starts_with($num, '09')) {
                    return $num;
                }

                return $num;
            }, (array) $recipients);

            $response = Http::withHeader('x-api-key', $apiKey)
                ->timeout(15)
                ->asJson()
                ->post($this->baseUrl, [
                    'deviceId' => $deviceId,
                    'recipients' => $formattedRecipients,
                    'message' => $message,
                ]);

            $data = $response->json();

            Log::info('Textbee.dev SMS gateway response', [
                'status' => $response->status(),
                'response' => $data,
                'recipients' => $formattedRecipients,
            ]);

            return $data['data'] ?? $data ?? ['success' => $response->successful()];
        } catch (\Throwable $e) {
            Log::error('Textbee.dev SMS Exception: '.$e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
