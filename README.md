# Velvoria Laundry

Website laundry berbasis CodeIgniter 4, PHP 8.5.x, MySQL/MariaDB, Tailwind CSS dan Midtrans Snap.

## Setup cepat

1. Siapkan PHP 8.5.x beserta ekstensi `intl`, `mbstring`, `mysqli`, `curl`, `json`, dan `openssl`.
2. Salin `.env.example` menjadi `.env`, lalu isi database, Midtrans Sandbox, dan SMTP.
3. Buat database `velvoria_laundry` dengan charset `utf8mb4`.
4. Jalankan:

```bash
composer install
php spark migrate
# Isi velvoria.adminPassword minimal 12 karakter di .env sebelum seeding
php spark db:seed DatabaseSeeder
php spark serve
```

5. Buka URL lokal yang ditampilkan oleh `php spark serve`.

## Database

Migration CI4 merupakan sumber skema utama di `app/Database/Migrations/`. Alternatifnya, instalasi baru dapat mengimpor `database/velvoria_laundry.sql`. Pilih salah satu cara pada database baru, jangan mengimpor SQL di atas database yang sudah berisi data.

## Konfigurasi rahasia

Jangan bagikan atau commit `.env`. Gunakan `.env.example` sebagai template. Midtrans harus memakai Sandbox key saat development. Webhook perlu URL HTTPS publik ketika diuji dari Midtrans.

## Dokumentasi

Lihat `docs/PERBAIKAN-DAN-DATABASE.md` untuk ringkasan database dan `docs/PATCH-SESSION-DAN-AUDIT-FUNGSI.md` untuk perbaikan session, konfigurasi Laragon, dan checklist pengujian tombol.


## Integrasi pembayaran Midtrans

Ikuti langkah pemasangan SDK, pengisian `.env`, rotasi key, dan pengaturan webhook pada [`docs/MIDTRANS-SETUP.md`](docs/MIDTRANS-SETUP.md). Jangan masukkan Server Key asli ke source code atau ZIP.


## Perbaikan form pesanan dan Laragon

Baca `docs/PERBAIKAN-FORM-PESANAN-TOAST-PHPMYADMIN.md` untuk langkah mengisi layanan awal, menguji form pesanan, dan menangani peringatan phpMyAdmin pada PHP 8.5.


## phpMyAdmin Laragon dan PHP 8.5

Peringatan deprecation phpMyAdmin berasal dari instalasi Laragon, bukan aplikasi Velvoria. Ikuti `docs/PERBAIKAN-PHPMYADMIN-PHP85.md` untuk memastikan Apache memakai PHP 8.5 dan memperbarui phpMyAdmin Laragon secara terpisah. Persyaratan Composer proyek ini dikunci ke PHP ^8.5. Debug Toolbar CI4 dinonaktifkan pada paket ini agar ikon toolbar tidak muncul di pojok halaman.

## Perbaikan pembayaran dan CSRF AJAX

Versi ini memperbaiki request tombol Bayar Sekarang agar mengirim token CSRF dalam format yang dikenali CI4, menyertakan cookie sesi, dan menampilkan error HTTP dengan lebih jelas. Baca `docs/MIDTRANS-SETUP.md`. ZIP tidak menyimpan kredensial Midtrans aktif; isi ulang `.env` dengan key Sandbox yang sudah dirotasi. Jalankan `composer install` dan pastikan `composer show midtrans/midtrans-php` berhasil sebelum menguji pembayaran.

## Instalasi XAMPP Apache (Windows)

Untuk migrasi dari Laragon ke XAMPP, ikuti `XAMPP-APACHE-INSTALL-ID.txt`. VirtualHost yang disediakan menggunakan `http://velvoria.test/` dan DocumentRoot `public/`.
