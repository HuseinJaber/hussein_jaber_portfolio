# Hussein Jaber — Professional Developer Portfolio

> Hand-off brief for the next developer / Cursor agent. This document captures
> **what the project is, why it was built this way, what already works, and what
> to do next**. Read it top-to-bottom before making changes.

---

## 1. The vision

A premium, **professional Full Stack Developer portfolio** designed to win clients
and get people to reach out for work. Core requirements:

- A polished, animated, modern public website with a high-end feel.
- An **admin dashboard** to manage *everything* on the site (no code edits needed
  for day-to-day content). Changes in the admin appear on the website after refresh.
- **Component-based** architecture everywhere (website **and** admin).
- Strong, secure foundation: **Laravel** backend + **MySQL**, **Next.js / React**
  frontend, **Livewire + Alpine.js + Tailwind** admin.
- Tasteful animations (GSAP / Motion).
- Easy to **deploy on Hostinger** later (see §9).
- Two top-level folders in one repo: `backend/` and `frontend/`.

**Current content status:** Profile, experience, education, certifications, services,
skills, and testimonials are seeded from the owner's CV. Projects (~90) are imported
from local development folders via `DocumentProjectScanner` / `DocumentProjectsSeeder`.
The résumé PDF lives at `backend/public/files/hussein-jaber-cv.pdf` with
`resume_url` set on the profile.

---

## 2. Tech stack & why

| Layer | Choice | Why |
| --- | --- | --- |
| Backend / API | **Laravel 13** (PHP 8.3) | Secure, batteries-included, perfect Hostinger support |
| Database | **MySQL 8** | Requested; ubiquitous on Hostinger |
| Admin dashboard | **Livewire 3 + Alpine.js + Tailwind** (Blade) | Server-rendered, secure, fast to build, fully component-based |
| Auth | **Laravel Breeze** (Livewire stack) + **Sanctum** | Session auth for admin; Sanctum ready for future token APIs |
| Public website | **React 19 + Vite + TypeScript** (SPA) | Static build for Hostinger shared hosting; same animations & API |
| Styling (frontend) | **Tailwind CSS v4 + SCSS** | Utility-first speed + SCSS for richer custom effects |
| Animation | **Motion (Framer Motion) + GSAP** | Scroll reveals, layout animations, hero timeline |

**Why two apps?** The public site is a decoupled React SPA consuming a JSON API.
The admin is a classic server-rendered Laravel app. They share one MySQL database —
marketing site stays fast on static hosting; admin stays simple and secure.

---

## 3. Repository layout

```
hussein_jaber_portfolio/
├── backend/                 # Laravel API + Livewire admin dashboard
│   ├── app/
│   │   ├── Http/Controllers/Api/   # Portfolio, Contact, Newsletter, Analytics, Certifications
│   │   ├── Livewire/Admin/         # One component per admin resource
│   │   ├── Livewire/Concerns/      # ManagesCancelledRecords, ReordersRecords
│   │   ├── Mail/                   # Contact + newsletter mailables, ContactReplyMail
│   │   ├── Models/                 # Profile, Project, ProjectCategory, TechStack, …
│   │   ├── Services/               # PortfolioNotifier, DocumentProjectScanner
│   │   └── Support/                # PortfolioSections, PortfolioMail
│   ├── config/
│   │   ├── portfolio.php           # owner email, registration flag
│   │   ├── portfolio_sections.php  # homepage section definitions + default copy
│   │   └── portfolio_engagement.php # project scanner engagement overrides
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   │       ├── DatabaseSeeder.php      # profile, skills, experience, certs, …
│   │       └── DocumentProjectsSeeder.php  # scans local project folders
│   ├── resources/views/
│   │   ├── components/admin/       # input, card, button, modal, multi-select dropdown…
│   │   ├── components/layouts/admin.blade.php
│   │   ├── livewire/admin/
│   │   └── mail/                   # HTML email templates
│   └── routes/{web.php, api.php}
│
├── frontend/                # React + Vite public website (SPA)
│   └── src/
│       ├── pages/           # Home, Project, Cv, Privacy, Terms, Cookies
│       ├── components/
│       │   ├── layout/      # Navbar, Footer, ScrollProgress, FooterNewsletter
│       │   ├── sections/    # Hero, About, Services, Skills, Work, Experience,
│       │   │                #   Certifications, Testimonials, Contact
│       │   ├── cv/          # CvActions
│       │   ├── ui/          # Reveal, SectionHeading, Aurora, AnimatedCounter, icons
│       │   ├── AnalyticsTracker.tsx, CookieConsent.tsx, PageMeta.tsx, …
│       │   └── lib/         # api.ts, types.ts, sections.ts, cv.ts
│       └── styles/          # aurora.scss
│
├── frontend-nextjs-backup/  # Archived Next.js frontend (optional local copy)
├── HusseinJaberCV.pdf       # source CV (copy also in backend/public/files/)
├── PROJECT.md               # ← you are here
├── AGENTS.md                # Operating notes for Cursor agents
└── README.md
```

