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

        $resendApiKey = env('RESEND_API_KEY');

        if (! empty($resendApiKey)) {
            try {
                $statusStr = strtoupper(str_replace('_', ' ', $order->order_status));
                $subject = "HourWash Notification: Order #{$order->order_number} is {$statusStr}";
                $html = view('emails.order_status_updated', ['order' => $order])->render();

                $response = Http::withToken($resendApiKey)
                    ->timeout(5)
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

                Log::error("Resend HTTP API returned failure [{$response->status()}]: ".$response->body());
            } catch (\Throwable $e) {
                Log::error("Resend HTTP API Exception for {$recipientEmail}: ".$e->getMessage());
            }
        }

        // Fallback to standard Laravel Mailer
        try {
            Mail::to($recipientEmail)->send(new OrderStatusUpdated($order, 'customer'));
            Log::info("Standard Mailer sent to {$recipientEmail} for Order #{$order->order_number}");
        } catch (\Throwable $e) {
            Log::error("Standard Mailer failed for {$recipientEmail}: ".$e->getMessage());
        }
    }
}
