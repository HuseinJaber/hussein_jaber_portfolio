# Frontend — React Public Site (Vite)

React 19 + TypeScript + Vite SPA for the portfolio website. Fetches content from
the Laravel API at runtime. Builds to static files for **Hostinger shared hosting**.

> **Next.js backup:** The original Next.js 16 frontend is preserved in
> `frontend-nextjs-backup/` and `frontend-nextjs-backup.zip` at the repo root.

## Routes

| Path | Description |
| --- | --- |
| `/` | Home — configurable sections (hero, work, skills, …) |
| `/projects/:slug` | Project detail page |
| `/cv` | Live résumé built from API data + PDF download |
| `/privacy`, `/terms`, `/cookies` | Legal pages |

## Quick start

```bash
cp .env.example .env.local
npm install
npm run dev    # http://localhost:5173
```

The backend **must be running** — pages fetch `/api/portfolio` from the browser.

| Variable | Purpose |
| --- | --- |
| `VITE_API_URL` | Laravel API base (e.g. `http://localhost:8000/api`) |
| `VITE_SITE_URL` | This site's public URL (canonical links) |

## Commands

```bash
npm run dev      # Vite dev server
npm run lint     # ESLint
npm run build    # Production build → dist/
npm run preview  # Preview production build locally
```

## Hostinger deployment

1. Set production env before build:
   ```bash
   VITE_API_URL=https://api.huseinjaber.com/api
   VITE_SITE_URL=https://huseinjaber.com
   npm run build
   ```
2. Upload **everything inside** `dist/` to the domain's `public_html`.
3. `public/.htaccess` is copied into `dist/` automatically for SPA routing.

Backend (Laravel) deploys separately to `api.huseinjaber.com` on the same Hostinger plan.

## Styling

Tailwind CSS v4 utilities are generated from the `@theme` block in `src/globals.css`
(e.g. `text-muted`, `bg-brand`). Custom effects live in SCSS (`src/styles/`).

Animation: **Motion** + **GSAP** for hero and scroll reveals.

## Privacy / analytics

Cookie consent (`CookieConsent.tsx`) gates lightweight first-party analytics
(page views, section engagement). No third-party trackers.

Full architecture: **[PROJECT.md](../PROJECT.md)**.
