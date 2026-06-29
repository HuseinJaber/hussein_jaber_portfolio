# Hostinger production deployment — huseinjaber.com

Step-by-step guide for **Premium Web Hosting** (shared hosting). Both apps run on
the same Hostinger plan — no VPS or Vercel required.

| URL | App | Document root |
| --- | --- | --- |
| `https://huseinjaber.com` | React SPA (Vite build) | `public_html/` |
| `https://api.huseinjaber.com` | Laravel API + admin | `backend/public/` |

---

## Phase 1 — Push to GitHub (local Mac)

### 1. Pre-push checks

```bash
cd backend && php artisan test && ./vendor/bin/pint --test && npm run build
cd ../frontend && npm run lint && npm run build
```

### 2. Export your local database

Your production site should use the **same content** you have locally (profile,
~90 projects, certifications, etc.).

```bash
# From repo root — adjust credentials if your local .env differs
mysqldump -u portfolio -pportfolio_secret -h127.0.0.1 portfolio \
  --single-transaction --routines --triggers \
  > portfolio_export.sql
```

Keep `portfolio_export.sql` **outside** the repo (or in a folder gitignored). You
will import it in hPanel after the backend is set up.

### 3. Commit and push

Stage the Vite migration and docs (do **not** commit `.env`, `node_modules`,
`vendor/`, or `frontend/dist/`):

```bash
git add -A
git restore --staged frontend-nextjs-backup.zip portfolio_export.sql 2>/dev/null || true
git status   # review
git commit -m "Migrate frontend to Vite SPA for Hostinger shared hosting"
git push origin main
```

---

## Phase 2 — Hostinger setup (hPanel)

### Step 1 — Attach domain to hosting

1. hPanel → **Websites** → your **Premium Web Hosting** plan.
2. **Add website** → select **huseinjaber.com** (or connect existing domain).
3. Wait until the site shows as active.

### Step 2 — Create API subdomain

1. **Websites** → **huseinjaber.com** → **Subdomains** (or **Domains** → **Subdomains**).
2. Create subdomain: **`api`** → `api.huseinjaber.com`.
3. Note the folder Hostinger creates (often `domains/api.huseinjaber.com/public_html` or
   similar under your account home).

### Step 3 — Enable SSL

1. **Websites** → **huseinjaber.com** → **SSL** → enable free SSL.
2. Repeat for **api.huseinjaber.com**.

DNS for domains on the same Hostinger account is usually automatic. If the site
does not resolve within an hour, check **Domains → huseinjaber.com → DNS** — the
main domain and `api` should point to Hostinger (not a parking page).

---

## Phase 3 — Deploy backend (api.huseinjaber.com)

### Step 4 — SSH or File Manager

Use **SSH** (hPanel → **Advanced → SSH Access**) if available; otherwise use
**File Manager**.

### Step 5 — Clone the repo

SSH example (paths vary by account — check hPanel **File Manager** for yours):

```bash
cd ~
git clone git@github.com:HuseinJaber/hussein_jaber_portfolio.git
cd hussein_jaber_portfolio/backend
```

If Git SSH is not set up on Hostinger, use HTTPS or upload a zip of the repo via
File Manager.

### Step 6 — Create MySQL database

1. hPanel → **Databases → MySQL Databases**.
2. Create database (e.g. `u123456789_portfolio`).
3. Create user + strong password; grant **all privileges** on that database.
4. Note: **host** is often `localhost` on shared hosting (not `127.0.0.1`).

### Step 7 — Configure `.env`

```bash
cd ~/hussein_jaber_portfolio/backend
cp .env.example .env
nano .env   # or edit in File Manager
```

Set at minimum:

```env
APP_NAME="Hussein Jaber Portfolio"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.huseinjaber.com

FRONTEND_URL=https://huseinjaber.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456789_portfolio
DB_USERNAME=u123456789_user
DB_PASSWORD=your_strong_password

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@huseinjaber.com"
MAIL_OWNER_ADDRESS="HusseinJaber5@hotmail.com"

PORTFOLIO_REGISTRATION_ENABLED=false
```

Generate app key (first deploy only):

```bash
php artisan key:generate
```

