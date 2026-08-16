<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SmsLogController extends Controller
{
    public function index()
    {
        $smsLogs = SmsNotification::with(['order', 'user'])
            ->latest()
            ->get();

        $totalDispatched = $smsLogs->count();

        return view('admin.sms.index', compact('smsLogs', 'totalDispatched'));
    }

    public function sendTest(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $phone = trim($request->phone);
        $message = trim($request->message);

        // Normalize Philippine phone format
        $recipient = preg_replace('/[^0-9+]/', '', $phone);
        if (str_starts_with($recipient, '09')) {
            $recipient = '63'.substr($recipient, 1);
        } elseif (str_starts_with($recipient, '+639')) {
            $recipient = substr($recipient, 1);
        } elseif (str_starts_with($recipient, '9') && strlen($recipient) === 10) {
            $recipient = '63'.$recipient;
        }

        $textbeeApiKey = config('services.textbee.api_key');
        $textbeeDeviceId = config('services.textbee.device_id');
        $philsmsToken = config('services.philsms.api_token');

        $status = 'failed';
        $logDetails = '';

        if (! empty($textbeeApiKey) && ! empty($textbeeDeviceId)) {
            try {
                $response = Http::timeout(20)
                    ->withHeaders(['x-api-key' => $textbeeApiKey])
                    ->asJson()
                    ->post("https://api.textbee.dev/api/v1/gateway/devices/{$textbeeDeviceId}/send-sms", [
                        'recipients' => ['+'.$recipient],
                        'message' => $message,
                    ]);

                $data = $response->json();
                if ($response->successful() && (($data['success'] ?? false) === true || ($data['status'] ?? '') === 'success')) {
                    $status = 'sent';
                    $logDetails = 'Sent via Textbee.dev API successfully!';
                } else {
                    $logDetails = 'Textbee Error: '.($response->body() ?? 'API request failed');
                }
            } catch (\Throwable $e) {
                $logDetails = 'Textbee Exception: '.$e->getMessage();
            }
        } elseif (! empty($philsmsToken)) {
            try {
                $response = Http::timeout(20)
                    ->withToken($philsmsToken)
                    ->asJson()
                    ->post('https://dashboard.philsms.com/api/v3/sms/send', [
                        'recipient' => $recipient,
                        'sender_id' => config('services.philsms.sender_id', 'PhilSMS'),
                        'type' => 'plain',
                        'message' => $message,
                    ]);

                $data = $response->json();
                if ($response->successful() && ($data['status'] ?? null) === 'success') {
                    $status = 'sent';
                    $logDetails = 'Sent via PhilSMS API successfully!';
                } else {
                    $logDetails = 'PhilSMS Error: '.($response->body() ?? 'API request failed');
                }
            } catch (\Throwable $e) {
                $logDetails = 'PhilSMS Exception: '.$e->getMessage();
            }
        } else {
            $logDetails = 'No SMS provider API key configured in .env (Add TEXTBEE_API_KEY or PHILSMS_API_TOKEN).';
        }

        SmsNotification::create([
            'phone' => $phone,
            'message' => $message,
            'status' => $status,
            'user_id' => auth()->id(),
        ]);

        if ($status === 'sent') {
            return back()->with('success', '✅ Test SMS successfully dispatched to '.$phone.'! '.$logDetails);
        } else {
            return back()->with('error', '❌ Test SMS dispatch failed. '.$logDetails);
        }
    }
}
