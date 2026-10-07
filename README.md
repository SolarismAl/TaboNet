# TaboNet

> **Municipal Digital Agri-Trading, Spot Market Price Index, and Farmgate Produce Aggregation Platform for Cantilan, Surigao del Sur**

---

## Overview

**TaboNet** is a municipal agricultural trading platform designed for the Municipality of Cantilan. It empowers local smallholder farmers by eliminating intermediary price gouging, providing direct market access with **0% transaction commission**, and establishing an official, transparent **Municipal Commodity Spot Market Price Index** based on real-time aggregated farmgate rates.

---

## Key Capabilities

- **Role-Based Access Control (RBAC):**
  - **Producer Hub (Farmers):** Manage farmgate harvest listings, track inventory, execute inline edit/remove controls, and process incoming buyer pre-orders.
  - **Buyer Marketplace (Commercial & Institutional Buyers):** Discover fresh harvest arrivals across 17 barangays, inspect DA-RSBSA verification status, place pre-orders, and call producers directly.
  - **Executive Dashboard (Municipal Administrators):** DA-RSBSA farmer accreditation review, municipal harvest catalog oversight, spot price bulletin audits, and system security logging.
- **Spot Market Price Index ($P_{avg}$):** Mathematical mean benchmark calculated in real time ($P_{avg} = \frac{\sum P}{N}$) across agricultural commodity classifications (Grains & Cereals, Fruits & Orchard, Root Crops, Vegetables, Aquaculture).
- **Zero-Commission Lead Dispatch:** Direct trade inquiries and pre-orders with instant status transitions (`pending`, `accepted`, `declined`).
- **High-Density Data Tables with Livewire Pagination:** Sleek tables with built-in sliding-window pagination across all 9 data views.
- **Dark Mode Support:** Integrated theme toggle with system preference auto-detection and persistence.

---

## System Architecture & Data Model

TaboNet operates on an enterprise **13-table normalized relational schema**:

| Table | Purpose |
|---|---|
| `users` | Core authentication, role (`farmer`, `buyer`, `admin`), contact data, verification status |
| `farmer_profiles` | Extension for farmer data: RSBSA ID URL, farm location (Barangay), farm type, bio |
| `buyer_profiles` | Extension for buyer data: business name, commercial type, delivery barangay |
| `categories` | Agricultural groupings (Root Crops, Grains, Vegetables, Fruits, Aquaculture) |
| `commodities` | Standardized produce registry with default units of measure |
| `listings` | Produce batches with farmgate rates, volume, status (`active`, `sold_out`, `archived`) |
| `listing_images` | Multi-image support with primary thumbnail flagging |
| `inquiries` | Trade leads / pre-orders dispatched between buyers and farmers |
| `inquiry_messages` | In-app messaging and negotiation audit trail |
| `notifications` | System alerts for order status transitions and administrative approvals |
| `price_records` | Spot market benchmark log (prevailing, minimum, maximum rates per commodity) |
| `moderation_reviews`| Regulatory and administrative listing audit logs |
| `audit_logs` | Security, RBAC, and governance activity audit trail |

---

## Tech Stack

- **Framework:** Laravel 12 (PHP 8.4)
- **Frontend / Dynamic UI:** Livewire 3, Alpine.js, Tailwind CSS
- **Authentication & Security:** Laravel Fortify (Passkeys & Two-Factor Authentication support)
- **Testing:** PHPUnit (95 automated tests, 100% pass rate)

---

## Getting Started

### Prerequisites

- **PHP 8.2+** (with `bcmath`, `curl`, `mbstring`, `pdo_sqlite` or `pdo_mysql`, `xml`)
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

5. **Run Migrations & Seeders:**
   ```bash
   php artisan migrate --seed
   ```

6. **Start Local Development Environment:**
   ```bash
   composer run dev
   ```
   *Or run Vite and Laravel separately:*
   ```bash
   # Terminal 1
   php artisan serve

   # Terminal 2
   npm run dev
   ```

   Access the application at: `http://localhost:8000`

---

## Default Seeded Accounts

For testing and local evaluation, default test accounts are seeded:

| Role | Email | Password | Landing View |
|---|---|---|---|
| **Municipal Admin** | `admin@cantilan.gov.ph` | `password` | **Executive Dashboard** |
| **Accredited Farmer** | `farmer@cantilan.gov.ph` | `password` | **Producer Hub** |
| **Commercial Buyer** | `buyer@cantilan.gov.ph` | `password` | **Buyer Marketplace** |

---

## Testing

Execute the automated test suite covering RBAC authorization, registration workflows, listing operations, spot price indexing, and pagination:

```bash
php artisan test
```

To run marketplace-specific feature tests:
```bash
php artisan test --filter=MunicipalMarketplaceTest
```

---

## Cantilan Coverage

TaboNet is tailored for all 17 barangays of Cantilan, Surigao del Sur:

`Bugsukan` • `Buntalid` • `Cabangahan` • `Cabas-an` • `Calagdaan` • `Consuelo` • `General Island` • `Linotan` • `Lobo` • `Magasang` • `Magosilom` • `Pag-Antayan` • `Palasao` • `Parang` • `Poblacion` • `San Pedro` • `Tigabongan`

---

## License

This project is licensed under the [MIT License](LICENSE).
