# Velvoria Laundry - Auth Motion & Activity Logs Fix

Paket ini memperbarui dua file tampilan:

- `app/Views/layouts/auth.php`
  - Animasi teks opening pada panel login/register.
  - Tiap baris teks masuk dan keluar dengan gerakan slide yang halus.
  - Urutan animasi dibuat stagger melalui JavaScript dan berulang otomatis.
  - Form login/register mendapat animasi masuk singkat.
  - Menghormati pengaturan `prefers-reduced-motion`.

- `app/Views/admin/activity-logs/index.php`
  - Memperbaiki area pagination agar tidak dirender ketika data hanya satu halaman.
  - Menggunakan markup tabel yang lebih rapi dan aman.
  - Menghindari penanda halaman yatim di bawah tabel.

## Instalasi

1. Backup folder proyek saat ini.
2. Ekstrak ZIP.
3. Salin folder `VelvoriaLaundry` dari paket ini ke folder proyek, lalu izinkan replace file.
4. Tidak perlu menjalankan migrasi database karena tidak ada perubahan skema.
5. Bersihkan cache browser dengan Ctrl+F5, lalu buka halaman login, register, dan admin/activity-logs.

File `.env` tidak diubah oleh patch ini.
