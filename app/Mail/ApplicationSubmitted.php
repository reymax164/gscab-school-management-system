<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $referenceCode; // Add this property

    public function __construct($referenceCode)
    {
        $this->referenceCode = $referenceCode; // Assign it
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Application Reference Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application-submitted',
        );
    }
}