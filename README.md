# Khazprokhir - Operational Management & Reporting System

Khazprokhir is a high-performance web application designed to streamline and monitor industrial operational workflows, specifically tailored for receiving, sorting, and packaging processes (HCS & HCTS). Built on top of the Laravel framework, it provides a robust platform for real-time reporting, target management, and inventory tracking with a focus on data integrity and user experience.

## ✨ Key Features

- **📊 Comprehensive Dashboards**: Real-time overview of operational counts, inventory status, and target achievements.
- **📦 Workflow Management**: Standardized modules for Receiving, Sorting, and Packaging (HCS/HCTS) with integrated stock ledger updates.
- **📈 Real-time Reporting**: Dynamic Daily Operational Reports with live-polling updates and advanced filtering (Budget Year, Emission Year, Denomination).
- **🎯 Target Tracking**: Management of Annual and Monthly packaging targets with visual progress indicators.
- **🎨 Premium UI/UX**: State-of-the-art interface featuring a "Double Card" aesthetic, responsive layouts, and interactive components powered by Alpine.js and Tailwind CSS.
- **📄 Export & Printing**: High-fidelity export options for Excel and PDF, with optimized print layouts for all operational reports.
- **🔐 Secure Access**: Role-based access control and detailed audit logging of critical actions.

## 🛠 Tech Stack

- **Backend**: [Laravel 11](https://laravel.com/) (PHP 8.2+)
- **Frontend**: [Tailwind CSS](https://tailwindcss.com/), [Alpine.js](https://alpinejs.dev/), [Blade Templates](https://laravel.com/docs/blade)
- **Build Tool**: [Vite](https://vitejs.dev/)
- **Database**: SQLite (Configurable to MySQL/PostgreSQL)
- **Icons**: [Heroicons](https://heroicons.com/)

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite (or your preferred DB driver)

### Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/username/khazprokhir.git
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
   *Configure your database settings in the `.env` file.*

4. **Database Migration**

   ```bash
   php artisan migrate --seed
   ```

5. **Build Assets**

   ```bash
   npm run build
   ```

6. **Start Application**

   ```bash
   php artisan serve
   ```
   Visit `http://localhost:8000` to access the portal.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).
