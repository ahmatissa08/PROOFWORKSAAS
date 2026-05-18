# ProofWork

ProofWork is a Laravel SaaS product built for freelancers and agencies who need to show clients what got done, without spending hours turning commits, tasks, and meetings into polished status reports.

It helps teams connect project activity, turn it into client-friendly proof of work, and share reports in a way that feels professional.

## The Problem

Freelancers and small agencies often do a lot of valuable work that clients do not fully see:

- code ships quietly in GitHub
- meetings happen without a clean recap
- progress lives across several tools
- weekly reporting becomes manual, repetitive, and hard to maintain

The result is a common gap between real effort and perceived value.

ProofWork is designed to reduce that gap by turning delivery activity into client-ready reporting.

## Who It Is For

ProofWork is built for:

- freelancers who want to justify work clearly
- agencies managing several client projects
- productized service teams that send recurring updates
- technical teams that need a cleaner way to communicate progress to non-technical clients

## Why It Matters

ProofWork helps teams:

- save time on weekly reporting
- make delivery more visible
- create more trust with clients
- reduce back-and-forth around "what was done"
- keep reporting tied to real project activity

In short, it helps translate execution into perceived value.

## Core Features

### Workspace

- user authentication with email/password and social login scaffolding
- client management
- project management
- report creation and editing
- manual report entries for context and narrative
- public share links for client-facing reports

### GitHub Integration

- GitHub OAuth connection
- repository selection after connecting GitHub
- repository binding per project
- support for multiple projects using different repositories for the same user
- GitHub activity used during report generation only for the repository attached to the active project

### Reporting

- generate reports from project context
- add and remove report entries
- share reports publicly through a tokenized link
- send reports to clients by email
- in-app confirmation modals instead of raw browser alerts

### Billing

- plans page
- billing management screen
- Stripe checkout scaffolding
- Stripe webhook endpoint scaffolding

## Product Benefits

### For freelancers

- prove work clearly
- look more organized
- communicate like a more mature business

### For agencies

- standardize client reporting
- keep project communication more consistent across accounts
- make account management easier to scale

### For clients

- understand progress faster
- get cleaner updates
- feel more confident in what is being delivered

## Screenshots

No screenshots are versioned in the repository yet.

Before a public GitHub push, add product screenshots in `docs/screenshots/` and update this section. Suggested images:

- dashboard overview
- project details page
- integrations page with GitHub repository selection
- generated report page
- public shared report view
- billing plans page

Example markdown once screenshots are added:

```md
![Dashboard](docs/screenshots/dashboard.png)
![Project](docs/screenshots/project-show.png)
![GitHub Integration](docs/screenshots/integrations-github.png)
![Report](docs/screenshots/report-show.png)
```

## Technical Stack

- PHP 8.2+
- Laravel 11
- MySQL
- Blade templating
- Laravel Socialite
- Laravel Cashier
- PHPUnit

## Current Product Status

Working flows currently covered and stabilized:

- registration, login, password reset, and email verification
- client, project, and report CRUD
- GitHub OAuth connection
- GitHub repository selection per project
- multi-project GitHub isolation for the same user
- public report sharing
- report sending flow with handled mail errors
- billing screens and checkout scaffolding

Still dependent on external service configuration:

- SMTP delivery
- Stripe keys, prices, and webhooks
- Google OAuth credentials

Not fully enabled yet:

- Linear integration
- Notion integration

## Setup

### 1. Install dependencies

```bash
composer install
copy .env.example .env
php artisan key:generate
```

### 2. Configure environment variables

Update `.env` with the values for:

- `APP_URL`
- database credentials
- mail credentials
- Stripe keys and price IDs
- GitHub OAuth credentials
- Google OAuth credentials if needed

Use `.env.example` as the reference template.

### 3. Create the database

Example:

```sql
CREATE DATABASE proofwork_saas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Run migrations

```bash
php artisan migrate
```

### 5. Start the application

```bash
php artisan serve
```

Default local URL:

- `http://127.0.0.1:8000`

## Required Environment Areas

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

### Optional / Future-facing

- `LINEAR_CLIENT_ID`
- `LINEAR_CLIENT_SECRET`
- `ANTHROPIC_API_KEY`

## OAuth Setup

### GitHub

Create a GitHub OAuth application with:

- Homepage URL: `http://127.0.0.1:8000`
- Callback URL: `http://127.0.0.1:8000/auth/github/callback`

Important:

- the callback URL must match exactly
- `127.0.0.1` and `localhost` are treated differently by OAuth providers

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

The automated suite currently covers:

- auth flows
- verification flow
- workspace CRUD
- GitHub integration flow
- repository binding per project
- multi-project GitHub isolation
- report generation
- public report sharing

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

- mail sending still depends on valid SMTP credentials
- Stripe flows still depend on valid keys, webhook secret, and price IDs
- Google, Linear, and Notion are not fully production-ready integrations in this repository state
- deployment infrastructure is not bundled in this repo

## Before Pushing to GitHub

- confirm `.env` is not tracked
- confirm `.env.example` contains placeholders only
- confirm no real secrets were committed
- add screenshots to `docs/screenshots/`
- verify OAuth callbacks for the target environment
- verify Stripe price IDs and webhook secret
- run `php artisan test`
- run `php artisan view:clear`
- review this README one last time against the actual product behavior

## License

This project is licensed under the MIT License.