---

## 4. Data model (MySQL tables)

| Table | Purpose |
| --- | --- |
| `users` | Admin accounts (`is_admin` flag; public registration disabled by default) |
| `profiles` | Singleton: identity, contact, stats, SEO, `sections` (visibility), `section_order`, `section_copy` |
| `social_links` | GitHub, LinkedIn, Facebook, WhatsApp, etc. |
| `skills` | Name, category, level (0–100), sort order |
| `services` | What the owner offers to clients |
| `project_categories` | Named categories (Web, E-Commerce, WordPress, …) with slug + sort order |
| `tech_stacks` | Named technologies (Laravel, Livewire, MySQL, …) with slug + sort order |
| `projects` | Title, slug, descriptions, URLs, engagement (`development`/`support`), work context (`none`/`company`/`freelance`), optional `experience_id`, `sites_count`, featured/published, sort order |
| `project_project_category` | Many-to-many: projects ↔ categories |
| `project_tech_stack` | Many-to-many: projects ↔ tech stacks |
| `experiences` | Career timeline; `Experience::webAddicts()` helper for TheWebAddicts link |
| `education` | Degrees / institutions |
| `certifications` | Credentials; optional uploaded PDF (`credential_file`) served via API |
| `testimonials` | Client quotes + rating |
| `contact_messages` | Contact form submissions (`is_read` flag) |
| `newsletter_subscribers` | Footer signup emails |
| `analytics_events` | Lightweight page/section view tracking from the frontend |

**Soft cancel:** Most content tables include a `cancelled` boolean. Admin **Cancel**
hides records from the public site but keeps them in the database with a **Cancelled**
tab for restore. Implemented via `HasCancelled` + `ManagesCancelledRecords`.

**Normalization:** Category and tech-stack names are canonicalized on save
(e.g. `E-commerce` → `E-Commerce`, `Laravel 13.8` → `Laravel`) to prevent duplicates.

---

## 5. Public API (consumed by the React site)

Base URL: `http://localhost:8000/api` (or your Herd `.test` domain + `/api`)

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/portfolio` | Aggregate payload: profile (with sections/copy), socials, skills, services, experiences, education, certifications, projects, testimonials |
| GET | `/projects` | All published, non-cancelled projects |
| GET | `/projects/{slug}` | Single project with categories, tech stack, experience link |
| GET | `/certifications/{id}/credential` | Download certification PDF (if uploaded) |
| POST | `/contact` | Store contact message + send auto-reply emails (honeypot, `6/min`) |
| POST | `/newsletter` | Subscribe + welcome email (`10/min`) |
| POST | `/analytics` | Record page/section view (`120/min`) |

The frontend fetches with `cache: "no-store"` so admin edits appear on refresh.

---

## 6. Admin dashboard

- URL: `http://localhost:8000/admin` (login at `/login`).
- **Seeded admin:** `admin@huseinjaber.com` / `password` — **change this immediately.**
- Reusable Blade UI in `resources/views/components/admin/`.
- Livewire components in `app/Livewire/Admin/*`.

