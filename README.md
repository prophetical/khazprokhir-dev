# Khazprokhir – Premium Operational & Reporting System

## 📖 Overview
Khazprokhir is an **enterprise‑grade** Laravel application that streamlines **industrial operational workflows** for the **HCS** (Receiving, Sorting, Packaging) and **HCTS** (Receiving, Inventory, Submission) processes. It provides real‑time analytics, automated bilyet calculations, role‑based access control, and premium‑look UI with dark‑mode support.

---

## ✨ Key Features
- **📊 Laporan Harian** – interactive daily reports with filtering by TA/TE, denomination, and date range.
- **🔁 Rekonsiliasi** – detailed reconciliation view with printable PDF layout (print‑rekonsiliasi.blade.php) and export to Excel.
- **🖨️ Print‑Ready PDFs** – custom Tailwind‑styled print pages that match the main UI.
- **🔐 Role‑Based Middleware** – granular permissions for `admin`, `supervisor`, `khazai`, `khazverutas`, `sortir`, `kemas`, and unassigned users (see `app/Http/Middleware/RoleMiddleware.php`).
- **📝 Audit Log** – model `AuditLog` tracks user actions for accountability.
- **🎨 Premium UI/UX** – glass‑morphism style, fluid typography, micro‑animations, and full dark‑mode support.
- **📈 Charts & Visualisations** – Chart.js integration for trend analysis.
- **💾 Export** – high‑fidelity PDF and XLSX exports for all reports.
- **📱 Responsive & Mobile‑First** – built with Tailwind CSS and Alpine.js.

---

## 🛠 Tech Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Tailwind CSS 3.4, Alpine.js 3
- **Build Tool**: Vite
- **Charts**: Chart.js
- **Modals & Alerts**: SweetAlert2
- **Icons**: Heroicons
- **Database**: PostgreSQL (default, required — uses `TO_CHAR`, window functions & `CAST AS VARCHAR`)

---

## 🚀 Getting Started
### Prerequisites
- PHP ≥ 8.2 (with JSON, PDO & pdo_pgsql extensions)
- Composer
- Node.js ≥ 18 & NPM
- A PostgreSQL database (required — the app uses TO_CHAR, window functions and CAST AS VARCHAR that are not SQLite-compatible)

### Installation
```bash
git clone https://github.com/prophetical/khazprokhir.git
cd khazprokhir
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build   # compile assets for production
php artisan serve
```
Open `http://localhost:8000` in your browser.

---

## 📂 Project Structure (high‑level)
- `app/Http/Controllers` – Controllers for daily reports and reconciliation.
- `app/Http/Middleware/RoleMiddleware.php` – Centralised role‑based access control.
- `app/Services/ReportService.php` – Business logic for generating reports.
- `resources/views/laporan-harian` – Blade templates, including the printable `print-rekonsiliasi.blade.php`.
- `routes/web.php` – Main web routes; authentication routes are defined in `routes/auth.php`.

---

## 🛠 Core Components
- **ReportService** (`app/Services/ReportService.php`): Central service that aggregates data for daily reports and reconciliation, exposing methods `generateDailyReport()` and `generateReconciliationData()`.
- **LaporanHarianController** (`app/Http/Controllers/LaporanHarianController.php`): Handles HTTP requests for `/laporan-harian` endpoints, leverages `ReportService` and applies `RoleMiddleware`.
- **Print‑Rekonsiliasi Blade View** (`resources/views/laporan-harian/print-rekonsiliasi.blade.php`): Dedicated print layout with Tailwind styling, displaying reconciliation tables with zero values as dashes.
- **RoleMiddleware** (`app/Http/Middleware/RoleMiddleware.php`): Role‑based access control enforcing granular permissions for various user roles.
- **AuditLog Model** (`app/Models/AuditLog.php`): Records user actions for compliance and debugging.
- **Export Features**: CSV export via native `fputcsv` with CSV-injection sanitization (`sanitizeCsvField`) and print-optimized Blade layouts for PDF generation through the browser's print-to-PDF.

---

# Updated README with recent enhancements
## 📈 Recent Enhancements
- Updated `/laporan-harian/rekonsiliasi.blade.php` table header styling to match `/laporan-harian` using Tailwind classes (indigo/gray backgrounds, consistent fonts).
- Implemented zero-value display as “-” across reconciliation tables for clearer data presentation.
- Added dedicated printable view `print-rekonsiliasi.blade.php` with Tailwind‑styled layout and print‑optimized CSS.
- Refactored reconciliation logic into its own service file for better separation of concerns.
- Enhanced role‑based access control in `RoleMiddleware` and documented permissions in README.

## 🤝 Contributing
1. Fork the repository.
2. Create a feature branch (`git checkout -b feature/your‑feature`).
3. Ensure code follows the existing coding style (Tailwind classes, Blade conventions).
4. Submit a Pull Request.

---

## 🔐 Private Repository
1. Fork the repository.
2. Create a feature branch (`git checkout -b feature/your‑feature`).
3. Ensure code follows the existing coding style (Tailwind classes, Blade conventions).
4. Submit a Pull Request.

---

## 🔐 Private Repository
This repository is **private** and managed by **[prophetical](https://github.com/prophetical)**. Access is limited to authorized personnel. For support or feature requests, contact the repository owner directly.

---
