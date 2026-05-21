<?php

return [
    // Admin contact used in the settings support link.
    'admin_password' => env('PROOFWORK_ADMIN_PASSWORD', 'changeme'),
    'admin_email' => env('PROOFWORK_ADMIN_EMAIL'),

    // Anthropic API key for optional AI summaries.
    'anthropic_api_key' => env('ANTHROPIC_API_KEY'),

    // Stripe price IDs.
    'stripe_prices' => [
        'pro' => env('STRIPE_PRICE_PRO', 'price_xxx'),
        'agency' => env('STRIPE_PRICE_AGENCY', 'price_yyy'),
    ],

    // Plausible analytics (optional).
    'plausible_domain' => env('PLAUSIBLE_DOMAIN'),

    // PDF digital signature certificate. In production, point these to a real
    // certificate/private key pair stored outside the public web root.
    'pdf_signature' => [
        'certificate_path' => env('PROOFWORK_PDF_CERTIFICATE_PATH'),
        'private_key_path' => env('PROOFWORK_PDF_PRIVATE_KEY_PATH'),
        'private_key_password' => env('PROOFWORK_PDF_PRIVATE_KEY_PASSWORD', ''),
    ],

    // Supported integrations.
    'integrations' => ['github', 'google_calendar', 'linear', 'notion'],
];
