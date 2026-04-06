# Khazprokhir - Premium Operational & Reporting Ecosystem

Khazprokhir is a high-performance, enterprise-grade web application designed to streamline industrial operational workflows. It specializes in the management of receiving, sorting, and packaging processes (**HCS & HCTS**), providing real-time analytics, automated bilyet calculations, and robust inventory tracking.

Built with a focus on visual excellence and data integrity, Khazprokhir transforms complex operational data into actionable insights through a modern, premium interface.

---

## ✨ Key Features

- **📊 Advanced Analytics Dashboard**: Real-time overview of production metrics, denomination-based trends, and automated bilyet summaries.
- **📱 Fully Mobile-Ready**: A completely responsive interface optimized for all devices (mobile, tablet, desktop) without compromising density or aesthetics.
- **📦 Workflow Modules**: Standardized end-to-end management for:
  - **HCS** (Receiving, Sorting, Packaging)
  - **HCTS** (Receiving, Inventory, Submission)
- **📈 Inschiet Analytical Module**: Specialized tracking for production discrepancies with dynamic chart visualizations and detailed modal breakdowns.
- **📋 Real-time Operational Reports**: Live-polling reports with advanced multi-criteria filtering (TA/TE, Denomination, and specific date ranges).
- **🎨 Premium UI/UX**: Modern glassmorphism aesthetic with specialized dark mode support, fluid typography, and micro-animations.
- **📄 Pro Export & Printing**: High-fidelity PDF and Excel exports with dedicated print-optimized layouts for all operational modules.

---

## 🛠 Tech Stack

- **Backend**: [Laravel 11](https://laravel.com/) (PHP 8.2+)
- **Frontend**: [Tailwind CSS 3.4](https://tailwindcss.com/) + [Alpine.js 3](https://alpinejs.dev/)
- **Build Tool**: [Vite](https://vitejs.dev/)
- **Charts**: [Chart.js](https://www.chartjs.org/)
- **Popups**: [SweetAlert2](https://sweetalert2.github.io/)
- **Icons**: [Heroicons](https://heroicons.com/)

---

## 🚀 Getting Started

### Prerequisites

- **PHP** >= 8.2 (with JSON & PDO extensions)
- **Composer** (PHP Package Manager)
- **Node.js** >= 18 & **NPM**
- **Database**: SQLite (Default) or MySQL/PostgreSQL

### Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/prophetical/khazprokhir.git
   cd khazprokhir
   ```

2. **Install Dependencies**

   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   *Configure your database and app settings in the `.env` file.*

4. **Database Initialization**

   ```bash
   php artisan migrate --seed
   ```

5. **Build Production Assets**

   ```bash
   npm run build
   ```

6. **Final Launch**

   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to access the application.

---

## 🏗 Deployment Note

For production deployment to a VM, follow the detailed instructions in [deployment_guide.md](deployment_guide.md) to properly compile assets and configure environment variables.

---

## 🔐 Internal Repository

This is a **private repository** managed by **[prophetical](https://github.com/prophetical)**. Access is restricted to authorized personnel only.

For technical support or feature requests, please contact the repository owner directly.

---
