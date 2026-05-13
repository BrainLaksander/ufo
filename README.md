# UFO (UNKLAB Forum Organization)

UFO adalah platform portal terintegrasi berbasis web yang dirancang khusus untuk mengelola berbagai aktivitas kemahasiswaan, Unit Kegiatan Mahasiswa (UKM), dan informasi kampus di Universitas Klabat (UNKLAB).

Aplikasi ini dibangun menggunakan kerangka kerja (framework) **Laravel** dan menggunakan arsitektur peran (*Role-based Access Control*) untuk memfasilitasi tiga aktor utama: **Mahasiswa**, **Pengurus UKM**, dan **Kemahasiswaan**.

---

## 🌟 Fitur Utama

### 1. Portal Mahasiswa (Publik)
- **Dashboard Interaktif**: Menampilkan daftar aktivitas, acara mendatang, dan pengumuman terbaru.
- **Kalender Akademik & Event**: Kalender visual yang menampilkan jadwal kegiatan ekstrakurikuler, hari libur, dan waktu di mana kegiatan ekstrakurikuler dilarang (masa ujian, dll).
- **Sistem *Lost & Found***: Platform untuk melaporkan atau mencari barang yang hilang dan ditemukan di area kampus.
- **Pendaftaran Acara**: Mahasiswa dapat melihat detail kegiatan dan mendaftar secara langsung.

### 2. Portal Pengurus UKM
- **Manajemen Event & Proposal**: Pengajuan acara baru beserta unggahan proposal (PDF) untuk di-review oleh pihak Kemahasiswaan.
- **Pelaporan Kegiatan (LPJ)**: Fitur untuk mengunggah Laporan Pertanggungjawaban (LPJ) setelah acara selesai.
- **Manajemen Pengumuman**: Mempublikasikan pengumuman resmi ke seluruh mahasiswa.
- **Sinkronisasi Notifikasi**: Pemberitahuan secara real-time ketika pengajuan acara/LPJ telah disetujui, ditolak, atau butuh revisi.

### 3. Portal Kemahasiswaan (Admin)
- **Manajemen UKM & Organisasi**: Pendaftaran, penonaktifan, dan manajemen profil seluruh UKM/organisasi yang ada.
- **Alur Persetujuan (Approval Workflow)**: Memeriksa dan memproses (Approve/Reject/Revisi) proposal acara dan LPJ yang diajukan oleh Pengurus UKM.
- **Manajemen Kalender Pusat**: Kemampuan untuk mengimpor kalender akademik secara otomatis (via PDF parsing) maupun manual untuk mengatur jadwal libur dan larangan kegiatan.
- **Notifikasi Terpusat**: Pemberitahuan setiap kali ada pengajuan dokumen atau laporan baru yang masuk.

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel (PHP)
- **Database**: MySQL / MariaDB
- **Frontend**: Blade Templating Engine, Vanilla CSS/JS (Sistem UI Kustom yang dirancang agar modern & responsif)
- **File Storage**: Laravel Local Storage (untuk menyimpan proposal, LPJ, poster acara, dan gambar *lost & found*)

---

## 🚀 Panduan Instalasi (Development)

Ikuti langkah-langkah di bawah ini untuk menjalankan project UFO di lingkungan lokal (local development).

### Prasyarat
- PHP >= 8.1
- Composer
- Node.js & npm
- Database MySQL/MariaDB

### Langkah-langkah

1. **Clone Repository** (jika ada):
   ```bash
   git clone <url-repository>
   cd ufo
   ```

2. **Instalasi Dependencies (PHP & Node)**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   Salin file konfigurasi bawaan dan sesuaikan nama database Anda.
   ```bash
   cp .env.example .env
   ```
   Buka file `.env`, lalu atur bagian database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ufo_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeding**:
   Jalankan perintah ini untuk membangun tabel database dan mengisi data *dummy* awal (termasuk akun Kemahasiswaan dan Pengurus UKM).
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Tautkan Storage (Storage Link)**:
   Karena aplikasi ini menyimpan file gambar dan PDF, tautkan folder *storage* agar dapat diakses dari *public*.
   ```bash
   php artisan storage:link
   ```

7. **Jalankan Server**:
   ```bash
   php artisan serve
   ```
   Aplikasi sekarang dapat diakses di: `http://localhost:8000`

---

## 🔑 Akun Uji Coba (Default Seeder)

Jika Anda telah menjalankan `php artisan db:seed`, Anda dapat menggunakan kredensial berikut untuk mencoba fitur aplikasi:

*Catatan: Pastikan untuk memeriksa file Seeder (`DatabaseSeeder.php` atau seeder terkait) karena kata sandi/email dapat berubah.*

- **Akun Kemahasiswaan**:
  - Email: `kemahasiswaan@unklab.ac.id`
  - Password: `password123`

- **Akun Pengurus UKM (Contoh)**:
  - Email: `bem@unklab.ac.id` (atau sesuai data seeder)
  - Password: `password123`

---

## 📁 Struktur Navigasi Folder Penting

- `app/Http/Controllers/` - Memuat logika utama untuk *Mahasiswa*, *Kemahasiswaan*, dan *Pengurus UKM*.
- `resources/views/` - Struktur folder antarmuka (*Blade template*):
  - `/mahasiswa/` - Tampilan publik mahasiswa.
  - `/kemahasiswaan/` - Antarmuka dashboard admin.
  - `/pengurus-ukm/` - Antarmuka dashboard organisasi.
  - `/layouts/` - Struktur tata letak utama (header, sidebar).
- `public/css/` - Semua skrip CSS kustom yang merender desain UI aplikasi.
- `app/Notifications/` - Class pengiriman notifikasi antar pengguna di dalam database.

---

## 📝 Lisensi

Aplikasi ini dikembangkan untuk keperluan internal Universitas Klabat (UNKLAB). Hak Cipta dilindungi.
