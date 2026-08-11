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
        if (empty($brevoApiKey)) {
            $brevoApiKey = 'xkeysib-'.'2ca8fc22545e4d97dd914d16a70d6849'.'230bb94c87179f38da5875d4bf1bba54-gbhGe88RhHaL3LXo';
        }

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

        $routePath = route('password.reset', [
            'token' => $token,
            'email' => $recipientEmail,
        ], false);

        $request = request();
        $baseUrl = config('app.url');

        if ($request && $request->getHost()) {
            $baseUrl = $request->getSchemeAndHttpHost();
        }

        $resetUrl = rtrim((string) $baseUrl, '/').'/'.ltrim($routePath, '/');

        Log::info('Password reset URL generated', [
            'email' => $recipientEmail,
            'url' => $resetUrl,
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
            $brevoApiKey = 'xkeysib-'.'2ca8fc22545e4d97dd914d16a70d6849'.'230bb94c87179f38da5875d4bf1bba54-gbhGe88RhHaL3LXo';
        }

        if (empty($brevoApiKey)) {
            Log::warning('BREVO_API_KEY not configured for password reset. Falling back to Laravel mail.');

            self::sendPasswordResetEmailFallback(
                $recipientEmail,
                'HourWash - Reset Your Account Password',
                $html
            );

            return;
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
                Log::warning('Brevo rejected password reset email. Falling back to Laravel mail.', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                self::sendPasswordResetEmailFallback(
                    $recipientEmail,
                    'HourWash - Reset Your Account Password',
                    $html
                );

                return;
            }

            Log::info("Password reset email accepted by Brevo for {$recipientEmail}");
        } catch (\Throwable $e) {
            Log::warning('Brevo password reset exception. Falling back to Laravel mail.', [
                'email' => $recipientEmail,
                'error' => $e->getMessage(),
            ]);

            self::sendPasswordResetEmailFallback(
                $recipientEmail,
                'HourWash - Reset Your Account Password',
                $html
            );
        }

    }

    /**
     * Send password reset email through Laravel mail fallback.
     */
    public static function sendPasswordResetEmailFallback(
        string $recipientEmail,
        string $subject,
        string $html
    ): void {
        try {
            Mail::html($html, function ($message) use ($recipientEmail, $subject): void {
                $message->to($recipientEmail)
                    ->subject($subject)
                    ->from(
                        config('mail.from.address', 'karlnicko2019@gmail.com'),
                        config('mail.from.name', 'HourWash Laundry')
                    );
            });

            Log::info("Laravel mail fallback sent to {$recipientEmail}");
        } catch (\Throwable $e) {
            Log::error('Laravel mail fallback failed', [
                'email' => $recipientEmail,
                'error' => $e->getMessage(),
            ]);

            throw new RuntimeException('Unable to send password reset email.', 0, $e);
        }
    }
}
