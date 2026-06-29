<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Support\PortfolioMail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thanks for reaching out — '.PortfolioMail::ownerName(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-received',
            with: [
                'siteName' => PortfolioMail::siteName(),
                'ownerName' => PortfolioMail::ownerName(),
                'frontendUrl' => PortfolioMail::frontendUrl(),
            ],
        );
    }
}
