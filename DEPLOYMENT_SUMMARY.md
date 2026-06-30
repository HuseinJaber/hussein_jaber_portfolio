# Deployment & operations summary — huseinjaber.com

Single reference for local development, production hosting, DNS, and common issues.
Detailed step-by-step deploy: [HOSTINGER_DEPLOY.md](./HOSTINGER_DEPLOY.md).

---

## Live URLs

| URL | Purpose | Document root (Hostinger) |
| --- | --- | --- |
| https://huseinjaber.com | Public portfolio (React SPA) | `~/domains/huseinjaber.com/public_html/frontend/` |
| https://api.huseinjaber.com | Laravel API + admin | `~/domains/huseinjaber.com/public_html/backend/public/` |
| https://api.huseinjaber.com/admin | Admin dashboard | same as API |

**Hosting IP:** `91.108.101.202`  
**SSH:** `ssh -p 65002 u841931881@91.108.101.202`  
**Git clone on server:** `~/hussein_jaber_portfolio/`

---

## Architecture

- **backend/** — Laravel 13 API + Livewire admin (MySQL)
- **frontend/** — React 19 + Vite SPA; static `dist/` on Hostinger
- Frontend calls API from the browser (`VITE_API_URL`)
- Admin is server-rendered Laravel; public site is decoupled SPA

---

## Local development

### Start services

```bash
# 1. Backend API + admin
cd backend && php artisan serve          # :8000 (or Herd: hussein_jaber_portfolio.test)
cd backend && npm run dev                # admin Tailwind assets

# 2. Frontend
cd frontend && npm run dev               # http://localhost:5173
```

Both must run. Frontend reads `frontend/.env.local` (`VITE_API_URL`).

**Admin login (local):** `admin@huseinjaber.com` / `password` — change in production.

### Local issues

| Symptom | Cause | Fix |
| --- | --- | --- |
| `localhost:5173` connection refused | Vite not running or crashed | `cd frontend && npm run dev`; wait for `VITE ready` |
| Turbopack / `.next` errors in terminal | Stale Next.js cache from pre-Vite migration | Stop dev server; `rm -rf frontend/.next`; restart Vite |
| Site loads but no content | Backend not running | Start `php artisan serve` (or Herd) |
| Backend not reachable | Wrong `VITE_API_URL` | Match backend URL in `frontend/.env.local` |

---

## Production DNS (Hostinger)

**Nameservers:** `cosmos.dns-parking.com`, `nova.dns-parking.com` (valid Hostinger NS)

**Required DNS records** (hPanel → Domains → huseinjaber.com → DNS):

| Type | Name | Value |
| --- | --- | --- |
| A | `@` | `91.108.101.202` |
| A | `api` | `91.108.101.202` |
| CNAME | `www` | `huseinjaber.com` |
| AAAA | `@`, `api` | Hostinger IPv6 (auto-filled) |

**Verify propagation:**

```bash
dig +short huseinjaber.com A      # expect 91.108.101.202
dig +short api.huseinjaber.com A  # expect 91.108.101.202
```

Wrong values (parking / block page):

- A: `208.91.112.55`
- Cert issuer: `Fortiguard SDNS Blocked Page`

---

## SSL / “Your connection is not private”

### Real DNS or hosting problem

- Domain still on parking IP → fix A records above, wait for propagation, enable SSL in hPanel
- Enable SSL: Websites → huseinjaber.com → SSL (both root and `api` subdomain)

### Corporate network blocking (FortiGuard)

On some **company Wi‑Fi**, Fortinet DNS filtering intercepts the domain:

- Browser shows `NET::ERR_CERT_AUTHORITY_INVALID`
- Certificate issuer: **Fortiguard SDNS Blocked Page** (self-signed)
- `dig` returns `208.91.112.55` instead of `91.108.101.202`
- **Mobile hotspot works** — site and SSL are fine

This is **not** a hosting bug. Ask IT to whitelist `huseinjaber.com` and `api.huseinjaber.com`, or test on home/mobile network.

---

## Routine deploy

**Mac — build & push:**

```bash
cd frontend && VITE_API_URL=https://api.huseinjaber.com/api VITE_SITE_URL=https://huseinjaber.com npm run build
git add -f dist/ && git commit -m "Update frontend build" && git push
```

**Hostinger SSH — sync:**

```bash
cd ~/hussein_jaber_portfolio && git pull
./scripts/hostinger-sync.sh
cd ~/domains/huseinjaber.com/public_html/backend
composer install --no-dev --optimize-autoloader
php artisan config:cache && php artisan migrate --force
```

---

## Production checklist

- [ ] DNS A records → `91.108.101.202`
- [ ] SSL enabled (both domains)
- [ ] `APP_DEBUG=false`, admin password changed
- [ ] `FRONTEND_URL=https://huseinjaber.com` (CORS)
- [ ] `https://api.huseinjaber.com/api/portfolio` returns JSON
- [ ] `https://huseinjaber.com` loads with content
- [ ] `.htaccess` in `public_html/frontend/` (SPA routing)

---

## Troubleshooting quick reference

| Problem | Fix |
| --- | --- |
| Privacy error on company Wi‑Fi only | FortiGuard block — use hotspot or ask IT to whitelist |
| Privacy error everywhere | DNS not propagated or SSL not issued — check `dig`, fix A records, enable SSL |
| `localhost:5173` refused | Start/restart `npm run dev` in `frontend/` |
| API 500 | `backend/storage/logs/laravel.log`; fix `storage/` permissions |
| CORS error | `FRONTEND_URL=https://huseinjaber.com` (no trailing slash) |
| SPA routes 404 | `.htaccess` missing in `public_html/frontend/` |
| Admin unstyled | `npm run build` in `backend/`; upload `public/build/` |
| Empty portfolio | DB not imported or wrong `DB_*` in `.env` |

---

## Related docs

| File | Purpose |
| --- | --- |
| [README.md](./README.md) | Project overview |
| [PROJECT.md](./PROJECT.md) | Architecture & content guide |
| [HOSTINGER_DEPLOY.md](./HOSTINGER_DEPLOY.md) | Full first-time deploy |
| [AGENTS.md](./AGENTS.md) | Cursor / VM notes |
