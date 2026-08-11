<?php

namespace App\Services;

use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class EmailNotificationService
{
    /**
     * Send order status update email.
     *
     * Attempts Brevo first, then Resend, then the standard Laravel mailer.
     */
    public static function sendStatusEmail(Order $order, string $recipientEmail): void
    {
        if (empty($recipientEmail)) {
            return;
        }

        $statusStr = strtoupper(str_replace('_', ' ', $order->order_status));
        $subject = "HourWash Notification: Order #{$order->order_number} is {$statusStr}";

        $html = view('emails.order_status_updated', [
            'order' => $order,
        ])->render();

        // 1. Brevo HTTP API
        $brevoApiKey = env('BREVO_API_KEY');

        if (!empty($brevoApiKey)) {
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
                    Log::info(
                        "Brevo HTTP API Email sent to {$recipientEmail} for Order #{$order->order_number}"
                    );

                    return;
                }

                Log::warning(
                    "Brevo HTTP API returned warning [{$response->status()}]: " .
                    $response->body()
                );
            } catch (\Throwable $e) {
                Log::error(
                    "Brevo HTTP API Exception for {$recipientEmail}: " .
                    $e->getMessage()
                );
            }
        } else {
            Log::warning('BREVO_API_KEY is not configured.');
        }

        // 2. Resend HTTP API
        $resendApiKey = env('RESEND_API_KEY');

        if (!empty($resendApiKey)) {
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
                    Log::info(
                        "Resend HTTP API Email sent to {$recipientEmail} for Order #{$order->order_number}"
                    );

                    return;
                }

                Log::warning(
                    "Resend HTTP API returned warning [{$response->status()}]: " .
                    $response->body()
                );
            } catch (\Throwable $e) {
                Log::error(
                    "Resend HTTP API Exception for {$recipientEmail}: " .
                    $e->getMessage()
                );
            }
        } else {
            Log::warning('RESEND_API_KEY is not configured.');
        }

        // 3. Standard Laravel Mailer
        try {
            Mail::to($recipientEmail)->send(
                new OrderStatusUpdated($order, 'customer')
            );

            Log::info(
                "Standard Mailer sent to {$recipientEmail} for Order #{$order->order_number}"
            );
        } catch (\Throwable $e) {
            Log::error(
                "Standard Mailer failed for {$recipientEmail}: " .
                $e->getMessage()
            );
        }
    }

    /**
     * Send password reset email via Brevo HTTP API.
     *
     * Unlike order-status notifications, password reset emails fail
     * explicitly when the email service is unavailable.
     *
     * @throws RuntimeException
     */
    public static function sendPasswordResetEmail(
        string $recipientEmail,
        string $token
    ): void {
        if (empty($recipientEmail)) {
            Log::warning('sendPasswordResetEmail called with empty email');

            throw new RuntimeException('Recipient email is required.');
        }

        Log::info("sendPasswordResetEmail started for {$recipientEmail}");

        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $recipientEmail,
        ], false));

        Log::info("Password reset URL generated for {$recipientEmail}");

        // Make sure resources/views/emails/password_reset.blade.php exists.
        $html = view('emails.password_reset', [
            'url' => $resetUrl,
            'email' => $recipientEmail,
        ])->render();

        $brevoApiKey = env('BREVO_API_KEY');

        if (empty($brevoApiKey)) {
            Log::error('BREVO_API_KEY is not configured.');

            throw new RuntimeException(
                'Email service is not configured. Please contact support.'
            );
        }

        try {
            $response = Http::withHeaders([
                'api-key' => $brevoApiKey,
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ])->timeout(15)->post(
                'https://api.brevo.com/v3/smtp/email',
                [
                    'sender' => [
                        'name' => 'HourWash Laundry',
                        'email' => 'karlnicko2019@gmail.com',
                    ],
                    'to' => [
                        ['email' => $recipientEmail],
                    ],
                    'subject' => 'HourWash - Reset Your Account Password',
                    'htmlContent' => $html,
                ]
            );

            if ($response->successful()) {
                Log::info(
                    "Brevo Password Reset Email SENT to {$recipientEmail}"
                );

                return;
            }

            Log::error(
                "Brevo Password Reset FAILED [{$response->status()}]: " .
                $response->body()
            );

            throw new RuntimeException(
                'Unable to send password reset email. Please try again later.'
            );
        } catch (RuntimeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error(
                "Brevo Password Reset Exception for {$recipientEmail}: " .
                $e->getMessage()
            );

            throw new RuntimeException(
                'Unable to send password reset email. Please try again later.',
                0,
                $e
            );
        }
    }
}
