# TaboNet

> **Municipal Agricultural Marketplace & Farmgate Price Monitoring Platform**  
> *Municipality of Cantilan, Province of Surigao del Sur (Zip: 8317)*  
> *In Partnership with North Eastern Mindanao State University (NEMSU) Cantilan Campus*

---

## Overview

**TaboNet** is a modern, community-centered digital agricultural marketplace designed specifically for the Municipality of Cantilan. It empowers local smallholder farmers by eliminating intermediary price markups, providing direct market access with **0% middleman fees**, and establishing an honest, transparent **Municipal Daily Price Board** based on real-time aggregated farmgate prices across all 17 barangays.

---

## Key Capabilities

- **Role-Based Access Control (RBAC):**
  - **🌾 Smallholder Producer Hub (Farmers):** Post fresh harvest listings (in kilos, *sako*, or *kaing*), track inventory, and receive direct buyer inquiries with zero commission.
  - **🛒 Buyer Marketplace (Commercial & Institutional Buyers):** Discover fresh produce arrivals across Cantilan, check DA-RSBSA farmer verification, and dispatch pre-order inquiries directly to farmers.
  - **🏛️ Municipal Administrator (LGU Cantilan):** Review DA-RSBSA farmer accreditations, supervise public harvest listings, monitor daily commodity price trends, and review system audit logs.
- **Fair Daily Price Board:** Real-time price tracking and commodity averages across staple groups (*Palay & Corn*, *Fresh Vegetables*, *Fruits & Root Crops*, *Coconut & Farm Goods*).
- **Direct Trade Lead Dispatch:** Zero-commission pre-orders and direct buyer-farmer communication with live status tracking (`pending`, `accepted`, `declined`).
- **High-Density Data Tables:** Built-in sliding-window pagination and search filters across all 9 data views.
- **Auto-Verification on Registration:** Streamlined onboarding allows registered farmers and buyers immediate access to their designated portal without email verification friction.
- **Dark Mode Support:** Full light/dark mode persistence with system preference detection.

---

## Default Seeded Accounts

For local development, thesis defense, and testing, the system provides pre-configured accounts (Password for all: **`password`**):

| Role | Email | Password | Access / Scope |
| :--- | :--- | :--- | :--- |
| **🏛️ Municipal Admin** | `admin@tabonet.ph` | `password` | LGU Cantilan municipal oversight, farmer verification, price controls |
| **🌾 Accredited Farmer** | `farmer@tabonet.ph` | `password` | Mang Pedro (Linotan Farm) — Harvest listings & incoming buyer inquiries |
| **🛒 Commercial Buyer** | `buyer@tabonet.ph` | `password` | Maria Santos (Wholesaler) — Produce browsing & pre-order dispatch |
| **⏳ Pending Farmer** | `farmer.pending@tabonet.ph` | `password` | Juan Dela Cruz (Calagdaan) — Pending DA-RSBSA accreditation workflow |

---

## System Architecture & Data Model

TaboNet operates on an enterprise **13-table normalized relational schema**:

| Table | Purpose |
|---|---|
| `users` | Core authentication, role (`farmer`, `buyer`, `admin`), barangay, phone number, verification status |
| `farmer_profiles` | Extension for farmer data: RSBSA registration ID, farm location, farm type, bio |
| `buyer_profiles` | Extension for buyer data: business name, commercial type, delivery barangay |
| `categories` | Agricultural groupings (Grains & Cereals, Root Crops, Vegetables, Fruits, Aquaculture) |
| `commodities` | Standardized produce registry with default units of measure (kg, sako, kaing) |
| `listings` | Produce batches with farmgate rates, stock volume, and status (`active`, `sold_out`, `archived`) |
| `listing_images` | Multi-image support with primary thumbnail flagging |
| `inquiries` | Direct trade leads and pre-orders dispatched between buyers and farmers |
| `inquiry_messages` | In-app messaging and negotiation audit trail |
| `notifications` | System alerts for order status changes and administrative accreditations |
| `price_records` | Spot market benchmark log (prevailing, minimum, and maximum rates per commodity) |
| `moderation_reviews`| Regulatory and administrative listing audit logs |
| `audit_logs` | Security, RBAC, and governance activity audit trail |

---

## Tech Stack

- **Backend:** Laravel 12 / PHP 8.4
- **Frontend / Real-Time UI:** Livewire 3, Alpine.js, Flux UI, Tailwind CSS
- **Database:** SQLite (local dev/testing) & Cloud MySQL / TiDB Serverless (SSL-encrypted production)
- **Security:** Laravel Fortify (RBAC, Two-Factor Authentication support)
- **Quality & Testing:** PHPUnit (95 tests, 100% pass rate), Laravel Pint, PHPStan

---

## Getting Started

### Prerequisites

- **PHP 8.2+** (with `bcmath`, `curl`, `mbstring`, `openssl`, `pdo_mysql`, `pdo_sqlite`, `xml`)
- **Composer 2+**
- **Node.js 18+** & **npm**

### Installation

1. **Clone the repository:**
   ```bash
   git clone -b develop https://github.com/SolarismAl/TaboNet.git
   cd TaboNet
   ```

2. **Install backend dependencies:**
   ```bash
   composer install
   ```

3. **Install frontend dependencies:**
   ```bash
   npm install
   ```

4. **Environment Setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure Database:**
   
   - **Option A: Local SQLite (Quick Start)**
     ```env
     DB_CONNECTION=sqlite
     ```
     ```bash
     touch database/database.sqlite
     php artisan migrate --seed
     ```

   - **Option B: Online Cloud Database (TiDB / MySQL with SSL)**
     ```env
     DB_CONNECTION=mysql
     DB_HOST=gateway01.ap-southeast-1.prod.aws.tidbcloud.com
     DB_PORT=4000
     DB_DATABASE=tabonet
     DB_USERNAME=your_username
     DB_PASSWORD=your_password
     MYSQL_ATTR_SSL_CA=database/isrgrootx1.pem
     ```
     ```bash
     php artisan migrate --seed
     ```

6. **Start Local Development Server:**
   ```bash
   composer run dev
   ```
   *Runs Laravel, Vite, and queue worker concurrently on `http://127.0.0.1:8001`.*

---

## Code Quality & Testing

TaboNet includes a comprehensive CI check script executing Pint formatting, PHPStan static analysis, and the automated test suite:

```bash
# Run full suite (Pint, PHPStan, PHPUnit)
composer ci:check
```

Or run individual tools:
```bash
# Run PHPUnit tests (95 tests)
php artisan test

# Run marketplace feature tests only
php artisan test --filter=MunicipalMarketplaceTest

# Code style formatting
composer pint

# Static type analysis
vendor/bin/phpstan analyse
```

---

## 17 Cantilan Barangays Coverage

TaboNet is tailored for all seventeen constituent barangays of the Municipality of Cantilan, Surigao del Sur:

| | | |
|---|---|---|
| • Bugsukan | • Buntalid | • Cabangahan |
| • Cabas-an | • Calagdaan | • Consuelo |
| • General Island | • Linotan | • Lobo |
| • Magasang | • Magosilom | • Pag-Antayan |
| • Palasao | • Parang | • Poblacion |
| • San Pedro | • Tigabongan | *(Zip Code: 8317 SDS)* |

---

## Institutional Partnership

- **Municipality of Cantilan, Surigao del Sur** — Municipal Agriculture Office & Public Market
- **North Eastern Mindanao State University (NEMSU)** — Cantilan Campus ([www.nemsu.edu.ph](https://www.nemsu.edu.ph))

---

## License

This project is licensed under the [MIT License](LICENSE).
