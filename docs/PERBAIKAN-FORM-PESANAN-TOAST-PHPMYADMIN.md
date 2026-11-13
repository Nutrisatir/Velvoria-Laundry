# Perbaikan form pesanan, notifikasi, dan Laragon/phpMyAdmin

## 1. Mengapa tombol lanjut mengatakan belum memilih layanan

Tab **Laundry Kiloan** dan **Laundry Satuan** hanya memfilter kategori. Pengguna tetap harus menekan tombol `+` pada kartu layanan untuk menambahkan kuantitas. Jika kartu layanan tidak muncul sama sekali, database belum memiliki data layanan aktif.

Pada instalasi baru, jalankan dari terminal di root proyek:

```bash
php spark migrate
php spark db:seed DatabaseSeeder
```

Seeder akan membuat kategori, layanan contoh, pengaturan toko, dan data admin awal sesuai konfigurasi proyek. Setelah itu buka ulang `/customer/orders/create`.

Jika seeder pernah dijalankan tetapi layanan masih kosong, periksa tabel `service_categories` dan `services` di phpMyAdmin. Pastikan ada kategori aktif `kiloan` dan `satuan`, serta layanan dengan `is_active = 1`. Jangan menghapus database yang sudah berisi transaksi.

## 2. Perubahan pada form pesanan

- Kategori layanan dinormalisasi dari slug maupun nama kategori sehingga variasi slug tidak membuat kartu hilang dari kedua tab.
- Nama, kode, dan unit layanan diserialisasi aman ke JavaScript menggunakan `json_encode`, sehingga tanda kutip/apostrof pada nama layanan tidak merusak Alpine.js.
- Kuantitas dimulai dari nol. Pengguna memilih layanan dengan tombol `+`, dan ringkasan menghitung kuantitas yang dipilih.
- Notifikasi validasi pada form memakai toast di dalam halaman, bukan dialog `alert()` bawaan browser.
- Kartu, tombol, tautan, dan notifikasi diberi transisi yang lebih halus. Pengaturan `prefers-reduced-motion` tetap dihormati.

## 3. Jika toast tidak muncul dan masih terlihat dialog browser

Pastikan file proyek yang baru benar-benar menggantikan file lama:
`app/Views/customer/orders/create.php` dan `app/Views/layouts/customer.php`.

Lalu:
1. Bersihkan cache browser dengan `Ctrl + Shift + R`.
2. Jalankan `php spark cache:clear`.
3. Pastikan koneksi internet tersedia karena Alpine.js pada layout dimuat dari CDN.
4. Buka DevTools > Console dan pastikan tidak ada error JavaScript.

## 4. Peringatan E_STRICT di phpMyAdmin pada PHP 8.5

Peringatan berikut berasal dari aplikasi phpMyAdmin bawaan Laragon, bukan dari kode Velvoria:

`Constant E_STRICT is deprecated ... Laragon\\etc\\apps\\phpMyAdmin\\libraries\\classes\\ErrorHandler.php`

Jangan mengedit file `ErrorHandler.php` secara manual. Itu file inti phpMyAdmin dan perubahan bisa tertimpa saat Laragon diperbarui.

Urutan penanganan yang disarankan:

1. **Untuk proyek Velvoria:** gunakan PHP 8.5.x pada CLI dan Apache Laragon.
2. **Untuk phpMyAdmin:** perbarui phpMyAdmin Laragon ke rilis yang menyatakan kompatibel dengan PHP 8.5. phpMyAdmin adalah aplikasi terpisah dari proyek ini.
3. Jika phpMyAdmin masih bisa dibuka dan hanya menampilkan peringatan, nonaktifkan tampilan error/deprecation pada konfigurasi PHP yang dipakai Laragon untuk web, bukan dengan menghapus konstanta dari source phpMyAdmin. Untuk development lokal, set `display_errors=Off` dan `log_errors=On`, lalu restart Apache. Error tetap tercatat di log.
4. Bila halaman phpMyAdmin benar-benar tidak terbuka, periksa `C:\\laragon\\logs\\apache_error.log` dan `C:\\laragon\\logs\\php_error.log`. Peringatan E_STRICT sendiri biasanya bukan penyebab Apache gagal start.

Mengganti versi PHP untuk phpMyAdmin juga mengubah PHP yang menjalankan proyek. Velvoria pada paket ini menetapkan persyaratan Composer PHP ^8.5. Pastikan PHP CLI dan Apache Laragon sama-sama menggunakan PHP 8.5.x. Pastikan ekstensi `intl`, `mbstring`, `mysqli`, `curl`, `openssl`, dan `json` aktif.

## 5. Catatan database

Peringatan E_STRICT tidak diperbaiki dengan mengubah migration atau query database Velvoria. phpMyAdmin adalah aplikasi terpisah yang disediakan Laragon. Kode proyek dan phpMyAdmin perlu ditangani pada tempatnya masing-masing.
