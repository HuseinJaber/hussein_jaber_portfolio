# Frontend — Next.js Public Site

Next.js 16 (App Router) + React 19 + TypeScript portfolio website. Server-renders
pages and fetches content from the Laravel API.

## Routes

| Path | Description |
| --- | --- |
| `/` | Home — configurable sections (hero, work, skills, …) |
| `/projects/[slug]` | Project detail page |
| `/cv` | Live résumé built from API data + PDF download |
| `/privacy`, `/terms`, `/cookies` | Legal pages |

## Quick start

```bash
cp .env.example .env.local
npm install
npm run dev    # http://localhost:3000
```

The backend **must be running** — the home page fetches `/api/portfolio` at request time.

| Variable | Purpose |
| --- | --- |
| `NEXT_PUBLIC_API_URL` | Laravel API base (e.g. `http://localhost:8000/api`) |
| `NEXT_PUBLIC_SITE_URL` | This site's public URL (metadata, canonical links) |

On macOS with **Laravel Herd**, `package.json` scripts set `NODE_EXTRA_CA_CERTS` so
Node trusts the local Valet CA when calling an HTTPS `.test` API during dev/build.

## Commands

```bash
npm run dev      # Development server
npm run lint     # ESLint
npm run build    # Production build
npm run start    # Serve production build
```

## Styling

Tailwind CSS v4 utilities are generated from the `@theme` block in `src/app/globals.css`
(e.g. `text-muted`, `bg-brand`). Custom effects live in SCSS (`src/styles/`).

Animation: **Motion** (Framer Motion) + **GSAP** for hero and scroll reveals.

## Privacy / analytics

Cookie consent (`CookieConsent.tsx`) gates lightweight first-party analytics
(page views, section engagement). No third-party trackers.

Full architecture: **[PROJECT.md](../PROJECT.md)**.
