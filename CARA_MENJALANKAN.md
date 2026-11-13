# 🚀 Cara Menjalankan SI-RFC

Panduan lengkap untuk setup dan menjalankan aplikasi SI-RFC (Sistem Informasi Request for Change).

---

## 📋 Prerequisites

Pastikan sudah terinstall:

- **PHP** >= 8.2 ([Download PHP](https://www.php.net/downloads))
- **Composer** ([Download Composer](https://getcomposer.org/download/))
- **Node.js** >= 18.x & NPM ([Download Node.js](https://nodejs.org/))
- **MySQL** atau **MariaDB** ([Download XAMPP](https://www.apachefriends.org/))
- **Git** ([Download Git](https://git-scm.com/))

### Cek Instalasi:
```bash
php -v        # Harus >= 8.2
composer -V   # Harus terinstall
node -v       # Harus >= 18.x
npm -v        # Harus terinstall
mysql --version  # Harus terinstall
```

---

## 🛠️ Setup Pertama Kali

### 1. Clone Repository

```bash
# Clone dari GitHub
git clone https://github.com/tabahmp/SI-RFC.git
cd SI-RFC
```

### 2. Install Dependencies

**Install PHP dependencies:**
```bash
composer install
```

**Install Node.js dependencies:**
```bash
npm install
```

### 3. Setup Environment

**Copy file .env.example ke .env:**
```bash
# Windows (PowerShell)
Copy-Item .env.example .env

# Atau bisa manual copy-paste file .env.example jadi .env
```

**Generate Application Key:**
```bash
php artisan key:generate
```

### 4. Konfigurasi Database

**A. Buat Database MySQL:**

**Jika pakai XAMPP:**
1. Buka XAMPP Control Panel
2. Start **Apache** dan **MySQL**
3. Buka browser ke `http://localhost/phpmyadmin`
4. Klik **New** → Buat database baru dengan nama: **`si_rfc`**
5. Charset: `utf8mb4_unicode_ci`

**Atau via command line:**
```bash
mysql -u root -p
CREATE DATABASE si_rfc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

**B. Update file .env:**

Edit file `.env` dengan text editor, sesuaikan:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=si_rfc
DB_USERNAME=root
DB_PASSWORD=           # Kosongkan jika pakai XAMPP default
```

### 5. Run Migrations & Seeders

**Jalankan database migrations:**
```bash
php artisan migrate
```

**Jalankan seeders (data awal):**
```bash
php artisan db:seed
```

Ini akan membuat:
- User admin default
- Kategori RFC
- Status RFC
- Data master lainnya

### 6. Setup Storage & Permissions

**Create storage link (untuk file uploads):**
```bash
php artisan storage:link
```

**Windows: Pastikan folder writable:**
```bash
# Folder yang harus bisa ditulis:
# - storage/
# - bootstrap/cache/
# - database/ (jika pakai SQLite)
```

### 7. Setup Queue Table

**Create jobs table untuk queue system:**
```bash
php artisan queue:table
php artisan migrate
```

---

## ▶️ Menjalankan Aplikasi

### Cara 1: Otomatis (Recommended)

**Jalankan semuanya sekaligus dengan 1 command:**

```bash
composer run dev
```

Ini akan menjalankan secara bersamaan:
- ✅ Laravel server (http://localhost:8000)
- ✅ Queue worker (background jobs)
- ✅ Log viewer (pail)
- ✅ Vite dev server (hot reload)

**Buka browser ke:** `http://localhost:8000`

### Cara 2: Manual (Terminal Terpisah)

Jika cara 1 tidak bekerja, jalankan manual di 3 terminal berbeda:

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```
Server akan berjalan di: `http://localhost:8000`

**Terminal 2 - Queue Worker:**
```bash
php artisan queue:work --tries=3 --timeout=90
```
Untuk proses email notifications dan background jobs.

**Terminal 3 - Vite Dev Server:**
```bash
npm run dev
```
Untuk hot reload frontend (Vue components).

**Buka browser ke:** `http://localhost:8000`

---

## 👤 Login Default

Setelah database seeded, login dengan:

**Admin:**
- Email: `admin@si-rfc.go.id`
- Password: `password`

**Verifikator:**
- Email: `verifikator@si-rfc.go.id`
- Password: `password`

**Pemohon (User biasa):**
- Bisa register sendiri di halaman Register

---

## 📧 Setup Email (Opsional)

Untuk testing email notifications:

### Opsi 1: Log (Development)

Edit `.env`:
```env
MAIL_MAILER=log
```

Email akan ditulis ke file: `storage/logs/laravel.log`

### Opsi 2: Mailtrap (Recommended untuk Testing)

1. Daftar gratis di [Mailtrap.io](https://mailtrap.io/)
2. Buat inbox baru
3. Copy credentials ke `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username_here
MAIL_PASSWORD=your_password_here
MAIL_FROM_ADDRESS="noreply@si-rfc.go.id"
MAIL_FROM_NAME="SI-RFC"
```

### Opsi 3: Gmail (Production)

1. Buat App Password di Google Account
2. Edit `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@si-rfc.go.id"
MAIL_FROM_NAME="SI-RFC"
```

---

## ⏰ Setup Scheduler (Background Tasks)

Untuk SLA monitoring dan monthly reports, setup task scheduler:

### Windows

**Buat file batch script:**

1. Buat file `schedule.bat` di root project:

```batch
@echo off
cd "D:\1. KULIAH\1. BELAJAR\SEMESTER 7\4. Kerja Praktik\SI-RFC"
php artisan schedule:run >> nul 2>&1
```

2. Buka **Task Scheduler** (Jadwal Tugas)
3. **Create Basic Task** → "Laravel Scheduler"
4. Trigger: **Daily**
5. Time: **Repeat every 1 minute**
6. Action: **Start a program** → Browse ke `schedule.bat`

### Linux/Mac

Edit crontab:
```bash
crontab -e

# Tambahkan baris ini:
* * * * * cd /path/to/SI-RFC && php artisan schedule:run >> /dev/null 2>&1
```

### Manual Testing

Test scheduler tanpa cron:
```bash
# Test SLA monitoring
php artisan rfc:check-sla

# Test monthly report
php artisan report:monthly

# List semua scheduled tasks
php artisan schedule:list
```

---

## 🔧 Command-command Berguna

### Development

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild cache
php artisan config:cache
php artisan route:cache

# Check routes
php artisan route:list

# Check queue jobs
php artisan queue:work --once
php artisan queue:failed        # Lihat failed jobs
php artisan queue:retry all     # Retry failed jobs

# Database
php artisan migrate:fresh --seed   # Reset database
php artisan migrate:rollback       # Undo last migration
php artisan db:seed                # Run seeders only

# View logs real-time
php artisan pail
# atau
tail -f storage/logs/laravel.log

# Tinker (Laravel REPL)
php artisan tinker
```

### Build untuk Production

```bash
# Install production dependencies
composer install --optimize-autoloader --no-dev

# Build frontend assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Set permissions
# (Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

---

## 🧪 Testing Fitur-fitur Utama

### 1. Test RFC Workflow

1. Login sebagai **Pemohon**
2. Klik **Buat RFC Baru**
3. Isi form dan upload dokumen
4. Submit → Cek email notification

### 2. Test Dashboard Analytics

1. Login sebagai **Admin** atau **Verifikator**
2. Menu **Analytics** di sidebar
3. Lihat charts dan statistik
4. Test date range filter

### 3. Test Export Excel

1. Login sebagai **Admin**
2. Menu **Laporan**
3. Pilih filter (tanggal, status, dll)
4. Klik **Export Excel**
5. File akan terdownload

### 4. Test Comment System

1. Buka detail RFC
2. Scroll ke bagian Comments
3. Tambah komentar
4. Test reply (nested comments)
5. Staff bisa buat internal notes

### 5. Test Search & Filter

1. Di Dashboard
2. Gunakan search box
3. Test filter by status, kategori, urgensi
4. Test date range filter

---

## 🐛 Troubleshooting

### Error: "No application encryption key"

```bash
php artisan key:generate
```

### Error: Database connection refused

1. Cek MySQL/XAMPP sudah running
2. Cek credentials di `.env`
3. Test koneksi:
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

### Error: storage/logs/laravel.log not writable

**Windows:**
```bash
# Pastikan folder ada
if (!(Test-Path "storage/logs")) { New-Item -ItemType Directory -Path "storage/logs" }
```

**Linux/Mac:**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Error: Vite manifest not found

```bash
# Build ulang frontend
npm run build

# Atau jalankan dev mode
npm run dev
```

### Queue jobs tidak jalan

```bash
# Cek ada jobs di queue
php artisan queue:work --once

# Start queue worker
php artisan queue:work --tries=3

# Cek failed jobs
php artisan queue:failed
php artisan queue:retry all
```

### Email tidak terkirim

1. Cek `.env` mail configuration
2. Pastikan queue worker berjalan
3. Cek logs: `storage/logs/laravel.log`
4. Test mail:
```bash
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

### Chart.js tidak muncul

```bash
# Reinstall node modules
Remove-Item node_modules -Recurse -Force
Remove-Item package-lock.json
npm install
npm run dev
```

---

## 📁 Struktur Project Penting

```
SI-RFC/
├── app/
│   ├── Http/Controllers/       # Controllers
│   ├── Models/                 # Models (PermohonanRfc, RfcComment, dll)
│   ├── Mail/                   # Email templates (Mailable classes)
│   └── Console/Commands/       # Artisan commands (SLA, Reports)
├── database/
│   ├── migrations/             # Database schema
│   └── seeders/                # Data seeders
├── resources/
│   ├── js/
│   │   ├── Pages/             # Vue pages (Inertia)
│   │   └── Components/        # Vue components (Charts, dll)
│   └── views/
│       └── emails/            # Email templates (Blade)
├── routes/
│   ├── web.php                # Web routes
│   └── console.php            # Scheduler configuration
├── storage/
│   ├── app/public/            # File uploads
│   └── logs/                  # Log files
├── .env                       # Configuration (JANGAN COMMIT!)
└── composer.json              # PHP dependencies
```

---

## 🔐 Security Notes

1. **Jangan commit file `.env`** - Contains sensitive data
2. **Change default passwords** setelah production
3. **Set `APP_DEBUG=false`** di production
4. **Setup SSL/HTTPS** untuk production
5. **Regular backup database**

---

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs/12.x)
- [Vue 3 Documentation](https://vuejs.org/guide/)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Chart.js Documentation](https://www.chartjs.org/docs/)

---

## 🆘 Need Help?

**Jika ada masalah:**

1. Check logs: `storage/logs/laravel.log`
2. Run diagnostics:
```bash
php artisan about
php artisan route:list
php artisan queue:failed
```
3. Clear all caches:
```bash
php artisan optimize:clear
```

---

## 🎉 Quick Start (TL;DR)

```bash
# 1. Clone & Install
git clone https://github.com/tabahmp/SI-RFC.git
cd SI-RFC
composer install
npm install

# 2. Setup
Copy-Item .env.example .env
php artisan key:generate

# 3. Database (buat database 'si_rfc' di MySQL/phpMyAdmin dulu!)
# Edit .env, set DB_DATABASE=si_rfc
php artisan migrate --seed

# 4. Run
composer run dev

# 5. Open browser: http://localhost:8000
# Login: admin@si-rfc.go.id / password
```

---

**✅ Project Ready! Happy Coding! 🚀**

_Last Updated: 2026-11-13_
