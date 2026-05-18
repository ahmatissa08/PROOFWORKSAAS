<?php

return [
    'key'      => env('STRIPE_KEY'),
    'secret'   => env('STRIPE_SECRET'),

    'webhook' => [
        'secret'    => env('STRIPE_WEBHOOK_SECRET'),
        'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
    ],

    'model'    => App\Models\User::class,
    'currency' => env('CASHIER_CURRENCY', 'usd'),
    'currency_locale' => env('CASHIER_CURRENCY_LOCALE', 'en'),

    'payment_notification' => null,
    'payment_urls' => [
        'success' => env('CASHIER_PAYMENT_SUCCESS_URL', '/billing/success'),
        'cancel'  => env('CASHIER_PAYMENT_CANCEL_URL',  '/billing/plans'),
    ],

    'logger' => env('CASHIER_LOGGER'),
    'invoices' => true,
];
