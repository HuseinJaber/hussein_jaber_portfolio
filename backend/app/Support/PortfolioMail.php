<?php

namespace App\Support;

use App\Models\Profile;

class PortfolioMail
{
    public static function ownerEmail(): string
    {
        if ($configured = config('portfolio.owner_email')) {
            return $configured;
        }

        $profile = Profile::current();

        if ($profile->email) {
            return $profile->email;
        }

        return (string) config('mail.from.address');
    }

    public static function siteName(): string
    {
        return Profile::current()->name ?? (string) config('app.name');
    }

    public static function ownerName(): string
    {
        return Profile::current()->name ?? (string) config('mail.from.name');
    }

    public static function frontendUrl(): string
    {
        return rtrim((string) config('portfolio.frontend_url', config('app.url')), '/');
    }
}
