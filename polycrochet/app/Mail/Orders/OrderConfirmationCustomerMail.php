<?php

namespace App\Mail\Orders;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationCustomerMail extends Mailable
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
        return new Envelope(
            subject: 'Confirmación de compra #' . ($this->order->order_number ?? $this->order->id),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.customer-confirmation',
            with: [
                'order' => $this->order,
                'shipping' => $this->order->shipping_data ?? [],
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
