# Chit-Chat Cafe

Chit-Chat Cafe is a portfolio-quality Laravel 12 application for a fictional board-game cafe. It supports the journey from creating a table reservation through staff check-in and visit completion, alongside loyalty rewards and community event registration.

## Why this project

A café reservation system needs more than a booking form: customers need accurate availability, staff need an operational view of arrivals, and events must not conflict with table use. This project models those constraints explicitly instead of relying on display-only validation.

## Core workflows

### Customer

- Create an account and manage profile details.
- Reserve a table for selected date, capacity, and time slots.
- See reservation status and visit history.
- Earn and redeem loyalty tokens.
- Browse the menu and register for café events.

### Staff / administrator

- View reservations and filter them by operational status.
- Move bookings through `confirmed`, `checked_in`, `completed`, `cancelled`, and `no_show` states.
- Record actual arrival and completion times.
- Cancel a booking to release its table slots for another customer.
- Create, update, and remove events; capacity changes are protected against overselling.

## Technical highlights

- **Conflict-safe availability:** reservations and event holds share date-specific table/time-slot records, preventing double booking.
- **Transactional domain services:** reservation, cancellation, loyalty, and event operations use database transactions.
- **Concurrency-aware events:** event registration uses row locking for capacity checks.
- **Role-based authorization:** administrative actions are protected by middleware; members can access only their own bookings.
- **Audit-friendly lifecycle:** status transitions are intentionally limited to valid operational paths and capture timestamps.
- **Automated checks:** Laravel feature and unit tests run in GitHub Actions for pushes and pull requests.

## Stack

- PHP 8.2+ and Laravel 12
- SQLite for automated tests; MySQL 8+ supported for local production-like development
- Blade, Tailwind CSS, Vite, and vanilla JavaScript
- PHPUnit and Laravel's testing tools

## Local setup

```bash
git clone https://github.com/wwa3123/sehs4517-group-project-cafe-reservation-laravel.git
cd sehs4517-group-project-cafe-reservation-laravel
composer install
npm ci
cp .env.example .env
php artisan key:generate
```

Set the `DB_*` values in `.env`, then create local demo data and compiled assets:

```bash
php artisan migrate:fresh --seed
npm run build
composer run dev
```

`migrate:fresh --seed` destroys database data, so use it only for local development.

### Demo account

| Role | Email | Password |
| --- | --- | --- |
| Administrator | `admin@example.com` | `AdminDemo2026!` |

Set `DEMO_ADMIN_PASSWORD` in `.env` before presenting a shared demo. The provided seeder only uses that configured password in local and testing environments; production seeds generate a random password.

## Validation

```bash
php artisan test
npm run build
```

The tests use in-memory SQLite. They cover booking collisions, event-owned slots, service-backed seed data, authorization, lifecycle transitions, loyalty behavior, and logout behavior.

## Deployment

[Dockerfile](./Dockerfile) builds production Composer dependencies and Vite assets, then serves Laravel through Apache. Configure `APP_KEY`, `APP_ENV=production`, `APP_DEBUG=false`, and production database variables in the deployment environment. Then run:

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Portfolio walkthrough

For a concise demo, create a reservation as a customer, sign in as the administrator, check the party in, then complete the visit. Alternatively, cancel a confirmed booking to show that the slot becomes available again. The reservation list, detail page, and customer history show the lifecycle state throughout the workflow.

Add screenshots or a short screen recording of that flow here when the project is deployed or presented.
