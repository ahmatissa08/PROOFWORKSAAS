<?php

return [

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT_URI'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
        'from' => env('RESEND_FROM_ADDRESS', env('MAIL_FROM_ADDRESS', 'hello@proofwork.app')),
        'timeout' => env('RESEND_TIMEOUT', 15),
    ],

    'verification_email' => [
        'provider' => env('VERIFICATION_EMAIL_PROVIDER', 'resend'),
    ],

    'gmail_api' => [
        'client_id' => env('GMAIL_API_CLIENT_ID'),
        'client_secret' => env('GMAIL_API_CLIENT_SECRET'),
        'refresh_token' => env('GMAIL_API_REFRESH_TOKEN'),
        'from' => env('GMAIL_API_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
        'timeout' => env('GMAIL_API_TIMEOUT', 15),
    ],

    'linear' => [
        'client_id' => env('LINEAR_CLIENT_ID'),
        'client_secret' => env('LINEAR_CLIENT_SECRET'),
        'redirect' => env('APP_URL').'/integrations/callback/linear',
    ],

];
