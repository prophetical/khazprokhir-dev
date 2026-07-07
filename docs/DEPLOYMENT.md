# Dokumentasi Deployment — Khazprokhir

Dokumen ini ditujukan untuk tim IT/SysOps untuk men-deploy aplikasi **Khazprokhir** ke Virtual Machine (VM) berbasis Linux. Disusun berdasarkan analisis langsung terhadap source code dan konfigurasi proyek.

---

## 1. Gambaran Aplikasi

| Item | Nilai |
|------|-------|
| Framework | Laravel 12 |
| Bahasa | PHP 8.2+ |
| Frontend | Tailwind CSS 3.4, Alpine.js 3, Vite 7 |
| Database | **PostgreSQL 15+ (wajib)** — aplikasi memakai `TO_CHAR`, window function, dan `CAST AS VARCHAR` yang tidak kompatibel dengan SQLite/MySQL |
| Driver Session/Queue/Cache | `database` (semua disimpan di PostgreSQL) |
| Monitoring | Laravel Pulse (aktif default, dashboard di `/pulse`) |
| Auth | Laravel Breeze (login berbasis `username` + `password`) |
| Antrian (queue) | Tidak ada Job saat ini, namun `QUEUE_CONNECTION=database` (siap untuk pemakaian mendatang) |
| Scheduler | Tidak ada task terjadwal kustom (hanya `inspire` bawaan) |

> **Implikasi penting:** karena session & cache memakai driver `database`, **database PostgreSQL wajib sudah running & ter-migrate sebelum aplikasi bisa diakses** (termasuk halaman login).

---

## 2. Spesifikasi VM Minimum

### 2.1 Hardware
- **CPU:** 2 vCPU
- **RAM:** 4 GB (8 GB direkomendasikan jika Pulse + PostgreSQL satu VM)
- **Disk:** 20 GB (SSD)
- **OS:** Ubuntu Server 24.04 LTS (dapat juga 22.04)

### 2.2 Software Prasyarat
| Komponen | Versi | Keterangan |
|----------|-------|------------|
| PHP | 8.2 / 8.3 / 8.4 | Dengan extension di §2.3 |
| Composer | 2.7+ | Dependency manager PHP |
| Node.js | 18+ (24 dipakai di dev) | Untuk build asset Vite |
| NPM | 10+ | Dibundle Node.js |
| PostgreSQL | 15+ (17 dipakai di dev) | Database utama |
| Nginx | 1.24+ | Web server (direkomendasikan) |
| Supervisor / systemd | — | Untuk proses queue & Pulse worker |

### 2.3 Extension PHP yang Wajib/Disarankan

Disesuaikan dengan Dockerfile dev (`docker/8.2/Dockerfile`):

```
php-cli php-fpm php-pgsql php-mbstring php-xml php-curl php-zip
php-bcmath php-intl php-gd php-readline php-opcache
```

> Extension `pdo_pgsql` (bagian dari `php-pgsql`) **mutlak wajib**. `php-gd` & `php-intl` diperlukan untuk Laravel Pulse & fitur framework. `php-zip` diperlukan Composer.

Cek ketersediaan setelah instalasi:
```bash
php -m | grep -iE 'pdo_pgsql|mbstring|xml|curl|zip|bcmath|intl|gd|opcache'
```

### 2.4 Port & Firewall

| Port | Tujuan | Akses |
|------|--------|-------|
| 80 / 443 | HTTP(S) publik ke Nginx | Terbuka (boleh lewat load balancer/reverse proxy) |
| 9000 | PHP-FPM (hanya `127.0.0.1` atau unix socket) | Internal |
| 5432 | PostgreSQL | **Jangan** dibuka ke publik; hanya `127.0.0.1` jika satu VM, atau private network jika DB terpisah |

Aturan ufw contoh:
```bash
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

---

## 3. Instalasi Prasyarat (Ubuntu 24.04)

```bash
# Update sistem
sudo apt update && sudo apt upgrade -y

# PHP + extension
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2-fpm php8.2-cli php8.2-pgsql php8.2-mbstring \
  php8.2-xml php8.2-curl php8.2-zip php8.2-bcmath php8.2-intl \
  php8.2-gd php8.2-readline php8.2-opcache

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Node.js (via NodeSource)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Nginx & Supervisor
sudo apt install -y nginx supervisor

