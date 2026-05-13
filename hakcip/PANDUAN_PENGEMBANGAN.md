# Panduan Pengembangan Program (Software Development Guide)
**Nama Program**: UFO (UNKLAB Forum Organization)
**Jenis Ciptaan**: Program Komputer / Aplikasi Web Terintegrasi
**Pencipta**: [Isi Nama Anda/Tim Pencipta]
**Pemegang Hak Cipta**: Universitas Klabat / [Isi Nama Pemilik Hak Cipta]

---

## 1. Pendahuluan
Dokumen ini disusun sebagai lampiran teknis dan deskripsi ciptaan untuk pendaftaran Hak Cipta Program Komputer. **UFO (UNKLAB Forum Organization)** adalah sebuah *platform* web manajemen kemahasiswaan terpadu yang dirancang untuk mensentralisasi kegiatan ekstrakurikuler, komunikasi organisasi, pengajuan proposal acara, dan pelaporan pertanggungjawaban (LPJ) di lingkungan Universitas Klabat (UNKLAB).

## 2. Arsitektur Sistem
Program UFO dibangun menggunakan arsitektur **Model-View-Controller (MVC)** yang memisahkan logika bisnis (alur data), manipulasi database, dan antarmuka pengguna, sehingga program lebih mudah dikembangkan, aman, dan *scalable*.

### A. Teknologi & Framework Utama
- **Bahasa Pemrograman Utama**: PHP (versi 8.1 atau lebih baru)
- **Web Framework**: Laravel 
- **Database Management System**: MySQL / MariaDB
- **Web Server**: Nginx / Apache
- **Frontend / Antarmuka**: HTML5, CSS3 Kustom (tanpa library CSS berat, dioptimasi untuk kecepatan), dan Vanilla JavaScript.

### B. Konsep Role-Based Access Control (RBAC)
Keamanan data pada sistem UFO dibagi berdasarkan tiga pilar hak akses:
1. **Mahasiswa (Akses Publik/Student)**: Berfungsi sebagai *end-user* yang melihat kalender kegiatan, pengumuman, dan dapat menggunakan modul *Lost & Found*.
2. **Pengurus UKM (Unit Kegiatan Mahasiswa)**: Memiliki hak untuk menginisiasi kegiatan, mengunggah proposal (PDF), melaporkan LPJ, dan membuat *draft* acara kalender.
3. **Kemahasiswaan (Administrator Pusat)**: Bertindak sebagai pengawas dan pemberi izin. Memiliki kendali penuh untuk meninjau (Approve/Reject/Revisi) proposal/LPJ, memblokir kalender (misal: masa ujian), dan mengatur daftar organisasi.

## 3. Alur Kerja Logika Utama (Core Workflows)

### Modul Pengajuan Kegiatan (Event Proposal Workflow)
1. **Inisiasi**: Pengurus UKM mengisi *form* kegiatan dan mengunggah dokumen proposal (format `.pdf`).
2. **State Management**: Status awal kegiatan adalah `diajukan`. Kegiatan ini tersembunyi dari publik.
3. **Trigger Notifikasi**: Sistem *backend* membangkitkan objek `GeneralNotification` berbasis sistem antrean (database), yang secara instan memberitahu akun Kemahasiswaan.
4. **Evaluasi**: Kemahasiswaan mengevaluasi dokumen pada *dashboard* mereka. Jika perlu perbaikan, status diubah menjadi `revisi`. Jika disetujui, diubah menjadi `approved`.
5. **Penerbitan Otomatis**: Jika disetujui, acara tersebut (yang tertaut dengan `event_id`) akan otomatis berubah status menjadi `upcoming` (akan datang) dan langsung tampil di kalender Mahasiswa se-kampus secara *real-time*.

### Modul Kalender Dinamis
Program ini menggunakan teknik penggabungan basis data (data merging) dan pengecekan kata kunci tingkat *backend* untuk menentukan visualisasi acara:
- Jika kategori kegiatan berbunyi **"Libur"** atau **"Tidak Boleh Berkegiatan"**, sistem akan memblokir tanggal tersebut sehingga UKM tidak dapat mengajukan acara di hari yang sama.
- Validasi tumpang tindih (*overlapping validation*) terjadi di level *Controller* sebelum data tersimpan di *database*.

### Modul Sinkronisasi Notifikasi Terpusat
Menggantikan *alert* tradisional, program UFO memuat *View Composer* dan `Laravel Database Notifications`. Sistem ini:
- Melakukan hitungan secara asinkron (*unread counts*).
- Menampilkan indikator angka visual (badge) pada Header dan Panel Dropdown yang di-render saat *page load*.
- Melakukan perubahan status `read_at` menjadi *timestamp* yang terekam persisten saat pengguna mengeklik "Tandai Semua Sudah Dibaca".

## 4. Struktur Basis Data Relasional
Walaupun detail tabel dirahasiakan sebagai properti hak cipta, entitas utama meliputi:
- `users`: Autentikasi dan otorisasi *role*.
- `organizations`: Profil identitas tiap UKM.
- `events`: Kalender kegiatan beserta waktu pelaksanaannya.
- `activity_submissions`: Jembatan dokumen (Proposal & LPJ) yang diajukan oleh UKM.
- `lost_and_founds`: Laporan barang hilang.
- `notifications`: Rekaman pesan antar *role*.
*(Semua entitas dihubungkan menggunakan kunci tamu / foreign keys yang menaati aturan normalisasi basis data.)*

## 5. Orisinalitas & Inovasi
Aplikasi UFO mengklaim hak cipta atas:
1. **Desain Tata Letak (UI/UX)**: Tata letak portal Kemahasiswaan, Pengurus UKM, dan Mahasiswa yang dirancang secara mandiri dan *custom-made*, tidak menggunakan *template* bajakan atau berlisensi komersial pihak ketiga yang terikat.
2. **Logika Proses**: Alur sinkronisasi perizinan yang unik untuk ekosistem spesifik Universitas Klabat.
3. **Source Code**: Susunan fungsi di dalam folder `app/Http/Controllers` dan `resources/views` merupakan hasil karya tulis logis intelektual yang orisinal.

---
*Dokumen panduan ini bersifat rahasia dan dikhususkan sebagai lampiran untuk pendaftaran Kekayaan Intelektual (HKI) pada Direktorat Jenderal Kekayaan Intelektual (DJKI) Republik Indonesia.*