| Section | Route | Notes |
| --- | --- | --- |
| Dashboard | `/admin` | Stats + latest messages |
| Profile | `/admin/profile-content` | Identity, contact, stats, SEO, resume URL |
| Sections | `/admin/sections` | Toggle homepage blocks; edit nav labels + section headings (accordion UI) |
| Projects | `/admin/projects` | List with drag reorder; add/edit on separate pages |
| ↳ Add / Edit | `/admin/projects/create`, `/admin/projects/{id}/edit` | Compact category + tech-stack dropdowns |
| Project categories | `/admin/project-categories` | Manage category names |
| Tech stacks | `/admin/tech-stacks` | Manage stack names |
| Skills | `/admin/skills` | Drag reorder |
| Services | `/admin/services` | |
| Experience | `/admin/experience` | Projects can link to a company experience |
| Education | `/admin/education` | |
| Certifications | `/admin/certifications` | Optional PDF upload |
| Testimonials | `/admin/testimonials` | |
| Social Links | `/admin/socials` | |
| Messages | `/admin/messages` | Read/unread; **Reply** sends email from admin |
| Newsletter | `/admin/newsletter` | Subscriber list |
| Analytics | `/admin/analytics` | View tracked events |

**Projects:** Each project has an **engagement type** (Development vs Support), optional
**work context** (linked to an experience / freelance), **multiple categories**, and
**multiple tech stacks**. Default new projects link to TheWebAddicts Full Stack Developer
experience when that record exists.

**Messages:** Reply opens an in-admin compose modal; sends `ContactReplyMail` to the
visitor with Reply-To set to the owner inbox (`MAIL_OWNER_ADDRESS` or profile email).

---

## 7. Running locally (fresh machine)

**Prerequisites:** PHP 8.3 + extensions, Composer, Node 20+, MySQL 8.
(Laravel Herd on macOS is optional but supported — see `backend/.env.example`.)

```bash
# 1. Database
mysql -u root -e "CREATE DATABASE portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -e "CREATE USER 'portfolio'@'localhost' IDENTIFIED BY 'portfolio_secret'; GRANT ALL ON portfolio.* TO 'portfolio'@'localhost'; FLUSH PRIVILEGES;"

# 2. Backend
cd backend
cp .env.example .env          # adjust DB_*, APP_URL, FRONTEND_URL, MAIL_* as needed
composer install
npm install
php artisan key:generate
php artisan migrate --seed     # tables + CV content + admin user
# Optional on owner's Mac — import projects from /Library/WebServer/Documents:
# php artisan db:seed --class=DocumentProjectsSeeder
npm run dev                    # Vite — keep running for admin styles
php artisan serve              # http://localhost:8000 (or use Herd)

# 3. Frontend
cd ../frontend
cp .env.example .env.local     # VITE_API_URL=http://localhost:8000/api
npm install
npm run dev                    # http://localhost:5173
```

Open the site at **http://localhost:5173** and the admin at **http://localhost:8000/admin**.

---

## 8. Content management

**Preferred:** edit everything through the admin dashboard.

**Seeders:**
- `DatabaseSeeder` — admin user, profile (real CV data), skills, services, experience,
  education, certifications, testimonials, social links, sample contact message.
