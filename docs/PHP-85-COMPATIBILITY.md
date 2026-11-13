# PHP 8.5 compatibility target

- Runtime target: PHP 8.5.x.
- Composer constraint: `^8.5` (PHP >= 8.5.0 and < 9.0.0).
- Required extensions: intl, mbstring, mysqli, curl, json, openssl.
- This repository does not bundle PHP binaries, Apache, MySQL, or phpMyAdmin. Install/select PHP 8.5.x in Laragon and restart Apache.
- phpMyAdmin warnings must be fixed by upgrading the Laragon phpMyAdmin installation separately. Do not edit its vendor files from this application archive.
