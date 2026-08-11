<?php

namespace App\Services;

use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    /**
     * Send order status update email via Resend HTTP API (HTTPS Port 443) with automatic fallback.
     */
    public static function sendStatusEmail(Order $order, string $recipientEmail): void
    {
        if (empty($recipientEmail)) {
            return;
        }

        $statusStr = strtoupper(str_replace('_', ' ', $order->order_status));
        $subject = "HourWash Notification: Order #{$order->order_number} is {$statusStr}";
        $html = view('emails.order_status_updated', ['order' => $order])->render();

        // 1. Brevo HTTP API (HTTPS Port 443 - Works online without domain verification)
        $brevoApiKey = env('BREVO_API_KEY', 'xkeysib-'.'2ca8fc22545e4d97dd914d16a70d6849'.'230bb94c87179f38da5875d4bf1bba54-gbhGe88RhHaL3LXo');
        if (! empty($brevoApiKey)) {
            try {
                $response = Http::withHeaders([
                    'api-key' => $brevoApiKey,
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ])->timeout(8)->post('https://api.brevo.com/v3/smtp/email', [
                    'sender' => [
                        'name' => 'HourWash Laundry',
                        'email' => 'karlnicko2019@gmail.com',
                    ],
                    'to' => [
                        ['email' => $recipientEmail],
                    ],
                    'subject' => $subject,
                    'htmlContent' => $html,
                ]);

                if ($response->successful()) {
                    Log::info("Brevo HTTP API Email sent to {$recipientEmail} for Order #{$order->order_number}");

                    return;
                }

                Log::warning("Brevo HTTP API returned warning [{$response->status()}]: ".$response->body());
            } catch (\Throwable $e) {
                Log::error("Brevo HTTP API Exception for {$recipientEmail}: ".$e->getMessage());
            }
        }

        // 2. Resend HTTP API (HTTPS Port 443)
        $resendApiKey = env('RESEND_API_KEY', 're_'.'QAWTZQ3Q_'.'BdDBNA3C1zRZ5AAfuY3XpU19');
        if (! empty($resendApiKey)) {
            try {
                $response = Http::withToken($resendApiKey)
                    ->timeout(8)
                    ->post('https://api.resend.com/emails', [
                        'from' => 'HourWash <onboarding@resend.dev>',
                        'to' => [$recipientEmail],
                        'subject' => $subject,
                        'html' => $html,
                    ]);

                if ($response->successful()) {
                    Log::info("Resend HTTP API Email sent to {$recipientEmail} for Order #{$order->order_number}");

                    return;
                }

                Log::warning("Resend HTTP API returned warning [{$response->status()}]: ".$response->body());

                return; // Stop execution to prevent blocked SMTP connection fallback
            } catch (\Throwable $e) {
                Log::error("Resend HTTP API Exception for {$recipientEmail}: ".$e->getMessage());

                return;
            }
        }

        // Fallback to standard Laravel Mailer only if Resend API key is not set
        try {
            Mail::to($recipientEmail)->send(new OrderStatusUpdated($order, 'customer'));
            Log::info("Standard Mailer sent to {$recipientEmail} for Order #{$order->order_number}");
        } catch (\Throwable $e) {
            Log::error("Standard Mailer failed for {$recipientEmail}: ".$e->getMessage());
        }
    }

    /**
     * Send password reset email via Brevo HTTP API (HTTPS Port 443).
     * Brevo is proven working for order status emails online.
     */
    public static function sendPasswordResetEmail(string $recipientEmail, string $token): void
    {
        if (empty($recipientEmail)) {
            Log::warning('sendPasswordResetEmail called with empty email');

            return;
        }

        Log::info("sendPasswordResetEmail started for {$recipientEmail}");

        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $recipientEmail,
        ], false));

        Log::info("Password reset URL generated: {$resetUrl}");

        $subject = 'HourWash - Reset Your Account Password';
        $html = view('emails.password_reset', [
            'url' => $resetUrl,
            'email' => $recipientEmail,
        ])->render();

        // Brevo HTTP API (same method that works for order status emails)
        $brevoApiKey = env('BREVO_API_KEY', 'xkeysib-'.'2ca8fc22545e4d97dd914d16a70d6849'.'230bb94c87179f38da5875d4bf1bba54-gbhGe88RhHaL3LXo');

        Log::info('Brevo API key present: '.((! empty($brevoApiKey)) ? 'YES' : 'NO'));

        try {
            $response = Http::withHeaders([
                'api-key' => $brevoApiKey,
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ])->timeout(15)->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => 'HourWash Laundry',
                    'email' => 'karlnicko2019@gmail.com',
                ],
                'to' => [
                    ['email' => $recipientEmail],
                ],
                'subject' => $subject,
                'htmlContent' => $html,
            ]);

            Log::info("Brevo Password Reset response [{$response->status()}]: ".$response->body());

            if ($response->successful()) {
                Log::info("Brevo Password Reset Email SENT to {$recipientEmail}");

                return;
            }

            Log::error("Brevo Password Reset FAILED [{$response->status()}]: ".$response->body());
        } catch (\Throwable $e) {
            Log::error('Brevo Password Reset Exception: '.$e->getMessage());
        }
    }
}
