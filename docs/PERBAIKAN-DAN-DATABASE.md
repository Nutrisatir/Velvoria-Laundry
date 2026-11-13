# Catatan Perbaikan Velvoria Laundry

Dokumen ini menjelaskan patch lanjutan untuk source Antigravity. Perubahan dibuat dengan mempertahankan struktur CI4 dan nama tabel yang sudah digunakan aplikasi.

## Perbaikan kode

1. `app/Services/PromotionService.php`
   - Menyamakan nama kolom dengan migration: `starts_at`, `ends_at`, `usage_limit`, dan `usage_per_user`.
2. `app/Services/OrderService.php`
   - Memvalidasi metode penyerahan.
   - Memastikan alamat pickup memang milik customer yang sedang login.
3. `app/Services/MidtransService.php`
   - Menggunakan library resmi `midtrans/midtrans-php`.
   - Tidak membuat token pembayaran palsu.
   - Menolak webhook dengan signature kosong/tidak valid.
   - Meminta status transaksi langsung ke Midtrans dan mencocokkan order ID serta nominal.
   - Memetakan status dan menyimpan notifikasi secara idempotent.
4. `app/Views/customer/orders/show.php`
   - Menghapus simulasi pembayaran lunas dari browser.
   - Mengirim CSRF header pada request AJAX pembuatan token.
5. `app/Controllers/Admin/PaymentController.php`
   - Pembayaran tunai dicatat sebagai payment provider `manual`, bukan mengubah record Midtrans menjadi transaksi tunai.
6. `app/Filters/AuthFilter.php` dan `app/Controllers/Auth/LoginController.php`
   - Role `staff` tidak otomatis mendapat seluruh akses admin.
   - Akun `inactive` dan `blocked` tidak dapat login.
7. Fitur tambahan yang sebelumnya belum tersedia:
   - `Customer/HelpController.php` dan `customer/help.php`.
   - Halaman riwayat pesanan.
   - `Admin/ActivityLogController.php` dan halaman activity logs.
   - Migration dan model token reset password.
   - Implementasi reset password berbasis token sekali pakai dengan masa berlaku 60 menit.

## Database

Nama database: `velvoria_laundry`.

Ada dua cara membuat struktur database:

### Cara A: Migration CodeIgniter (disarankan)

```bash
composer install
```

Salin `.env.example` menjadi `.env`, lalu sesuaikan koneksi database. Buat database kosong melalui phpMyAdmin atau MySQL:

```sql
CREATE DATABASE velvoria_laundry CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Jalankan:

```bash
php spark migrate
# Isi velvoria.adminPassword minimal 12 karakter di .env sebelum seeding
php spark db:seed DatabaseSeeder
```

Migration akan membuat tabel inti termasuk `password_reset_tokens`.

### Cara B: Import SQL

Import file `database/velvoria_laundry.sql` melalui phpMyAdmin. File SQL membuat tabel yang sama dengan migration dan tidak menghapus tabel/data yang sudah ada. Jangan menjalankan migration setelah import SQL kecuali tabel migration CI4 juga sudah diselaraskan. Untuk instalasi baru, pilih salah satu cara saja.

## Konfigurasi `.env`

Isi setidaknya:

```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://velvoria-laundry.test/'
app.appTimezone = 'Asia/Jakarta'

database.default.hostname = localhost
database.default.database = velvoria_laundry
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_unicode_ci

midtrans.serverKey = 'SB-Mid-server-ISI_DARI_DASHBOARD'
midtrans.clientKey = 'SB-Mid-client-ISI_DARI_DASHBOARD'
midtrans.isProduction = false
```

Untuk email reset password, isi konfigurasi SMTP di `.env.example` menggunakan kredensial SMTP milik sendiri. Jangan commit `.env` ke Git dan jangan menaruh Server Key Midtrans di JavaScript/browser.

## Endpoint penting

- Webhook Midtrans: `POST /payment/midtrans/notification`
- Customer history: `GET /customer/history`
- Customer help: `GET /customer/help`
- Admin activity log: `GET /admin/activity-logs`
- Forgot password: `GET/POST /forgot-password`
- Reset password: `GET /reset-password/{token}` dan `POST /reset-password`

## Hal yang masih harus dites sebelum production

- Jalankan migration dan seeder pada database kosong.
- Uji register, login, role, reset password dan pengiriman SMTP.
- Uji checkout kiloan/satuan, alamat milik user lain, promo, pembatalan dan berat aktual.
- Uji Midtrans Sandbox untuk settlement, pending, deny, expire, cancel, webhook duplikat, nominal tidak cocok dan keterlambatan webhook.
- Uji transaksi manual/cash serta audit log.
- Jalankan `php spark routes` dan seluruh feature test.
- Konfigurasikan HTTPS dan Notification URL publik untuk webhook.

Patch ini belum menyertakan kredensial merchant, konfigurasi SMTP aktif, ataupun hasil uji transaksi Sandbox. Itu harus diisi dan diuji di lingkungan pemilik aplikasi.
