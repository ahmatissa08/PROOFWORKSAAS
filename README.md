# ProofWork

ProofWork is a Laravel SaaS for freelancers and agencies who need to turn delivery activity into client-ready proof-of-work reports.

Instead of writing manual weekly updates from scattered tools, ProofWork connects project context, GitHub activity, client data, and reporting workflows into one product.

## Problem

Freelancers and small agencies often do real work that clients do not fully see:

- code ships quietly in GitHub
- meetings happen without a clean recap
- progress is spread across multiple tools
- weekly updates become repetitive and manual

That creates a gap between work delivered and value perceived.

ProofWork is built to reduce that gap.

## Who It Is For

- freelancers
- agencies
- technical consultants
- productized service teams

## Benefits

- save time on status reporting
- make delivery more visible
- communicate progress in a cleaner way
- tie reports to real project activity
- improve trust with clients

## Core Features

### Workspace

- registration, login, password reset, email verification
- client management
- project management
- report creation and editing
- manual report entries
- public share links for reports

### GitHub integration

- GitHub OAuth connection
- repository selection per project
- multi-project repo isolation for the same user
- report generation from real repository activity

### Reporting

- generate reports from project activity
- AI summary generation with OpenAI
- send reports to clients by email
- public report verification link
- PDF export with verification details

### Billing

- plans page
- billing management page
- Stripe checkout scaffold
- Stripe webhook handling

### Public site

- landing page
- about page
- contact page
- roadmap, changelog, and security pages
- live GitHub demo page

## Screenshots

Add screenshots in `docs/screenshots/` before publishing publicly.

Suggested images:

- landing page
- dashboard
- integrations page
- report detail page
- PDF export
- demo page

## Tech Stack

- PHP 8.2
- Laravel 11
- MySQL
- Blade
- Laravel Socialite
- Laravel Cashier
- TCPDF / DomPDF
- OpenAI API
- PHPUnit

## Local Setup

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Default local URL:

- `http://127.0.0.1:8000`

## Required Environment Variables

### App

- `APP_URL`

### Database

- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### Mail

- `MAIL_MAILER`
- `MAIL_HOST`
- `MAIL_PORT`
- `MAIL_USERNAME`
- `MAIL_PASSWORD`
- `MAIL_FROM_ADDRESS`

### Stripe

- `STRIPE_KEY`
- `STRIPE_SECRET`
- `STRIPE_WEBHOOK_SECRET`
- `STRIPE_PRICE_PRO`
- `STRIPE_PRICE_AGENCY`

### OAuth

- `GITHUB_CLIENT_ID`
- `GITHUB_CLIENT_SECRET`
- `GITHUB_REDIRECT_URI`
- `GOOGLE_CLIENT_ID`
- `GOOGLE_CLIENT_SECRET`
- `GOOGLE_REDIRECT_URI`

### AI

- `OPENAI_API_KEY`

### Admin / PDF

- `PROOFWORK_ADMIN_EMAIL`
- `PROOFWORK_ADMIN_PASSWORD`
- `PROOFWORK_PDF_CERTIFICATE_PATH`
- `PROOFWORK_PDF_PRIVATE_KEY_PATH`
- `PROOFWORK_PDF_PRIVATE_KEY_PASSWORD`

## Tests

```bash
php artisan test
```

Current validated flows include:

- auth
- email verification
- projects / clients / reports CRUD
- GitHub integration flow
- multi-project GitHub isolation
- report generation
- PDF download
- Stripe webhook handling

## Deployment Notes

For production you should configure:

- `APP_URL`
- database credentials
- SMTP credentials
- Stripe keys and webhook secret
- GitHub / Google OAuth callbacks
- `OPENAI_API_KEY`
- PDF signing certificate paths if you want real signed PDFs

## License

MIT
