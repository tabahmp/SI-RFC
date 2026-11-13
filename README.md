# SI-RFC — Sistem Informasi Request for Change
**Diskominfo Prov. Jawa Barat · Bidang APTIKA**

Aplikasi web untuk mengelola siklus penuh Request for Change (RFC) perubahan layanan TI,
mulai dari pengajuan formulir FR-015, verifikasi, keputusan pimpinan, eksekusi teknis, hingga penerbitan BAST.

---

## Stack Teknologi

| Layer    | Teknologi                                |
|----------|------------------------------------------|
| Backend  | Laravel 12 · PHP 8.2                     |
| Frontend | Vue 3 · Inertia.js v3 · TailwindCSS 3   |
| Database | MySQL (dev: SQLite compatible)           |
| Email    | Mailtrap (dev) / SMTP (prod)             |
| Icons    | Lucide via @iconify/vue                  |

---

## Instalasi & Setup

```bash
# 1. Clone repository
git clone <repo-url>
cd SI-RFC

# 2. Copy environment
cp .env.example .env

# 3. Isi konfigurasi di .env:
#    - DB_DATABASE, DB_USERNAME, DB_PASSWORD
#    - MAIL_USERNAME, MAIL_PASSWORD (dari Mailtrap)
#    - APP_URL

# 4. Install dependencies
composer install
npm install

# 5. Generate key
php artisan key:generate

# 6. Migrate & seed database
php artisan migrate:fresh --seed

# 7. Build assets
npm run build

# 8. Storage link
php artisan storage:link

# 9. Jalankan aplikasi
composer run dev
```

---

## Akun Demo (Password: `Password@123`)

| Email                                    | Peran       | OPD               |
|------------------------------------------|-------------|-------------------|
| admin@diskominfo.jabarprov.go.id         | Admin       | Diskominfo Jabar  |
| pemohon@jabarprov.go.id                  | Pemohon     | Bappeda Jabar     |
| verifikator@jabarprov.go.id              | Verifikator | Diskominfo Jabar  |
| pimpinan@jabarprov.go.id                 | Pimpinan    | Diskominfo Jabar  |
| teknisi@jabarprov.go.id                  | Teknisi     | Diskominfo Jabar  |
| dadan.perhubungan@jabarprov.go.id        | Pemohon     | Dinas Perhubungan |

> **Catatan:** Akun `dadan.perhubungan` berstatus *tertunda* — cocok untuk testing aktivasi akun via Admin.

---

## Konfigurasi Email (Mailtrap)

1. Daftar di [mailtrap.io](https://mailtrap.io) (gratis)
2. Buka: Inboxes → pilih inbox → SMTP Settings
3. Isi `.env`:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=<dari mailtrap>
   MAIL_PASSWORD=<dari mailtrap>
   MAIL_FROM_ADDRESS="noreply@si-rfc.jabarprov.go.id"
   MAIL_FROM_NAME="SI-RFC Diskominfo Jabar"
   ```
4. Pastikan queue berjalan: `php artisan queue:work`

---

## Alur Kerja RFC (State Machine)

```
draf → diajukan → perlu_revisi ⟷ diajukan
              → ditolak_verifikator
              → menunggu_persetujuan → ditolak_pimpinan
                                     → ditunda
                                     → disetujui → sedang_dikerjakan → selesai (BAST)
```

---

## Skema Database (3NF)

| Tabel              | Keterangan                              |
|--------------------|-----------------------------------------|
| peran              | Master 5 peran sistem                   |
| opd                | Master 21 OPD Jawa Barat                |
| kategori_rfc       | Master kategori domain teknis           |
| users              | Pengguna (FK ke peran & opd)            |
| permohonan_rfc     | Tabel utama RFC (FR-015)                |
| revisi_rfc         | Catatan telaah & revisi verifikator     |
| persetujuan_rfc    | Keputusan pimpinan                      |
| eksekusi_rfc       | Log pengerjaan teknisi                  |
| dokumen_bast       | Berita Acara Serah Terima               |
| notifikasi         | Notifikasi in-app per pengguna          |
| rfc_histories      | Audit trail seluruh perubahan status    |

---

## Fitur Utama

- **Auth:** Login + CAPTCHA, registrasi mandiri pemohon, lupa kata sandi via email
- **RBAC:** 5 peran (Admin, Pemohon, Verifikator, Pimpinan, Teknisi) dengan proteksi rute ketat
- **Admin:** Dashboard statistik, kelola akun + aktivasi, master OPD & kategori, laporan & ekspor CSV
- **Pemohon:** Form FR-015 lengkap (7 seksi), simpan draf, ajukan, pantau progres, cetak dokumen
- **Verifikator:** Antrean telaah, pemantauan SLA, telaah teknis + revisi
- **Pimpinan:** Dashboard keputusan, setujui / tolak / tunda RFC
- **Teknisi:** Daftar tugas, mulai eksekusi, upload BAST, arsip dokumen
- **Notifikasi:** In-app real-time + email via Mailtrap untuk setiap perubahan status
- **Cetak:** FR-015 dan BAST dalam format print A4

---

## Standar

- ITIL v4 (Change Management)
- ISO/IEC 20000 (IT Service Management)
- ISO/IEC 27001 (Information Security)