### Step 8 — Install dependencies and build admin assets

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
```

If `composer` or `npm` are not available over SSH, use Hostinger’s **PHP**
version selector (8.3+) and run builds locally, then upload `vendor/` and
`public/build/` — prefer SSH when possible.

### Step 9 — Import database

**Option A — phpMyAdmin (recommended for first deploy):**

1. hPanel → **Databases → phpMyAdmin** → select your database.
2. **Import** → choose `portfolio_export.sql` from your Mac.
3. Wait for success.

**Option B — SSH:**

```bash
mysql -u DB_USER -p DB_NAME < ~/portfolio_export.sql
```

After import, confirm tables exist (`profiles`, `projects`, `users`, …).

### Step 10 — Laravel setup commands

```bash
cd ~/hussein_jaber_portfolio/backend
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Do **not** run `migrate --seed` if you imported your full local database — that
would duplicate or conflict with seeded data. Only run migrations on a **fresh**
empty database:

```bash
php artisan migrate --force   # only if you did NOT import a dump
```

### Step 11 — Point subdomain document root to `public/`

In hPanel → **Websites** → **api.huseinjaber.com** → **Advanced** or
**Document root**:

Set document root to:

```
~/hussein_jaber_portfolio/backend/public
```

(Use the full path Hostinger shows in File Manager.)

### Step 12 — Writable directories

Ensure these are writable (755 or 775):

- `backend/storage/` (recursive)
- `backend/bootstrap/cache/`

In File Manager: right-click → **Permissions** → `storage` and `bootstrap/cache`.

### Step 13 — Verify backend

- `https://api.huseinjaber.com/api/portfolio` → JSON payload
- `https://api.huseinjaber.com/admin` → login page
- Log in → **change the default password** immediately

---

## Phase 4 — Deploy frontend (huseinjaber.com)

### Step 14 — Production build (on your Mac)

Env vars are **baked in at build time** for Vite:

```bash
cd frontend
VITE_API_URL=https://api.huseinjaber.com/api \
VITE_SITE_URL=https://huseinjaber.com \
npm run build
```

### Step 15 — Upload `dist/` to `public_html`

Upload **everything inside** `frontend/dist/` to the main site’s `public_html`:

- `index.html`
- `assets/` folder
- `.htaccess` (SPA routing — required)
- `favicon.ico`

**Clear** any default Hostinger `index.php` or placeholder files first.

You can upload via File Manager, FTP, or:

```bash
# Example — adjust remote path to your Hostinger account
rsync -avz --delete frontend/dist/ user@host:/home/user/domains/huseinjaber.com/public_html/
```

### Step 16 — Verify frontend

- `https://huseinjaber.com` — homepage loads with your content
- `https://huseinjaber.com/projects/<slug>` — project detail (no 404)
- `https://huseinjaber.com/cv` — résumé page
- Contact form submits (check admin → Messages)

If routes 404, confirm `.htaccess` is present in `public_html` and **mod_rewrite**
is enabled (default on Hostinger).

---

## Phase 5 — Post-launch

### Email (when ready)

Contact form and admin replies need real SMTP. Options:

1. **Hostinger Email** — create `hello@huseinjaber.com`, use SMTP in `.env`.
2. **Resend / Brevo** — free tier; add DNS records on the domain.

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=hello@huseinjaber.com
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
```

Then: `php artisan config:cache`

### Routine updates

```bash
cd ~/hussein_jaber_portfolio
git pull origin main

cd backend
composer install --no-dev --optimize-autoloader
php artisan migrate --force
npm ci && npm run build
php artisan config:cache route:cache view:cache

# Frontend: rebuild on Mac with production VITE_* vars, re-upload dist/
```

### Security checklist

- [ ] Admin password changed from `password`
- [ ] `APP_DEBUG=false`
- [ ] HTTPS on both domains
- [ ] `FRONTEND_URL=https://huseinjaber.com` (CORS)
- [ ] `PORTFOLIO_REGISTRATION_ENABLED=false`

---

## Troubleshooting

| Problem | Fix |
| --- | --- |
| API returns 500 | Check `backend/storage/logs/laravel.log`; fix permissions on `storage/` |
| CORS error in browser | `FRONTEND_URL` must exactly match `https://huseinjaber.com` (no trailing slash) |
| Admin unstyled | Run `npm run build` in `backend/`; ensure `public/build/` exists |
| SPA routes 404 | `.htaccess` missing in `public_html` |
| Empty portfolio | Database not imported or wrong `DB_*` in `.env` |
| CV PDF 404 | File at `backend/public/files/hussein-jaber-cv.pdf`; run `storage:link` |

---

## Quick reference

```bash
# Local export before first deploy
mysqldump -u portfolio -pportfolio_secret -h127.0.0.1 portfolio > portfolio_export.sql

# Production frontend build
cd frontend && VITE_API_URL=https://api.huseinjaber.com/api VITE_SITE_URL=https://huseinjaber.com npm run build
```

See also **[PROJECT.md](./PROJECT.md)** and **[frontend/README.md](./frontend/README.md)**.
