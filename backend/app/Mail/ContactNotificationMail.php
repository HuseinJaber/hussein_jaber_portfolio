<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Support\PortfolioMail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage) {}

    public function envelope(): Envelope
    {
        $subject = $this->contactMessage->subject
            ? 'New contact: '.$this->contactMessage->subject
            : 'New contact message from '.$this->contactMessage->name;

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-notification',
            with: [
                'siteName' => PortfolioMail::siteName(),
            ],
        );
    }
}
