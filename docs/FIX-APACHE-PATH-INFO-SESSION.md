# Perbaikan Apache, PHP CGI/NTS, dan Session Velvoria

## Perubahan

- `public/.htaccess` tidak lagi menulis ulang URL menjadi `index.php/$1`.
- Semua URL non-file/non-direktori diarahkan ke `index.php` saja. URI asli tetap tersedia melalui `REQUEST_URI` untuk router CodeIgniter.
- Session memakai `WRITEPATH . 'session'` dari `app/Config/Session.php`.
- Session lama yang tersimpan keliru di `public/null` dibuang.
- Folder runtime `writable/session`, `cache`, `logs`, `uploads`, dan `debugbar` disiapkan.

## VirtualHost Laragon

Pastikan DocumentRoot mengarah ke folder `public`, bukan root proyek:

```apache
<VirtualHost *:80>
    ServerName velvoria.test
    DocumentRoot "C:/laragon/www/VelvoriaLaundry/public"

    <Directory "C:/laragon/www/VelvoriaLaundry/public">
        Options FollowSymLinks
        AllowOverride All
        Require all granted
        DirectoryIndex index.php
    </Directory>
</VirtualHost>
```

Aktifkan `mod_rewrite` di konfigurasi Apache dan restart Laragon.

## Konfigurasi lokal

1. Salin `.env.example` menjadi `.env` jika `.env` lokal belum ada.
2. Atur `app.baseURL = 'http://velvoria.test/'`.
3. Isi konfigurasi database dan layanan dengan kredensial milik sendiri.
4. Jangan mengisi `session.savePath` dengan `null`; biarkan konfigurasi default `app/Config/Session.php`.
5. Pastikan `app/Config/Session.php` menggunakan:

```php
public string $driver = FileHandler::class;
public string $savePath = WRITEPATH . 'session';
```

## Pengujian

Jalankan `php spark routes`, lalu uji `/`, `/index.php`, `/login`, dan `/register`. Jika `/index.php` bisa dibuka tetapi `/login` tidak, periksa `mod_rewrite` dan `AllowOverride All`. Jika semuanya menghasilkan `No input file specified`, periksa handler PHP/Apache dan DocumentRoot.
