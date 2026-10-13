# CCS112 Full Stack Product Exam Starter

Laravel 12 REST API for the companion React/Vite application. Includes hashed bearer tokens, validated CRUD, ownership checks, admin routes, migrations, and a demo seeder.

## Local setup
Requirements: PHP 8.2+, Composer, PostgreSQL 14+. Copy .env.example to .env and set database details. Then run:
```sh
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```
Demo-only login: admin@example.com / ChangeMe123! and user@example.com / ChangeMe123!. Do not deploy these credentials publicly.

API base: http://127.0.0.1:8000/api/v1. Routes: POST /login; GET /me; POST /logout; products resource CRUD; admin /admin/stats, /admin/users, /admin/products. Protected calls send Authorization: Bearer <token>. Standard users see only their products; admins can see all. Lists paginate; product search uses ?search=.

## Render + Aiven
Deploy as a Docker Web Service. Set APP_KEY from `php artisan key:generate --show`, APP_ENV=production, APP_DEBUG=false, CORS_ALLOWED_ORIGINS to the exact frontend URL, DB_CONNECTION=pgsql, and Aiven DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD. Set DB_SSLMODE=require and satisfy Aiven TLS CA and network allowlist requirements. Keep credentials in Render Environment settings, never Git. Docker runs migrations on start and binds to Render's PORT. Back up before schema changes.

Exam starter only: tokens expire in seven days and logout revokes them. Add recovery, email verification, audit logging and tests before real use.
