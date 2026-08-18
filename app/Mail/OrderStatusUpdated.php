<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $recipientType = 'admin'
    ) {}

    public function envelope(): Envelope
    {
        $statusStr = strtoupper(str_replace('_', ' ', $this->order->order_status));

        return new Envelope(
            subject: "Hour Wash Notification: Order #{$this->order->order_number} is {$statusStr}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_status_updated',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
