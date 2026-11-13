# Velvoria Laundry: multi-user / multi-session setup

Paket ini menyiapkan aplikasi agar guest, customer (user), dan admin dapat membuka website pada waktu yang sama dari browser/perangkat berbeda dengan sesi login masing-masing.

## Apa yang diubah

- Session CodeIgniter 4 menggunakan MySQL `ci_sessions` melalui `MySQLiHandler`, bukan satu session global. Setiap browser memiliki cookie/session ID sendiri.
- Migration `CreateCiSessionsTable` sudah tersedia di `app/Database/Migrations/2026-11-12-101300_CreateCiSessionsTable.php`.
- Login dan registrasi meregenerasi session ID serta menghancurkan ID lama untuk mengurangi risiko session fixation.
- Page cache global dinonaktifkan. Ini penting karena dashboard, profil, alamat, dan pesanan bersifat privat. Cache halaman yang salah konfigurasi dapat menampilkan data akun lain.
- Respons route yang memerlukan login diberi header `Cache-Control: private, no-store`.
- Filter route yang sudah ada tetap memisahkan `/admin/*` untuk role `admin` dan `/customer/*` untuk role `customer`. Guest adalah pengunjung yang belum login dan dapat membuka halaman publik.
- Data pesanan customer tetap difilter berdasarkan `user_id` dari session login.

## Instalasi pada XAMPP

1. Backup folder website dan database.
2. Salin file dari paket ini ke folder proyek, misalnya `C:\xampp\htdocs\VelvoriaLaundry`, dengan Replace.
3. Jangan mengganti `.env` lokal. Bila belum ada, salin `.env.example` menjadi `.env`, lalu isi konfigurasi database dan Midtrans sendiri.
4. Pastikan MySQL aktif dan database `velvoria_laundry` sudah ada.
5. Jalankan dari terminal di root proyek:

   ```powershell
   php spark migrate
   ```

6. Pastikan tabel `ci_sessions` muncul di phpMyAdmin. Migration tidak perlu dijalankan berulang jika sudah tercatat sebagai selesai.
7. Jalankan pemeriksaan:

   ```powershell
   php -l app/Config/Session.php
   php -l app/Config/Filters.php
   php -l app/Filters/AuthFilter.php
   php -l app/Controllers/Auth/LoginController.php
   php -l app/Controllers/Auth/RegisterController.php
   php spark routes
   ```

8. Restart Apache dan MySQL dari XAMPP.

## Mengakses dari beberapa perangkat dalam satu Wi-Fi/LAN

`localhost` hanya menunjuk ke komputer yang sedang membuka browser. Perangkat lain harus mengakses alamat IP komputer yang menjalankan XAMPP.

1. Pada komputer server, jalankan `ipconfig` dan catat IPv4, misalnya `192.168.1.20`.
2. Ubah `app.baseURL` di `.env` menjadi alamat LAN yang sesuai:

   ```ini
   app.baseURL = 'http://192.168.1.20/VelvoriaLaundry/'
   ```

   Ganti contoh IP dengan IPv4 komputer server. Jangan menaruh trailing path yang berbeda dari RewriteBase pada `.htaccess`.
3. Izinkan Apache HTTP (port 80) pada Windows Defender Firewall untuk jaringan Private. Jangan membuka port MySQL 3306 ke jaringan publik.
4. Pastikan perangkat lain berada di Wi-Fi/LAN yang sama, lalu buka `http://192.168.1.20/VelvoriaLaundry/`.
5. Guest dapat membuka halaman publik. Customer login dari perangkat/browser sendiri. Admin login dari perangkat/browser admin.

Jika Apache menggunakan virtual host atau document root yang berbeda, sesuaikan URL dan `app.baseURL` dengan konfigurasi Apache tersebut.

## Uji isolasi multi-user

1. Browser A: buka website sebagai guest, tetap di halaman publik.
2. Browser B atau perangkat lain: login sebagai customer A dan buka dashboard/pesanannya.
3. Browser C atau perangkat lain: login sebagai admin dan buka dashboard admin.
4. Buat pesanan dari customer A. Admin harus melihat pesanan tersebut, sedangkan customer lain tidak boleh melihatnya pada daftar/detail pesanan.
5. Logout customer A. Browser admin dan guest tidak boleh ikut logout.

Gunakan profil browser/incognito atau perangkat berbeda. Dua tab dalam profil browser yang sama memang berbagi cookie dan dianggap satu sesi login.

## Catatan deployment internet

Konfigurasi LAN bukan berarti website sudah tersedia di internet. Untuk akses publik, deploy ke hosting/VPS dengan domain, HTTPS, database produksi, backup, dan konfigurasi environment production. Midtrans webhook juga memerlukan URL HTTPS publik yang dapat dijangkau Midtrans; `localhost` atau IP LAN tidak cukup.

## Model role

- `guest`: belum login, hanya halaman publik dan halaman autentikasi.
- `customer`: akun pelanggan/user, akses `/customer/*`.
- `admin`: administrator, akses `/admin/*`.

Jangan membuat satu akun/session yang dibagikan banyak orang. Setiap orang harus login dengan akun masing-masing.
