# Velvoria Laundry - XAMPP Apache (localhost subfolder)

Paket ini disiapkan untuk instalasi standar XAMPP pada:

`C:\\xampp\\htdocs\\VelvoriaLaundry`

URL utama:

`http://localhost/VelvoriaLaundry/`

## Mengapa sebelumnya 403 Forbidden?

Apache diarahkan ke folder root proyek yang tidak memiliki front controller. Paket ini menambahkan `index.php` root sebagai pengarah ke `public/index.php`, serta `.htaccess` untuk meneruskan route ke CodeIgniter. Folder sumber seperti `app`, `system`, `writable`, `tests`, `database`, dan `vendor` diblokir dari akses URL langsung.

## Instalasi

1. Ekstrak folder `VelvoriaLaundry` ke `C:\\xampp\\htdocs\\VelvoriaLaundry`. Pastikan tidak menjadi folder bersarang `VelvoriaLaundry\\VelvoriaLaundry`.
2. Buka `C:\\xampp\\php\\php.ini`. Aktifkan `extension=intl`, `extension=mbstring`, `extension=curl`, `extension=mysqli`, `extension=fileinfo`, `extension=openssl`, dan `extension=zip`. Pastikan `extension_dir="C:\\xampp\\php\\ext"`.
3. Restart Apache dari XAMPP Control Panel.
4. Pastikan Apache `mod_rewrite` aktif dan konfigurasi direktori `C:/xampp/htdocs` memiliki `AllowOverride All`. Pada XAMPP, cek `C:\\xampp\\apache\\conf\\httpd.conf`, cari `LoadModule rewrite_module modules/mod_rewrite.so` dan pastikan tidak diawali `#`.
5. Buka terminal baru:
   `cd C:\\xampp\\htdocs\\VelvoriaLaundry`
6. Periksa `php --ini` dan pastikan PHP yang aktif adalah `C:\\xampp\\php\\php.ini`.
7. Jalankan `composer install`. Jika belum ada `composer.lock`, jalankan `composer update`.
8. Jika Midtrans SDK belum terpasang, jalankan `composer require midtrans/midtrans-php`.
9. Atur database di `.env`, lalu import `database/velvoria_laundry.sql` melalui phpMyAdmin atau jalankan migrasi sesuai kebutuhan proyek.
10. Masukkan Server Key dan Client Key Sandbox baru pada `.env`. Jangan membagikan Server Key.
11. Buka `http://localhost/VelvoriaLaundry/`.

## Konfigurasi URL

`.env` sudah menggunakan `app.baseURL = 'http://localhost/VelvoriaLaundry/'`.
Jangan gunakan URL `velvoria.test` untuk instalasi subfolder ini. Jika memilih VirtualHost dengan DocumentRoot langsung ke `public/`, ubah kembali baseURL dan gunakan konfigurasi VirtualHost terpisah.

## Catatan

- Folder `vendor` sengaja tidak dibundel. Composer harus dijalankan pada komputer lokal setelah ekstensi PHP aktif.
- `403 Forbidden` bisa tetap muncul bila `.htaccess` diabaikan. Pastikan `AllowOverride All` aktif dan restart Apache.
- Jika Apache tidak memproses `.htaccess`, cek `mod_rewrite` dan error log Apache.
- Untuk keamanan, jangan gunakan konfigurasi development pada server publik.
