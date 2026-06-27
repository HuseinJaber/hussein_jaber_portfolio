# Hussein Jaber — Professional Developer Portfolio

> Hand-off brief for the next developer / Cursor agent. This document captures
> **what the project is, why it was built this way, what already works, and what
> to do next**. Read it top-to-bottom before making changes.

---

## 1. The vision (what the owner asked for)

A premium, **professional Full Stack Developer portfolio** designed to win clients
and get people to reach out for work. Requirements gathered from the owner:

- A polished, animated, modern public website that "acts like a $100k project".
- An **admin dashboard** to manage *everything* on the site (no code edits needed
  to update content). Changes in the admin must instantly reflect on the website.
- **Component-based** architecture everywhere (website **and** admin) so features
  can be added/changed easily later.
- Strong, secure foundation: **Laravel** backend + **MySQL**, a modern JS frontend
  (**Next.js / React**), with **Livewire + Alpine.js + Tailwind CSS + SCSS**.
- Tasteful animations (GSAP / Motion) for a high-end feel.
- Easy to **deploy on Hostinger** later (see §9).
- Two top-level folders in one repo: `backend/` and `frontend/`.
- The owner will populate real content (projects, etc.) from the admin dashboard.
  A CV was meant to be attached to seed real data — **it was not provided yet**, so
  the project ships with **realistic placeholder content** that must be replaced
  (see §8).

---

## 2. Tech stack & why

| Layer | Choice | Why |
| --- | --- | --- |
| Backend / API | **Laravel 13** (PHP 8.3) | Secure, batteries-included, perfect Hostinger support |
| Database | **MySQL 8** | Requested; ubiquitous on Hostinger |
| Admin dashboard | **Livewire 3 + Alpine.js + Tailwind** (Blade) | Server-rendered, secure, fast to build, fully component-based |
| Auth | **Laravel Breeze** (Livewire stack) + **Sanctum** | Battle-tested session auth; Sanctum ready for future token APIs |
| Public website | **Next.js 16 (App Router) + React 19 + TypeScript** | SEO-friendly SSR, great DX, modern animations |
| Styling (frontend) | **Tailwind CSS v4 + SCSS** | Utility-first speed + SCSS for richer custom effects |
| Animation | **Motion (Framer Motion) + GSAP** | Scroll reveals, layout animations, hero timeline |

**Why two apps instead of one?** The owner explicitly wanted a separate React/Next
frontend *and* a Livewire admin. So the public site is a decoupled Next.js app that
consumes a JSON API, while the admin is a classic, secure server-rendered Laravel
app. They share one MySQL database. This keeps the marketing site fast and SEO-ready
while the admin stays simple and secure.

---

## 3. Repository layout

```
hussein_jaber_portfolio/
├── backend/                 # Laravel API + Livewire admin dashboard
│   ├── app/
│   │   ├── Http/Controllers/Api/   # PortfolioController, ContactController
│   │   ├── Livewire/Admin/         # One component per resource (CRUD)
│   │   └── Models/                 # Profile, Project, Skill, Service, ...
│   ├── database/
│   │   ├── migrations/             # All content tables
│   │   └── seeders/DatabaseSeeder.php  # Admin user + placeholder content
│   ├── resources/views/
│   │   ├── components/admin/       # Reusable admin UI (input, card, button…)
│   │   ├── components/layouts/admin.blade.php  # Sidebar admin shell
│   │   └── livewire/admin/         # Admin component views
│   └── routes/{web.php, api.php}
│
├── frontend/                # Next.js public website
│   └── src/
│       ├── app/             # Routes: / (home), /projects/[slug]
│       ├── components/
│       │   ├── layout/      # Navbar, Footer
│       │   ├── sections/    # Hero, About, Services, Skills, Work, Experience,
│       │   │                #   Testimonials, Contact
│       │   └── ui/          # Reveal (motion), SectionHeading, Aurora, icons
│       ├── lib/             # api.ts (fetchers), types.ts
│       └── styles/          # aurora.scss (SCSS demo)
│
├── PROJECT.md               # ← you are here
├── AGENTS.md                # Operating notes for Cursor agents
└── README.md
```

---

## 4. Data model (MySQL tables)

| Table | Purpose |
| --- | --- |
| `users` | Admin login accounts |
| `profiles` | Singleton: name, title, headline, bio, about, contact, stats, availability, SEO |
| `social_links` | GitHub / LinkedIn / X / WhatsApp etc. |
| `skills` | Name, category, level (0–100) |
| `services` | What the owner offers to clients |
| `projects` | Portfolio work: tech stack (JSON), images, links, featured/published flags |
| `experiences` | Career timeline |
| `education` | Degrees / institutions |
| `testimonials` | Client quotes + rating |
| `contact_messages` | Submissions from the public contact form |

