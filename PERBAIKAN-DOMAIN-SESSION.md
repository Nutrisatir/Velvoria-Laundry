# Domain dan Session Velvoria Laundry

Konfigurasi paket XAMPP Apache ini menggunakan domain lokal `http://velvoria.test/`.

- `.env`, `.env.example`, dan `app/Config/App.php` sudah diarahkan ke domain tersebut.
- `app/Config/Session.php` menyimpan session di `writable/session` dan menggunakan cookie `velvoria_session`.
- Apache VirtualHost harus memiliki `ServerName velvoria.test` dan `DocumentRoot` ke `C:/xampp/htdocs/VelvoriaLaundry/public`.
- File Windows `hosts` harus berisi `127.0.0.1 velvoria.test`.

Ikuti `XAMPP-APACHE-INSTALL-ID.txt` untuk langkah konfigurasi Apache, PHP, Composer, dan database.