# PostgreSQL 17 (repo resmi)
sudo sh -c 'echo "deb https://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list'
curl -fsSL https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo gpg --dearmor -o /etc/apt/trusted.gpg.d/postgresql.gpg
sudo apt update
sudo apt install -y postgresql-17
```

---

## 4. Konfigurasi PostgreSQL

```bash
sudo -u postgres psql
```

```sql
-- Ganti password dengan nilai KUAT sesuai environment
CREATE ROLE khazpro WITH LOGIN PASSWORD 'GANTI_PASSWORD_KUAT';
CREATE DATABASE khazprokhir OWNER khazpro ENCODING 'UTF8' LC_COLLATE 'C.UTF-8' LC_CTYPE 'C.UTF-8' TEMPLATE template0;
GRANT ALL PRIVILEGES ON DATABASE khazprokhir TO khazpro;
\q
```

Verifikasi koneksi (sebagai user biasa di VM):
```bash
psql -h 127.0.0.1 -U khazpro -d khazprokhir -c "SELECT version();"
```

Jika PostgreSQL di VM **terpisah** dari app server, edit `pg_hba.conf` agar menerima koneksi dari subnet private app server (jangan dari internet publik).

---

## 5. Menarik & Menyiapkan Source Code

### 5.1 Clone repository
```bash
sudo mkdir -p /var/www/khazprokhir
sudo chown -R $USER:$USER /var/www/khazprokhir
cd /var/www/khazprokhir
git clone https://github.com/prophetical/khazprokhir.git .
```

> Jika repo private, siapkan SSH key atau deploy token pada VM, atau copy tarball source dari CI.

### 5.2 Install dependency
```bash
# Composer (production, tanpa dev dependency)
composer install --no-dev --optimize-autoloader --no-interaction

# Frontend asset
npm ci
npm run build
```

> Output `npm run build` berada di `public/build/`. Pastikan folder ini ikut ter-deploy.

### 5.3 File environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` **minimal** sebagai berikut (lihat §7 untuk daftar lengkap):
```dotenv
APP_NAME=Khazprokhir
APP_ENV=production
APP_KEY=base64:...           # hasil key:generate
APP_DEBUG=false              # WAJIB false di produksi
APP_URL=https://domain.tld   # URL publik sebenarnya

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=khazprokhir
DB_USERNAME=khazpro
DB_PASSWORD=GANTI_PASSWORD_KUAT   # sama dengan §4

CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database

PULSE_ENABLED=true            # false bila tidak ingin monitoring
```

### 5.4 Migrasi & seeder
```bash
php artisan migrate --force

# Seeder awal: membuat 6 user role + 1 tamu. Password default: Peruri4321
# Hanya jalankan saat deploy pertama; jangan di-deploy ulang agar tidak menimpa data
php artisan db:seed --force
```

Akun yang dibuat `DatabaseSeeder` (username / role, password sama semua `Peruri4321` -> lihat source terbaru):

| username | email | role |
|----------|-------|------|
| admin | admin@khazprokhir.com | admin |
| supervisor | supervisor@khazprokhir.com | supervisor |
| sortir | sortir@khazprokhir.com | sortir |
| kemas | kemas@khazprokhir.com | kemas |
| khazai | khazai@khazprokhir.com | khazai |
| khazverutas | khazverutas@khazprokhir.com | khazverutas |
| tamu | tamu@khazprokhir.com | (null) |

> **Ganti password semua akun seeder segera setelah deploy pertama.** Password default ada di `database/seeders/DatabaseSeeder.php`.

