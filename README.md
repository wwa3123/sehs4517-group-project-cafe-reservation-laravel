# Chit-Chat Cafe

A Laravel 12 reservation platform for a board-game cafe. Customers can reserve tables, earn and redeem loyalty tokens, and join events; administrators manage reservations and event capacity.

## Highlights

- Database-enforced table/time-slot/day availability
- Event-owned table holds, distinct from customer reservations
- Transactional reservation loyalty accounting
- Event capacity checks protected with row locking
- Role-protected administration and CSRF-protected logout
- Seed data created through application services, not direct inconsistent inserts

## Requirements

- PHP 8.2 or newer with MySQL or SQLite support
- Composer 2
- Node.js 22 or newer
- MySQL 8+ for local production-like development

## Local setup

```bash
git clone https://github.com/wwa3123/sehs4517-group-project-cafe-reservation-laravel.git
cd sehs4517-group-project-cafe-reservation-laravel
composer install
npm ci
cp .env.example .env
php artisan key:generate
```

Configure the `DB_*` values in `.env`, then create a local demo database:

```bash
php artisan migrate:fresh --seed
npm run build
composer run dev
```

`migrate:fresh --seed` destroys existing data and is intended only for local development. The local demo administrator is `admin@example.com` with password `AdminDemo2026!`. Change `DEMO_ADMIN_PASSWORD` in `.env` before sharing a demo environment. The seeder only applies this configured password in `local` and `testing`; production seed runs always generate a random password.

## Validation

```bash
php artisan test
npm run build
```

The test suite uses an in-memory SQLite database and covers event slot ownership, slot conflicts, service-backed seed data, email privacy, and logout behavior.

## Deployment

The included [Dockerfile](./Dockerfile) builds production Composer dependencies and Vite assets, then serves Laravel from Apache. Configure `APP_KEY`, `APP_ENV=production`, `APP_DEBUG=false`, and production `DB_*` variables in the hosting platform. After deploy, run:

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

GitHub Actions runs dependency installation, frontend compilation, and the Laravel test suite for every push and pull request.
