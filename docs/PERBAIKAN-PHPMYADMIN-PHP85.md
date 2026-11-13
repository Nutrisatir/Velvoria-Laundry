# Velvoria Laundry pada PHP 8.5 dan perbaikan phpMyAdmin Laragon

## Status paket aplikasi

Paket Velvoria ini ditujukan untuk **PHP 8.5.x**. Persyaratan Composer sudah diubah menjadi `php: ^8.5`, sehingga `composer install` akan menolak runtime PHP yang lebih rendah dari 8.5. Pastikan ekstensi `intl`, `mbstring`, `mysqli`, `curl`, `json`, `openssl`, dan `fileinfo` aktif.

> Mengubah persyaratan Composer tidak memasang PHP ke komputer. PHP CLI dan PHP yang dipakai Apache Laragon harus dipilih/dipasang di Laragon secara terpisah.

## Pastikan PHP CLI dan Apache sama-sama 8.5

1. Di Laragon pilih **Menu → PHP → Version → PHP 8.5.x**.
2. Klik **Stop All**, lalu **Start All** agar Apache memuat runtime yang dipilih.
3. Di terminal baru, jalankan `php --version`. Pastikan keluar `PHP 8.5.x`.
4. Untuk memeriksa PHP Apache, buat sementara `public/phpinfo-check.php` dengan isi `<?php phpinfo();`, lalu buka `http://velvoria.test/phpinfo-check.php`. Pastikan versinya 8.5.x.
5. Hapus `public/phpinfo-check.php` setelah pemeriksaan karena phpinfo membeberkan detail konfigurasi server.

## phpMyAdmin adalah aplikasi terpisah

phpMyAdmin Laragon berada di `C:\laragon\etc\apps\phpMyAdmin`, bukan di folder proyek Velvoria. ZIP aplikasi tidak mengganti file phpMyAdmin atau dependensi vendor Laragon. Karena itu, mengganti kode proyek/SQL tidak dapat memperbaiki deprecation yang berasal dari file seperti `vendor\twig`, `vendor\symfony`, `vendor\slim`, atau `libraries\classes\ErrorHandler.php`.

Untuk tetap menggunakan PHP 8.5:

1. Backup konfigurasi `C:\laragon\etc\apps\phpMyAdmin\config.inc.php`.
2. Periksa versi phpMyAdmin melalui halaman utama atau bagian bawah halaman login.
3. Perbarui phpMyAdmin melalui mekanisme resmi Laragon atau unduh rilis phpMyAdmin yang secara eksplisit mendukung PHP 8.5 dari `https://www.phpmyadmin.net/downloads/`.
4. Ikuti petunjuk upgrade phpMyAdmin. Jangan menimpa folder `vendor` secara parsial dan jangan menghapus folder data MySQL.
5. Pastikan nilai `$cfg['blowfish_secret']` pada `config.inc.php` berupa string acak sepanjang 32 karakter. Contoh pembuatannya dari terminal: `php -r "echo bin2hex(random_bytes(16)), PHP_EOL;"`. Simpan hasilnya sebagai nilai `blowfish_secret`.
6. Restart Apache melalui Laragon dan buka kembali `http://localhost/phpmyadmin/`.

## Peringatan deprecation sementara

Jika phpMyAdmin masih dapat dibuka tetapi hanya menampilkan peringatan deprecation, pengaturan sementara dapat dilakukan pada `php.ini` yang benar-benar dipakai Apache (cek melalui `phpinfo()`):

```ini
display_errors = Off
log_errors = On
error_reporting = E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED
```

Restart Apache setelah mengubah `php.ini`. Ini hanya menyembunyikan notifikasi deprecation dari halaman dan mengarahkannya ke log. Ini **bukan** pengganti upgrade phpMyAdmin dan tidak memperbaiki fatal error atau kegagalan koneksi MySQL.

## Jaga database tetap aman

- Jangan menghapus `C:\laragon\data\mysql` atau folder data MySQL lainnya.
- Jangan reinstall MySQL sebelum membuat backup.
- Peringatan deprecation phpMyAdmin tidak berarti tabel `velvoria_laundry` rusak.
- Untuk menguji koneksi aplikasi, pastikan MySQL berjalan dan konfigurasi `database.default.*` di `.env` sesuai port, nama database, dan kredensial lokal.

## Instalasi dependensi aplikasi

Dari root folder Velvoria:

```bash
php --version
composer check-platform-reqs
composer install
php spark migrate
php spark db:seed DatabaseSeeder
```

Jalankan seeding hanya sesuai kebutuhan instalasi baru. Jangan menjalankan ulang proses setup pada database produksi/berisi transaksi tanpa backup dan pemeriksaan migration terlebih dahulu.
