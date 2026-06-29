<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Portfolio notifications
    |--------------------------------------------------------------------------
    |
    | MAIL_OWNER_ADDRESS receives contact form and newsletter alerts.
    | Falls back to the profile email in the database when unset.
    |
    */

    'owner_email' => env('MAIL_OWNER_ADDRESS'),

    'frontend_url' => env('FRONTEND_URL', env('APP_URL')),

    /*
    |--------------------------------------------------------------------------
    | Admin access
    |--------------------------------------------------------------------------
    |
    | Public registration is disabled by default. Only users with is_admin=true
    | may access /admin after logging in.
    |
    */

    'registration_enabled' => env('PORTFOLIO_REGISTRATION_ENABLED', false),

];
