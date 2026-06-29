<?php

namespace App\Services;

use App\Mail\ContactNotificationMail;
use App\Mail\ContactReceivedMail;
use App\Mail\NewsletterSubscriptionNotificationMail;
use App\Mail\NewsletterWelcomeMail;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Support\PortfolioMail;
use Illuminate\Support\Facades\Mail;

class PortfolioNotifier
{
    public static function contactSubmitted(ContactMessage $message): void
    {
        try {
            Mail::to($message->email)->send(new ContactReceivedMail($message));
            Mail::to(PortfolioMail::ownerEmail())->send(new ContactNotificationMail($message));
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

    public static function newsletterSubscribed(NewsletterSubscriber $subscriber): void
    {
        try {
            Mail::to($subscriber->email)->send(new NewsletterWelcomeMail($subscriber));
            Mail::to(PortfolioMail::ownerEmail())->send(new NewsletterSubscriptionNotificationMail($subscriber));
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
