# Digital Wallet (Laravel + Vue.js)

This project is a simplified high‑performance digital wallet system built using **Laravel**, **MySQL**, **Vue 3**, and **Pusher**. It allows users to transfer money between accounts in real time, using fully atomic, concurrency‑safe operations.

This README intentionally follows a **Test‑Driven Development (TDD)** workflow: each feature section begins with a **Feature Test**, followed by the implementation steps.

---

# 1. Requirements

* PHP >= 8.3
* Composer
* Node.js + NPM
* MySQL 8+
* Pusher account

---

# 2. Initial Laravel Setup

Install Laravel Sanctum for API authentication:

```bash
composer create-project laravel/laravel digit-wallet
```

Generate app key (if required):

```bash
php artisan key:generate
```

Install Laravel Sanctum for API authentication:

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan install:api
```

Enable Sanctum middleware in `app/Http/Kernel.php`.

---

# 3. Environment Configuration

Copy `.env.example`:

```bash
cp .env .env
```

Add database credentials:

```
DB_DATABASE=digital_wallet
DB_USERNAME=root
DB_PASSWORD=
```

SPA Cookie settings between VUE javascript and laravel
```
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=localhost
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:8000,127.0.0.1,127.0.0.1:8000,::1,127.0.0.1:5173,localhost:5173
```

Add Pusher:

```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-id
PUSHER_APP_KEY=your-key
PUSHER_APP_SECRET=your-secret
PUSHER_APP_CLUSTER=mt1
PUSHER_PORT=443
PUSHER_SCHEME=https
```


---

# 4. Install Frontend (Vue.js)

```bash
npm install
npm install vue@latest laravel-vite-plugin axios pusher-js laravel-echo
```

Initialize Vue:

```bash
php artisan breeze:install vue
php artisan preset vue --no-auth
```

---

# 5. Database Test (TDD)

Create migrations:

```bash
php artisan migrate
```

## Unit Test

Database schema tests:

```bash
php artisan test --filter=DatabaseStructureTest
```

---

# 6. Feature: List Transactions (`GET /api/transactions`)

## Feature Test

```bash
php artisan test --filter=TransactionListTest
```

---

# 7. Feature: Create Transfer (`POST /api/transactions`)

## Feature Test

```bash
php artisan test --filter=MoneyTransferTest
```

---

# 8. Feature: Real‑Time Pusher Broadcasting

## Feature Test

Laravel Pusher calls in test mode with the event dispatch:

```bash
php artisan test --filter=RealtimeTransferEventTest
```

---

# 9. Running All the Tests

```bash
php artisan test
```

---

# 10. Starting the App

Backend:

```bash
php artisan serve
```

Queue worker (needed for broadcasts):

```bash
php artisan queue:work
```

Frontend:

```bash
npm run dev
```

---

## Deployment Instructions

### 1. Production Server Requirements

* **PHP**: Latest supported version for Laravel (e.g., PHP 8.3+)
* **Nginx or Apache** (Nginx recommended)
* **MySQL 8+**
* **Redis** (recommended for queue & cache performance)
* **Supervisor** for queue workers (optional)

---

### 2. Deployment Steps

#### **Clone the Repository**

```bash
git clone https://github.com/fshahzad/digi-wallet
cd digi-wallet
```

#### **Install Dependencies**

```bash
composer install --optimize-autoloader --no-dev
npm install && npm run build
```

#### **Environment Setup**

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Optimize Laravel for Production

```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```


### 7. Deploy Frontend SPA (Vue)

The production build is already located in `public/` after running:

```bash
npm run build
```

Ensure the server points to `public/index.html` for the SPA.

---