### 5.5 Storage link & cache optimasi
```bash
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

> Catatan: source code tidak memakai upload file user (`Storage::disk`/`store` tidak digunakan), sehingga `storage:link` bersifat opsional namun tetap direkomendasikan sebagai konvensi Laravel.

### 5.6 Permission folder
Web user (biasanya `www-data`) harus bisa menulis ke `storage` dan `bootstrap/cache`:
```bash
sudo chown -R $USER:www-data /var/www/khazprokhir
sudo find /var/www/khazprokhir -type d -exec chmod 775 {} \;
sudo find /var/www/khazprokhir -type f -exec chmod 664 {} \;
sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
```

---

## 6. Konfigurasi Web Server

### 6.1 Nginx vhost
Buat `/etc/nginx/sites-available/khazprokhir`:
```nginx
server {
    listen 80;
    server_name domain.tld;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name domain.tld;

    root /var/www/khazprokhir/public;
    index index.php;

    # SSL (isi dengan sertifikat Anda, mis. Let's Encrypt)
    ssl_certificate     /etc/letsencrypt/live/domain.tld/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/domain.tld/privkey.pem;

    # Aset Vite yang sudah di-build
    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    client_max_body_size 20m;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
```

Aktifkan:
```bash
sudo ln -s /etc/nginx/sites-available/khazprokhir /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

### 6.2 SSL (Let's Encrypt)
```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d domain.tld
```

### 6.3 Tuning PHP-FPM (`/etc/php/8.2/fpm/php.ini`)
```ini
memory_limit = 256M
upload_max_filesize = 20M
post_max_size = 22M
max_execution_time = 60
date.timezone = Asia/Jakarta
```
```bash
sudo systemctl restart php8.2-fpm
```

---

## 7. Daftar Lengkap Variabel `.env` Produksi

```dotenv
APP_NAME=Khazprokhir
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://khazprokhir.peruri.co.id

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_MAINTENANCE_DRIVER=database

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=warning

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=khazprokhir
DB_USERNAME=khazpro
DB_PASSWORD=GANTI_PASSWORD_KUAT

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
CACHE_STORE=database

PULSE_ENABLED=true
# PULSE_DOMAIN=pulse.khazprokhir.peruri.co.id   # opsional: isolasi dashboard ke subdomain
# PULSE_PATH=pulse                # default /pulse
```

> Jika ingin dashboard Pulse lebih aman, set `PULSE_DOMAIN` ke subdomain terpisah dan proteksi via network ACL. Authorization Pulse default mengizinkan akses **hanya dari request lokal**; untuk produksi, definisikan Gate `viewPulse` (lihat `config/pulse.php` + `app/Providers/AppServiceProvider.php`).

---

## 8. Proses Latar Belakang (Worker)

### 8.1 Queue worker (via Supervisor)
Walaupun saat ini belum ada Job, worker berguna saat fitur antrian ditambahkan nanti.
Buat `/etc/supervisor/conf.d/khazprokhir-worker.conf`:
```ini
[program:khazprokhir-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/khazprokhir/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/khazprokhir/storage/logs/worker.log
stopwaitsecs=3600
```
```bash
sudo supervisorctl reread && sudo supervisorctl update && sudo supervisorctl start khazprokhir-worker:*
```

### 8.2 Laravel Pulse worker
Pulse butuh proses ingestion untuk data real-time. Jalankan via Supervisor:
```ini
[program:khazprokhir-pulse]
command=php /var/www/khazprokhir/artisan pulse:work
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/khazprokhir/storage/logs/pulse.log
```
> Jika Pulse tidak dipakai, set `PULSE_ENABLED=false` di `.env` dan lewati langkah ini.

### 8.3 Cron scheduler (opsional)
Tidak ada schedule kustom saat ini, namun pasang cron standar Laravel agar siap saat ditambahkan:
```bash
(crontab -l 2>/dev/null; echo "* * * * * cd /var/www/khazprokhir && php artisan schedule:run >> /dev/null 2>&1") | crontab -
```

---

## 9. Checklist Post-Deployment

- [ ] `APP_DEBUG=false` di `.env`
- [ ] `APP_URL` sesuai domain publik
- [ ] Password DB diganti dari default (`secret`)
- [ ] `php artisan config:cache` dijalankan ulang setelah ubah `.env`
- [ ] Permission `storage/` & `bootstrap/cache/` writable oleh `www-data`
- [ ] SSL aktif dan auto-renew (`certbot renew --dry-run`)
- [ ] Login berhasil dengan akun admin (test: `https://khazprokhir.peruri.co.id/login`)
- [ ] Halaman `/laporan-harian` tampil tanpa error 500
- [ ] `/pulse` tidak dapat diakses publik (cek Gate / ACL)
- [ ] Password akun seeder diganti setelah login pertama
- [ ] Backup database terjadwal (lihat §10)

---

## 10. Backup & Restore

### Backup harian PostgreSQL (cron root)
`/etc/cron.daily/backup-khazprokhir`:
```bash
#!/usr/bin/env bash
set -euo pipefail
BACKUP_DIR=/var/backups/khazprokhir
mkdir -p "$BACKUP_DIR"
TS=$(date +%F)
pg_dump -h 127.0.0.1 -U khazpro -d khazprokhir -Fc -f "$BACKUP_DIR/khazprokhir-$TS.dump"
# Retensi 14 hari
find "$BACKUP_DIR" -name "khazprokhir-*.dump" -mtime +14 -delete
```
```bash
sudo chmod +x /etc/cron.daily/backup-khazprokhir
```
> Gunakan `.pgpass` atau `~postgres/.pgpass` agar tidak menyimpan password di skrip.

### Restore
```bash
sudo -u postgres pg_restore --clean --if-exists -d khazprokhir /var/backups/khazprokhir-YYYY-MM-DD.dump
```

---

## 11. Workflow Update / Re-deploy

```bash
cd /var/www/khazprokhir

# 1. Mode maintenance
php artisan down

# 2. Tarik kode terbaru
git pull --ff-only origin main

# 3. Update dependency
composer install --no-dev --optimize-autoloader --no-interaction
npm ci && npm run build

# 4. Migrasi database
php artisan migrate --force

# 5. Bersihkan & bangun ulang cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 6. Restart worker & web
sudo supervisorctl restart khazprokhir-worker:* khazprokhir-pulse:*
sudo systemctl reload php8.2-fpm nginx

# 7. Aktifkan kembali
php artisan up
```

---

## 12. Troubleshooting

| Gejala | Kemungkinan Penyebab | Solusi |
|--------|---------------------|---------|
| Halaman putih / error 500 di login | DB belum running atau migrate belum dijalankan (session = database) | Pastikan PostgreSQL jalan & `php artisan migrate --force` sudah dieksekusi |
| `Illuminate\Database\QueryException` koneksi refused | `DB_HOST`/`DB_PORT`/kredensial salah | Cek `.env` & uji `psql -h ... -U khazpro -d khazprokhir` |
| Asset CSS/JS tidak termuat (halaman tanpa style) | `npm run build` belum dijalankan atau `public/build/` tidak ikut ter-deploy | Jalankan `npm ci && npm run build` |
| `The stream or file could not be opened` (log) | Permission `storage/logs` | `sudo chown -R www-data:www-data storage` |
| `500` setelah ubah `.env` | Cache config lama masih dipakai | `php artisan config:cache` |
| `Illegal operator and value combination` | Input tanggal kosong dikonversi `null` lalu dipakai `whereDate` (sudah di-patch di `LaporanHarianController`) | Pastikan patch `getFilters()` ter-deploy |
| Pulse dashboard 403/404 | Gate `viewPulse` belum diizinkan / path diubah | Definisikan Gate atau set `PULSE_DOMAIN` |

### Lokasi file penting
- Log aplikasi: `storage/logs/laravel.log`
- Log worker: `storage/logs/worker.log`
- Log Pulse: `storage/logs/pulse.log`
- Log Nginx: `/var/log/nginx/error.log`
- Log PHP-FPM: `/var/log/php8.2-fpm.log`

### Perintah diagnostik cepat
```bash
php artisan about            # ringkasan environment Laravel
php artisan migrate:status   # status migrasi
php artisan config:show database   # cek konfigurasi DB aktif
php artisan route:list       # daftar route
php artisan tinker           # REPL; cek: User::count(), DB::connection()->getPdo()
```

---

## 13. Catatan Keamanan

1. **Jangan commit `.env` produksi** ke repository (sudah di-`.gitignore`).
2. Rotasi `APP_KEY` menginvalidasi semua session — lakukan saat maintenance window dan jalankan `php artisan config:cache` setelahnya.
3. `SESSION_ENCRYPT` di `.env` dev saat ini `false`. Untuk produksi, pertimbangkan set `true` (membutuhkan `APP_KEY` valid & library openssl).
4. Batasi akses SSH VM hanya dari IP kantor/VPN. Pasang fail2ban bila perlu.
5. Lakukan hardening PostgreSQL: ubah `listen_addresses`, batasi `pg_hba.conf`, nonaktifkan trust auth.
6. Update OS rutin: `sudo unattended-upgrade` untuk security patch.

---

*Dokumen ini mengacu pada state source code per tanggal deploy. Bila ada perubahan signifikan (penambahan queue job, scheduler, storage upload), update bagian §2, §5.4, §8, dan §10 sesuai kebutuhan.*
