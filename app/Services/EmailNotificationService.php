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
     * Brevo → Resend → Laravel Mail fallback.
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

        $brevoApiKey = config('services.brevo.api_key');

        if (! empty($brevoApiKey)) {
            try {
                $response = Http::withHeaders([
                    'api-key' => $brevoApiKey,
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ])
                    ->timeout(15)
                    ->post('https://api.brevo.com/v3/smtp/email', [
                        'sender' => [
                            'name' => config('services.brevo.sender_name', 'HourWash Laundry'),
                            'email' => config('services.brevo.sender_email', 'karlnicko2019@gmail.com'),
                        ],
                        'to' => [
                            ['email' => $recipientEmail],
                        ],
                        'subject' => $subject,
                        'htmlContent' => $html,
                    ]);

                if ($response->successful()) {
                    Log::info(
                        "Brevo email sent to {$recipientEmail} for Order #{$order->order_number}"
                    );

                    return;
                }

                Log::warning(
                    "Brevo failed [{$response->status()}]: ".
                    $response->body()
                );
            } catch (\Throwable $e) {
                Log::error(
                    "Brevo exception for {$recipientEmail}: ".
                    $e->getMessage()
                );
            }
        } else {
            Log::warning('BREVO_API_KEY is not configured.');
        }

        $resendApiKey = env('RESEND_API_KEY');

        if (! empty($resendApiKey)) {
            try {
                $response = Http::withToken($resendApiKey)
                    ->timeout(15)
                    ->post('https://api.resend.com/emails', [
                        'from' => 'HourWash <onboarding@resend.dev>',
                        'to' => [$recipientEmail],
                        'subject' => $subject,
                        'html' => $html,
                    ]);

                if ($response->successful()) {
                    Log::info(
                        "Resend email sent to {$recipientEmail} for Order #{$order->order_number}"
                    );

                    return;
                }

                Log::warning(
                    "Resend failed [{$response->status()}]: ".
                    $response->body()
                );
            } catch (\Throwable $e) {
                Log::error(
                    "Resend exception for {$recipientEmail}: ".
                    $e->getMessage()
                );
            }
        }

        try {
            Mail::to($recipientEmail)->send(
                new OrderStatusUpdated($order, 'customer')
            );

            Log::info(
                "Laravel mail sent to {$recipientEmail} for Order #{$order->order_number}"
            );
        } catch (\Throwable $e) {
            Log::error(
                "Laravel mail failed for {$recipientEmail}: ".
                $e->getMessage()
            );
        }
    }

    /**
     * Send password reset email through Brevo.
     */
    public static function sendPasswordResetEmail(
        string $recipientEmail,
        string $token
    ): void {
        if (empty($recipientEmail)) {
            Log::warning('Password reset email called with empty recipient.');

            throw new RuntimeException('Recipient email is required.');
        }

        Log::info('Password reset email started', [
            'email' => $recipientEmail,
        ]);

        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $recipientEmail,
        ], false));

        Log::info('Password reset URL generated', [
            'email' => $recipientEmail,
        ]);

        try {
            $html = view('emails.password_reset', [
                'url' => $resetUrl,
                'email' => $recipientEmail,
            ])->render();
        } catch (\Throwable $e) {
            Log::error('Password reset email template failed: '.$e->getMessage());

            throw new RuntimeException('Password reset email template is unavailable.', 0, $e);
        }

        $brevoApiKey = config('services.brevo.api_key');

        if (empty($brevoApiKey)) {
            Log::error('Password reset failed: BREVO_API_KEY is missing.');

            throw new RuntimeException('Email service is not configured.');
        }

        try {
            $response = Http::withHeaders([
                'api-key' => $brevoApiKey,
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ])
                ->timeout(15)
                ->post('https://api.brevo.com/v3/smtp/email', [
                    'sender' => [
                        'name' => config('services.brevo.sender_name', 'HourWash Laundry'),
                        'email' => config('services.brevo.sender_email', 'karlnicko2019@gmail.com'),
                    ],
                    'to' => [
                        ['email' => $recipientEmail],
                    ],
                    'subject' => 'HourWash - Reset Your Account Password',
                    'htmlContent' => $html,
                    'textContent' => "Reset your HourWash password:\n\n{$resetUrl}",
                ]);

            Log::info('Brevo password reset response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'response' => $response->json(),
            ]);

            if (! $response->successful()) {
                Log::error('Brevo rejected password reset email', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                throw new RuntimeException('Brevo rejected the password reset email.');
            }

            Log::info("Password reset email accepted by Brevo for {$recipientEmail}");
        } catch (RuntimeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Password reset Brevo exception', [
                'email' => $recipientEmail,
                'error' => $e->getMessage(),
            ]);

            throw new RuntimeException('Unable to send password reset email.', 0, $e);
        }
    }
}
