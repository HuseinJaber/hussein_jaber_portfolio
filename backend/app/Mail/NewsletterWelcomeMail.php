<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use App\Support\PortfolioMail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public NewsletterSubscriber $subscriber) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You\'re subscribed — updates from '.PortfolioMail::ownerName(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.newsletter-welcome',
            with: [
                'siteName' => PortfolioMail::siteName(),
                'ownerName' => PortfolioMail::ownerName(),
                'frontendUrl' => PortfolioMail::frontendUrl(),
            ],
        );
    }
}
