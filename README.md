# Hussein Jaber — Portfolio

A professional, animated Full Stack Developer portfolio with a content-managed
admin dashboard.

- **`backend/`** — Laravel 13 API + Livewire/Alpine/Tailwind admin dashboard (MySQL).
- **`frontend/`** — Next.js 16 (React, TypeScript) public website with Tailwind, SCSS,
  Motion & GSAP animations, consuming the Laravel API.

Manage everything (profile, projects, skills, services, experience, testimonials,
social links, messages) from the admin dashboard — changes reflect on the live site.

## Quick start

See **[PROJECT.md](./PROJECT.md)** for the full brief, architecture, local setup,
content guide, and Hostinger deployment steps.

```bash
# Backend (http://localhost:8000)
cd backend && composer install && npm install && cp .env.example .env \
  && php artisan key:generate && php artisan migrate --seed
npm run dev        # admin assets
php artisan serve  # API + admin

# Frontend (http://localhost:3000)
cd frontend && npm install && cp .env.example .env.local && npm run dev
```

Admin: <http://localhost:8000/admin> — seeded login `admin@huseinjaber.com` / `password`
(**change immediately**).
