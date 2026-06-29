<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use App\Support\PortfolioMail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterSubscriptionNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public NewsletterSubscriber $subscriber) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New newsletter subscriber — '.$this->subscriber->email,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.newsletter-notification',
            with: [
                'siteName' => PortfolioMail::siteName(),
            ],
        );
    }
}
