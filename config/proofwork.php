<?php

return [
    // Admin
    'admin_password' => env('PROOFWORK_ADMIN_PASSWORD', 'changeme'),
    'admin_email'    => env('PROOFWORK_ADMIN_EMAIL'),

    // Anthropic Claude API for AI summaries
    'anthropic_api_key' => env('ANTHROPIC_API_KEY'),

    // Stripe price IDs — set these after creating products in Stripe dashboard
    'stripe_prices' => [
        'pro'    => env('STRIPE_PRICE_PRO',    'price_xxx'),
        'agency' => env('STRIPE_PRICE_AGENCY', 'price_yyy'),
    ],

    // Plausible analytics (optional)
    'plausible_domain' => env('PLAUSIBLE_DOMAIN'),

    // Supported integrations
    'integrations' => ['github', 'google_calendar', 'linear', 'notion'],
];
