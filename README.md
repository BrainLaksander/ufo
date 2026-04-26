# UFO

Aplikasi Laravel untuk mengelola konten publik mahasiswa, alur kerja pengurus UKM, dan layar review departemen kemahasiswaan.

## Menjalankan Lokal Dengan XAMPP

### Kebutuhan

- PHP 8.2+
- MySQL/MariaDB dari XAMPP
- Composer
- Apache dari XAMPP

### 1) Letakkan Project

Clone atau salin project ke workspace XAMPP, misalnya:

```bash
/var/www/ufounk/ufo
```

### 2) Jalankan Service XAMPP

Nyalakan service berikut dari XAMPP:

- Apache
- MySQL

### 3) Atur `.env`

Ubah pengaturan database di `.env`:

```env
APP_NAME=UFO
APP_ENV=local
APP_DEBUG=true
APP_URL=http://ufo.local

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ufo_db
DB_USERNAME=root
DB_PASSWORD=
```

Kalau Anda memakai user atau password MySQL yang berbeda di XAMPP, sesuaikan nilainya.

### 4) Buat Database

Buat database secara manual lewat phpMyAdmin atau MySQL:

```sql
CREATE DATABASE ufo_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5) Install Dependensi

Jalankan:

```bash
composer install
```

### 6) Generate App Key

Jika file `.env` sudah ada, generate key Laravel sekali saja:

```bash
php artisan key:generate
```

### 7) Jalankan Migrasi dan Seed Data

Untuk membangun schema dan mengisi data dummy:

```bash
php artisan migrate --seed
```

Anda juga bisa memakai command helper project berikut:

```bash
php artisan server:migrate --seed
```

Command itu akan membuat database secara otomatis untuk setup lokal MySQL/MariaDB jika perlu, lalu menjalankan migrasi dan seeder.

### 8) Atur Apache Virtual Host

Arahkan document root Apache ke folder `public` milik Laravel.

Contoh virtual host:

```apache
<VirtualHost *:80>
    ServerName ufo.local
    DocumentRoot "/var/www/ufounk/ufo/public"

    <Directory "/var/www/ufounk/ufo/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Lalu tambahkan entry host ini:

```text
127.0.0.1 ufo.local
```

Kalau tidak ingin memakai virtual host, Apache tetap harus mengarah ke folder `public`, bukan ke root project.

### 9) Jalankan Aplikasi

Buka aplikasi di browser:

```text
http://ufo.local
```

## Data Dummy

Seeder database mengisi tampilan utama dengan konten dummy untuk:

- Halaman publik mahasiswa
- Halaman pengurus UKM
- Halaman departemen kemahasiswaan

Akun user yang sudah ada tidak akan ditimpa oleh seed dummy.

## Command Berguna

```bash
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed
php artisan route:list
```

## Catatan

- Kalau Anda mengubah migrasi atau seeder, jalankan ulang `php artisan migrate --seed`.
- Kalau perubahan Blade belum muncul, bersihkan cache view dengan `php artisan view:clear`.
- Kalau Anda memakai Apache dan mengubah konfigurasi virtual host, restart Apache dari XAMPP.