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
        $brevoApiKey = env('BREVO_API_KEY');
        if (! empty($brevoApiKey)) {
            try {
                $response = Http::withHeaders([
                    'api-key' => $brevoApiKey,
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ])->timeout(5)->post('https://api.brevo.com/v3/smtp/email', [
                    'sender' => [
                        'name' => env('MAIL_FROM_NAME', 'HourWash Laundry'),
                        'email' => env('MAIL_FROM_ADDRESS', 'karlnicko2019@gmail.com'),
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
        $resendApiKey = env('RESEND_API_KEY');
        if (! empty($resendApiKey)) {
            try {
                $fromAddress = env('MAIL_FROM_ADDRESS', 'onboarding@resend.dev');

                $response = Http::withToken($resendApiKey)
                    ->timeout(5)
                    ->post('https://api.resend.com/emails', [
                        'from' => "HourWash <{$fromAddress}>",
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
     * Send password reset email via Brevo / Resend HTTP API (HTTPS Port 443) with fallback.
     */
    public static function sendPasswordResetEmail(string $recipientEmail, string $token): void
    {
        if (empty($recipientEmail)) {
            return;
        }

        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $recipientEmail,
        ], false));

        $subject = 'HourWash - Reset Your Account Password';
        $html = view('emails.password_reset', [
            'url' => $resetUrl,
            'email' => $recipientEmail,
        ])->render();

        // 1. Brevo HTTP API
        $brevoApiKey = env('BREVO_API_KEY');
        if (! empty($brevoApiKey)) {
            try {
                $response = Http::withHeaders([
                    'api-key' => $brevoApiKey,
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ])->timeout(5)->post('https://api.brevo.com/v3/smtp/email', [
                    'sender' => [
                        'name' => env('MAIL_FROM_NAME', 'HourWash Laundry'),
                        'email' => env('MAIL_FROM_ADDRESS', 'karlnicko2019@gmail.com'),
                    ],
                    'to' => [
                        ['email' => $recipientEmail],
                    ],
                    'subject' => $subject,
                    'htmlContent' => $html,
                ]);

                if ($response->successful()) {
                    Log::info("Brevo HTTP API Password Reset Email sent to {$recipientEmail}");

                    return;
                }

                Log::warning("Brevo HTTP API Password Reset warning [{$response->status()}]: ".$response->body());
            } catch (\Throwable $e) {
                Log::error('Brevo HTTP API Password Reset Exception: '.$e->getMessage());
            }
        }

        // 2. Resend HTTP API
        $resendApiKey = env('RESEND_API_KEY');
        if (! empty($resendApiKey)) {
            try {
                $fromAddress = env('MAIL_FROM_ADDRESS', 'onboarding@resend.dev');

                $response = Http::withToken($resendApiKey)
                    ->timeout(5)
                    ->post('https://api.resend.com/emails', [
                        'from' => "HourWash <{$fromAddress}>",
                        'to' => [$recipientEmail],
                        'subject' => $subject,
                        'html' => $html,
                    ]);

                if ($response->successful()) {
                    Log::info("Resend HTTP API Password Reset Email sent to {$recipientEmail}");

                    return;
                }

                Log::warning("Resend HTTP API Password Reset warning [{$response->status()}]: ".$response->body());

                return;
            } catch (\Throwable $e) {
                Log::error('Resend HTTP API Password Reset Exception: '.$e->getMessage());

                return;
            }
        }

        // 3. Fallback to standard Laravel Mailer
        try {
            Mail::html($html, function ($message) use ($recipientEmail, $subject) {
                $message->to($recipientEmail)
                    ->subject($subject);
            });
            Log::info("Standard Mailer Password Reset sent to {$recipientEmail}");
        } catch (\Throwable $e) {
            Log::error('Standard Mailer Password Reset failed: '.$e->getMessage());
        }
    }
}
