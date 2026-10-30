# Laravel 13 Backend

PHP 8.3+ • MySQL • Laravel Sanctum • Database Queue • Laravel Reverb

## 1. Install and run

From the backend project root, install the project dependencies:

```bash
composer install
```

## 2. Configure the environment

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

On Windows Command Prompt, use `copy .env.example .env` instead.

Open `.env` and fill in the settings for your environment, particularly the database, application URLs, Reverb, queue, mail, and initial Super Administrator account:

```dotenv
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

# PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=
SESSION_EXPIRE_ON_CLOSE=false
SESSION_CONNECTION=

BROADCAST_CONNECTION=reverb
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
DB_QUEUE=notifications
DB_QUEUE_RETRY_AFTER=90

CACHE_STORE=database
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

SEED_ADMIN_USERNAME=user1234
SEED_ADMIN_EMAIL=user1234@gmail.com
SEED_ADMIN_PASSWORD=Laravel2026
SEED_ADMIN_FIRSTNAME="Two Named"
SEED_ADMIN_MIDDLENAME=Middle
SEED_ADMIN_LASTNAME=Last

AUDIT_ENABLED=true
AUDIT_LOG_READS=false
AUDIT_MAX_PAYLOAD_BYTES=8192
AUDIT_DIAGNOSTIC_DAYS=14

REVERB_APP_ID=123456
REVERB_APP_KEY=my-local-reverb-key
REVERB_APP_SECRET=my-private-reverb-secret
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8080
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

FRONTEND_URL=http://localhost:3000
```

Also configure your existing Sanctum stateful domains, session/cookie settings, and CORS origins to match the frontend URL. Keep `.env` private, especially `REVERB_APP_SECRET` and `SEED_ADMIN_PASSWORD`.

## 3. Generate the key and initialize the database

```bash
php artisan key:generate
php artisan migrate:fresh --seed
```

**Warning:** `migrate:fresh --seed` drops **all tables** in the configured database before rebuilding and seeding them. Use it only for a new or disposable development database, **never on a database containing data you need to keep**.

To run the backend locally:

```bash
php artisan serve
```

## 4. Run background services

Run these in **separate terminals**, leaving both processes running:

```bash
php artisan queue:work
```

```bash
php artisan reverb:start
```

The queue worker processes queued jobs and notifications; Reverb delivers real-time events over WebSockets. With `DB_QUEUE=notifications`, the plain `queue:work` command processes that queue. If your project also uses the `default` queue, use `php artisan queue:work database --queue=notifications,default` instead.

In production, run both processes under a process manager such as Supervisor or systemd.

## 5. Create models with modules

Generate a Laravel model and its associated module structure:

```bash
php artisan make:module-model Product --module
```

This generates the model plus the module's Controller, Service, Repository Interface, Eloquent Repository, repository provider, Add/Update Form Requests, and modular route class, using the project's CRUD templates.

Generated routes are initially commented out. Implement and verify validation, permissions, and business logic before enabling them.
