# Helpdesk Ticket Management API

Laravel REST API for the CCS112 Helpdesk Ticket Management System. The React/Vite frontend has the lab-required client-side login (`itadmin` / `helpdesk123`); that login is a route gate and does not authenticate API requests. Ticket records are stored in the configured database.

## Local setup

Requirements: PHP 8.2+, Composer, and SQLite or PostgreSQL. For a quick local setup, create an empty SQLite database and configure `DB_CONNECTION=sqlite` and `DB_DATABASE` in `.env`. Then run:

```sh
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

For PowerShell, use `Copy-Item .env.example .env` and create `database/database.sqlite` before running migrations. Set `DB_DATABASE` to that file's full path.

## Ticket API

Base URL: `http://127.0.0.1:8000/api/v1`

- `GET /tickets?search=` — list and search tickets
- `POST /tickets` — create ticket
- `GET /tickets/{id}` — show details
- `PUT /tickets/{id}` — update details/status
- `DELETE /tickets/{id}` — delete ticket

Ticket fields: `ticket_number`, `requester_name`, `requester_email`, `category`, `priority`, `subject`, `description`, `status`, and timestamps. Ticket numbers are generated as `TKT-000001`.

## Render + Aiven PostgreSQL

Deploy this backend as a Render Docker Web Service. Set `APP_KEY`, `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, `CORS_ALLOWED_ORIGINS` (the exact deployed frontend origin), and Aiven PostgreSQL values for `DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, and `DB_SSLMODE=require`. Keep secrets in Render environment settings, not Git. The Docker startup command runs migrations before starting Laravel. Use Aiven for persistent data; the container filesystem is not the database.

The client-side login is required by the lab brief and is not suitable for protecting a real production API.
