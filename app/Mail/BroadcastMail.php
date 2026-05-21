<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BroadcastMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $broadcastSubject,
        public string $broadcastBody,
        public string $recipientName = '',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->broadcastSubject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.broadcast');
    }
}
