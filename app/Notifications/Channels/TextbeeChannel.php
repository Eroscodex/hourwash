<?php

namespace App\Notifications\Channels;

use App\Services\SmsService;
use Illuminate\Notifications\Notification;

class TextbeeChannel
{
    public function __construct(private SmsService $sms) {}

    public function send(mixed $notifiable, Notification $notification): void
    {
        $phone = $notifiable->routeNotificationFor('textbee') ?? $notifiable->phone ?? $notifiable->phone_number;

        if (! $phone) {
            return;
        }

        if (method_exists($notification, 'toTextbee')) {
            $message = $notification->toTextbee($notifiable);
            $this->sms->send($phone, $message);
        }
    }
}
