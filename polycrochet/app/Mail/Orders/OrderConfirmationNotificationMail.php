<?php

namespace App\Mail\Orders;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Order $order
    ) {
        $this->order->loadMissing(['items.product.primaryImage', 'user']);
    }

    public function envelope(): Envelope
    {
        $customerEmail = $this->order->billing_data['email'] ?? $this->order->user?->email;

        return new Envelope(
            subject: 'Nuevo pedido confirmado #' . ($this->order->order_number ?? $this->order->id),
            replyTo: $customerEmail ? [
                new Address($customerEmail, $this->order->shipping_data['name'] ?? $this->order->user?->name ?? $customerEmail),
            ] : []
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.admin-notification',
            with: [
                'order' => $this->order,
                'shipping' => $this->order->shipping_data ?? [],
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
