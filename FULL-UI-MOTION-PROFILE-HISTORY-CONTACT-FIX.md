# Velvoria Laundry - Full UI Motion, Avatar, History & Contact Fix

Patch dibuat dari paket proyek Velvoria Laundry yang sebelumnya disediakan dalam percakapan.

## Perubahan
1. Riwayat pesanan: elemen pagination tidak lagi ditampilkan jika hanya ada satu halaman, sehingga angka `1` yang muncul sendirian di bawah tabel hilang.
2. Foto profil pelanggan: pengguna dapat mengunggah JPG, PNG, atau WEBP maksimal 2 MB. Ada preview sebelum disimpan. Nama file dibuat acak, MIME/ukuran diperiksa, dan file avatar lama dihapus setelah update database berhasil.
3. Animasi global halaman publik: scroll reveal untuk konten, entrance antarhalaman dengan View Transitions API bila browser mendukung, serta fallback animasi masuk.
4. Hover tombol/link: transisi ease-in-out yang lebih lembut.
5. Login/register: panel ungu memiliki latar gradien bergerak, dan tombol/form mendapatkan hover/entrance yang lebih halus.
6. Kontak: alamat outlet dilengkapi peta dan tombol rute Google Maps. Query peta memakai nilai `store_address` dari pengaturan toko.

## Instalasi
1. Backup folder proyek saat ini.
2. Ekstrak ZIP.
3. Salin isi folder `VelvoriaLaundry` ke folder proyek dan izinkan replace.
4. Pastikan Apache/PHP dapat menulis ke `public/uploads/avatars` (folder akan dibuat otomatis saat upload).
5. Hard refresh browser: Ctrl+F5.

## Database
Kolom `users.avatar` sudah tersedia pada migration proyek yang menjadi dasar patch. Tidak ada migration tambahan untuk perubahan ini.