All content is managed from the admin dashboard and exposed (read-only) through the API.

---

## 5. Public API (consumed by the Next.js site)

Base URL: `http://localhost:8000/api`

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/portfolio` | Aggregate payload: profile, socials, skills, services, experiences, education, projects, testimonials |
| GET | `/projects` | All published projects |
| GET | `/projects/{slug}` | Single project |
| POST | `/contact` | Store a contact message (validated, honeypot + rate-limited `6/min`) |

The frontend fetches with `cache: "no-store"` so admin edits appear on refresh.

---

## 6. Admin dashboard

- URL: `http://localhost:8000/admin` (login at `/login`).
- **Seeded admin:** `admin@huseinjaber.com` / `password` — **change this immediately.**
- Every resource has its own Livewire component (`app/Livewire/Admin/*Manager.php`)
  with list + create/edit/delete, validation, and flash feedback. Reusable Blade
  UI lives in `resources/views/components/admin/`.
- Sections: Dashboard (stats + latest messages), Profile, Projects, Skills,
  Services, Experience, Education, Testimonials, Social Links, Messages.

---

## 7. Running locally (fresh machine)

**Prerequisites:** PHP 8.3 + extensions, Composer, Node 20+, MySQL 8.

```bash
# 1. Database (create db + user, or use your own and edit backend/.env)
mysql -u root -e "CREATE DATABASE portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -e "CREATE USER 'portfolio'@'localhost' IDENTIFIED BY 'portfolio_secret'; GRANT ALL ON portfolio.* TO 'portfolio'@'localhost'; FLUSH PRIVILEGES;"

# 2. Backend
cd backend
cp .env.example .env          # adjust DB_* if needed
composer install
npm install
php artisan key:generate
php artisan migrate --seed     # creates tables + placeholder content + admin user
npm run dev                    # compiles admin assets (Vite) — keep running
# in another terminal:
php artisan serve              # http://localhost:8000

# 3. Frontend
cd ../frontend
cp .env.example .env.local     # NEXT_PUBLIC_API_URL=http://localhost:8000/api
npm install
npm run dev                    # http://localhost:3000
```

Open the site at **http://localhost:3000** and the admin at **http://localhost:8000/admin**.

---

## 8. IMPORTANT — replace placeholder content

The CV was not provided, so all personal data is **placeholder**. Update it via the
admin dashboard (preferred) or in `backend/database/seeders/DatabaseSeeder.php`:

- Real bio, headline, contact details, location, social URLs, stats.
- Real projects (with images), skills levels, services, experience, education,
  testimonials.
- A real résumé PDF (drop it in `backend/public/files/` and set `resume_url`).
- SEO `meta_title` / `meta_description` on the Profile.
- **Change the admin password** and the seeded admin email.

> When the CV is available, hand it to the Cursor agent and ask it to update the
> seeder + profile content to match.

---

## 9. Deploying to Hostinger (roadmap)

**Backend (Laravel) — Hostinger shared hosting or VPS:**
1. Create a MySQL database + user in hPanel; put credentials in `.env`.
2. Upload `backend/` (or git deploy). Point the domain's document root to
   `backend/public`.
3. `composer install --no-dev --optimize-autoloader`, `php artisan key:generate`,
   `php artisan migrate --force`, `php artisan config:cache route:cache view:cache`.
4. `npm install && npm run build` for the admin assets.
5. Set `APP_ENV=production`, `APP_DEBUG=false`, correct `APP_URL` + `FRONTEND_URL`.

**Frontend (Next.js):**
- **Option A (recommended): Hostinger VPS / Node hosting** — run `npm run build`
  then `npm run start` behind a reverse proxy (or `pm2`).
- **Option B: static-friendly host (Vercel/Netlify)** — easiest for Next.js; point
  `NEXT_PUBLIC_API_URL` at the deployed Laravel API and enable CORS for that origin.

**Security checklist before launch:** strong admin password, `APP_DEBUG=false`,
HTTPS, lock CORS (`backend` `config/cors.php`) to the real frontend origin, keep
the contact-form rate limit, and consider adding captcha if spam appears.

---

## 10. Suggested next steps (backlog)

- Image **uploads** in the admin (currently image URLs) via Laravel filesystem.
- Email notification when a new contact message arrives (Laravel Mail/queue).
- Rich-text editor for project descriptions.
- Drag-and-drop ordering for projects/skills.
- Blog/articles section.
- Multi-language (the owner is in Lebanon — EN/AR could help).
- Automated tests for the frontend (Playwright) + more backend coverage.

---

## 11. Testing & quality

- Backend: `cd backend && php artisan test` (feature tests cover the API, contact
  validation, auth protection, and that every admin page renders). Formatting:
  `./vendor/bin/pint`.
- Frontend: `cd frontend && npm run lint` and `npm run build`.
