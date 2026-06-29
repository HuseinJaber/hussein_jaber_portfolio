# AGENTS.md

This repo contains **two apps** that run together:

- `backend/` — Laravel 13 API + Livewire admin dashboard (MySQL).
- `frontend/` — Next.js 16 public website that reads the Laravel API.

For full architecture, local setup, content guide and Hostinger deployment, read
**[PROJECT.md](./PROJECT.md)**. Standard commands live in each app's
`composer.json` / `package.json` and in `PROJECT.md` — don't duplicate them; the
notes below only capture non-obvious, durable gotchas.

## Cursor Cloud specific instructions

**System deps** (PHP 8.3 + ext, Composer, Node 20+, MySQL 8) are provided by the VM
image. The startup/update script only refreshes app dependencies
(`composer install`, `npm install` in both apps). Migrations, builds and service
startup are intentionally **not** in the update script — do them as below.

**Start MySQL every session (no systemd in the VM):**
```bash
sudo service mysql start
```
- Connect as the app user over **TCP**, not the socket:
  `mysql -uportfolio -pportfolio_secret -h127.0.0.1 portfolio`. A non-root socket
  login fails with `ERROR 2002 ... mysqld.sock (13)` — this is expected; use `-h127.0.0.1`.
- DB `portfolio` / user `portfolio` / pass `portfolio_secret` already exist and the
  schema + seed data persist in the VM image. If the DB is ever empty, run
  `cd backend && php artisan migrate --seed`.

**Run the backend (API + admin):**
```bash
cd backend && php artisan serve --host=0.0.0.0 --port=8000   # API + Livewire admin
cd backend && npm run dev                                    # Vite: compiles admin (Tailwind) assets
```
- Admin lives at `/admin` (login `/login`). `/dashboard` just **redirects to `/admin`**.
- Seeded admin: `admin@huseinjaber.com` / `password`.
- If the admin looks unstyled, the Vite dev server isn't running (or run `npm run build` once).
- On the owner's Mac, **Laravel Herd** may serve the backend at
  `https://hussein_jaber_portfolio.test` instead of `:8000` — match `APP_URL` in
  `backend/.env` and `NEXT_PUBLIC_API_URL` in `frontend/.env.local`.

**Run the frontend:**
```bash
cd frontend && npm run dev   # http://localhost:3000
```
- The home page is **server-rendered and fetches the Laravel API**, so the backend
  **must be running** or the page shows a "Backend not reachable" fallback.
  API base URL is `frontend/.env.local` (`NEXT_PUBLIC_API_URL`).

**Frontend styling gotcha:** Tailwind v4 auto-generates utilities from the
`@theme` block in `src/app/globals.css` (e.g. `--color-muted` → `text-muted`,
`--color-brand` → `bg-brand`). Use those generated class names — do **not** use
arbitrary `text-[--color-muted]` syntax.

**Admin patterns worth knowing:**
- Most resources use **Active / Cancelled** tabs (`ManagesCancelledRecords`) — records
  are soft-cancelled, not hard-deleted.
- Sortable lists (projects, skills, categories, etc.) use drag-and-drop via
  `initAdminSortable` in `resources/js/app.js`.
- **Projects** add/edit live on dedicated pages (`/admin/projects/create`,
  `/admin/projects/{id}/edit`), not in a modal. Categories and tech stacks use
  compact multi-select dropdowns; manage options under **Project categories** and
  **Tech stacks**.
- **Messages → Reply** sends email from the admin (`ContactReplyMail`); configure
  `MAIL_*` and `MAIL_OWNER_ADDRESS` in `backend/.env` for real delivery. With
  `MAIL_MAILER=log`, output goes to `storage/logs/laravel.log`.
- **Sections** admin toggles homepage blocks and per-section copy (nav label,
  eyebrow, title, subtitle). Hero copy still comes from **Profile**.

**Project import gotcha:** `DocumentProjectsSeeder` scans
`/Library/WebServer/Documents` via `DocumentProjectScanner` — that path only
exists on the owner's machine. In CI / Cursor Cloud it no-ops harmlessly. Re-import
locally with:
```bash
cd backend && php artisan db:seed --class=DocumentProjectsSeeder
```
Edit engagement overrides in `config/portfolio_engagement.php` before re-seeding.

**Category / stack normalization:** `ProjectCategory::normalizeName()` and
`TechStack::normalizeName()` dedupe variants (e.g. `E-commerce` → `E-Commerce`,
`Laravel 13.8` → `Laravel`). Migrations `282000` and `283000` consolidated
existing duplicates — run `php artisan migrate` on stale databases.

**Tests / checks:**
- Backend: `cd backend && php artisan test`  ·  format: `./vendor/bin/pint`
- Frontend: `cd frontend && npm run lint && npm run build`
- CI: `.github/workflows/ci.yml` runs both on push/PR to `main`.

**Content:** Profile, experience, certifications and core copy are seeded from the
owner's CV in `DatabaseSeeder.php`. ~90 projects are imported from local dev
folders via `DocumentProjectsSeeder`. Further edits go through the admin dashboard.
The public résumé page is at `/cv` (API-driven + PDF download from `profile.resume_url`).
