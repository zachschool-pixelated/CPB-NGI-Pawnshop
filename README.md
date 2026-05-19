# CPB-NGI Pawnshop Management System

A highly secure, web-based Pawnshop Management System built with Laravel 11. It features end-to-end pawn transactions, a Point-of-Sale (POS) system for forfeited items, strict customer data management, and enterprise-grade role-based access control (RBAC).

---

## 🌟 Key Features

- **Strict Role-Based Access Control (RBAC):** 4-tier security matrix (Admin, Manager, Teller, Cashier) enforced via middleware and dynamic UI rendering.
- **Data Immutability:** Customer records are strictly append-only (no deletion or editing allowed) to ensure a bulletproof audit trail.
- **Enterprise Phone Validation:** Integrates Google's `libphonenumber` to automatically format and validate all phone numbers into the standard international E.164 format (`+639XXXXXXXXXX`).
- **Dynamic UI/UX:** Features a fully responsive Dark Mode, and a dynamic Grid/List view toggle powered by Alpine.js that remembers user preferences via LocalStorage.
- **Automated Workflow:** Includes a robust approval system for voiding transactions and removing items from inventory.

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11.51 (PHP 8.2)
- **Frontend:** Blade Templates, TailwindCSS 3, Alpine.js
- **Build Tool:** Vite 7
- **Database:** MySQL
- **Dependencies:**
  - `barryvdh/laravel-dompdf` (PDF Generation)
  - `propaganistas/laravel-phone` (E.164 Phone Formatting)
- **Auth:** Laravel Breeze

---

## ⚙️ Prerequisites

Make sure you have the following installed on your machine before proceeding:

| Software       | Required Version | Download Link                                      |
|----------------|------------------|----------------------------------------------------|
| **PHP**        | >= 8.2           | Bundled with XAMPP                                  |
| **Composer**   | >= 2.x           | https://getcomposer.org/download/                   |
| **Node.js**    | >= 18.x          | https://nodejs.org/                                 |
| **npm**        | >= 9.x           | Bundled with Node.js                                |
| **MySQL**      | >= 5.7 / 8.x     | Bundled with XAMPP                                  |
| **XAMPP**      | Latest           | https://www.apachefriends.org/download.html         |

### Required PHP Extensions (enabled by default in XAMPP)

- `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd` (for PDF receipt generation)

---

## 🚀 Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/YOUR_USERNAME/PROTOTYPE-CPB-NGI-PAWNSHOP.git
cd PROTOTYPE-CPB-NGI-PAWNSHOP/CPB-NGI-Pawnshop
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

> **Note:** The `.env.example` is pre-configured for a local XAMPP/MySQL setup with Database: `pawnshop`, Username: `root`, and an empty Password. Adjust these values in your `.env` if your MySQL credentials differ.

### 4. Create the Database

Open **phpMyAdmin** (http://localhost/phpmyadmin) or MySQL CLI and create the database:

```sql
CREATE DATABASE pawnshop;
```

### 5. Run Migrations & Seed Data

```bash
php artisan migrate --seed
```

This will create all tables and seed default accounts, item categories, and Philippine location data (regions, provinces, cities, barangays).

### 6. Build Frontend Assets & Start Server

```bash
npm run build
php artisan serve
```

Visit: **http://127.0.0.1:8000**

---

## ⚡ Quick Start (TL;DR)

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
# Create 'pawnshop' database in MySQL first, then:
php artisan migrate --seed
npm run build
php artisan serve
```

---

## 🔐 Default Login Credentials

| Role     | Email                   | Password   |
|----------|-------------------------|------------|
| Admin    | admin@pawnshop.com      | password   |
| Manager  | manager@pawnshop.com    | password   |
| Teller   | teller@pawnshop.com     | password   |
| Cashier  | cashier@pawnshop.com    | password   |

---

## 🛡️ User Roles & Access

| Module               | Admin | Manager | Teller | Cashier |
|----------------------|:-----:|:-------:|:------:|:-------:|
| Dashboard            |  ✅   |   ✅    |   ✅   |   ✅    |
| Customers            |  ✅   |   ✅    |   ✅   |   ❌    |
| Pawn Transactions    |  ✅   |   ✅    |   ✅   |   ❌    |
| POS (Forfeited Items)|  ✅   |   ✅    |   ❌   |   ✅    |
| Items / Inventory    |  ✅   |   ✅    |   ❌   |   ❌    |
| User Management      |  ✅   |   ❌    |   ❌   |   ❌    |
| Audit Logs           |  ✅   |   ❌    |   ❌   |   ❌    |

---

## 📁 Project Structure

```
CPB-NGI-Pawnshop/
├── app/
│   ├── Http/Controllers/     # Core application logic
│   ├── Http/Middleware/       # RBAC & Security middleware
│   └── Models/                # Eloquent models
├── database/
│   ├── migrations/            # Database schema definitions
│   └── seeders/               # Default data & user seeders
├── resources/views/
│   ├── layouts/               # App layout, dark mode & navigation
│   ├── auth/                  # Login & register pages
│   ├── customers/             # Immutable Customer records
│   ├── transactions/          # Pawn operations
│   ├── pos/                   # Point of Sale module
│   └── items/                 # Inventory & Grid/List views
└── routes/
    ├── web.php                # Main application routes
    └── auth.php               # Authentication routes
```

---

## 🔧 Troubleshooting

- **"Class not found" or autoload errors:** `composer dump-autoload`
- **Vite/CSS not loading:** `npm run build` or `npm run dev` for hot-reload
- **Migration errors:** Ensure the `pawnshop` database exists in MySQL before running migrations.

---

## 📄 License

This project is for academic/prototype purposes.
