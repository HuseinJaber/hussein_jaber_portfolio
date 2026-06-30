# Hussein Jaber — Portfolio

A professional, animated Full Stack Developer portfolio with a content-managed
admin dashboard.

- **`backend/`** — Laravel 13 API + Livewire/Alpine/Tailwind admin dashboard (MySQL).
- **`frontend/`** — React 19 + Vite SPA (TypeScript) public website with Tailwind, SCSS,
  Motion & GSAP animations, consuming the Laravel API.

Manage profile, sections, projects, categories, tech stacks, skills, services,
experience, education, certifications, testimonials, social links, messages,
newsletter subscribers, and analytics from the admin — changes reflect on the live
site after refresh.

## Features

**Public site:** Animated home page with configurable sections, project detail pages,
category filters on the work grid, live résumé at `/cv`, scroll progress indicator,
contact form, newsletter signup, cookie consent, privacy/terms pages, and lightweight
analytics.

**Admin:** Full CRUD for all content, drag-and-drop ordering, soft-cancel with
restore, section visibility + copy editor, dedicated project add/edit pages,
in-admin email replies to contact messages, certification PDF uploads, and a
dashboard with stats.

**Email:** Auto-reply to visitors on contact + newsletter signup; owner notifications
via `MAIL_OWNER_ADDRESS` (configure SMTP in production).

## Quick start

See **[PROJECT.md](./PROJECT.md)** for the full brief, architecture, data model,
local setup, content guide, and production deployment steps.

```bash
# Backend (http://localhost:8000 or Herd .test URL)
cd backend && composer install && npm install && cp .env.example .env \
  && php artisan key:generate && php artisan migrate --seed
npm run dev        # admin assets — keep running
php artisan serve  # API + admin (skip if using Herd)

# Frontend (http://localhost:5173)
cd frontend && npm install && cp .env.example .env.local && npm run dev
```

Admin: <http://localhost:8000/admin> — seeded login `admin@huseinjaber.com` / `password`
(**change immediately**).

## Pre-push checklist

Run before pushing to GitHub (also enforced in CI on `main`):

```bash
cd backend && php artisan test && ./vendor/bin/pint --test && npm run build
cd frontend && npm run lint && npm run build
```

Do **not** commit `.env` files. Copy from `.env.example` on each environment.

## Production (live)

- **Site:** https://huseinjaber.com
- **API / admin:** https://api.huseinjaber.com

Ops reference: **[DEPLOYMENT_SUMMARY.md](./DEPLOYMENT_SUMMARY.md)** — DNS, SSL, routine
updates, troubleshooting.  
First-time deploy: **[HOSTINGER_DEPLOY.md](./HOSTINGER_DEPLOY.md)** — GitHub push, database
export, `api.huseinjaber.com` (Laravel), `huseinjaber.com` (upload `frontend/dist/`).

> **Note:** Some corporate networks (FortiGuard) block the domain and show a fake SSL
> cert. Test on mobile hotspot or home Wi‑Fi if you see a privacy error at work.

## Docs

| File | Purpose |
| --- | --- |
| [PROJECT.md](./PROJECT.md) | Architecture, API, admin guide, deployment |
| [DEPLOYMENT_SUMMARY.md](./DEPLOYMENT_SUMMARY.md) | Live ops, DNS, SSL, troubleshooting |
| [AGENTS.md](./AGENTS.md) | Cursor agent / VM operating notes |
| [backend/README.md](./backend/README.md) | Laravel app quick reference |
| [HOSTINGER_DEPLOY.md](./HOSTINGER_DEPLOY.md) | Production go-live on Hostinger |
| [frontend/README.md](./frontend/README.md) | React / Vite app quick reference |
