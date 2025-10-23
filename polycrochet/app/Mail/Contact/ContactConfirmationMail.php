<?php

namespace App\Mail\Contact;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array{name:string,email:string,message:string}  $data
     */
    public function __construct(
        public array $data
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recibimos tu mensaje · PolyCrochet',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact.confirmation',
            with: $this->data,
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