- `DocumentProjectsSeeder` — scans `DocumentProjectScanner::DOCUMENTS_ROOT`
  (`/Library/WebServer/Documents` on the owner's Mac) and upserts projects with
  auto-detected categories, tech stacks, and engagement types. Override folder lists
  in `config/portfolio_engagement.php`.

**Résumé:** PDF at `backend/public/files/hussein-jaber-cv.pdf`; `resume_url` on profile
is `/files/hussein-jaber-cv.pdf`.

**Homepage sections:** Defaults and copy live in `config/portfolio_sections.php`.
Runtime values are stored on `profiles.sections`, `section_order`, and `section_copy`
and edited in Admin → Sections. Hero content always comes from Profile fields.

**Email (production):** Set real SMTP (or Mailgun/Resend) in `.env`. Key vars:
`MAIL_MAILER`, `MAIL_FROM_ADDRESS`, `MAIL_OWNER_ADDRESS`. Local dev uses `log` driver.

---

## 9. Deploying to production

**Hostinger (Premium shared hosting):** see **[HOSTINGER_DEPLOY.md](./HOSTINGER_DEPLOY.md)** for the
full go-live checklist (`huseinjaber.com` + `api.huseinjaber.com`).

### First-time server setup

**Backend (Laravel) — Hostinger / shared hosting:**

1. Clone the repo and `cd backend`.
2. Copy env: `cp .env.example .env` (or `cp .env.production.example .env`) then edit:
   - `APP_ENV=production`, `APP_DEBUG=false`
   - `APP_URL` — public backend URL (e.g. `https://api.huseinjaber.com`)
   - `FRONTEND_URL` — public React site URL (e.g. `https://huseinjaber.com`) — **required for CORS**
   - `DB_*` — production MySQL credentials (Hostinger hPanel)
   - `MAIL_*` and `MAIL_OWNER_ADDRESS` — real SMTP transport
3. Install and optimize:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan key:generate    # first deploy only
   npm ci && npm run build     # admin Vite assets → public/build/
   php artisan storage:link    # certification PDFs + uploads
   php artisan config:cache route:cache view:cache
   ```
4. **Import** your local database dump via phpMyAdmin (preferred for first deploy), or
   `php artisan migrate --force` on an empty database only.
5. Point the **api** subdomain document root to `backend/public`.
6. Ensure `storage/` and `bootstrap/cache/` are writable.

**Frontend (React + Vite) — static files on main domain:**

1. On your Mac (or CI), set production env and build:
   ```bash
   cd frontend
   VITE_API_URL=https://api.huseinjaber.com/api \
   VITE_SITE_URL=https://huseinjaber.com \
   npm run build
   ```
2. Upload **everything inside** `frontend/dist/` to `public_html` (includes `.htaccess` for SPA routing).

### Pulling updates (routine deploy)

```bash
git pull origin main

# Backend (on server via SSH)
cd backend
composer install --no-dev --optimize-autoloader
php artisan migrate --force
npm ci && npm run build
php artisan config:cache route:cache view:cache

# Frontend: rebuild with production VITE_* vars, re-upload dist/ to public_html
```

### Hostinger notes

- **Premium shared hosting:** Laravel on `api` subdomain; React `dist/` on main domain `public_html`.
- **VPS:** nginx reverse proxy — optional if you upgrade later.
- Re-run `php artisan migrate --force` after every pull that includes new migrations.
- Export local DB before first deploy: `./scripts/export-database.sh`

### Security checklist

- Change seeded admin password immediately.
- `APP_DEBUG=false`, HTTPS everywhere.
- `FRONTEND_URL` matches the real frontend origin (CORS is locked to `FRONTEND_URL` + `APP_URL`).
- `PORTFOLIO_REGISTRATION_ENABLED=false` unless you need public sign-up.
- Configure real mail transport; verify contact form + newsletter delivery.
- Optional: run queue worker for mail (`php artisan queue:work`) if using `QUEUE_CONNECTION=database`.

### CI

GitHub Actions (`.github/workflows/ci.yml`) runs backend tests + asset build and
frontend lint + build on every push/PR to `main`.

---

## 10. Suggested next steps (backlog)

- Image **uploads** in the admin (cover images are still URL fields).
- Rich-text editor for project descriptions.
- Blog / articles section.
- Multi-language (EN/AR).
- Frontend E2E tests (Playwright).
- Queue workers for mail in production (`QUEUE_CONNECTION=database` is already configured).

---

## 11. Testing & quality

**Backend** (`cd backend && php artisan test`):
- `PortfolioTest` — public API, contact/newsletter validation, admin page smoke tests.
- `SectionSettingsTest` — section visibility + copy via API.
- `MessageReplyTest` — admin contact reply email.
- `SecurityTest` — auth / admin middleware.
- Breeze auth tests under `tests/Feature/Auth/`.

Formatting: `./vendor/bin/pint`.

**Frontend:** `cd frontend && npm run lint && npm run build`.
