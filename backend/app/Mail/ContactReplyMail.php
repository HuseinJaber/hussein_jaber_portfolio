<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Support\PortfolioMail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $contactMessage,
        public string $replySubject,
        public string $replyBody,
    ) {}

    public function envelope(): Envelope
    {
        $ownerEmail = PortfolioMail::ownerEmail();
        $ownerName = PortfolioMail::ownerName();

        return new Envelope(
            from: new Address($ownerEmail, $ownerName),
            replyTo: [new Address($ownerEmail, $ownerName)],
            subject: $this->replySubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-reply',
            with: [
                'siteName' => PortfolioMail::siteName(),
                'ownerName' => PortfolioMail::ownerName(),
                'frontendUrl' => PortfolioMail::frontendUrl(),
            ],
        );
    }
}
