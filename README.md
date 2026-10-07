# MarketHub — Laravel 12 Marketplace Platform

A full-featured online marketplace built with Laravel 12, Tailwind CSS, and Stripe payments.
Developed as an internee training project.

---

## 👨‍💻 Developer

**Name:** Mansoor  
**Project:** Internee Training Project  
**Version:** 1.0  
**Year:** 2026  

---

## 🚀 Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12 (PHP 8.3+) |
| Frontend | Blade Templates + Vanilla JavaScript |
| Styling | Tailwind CSS 3 |
| Auth | Laravel Breeze |
| Roles | Spatie Laravel Permission |
| Payments | Stripe PHP SDK v13 (test mode) |
| Queue | Database Queue Driver |
| Email | Mailtrap SMTP (sandbox) |
| Storage | Local Public Disk |
| Database | MySQL |

---

## ✅ Features

### Buyer
- Browse and search listings
- Filter by category, condition, price
- Sort by newest, price, popularity
- Purchase via Stripe Checkout
- View order history and status timeline
- Leave reviews on completed orders
- Save listings to favorites
- Message sellers directly
- Receive notifications

### Seller
- Create and manage listings with images
- Dashboard with revenue stats and charts
- Manage incoming orders
- Mark orders as shipped and completed
- Public profile with reviews and ratings

### Admin
- Full admin panel with sidebar
- Manage all users (ban/unban, change roles)
- Approve and force-delete listings
- View all platform orders and revenue
- Delete reviews

---

## 📋 Requirements

- PHP 8.3+
- Composer 2.x
- MySQL 8.x
- Node.js 18+
- NPM

---

## ⚙️ Installation

### 1. Clone the repository

```bash
git clone https://github.com/Mansoor5566/markethub.git
cd markethub
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure .env

```env
APP_NAME=MarketHub
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=markethub
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS=noreply@markethub.test
MAIL_FROM_NAME=MarketHub

STRIPE_KEY=pk_test_your_key
STRIPE_SECRET=sk_test_your_secret
STRIPE_WEBHOOK_SECRET=whsec_your_secret
```

### 5. Database setup

```bash
php artisan migrate:fresh --seed
```

### 6. Storage link

```bash
php artisan storage:link
```

### 7. Build assets

```bash
npm run build
```

### 8. Start the server

```bash
php artisan serve
```

Open **http://localhost:8000**

---

## 🔑 Test Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@markethub.test | password |
| Seller | seller@markethub.test | password |
| Buyer | buyer1@markethub.test | password |

---

## 💳 Stripe Test Cards

| Card Number | Result |
|-------------|--------|
| 4242 4242 4242 4242 | Payment succeeds ✅ |
| 4000 0000 0000 0002 | Card declined ❌ |
| 4000 0025 0000 3155 | 3D Secure required |

Use any future expiry date and any 3-digit CVC.

---

## 🗄️ Database Seeders

| Seeder | Records |
|--------|---------|
| RolesAndPermissionsSeeder | 3 roles, 4 permissions |
| UserSeeder | 26 users (1 admin, 5 sellers, 20 buyers) |
| CategorySeeder | 40 categories (8 parents, 32 children) |
| ListingSeeder | 120 listings |
| OrderSeeder | 60 orders |
| ReviewSeeder | Up to 45 reviews |
| MessageSeeder | 80 messages (20 threads) |
| FavoriteSeeder | 50 favorites |

---

## 📁 Project Structure

markethub/
├── app/
│ ├── Http/
│ │ ├── Controllers/ # All controllers
│ │ ├── Middleware/ # ActiveUser middleware
│ │ └── Requests/ # Form request classes
│ ├── Models/ # Eloquent models
│ ├── Notifications/ # Email + database notifications
│ ├── Policies/ # Authorization policies
│ └── View/Components/ # Blade components
├── database/
│ ├── migrations/ # All migrations
│ └── seeders/ # All seeders
├── resources/
│ └── views/
│ ├── admin/ # Admin panel views
│ ├── auth/ # Login, register, password reset
│ ├── checkout/ # Success and cancel pages
│ ├── components/ # Reusable Blade components
│ ├── errors/ # 403, 404, 500 pages
│ ├── favorites/ # Favorites page
│ ├── home/ # Home page
│ ├── layouts/ # App and admin layouts
│ ├── listings/ # Browse and detail pages
│ ├── messages/ # Inbox and thread
│ ├── notifications/ # Notifications centre
│ ├── orders/ # Buyer order pages
│ ├── profile/ # Profile edit page
│ ├── search/ # Search results
│ ├── seller/ # Seller dashboard and pages
│ └── sellers/ # Public seller profile
└── routes/
└── web.php # All application routes


---

## 🚫 Constraints (SRS Requirements)

- ❌ No Livewire
- ❌ No Vue.js
- ❌ No React
- ❌ No S3 or cloud storage
- ❌ No hardcoded URLs (all use `route()` helper)
- ❌ No inline `$request->validate()` (all use Form Request classes)
- ❌ No manual role checks in controllers (all use Policies)
- ❌ `.env` file never committed to Git

---

## 🔧 Running in Development

You need 3 terminals running simultaneously:

**Terminal 1 — Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 — Queue Worker:**
```bash
php artisan queue:work
```

**Terminal 3 — Stripe Webhook (Windows):**
```bash
C:\stripe\stripe.exe listen --forward-to http://localhost:8000/stripe/webhook
```

---

## 📧 Email Setup (Mailtrap)

1. Create free account at https://mailtrap.io
2. Go to **Email Testing** → **Inboxes**
3. Click **Show Credentials**
4. Copy SMTP credentials to `.env`
5. All emails are captured in Mailtrap inbox — no real emails sent

---

## 🌐 Deployment

See deployment guide for Railway.app, Render, or shared hosting in the project wiki.

---

## 📄 License

This project is for educational purposes only — Internee Training Project 2026.

---

*Built with ❤️ using Laravel 12 · Tailwind CSS · Stripe*