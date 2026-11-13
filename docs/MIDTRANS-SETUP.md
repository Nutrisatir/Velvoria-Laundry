# Midtrans Snap dan endpoint pembayaran

## Kredensial

Gunakan kredensial Sandbox dari Dashboard Midtrans > Settings > Access Keys:

- `midtrans.serverKey`: Server Key untuk request backend dan verifikasi status.
- `midtrans.clientKey`: Client Key untuk memuat Snap.js pada browser.
- `midtrans.isProduction = false`: wajib untuk pengujian Sandbox.

Server Key bersifat rahasia. Jangan menaruhnya di JavaScript, HTML, Git, screenshot, atau ZIP. Karena key sebelumnya terlihat pada tangkapan layar, lakukan regenerate/rotate dari Dashboard Midtrans dan gunakan key baru. ZIP ini sengaja mengosongkan nilai key.

## Konfigurasi `.env`

```ini
midtrans.serverKey = "SB-Mid-server-KEY_BARU"
midtrans.clientKey = "SB-Mid-client-KEY_BARU"
midtrans.isProduction = false
midtrans.isSanitized = true
midtrans.is3ds = true
```

Jangan menyalin placeholder. Ganti dengan key Sandbox milik merchant.

## Instalasi SDK

Jalankan dari folder yang memiliki `composer.json`:

```powershell
composer install
composer require midtrans/midtrans-php
composer dump-autoload
composer show midtrans/midtrans-php
```

Pastikan ekstensi `curl` aktif pada PHP yang digunakan Apache/Laragon, lalu restart Apache.

## Perbaikan request tombol Bayar Sekarang

Endpoint `POST /customer/orders/{order_number}/pay` tetap dilindungi filter autentikasi dan CSRF. Request AJAX sekarang mengirim token CSRF sebagai form-urlencoded dan header, menyertakan cookie sesi, serta menangani respons non-JSON/HTTP error dengan notifikasi dalam halaman. Regenerasi token CSRF per request dinonaktifkan agar token di halaman tidak kedaluwarsa di tengah request AJAX; perlindungan CSRF tetap aktif dan token tetap memiliki masa berlaku.

AuthFilter juga mengembalikan JSON 401/403 untuk request AJAX, bukan HTML redirect yang gagal diparse sebagai JSON.

## URL notifikasi

Webhook harus memakai URL publik HTTPS yang dapat dijangkau Midtrans. Domain `.test` atau `localhost` tidak dapat diakses dari server Midtrans. Untuk pengujian lokal, gunakan tunnel HTTPS dan daftarkan URL tunnel tersebut pada Payment Notification URL.

## Checklist pengujian

1. Pastikan `.env` berisi pasangan Server Key dan Client Key dari environment Sandbox yang sama.
2. Jalankan `composer show midtrans/midtrans-php`.
3. Restart Apache setelah mengganti `.env` atau konfigurasi PHP.
4. Login sebagai customer dan buka detail pesanan yang belum dibayar.
5. Klik Bayar Sekarang. Network request ke `/pay` harus mengembalikan JSON `success: true` beserta `snap_token`.
6. Snap Sandbox akan terbuka setelah token berhasil dibuat.
7. Saat token sedang dibuat, halaman menampilkan overlay loading dengan transisi halus. Setelah token tersedia, popup Snap resmi dibuka oleh Midtrans. Tampilan internal popup Snap dikendalikan Midtrans, bukan CSS website.
8. Jika HTTP 422, periksa `writable/logs` untuk pesan server Midtrans. Jika HTTP 403 masih muncul, pastikan browser mengirim cookie `csrf_cookie_name` dan request mengirim token CSRF yang masih berlaku.
9. Uji webhook hanya dari URL HTTPS publik, bukan domain `.test`.

## Catatan keamanan

Status pembayaran tidak boleh diubah berdasarkan callback browser. Status final harus diverifikasi melalui notifikasi server-to-server Midtrans dan pemeriksaan status transaksi.
