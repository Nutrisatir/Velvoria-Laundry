# Patch konfigurasi dan pemeriksaan fungsi Velvoria Laundry

## Perbaikan yang diterapkan pada paket ini

1. Menghapus `.env` lokal dari arsip karena berisi kredensial lingkungan. Buat `.env` baru dengan menyalin `.env.example`.
2. Memperbaiki `app.baseURL` pada `.env.example` menjadi `http://velvoria.test/` (tanpa salah ketik).
3. Menghapus override `session.savePath = null`. Konfigurasi CI4 menggunakan `WRITEPATH . 'session'` melalui `app/Config/Session.php`.
4. Menyertakan direktori `writable/session`, `writable/cache`, `writable/logs`, `writable/uploads`, dan `writable/debugbar`.
5. Menghapus file session yang keliru tersimpan di `public/null`.
6. Mempertahankan controller dan route aplikasi yang sudah ada. Audit statis menemukan semua target route mengarah ke controller dan method yang tersedia.
7. Menjalankan PHP lint pada 169 file PHP aplikasi. Tidak ditemukan kesalahan sintaks.

## Instalasi di Laragon

1. Ekstrak paket ke `C:\laragon\www\VelvoriaLaundry`.
2. Salin `.env.example` menjadi `.env`.
3. Pastikan bagian awal `.env` berisi:

```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://velvoria.test/'
app.appTimezone = 'Asia/Jakarta'

database.default.hostname = localhost
database.default.database = velvoria_laundry
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
database.default.charset = utf8mb4
database.default.DBCollat = utf8mb4_unicode_ci
```

4. Jangan menambahkan `session.savePath = null`. Biarkan `app/Config/Session.php` memakai `WRITEPATH . 'session'`.
5. Pastikan folder `writable/session` ada dan dapat ditulis oleh Windows/Apache.
6. Jalankan `composer install`.
7. Buat database `velvoria_laundry`, lalu jalankan `php spark migrate` dan `php spark db:seed DatabaseSeeder`.
8. Pastikan virtual host Laragon mengarah ke folder `public` dan host `velvoria.test` tercantum di file Windows `hosts` sebagai `127.0.0.1 velvoria.test`.
9. Jalankan `php spark routes` untuk melihat daftar route.

## Jika memakai domain Laragon otomatis

Aktifkan Auto Virtual Hosts dan klik Reload pada Laragon. Pastikan DocumentRoot virtual host adalah folder `public`, bukan root proyek. Bila domain tidak resolve, jalankan CMD sebagai Administrator:

```cmd
ipconfig /flushdns
```

Alternatif untuk uji cepat:

```bash
php spark serve
```

Lalu buka `http://localhost:8080`.

## Catatan penting mengenai tombol

Audit statis pada paket ini menunjukkan semua target route terdaftar dan method controller yang dituju tersedia. Form utama pada halaman autentikasi, customer, dan admin juga memiliki endpoint POST yang sesuai.

Audit statis tidak dapat membuktikan operasi database, email SMTP, pembayaran Midtrans, upload, atau izin akun berjalan di komputer pengguna. Semua itu perlu diuji langsung dengan database dan konfigurasi lokal.

### Urutan uji manual

1. Guest: Beranda, Layanan, Harga, Cara Kerja, FAQ, Kontak.
2. Auth: Register, Login, Logout, Lupa Password, Reset Password (SMTP harus diisi untuk email reset).
3. Customer: Profil, Ubah Password, Alamat (tambah/edit/hapus/jadikan utama), Buat Pesanan, Detail, Batalkan, Riwayat, Cetak Nota.
4. Admin: Dashboard, Pesanan (status/berat/catatan/nota), Layanan, Kategori, Pelanggan, Pembayaran, Promo, Laporan/CSV, Pengaturan, Staf, Activity Logs.
5. Payment: gunakan Midtrans Sandbox. Jangan menandai pembayaran Midtrans sebagai berhasil hanya dari redirect browser. Pastikan Notification URL publik HTTPS mengarah ke `/payment/midtrans/notification`.

## Keamanan

Arsip awal yang diunggah berisi `.env`. Karena file tersebut pernah dibagikan, rotasi Midtrans Server Key/Client Key jika key tersebut aktif atau bukan key dummy. Jangan pernah mengunggah `.env` yang berisi secret. Gunakan `.env.example` saat meminta pemeriksaan source code.
