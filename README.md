# ProofWork SaaS

ProofWork is a Laravel 11 SaaS application that helps freelancers and agencies generate client-ready proof-of-work reports from project activity.

It includes authentication, project and client management, report generation, GitHub repository binding per project, public report sharing, and billing scaffolding.

## Current Status

This repository is in a solid local-development state and has automated feature coverage for the main user journeys.

Implemented and working:
- registration, login, password reset, email verification
- project, client, and report CRUD
- GitHub OAuth connection
- repository selection per project
- multiple GitHub project integrations for the same user
- Google Calendar integration scaffold
- public report sharing
- report sending flow with handled mail errors
- billing screens and Stripe flow scaffolding

Still dependent on external configuration:
- SMTP delivery
- Stripe checkout and portal
- Google OAuth credentials

Not fully enabled yet:
- Linear integration
- Notion integration

## Tech Stack

- PHP 8.2+
- Laravel 11
- MySQL
- Blade
- Laravel Cashier
- Laravel Socialite
- PHPUnit

## Main Features

### Authentication
- email/password auth
- GitHub OAuth login
- Google OAuth login
- email verification
- password reset

### Workspace
- clients
- projects
- reports
- manual report entries
- public report links

### Integrations
- GitHub connection
- repository selection per project
- isolated GitHub repo usage per project during report generation
- Google Calendar connection scaffold

### Billing
- plans page
- billing overview
- Stripe checkout endpoint
- Stripe webhook endpoint

## Project Structure

```text
app/
  Console/Commands/
  Http/Controllers/
    App/
    Auth/
    Billing/
  Mail/
  Models/
  Policies/
  Providers/
  Services/

config/
database/
resources/views/
routes/
tests/Feature/
```

## Local Installation

### 1. Install dependencies

```bash
composer install
copy .env.example .env
php artisan key:generate
```

### 2. Configure environment

Update `.env` with:
- application URL
- database credentials
- mail credentials
- Stripe keys and price IDs
- GitHub OAuth credentials
- Google OAuth credentials if Calendar is needed

Reference file:
- [C:\xampp\htdocs\proofwork-saas\.env.example](C:\xampp\htdocs\proofwork-saas\.env.example)

### 3. Create the database

Example:

```sql
CREATE DATABASE proofwork_saas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Run migrations

```bash
php artisan migrate
```

### 5. Start the app

```bash
php artisan serve
```

Default local URL:
- `http://127.0.0.1:8000`

## Environment Variables

### Application
- `APP_NAME`
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
- `MAIL_ENCRYPTION`
- `MAIL_FROM_ADDRESS`
- `MAIL_FROM_NAME`

### Stripe
- `STRIPE_KEY`
- `STRIPE_SECRET`
- `STRIPE_WEBHOOK_SECRET`
- `STRIPE_PRICE_PRO`
- `STRIPE_PRICE_AGENCY`

### GitHub OAuth
- `GITHUB_CLIENT_ID`
- `GITHUB_CLIENT_SECRET`
- `GITHUB_REDIRECT_URI`

### Google OAuth
- `GOOGLE_CLIENT_ID`
- `GOOGLE_CLIENT_SECRET`
- `GOOGLE_REDIRECT_URI`

### Optional / future-facing
- `LINEAR_CLIENT_ID`
- `LINEAR_CLIENT_SECRET`
- `ANTHROPIC_API_KEY`

## OAuth Setup

### GitHub

Create a GitHub OAuth app with:
- Homepage URL: `http://127.0.0.1:8000`
- Callback URL: `http://127.0.0.1:8000/auth/github/callback`

Important:
- the callback URL must match exactly
- `127.0.0.1` and `localhost` are different for OAuth callback validation

### Google

Create a Google OAuth client with:
- Authorized redirect URI: `http://127.0.0.1:8000/auth/google/callback`

## Stripe Setup

For local webhook testing:

```bash
stripe listen --forward-to 127.0.0.1:8000/stripe/webhook
```

Then set:
- `STRIPE_WEBHOOK_SECRET`

## Running Tests

```bash
php artisan test
```

The suite currently covers:
- auth flows
- verification flow
- workspace CRUD
- GitHub integration flow
- repository binding per project
- multi-project GitHub isolation
- report generation
- public report sharing

## Important Product Behavior

### GitHub integration
- connecting GitHub connects the account first
- selecting a repository binds the repository to a project
- the same user can bind different repositories to different projects
- report generation only pulls GitHub activity from the repository attached to the current project

### Report sending
- reports can be emailed when the client has an email address
- mail failures are handled without crashing the app
- destructive and send actions use in-app confirmation UI instead of browser-native alerts

## Main Routes

| Route | Purpose |
|---|---|
| `/` | redirect to login or dashboard |
| `/register` | create an account |
| `/login` | sign in |
| `/dashboard` | workspace overview |
| `/projects` | projects |
| `/clients` | clients |
| `/reports` | reports |
| `/integrations` | integrations |
| `/settings` | account settings |
| `/billing/manage` | billing overview |
| `/billing/plans` | plans |
| `/r/{token}` | public client report |

## Known Limitations

- SMTP still depends on valid external credentials
- Stripe still depends on valid external keys and price IDs
- Linear and Notion are not fully active integrations
- production deployment configuration is not bundled in this repo

## Before Pushing to GitHub

Use this checklist before a public or team-facing push:

- confirm `.env` is not tracked and contains the real local secrets only on your machine
- verify `.env.example` contains placeholders only, not real credentials
- verify no API keys, webhook secrets, app passwords, or OAuth secrets were pasted into committed files
- run `php artisan test`
- run `php artisan view:clear`
- confirm GitHub and Google OAuth callback URLs match the target environment
- confirm Stripe price IDs and webhook secret are correct for the target environment
- confirm mail settings are valid for the target environment
- review the `README` so it matches the actual shipped behavior

### External Credentials to Recheck

- `MAIL_*`
- `STRIPE_*`
- `GITHUB_CLIENT_*`
- `GOOGLE_CLIENT_*`
- `LINEAR_CLIENT_*`
- `ANTHROPIC_API_KEY`

## Deployment Checklist

Before pushing to production:
- set `APP_ENV=production`
- set `APP_DEBUG=false`
- configure a production database
- configure SMTP
- configure Stripe keys and webhooks
- configure GitHub and Google OAuth callbacks with the production domain
- run migrations
- configure the Laravel scheduler

Example scheduler entry:

```bash
* * * * * cd /var/www/proofwork-saas && php artisan schedule:run >> /dev/null 2>&1
```

## License

This project is licensed under the MIT License.
