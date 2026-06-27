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

**Run the frontend:**
```bash
cd frontend && npm run dev   # http://localhost:3000
```
- The home page is **server-rendered and fetches the Laravel API**, so the backend
  (`php artisan serve` on :8000) **must be running** or the page shows a
  "Backend not reachable" fallback. API base URL is `frontend/.env.local`
  (`NEXT_PUBLIC_API_URL`).

**Frontend styling gotcha:** Tailwind v4 auto-generates utilities from the
`@theme` block in `src/app/globals.css` (e.g. `--color-muted` → `text-muted`,
`--color-brand` → `bg-brand`). Use those generated class names — do **not** use
arbitrary `text-[--color-muted]` syntax.

**Tests / checks:**
- Backend: `cd backend && php artisan test`  ·  format: `./vendor/bin/pint`
- Frontend: `cd frontend && npm run lint && npm run build`

**Content is placeholder** (no CV was provided). Real data should be entered via the
admin dashboard or `backend/database/seeders/DatabaseSeeder.php`.
