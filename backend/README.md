# Backend — Laravel API + Admin

Laravel 13 application that powers the portfolio **JSON API** and **Livewire admin
dashboard**. Shares one MySQL database with the React frontend.

## Responsibilities

- **Public API** (`/api/*`) — portfolio payload, contact, newsletter, analytics, certification downloads.
- **Admin** (`/admin`) — content management for profile, sections, projects, skills, messages, etc.
- **Auth** — Laravel Breeze (session); admin-only access via `is_admin` middleware.
- **Mail** — contact auto-replies, newsletter welcome, owner notifications, admin message replies.

## Quick start

Full setup lives in the root **[PROJECT.md](../PROJECT.md)**. Minimum commands:

```bash
cp .env.example .env
composer install && npm install
php artisan key:generate
php artisan migrate --seed
npm run dev          # Vite — keep running for admin styles
php artisan serve    # http://localhost:8000 (or use Laravel Herd)
```

Admin: `/admin` — seeded login `admin@huseinjaber.com` / `password` (**change in production**).

## Environment

Key variables in `.env.example`:

| Variable | Purpose |
| --- | --- |
| `APP_URL` | Backend public URL (Herd `.test` or `http://localhost:8000`) |
| `FRONTEND_URL` | React site origin — used for CORS |
| `MAIL_*` / `MAIL_OWNER_ADDRESS` | SMTP + inbox for contact/newsletter alerts |
| `PORTFOLIO_REGISTRATION_ENABLED` | Public sign-up (default `false`) |

## Commands

```bash
php artisan test              # PHPUnit (SQLite in-memory)
./vendor/bin/pint             # PHP formatting
npm run build                 # Production admin assets → public/build/
php artisan migrate --force   # Production migrations
php artisan config:cache route:cache view:cache
```

## Project import (owner's Mac only)

`DocumentProjectsSeeder` scans local dev folders under `/Library/WebServer/Documents`.
On other machines it no-ops. Re-import locally:

```bash
php artisan db:seed --class=DocumentProjectsSeeder
```

See **[PROJECT.md](../PROJECT.md)** §8 for content management details.
