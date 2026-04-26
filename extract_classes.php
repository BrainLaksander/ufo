<?php
/*
=============================================================================
PANDUAN PEMBUATAN CLASS DIAGRAM UNTUK SISTEM MANAJEMEN ORGANISASI (UFO-1)
=============================================================================

Berdasarkan keseluruhan arsitektur kode (MVC Laravel) yang Anda miliki, Anda dapat 
membuat beberapa jenis Class Diagram agar lebih fokus dan mudah dibaca, daripada
menggabungkan semuanya dalam satu diagram yang sangat besar.

Berikut adalah rekomendasi jenis-jenis Class Diagram yang bisa Anda buat beserta
detail penulisannya:

-----------------------------------------------------------------------------
1. DOMAIN MODEL / ENTITY CLASS DIAGRAM (Fokus pada Database / Model)
-----------------------------------------------------------------------------
Diagram ini adalah yang paling umum dibuat untuk Skripsi. Diagram ini hanya 
memfokuskan pada struktur entitas data (tabel database) dan relasinya.

* Class yang dilibatkan: Semua yang ada di folder `app/Models` 
  (User, Organization, Event, Task, LostFoundItem, Proposal, dll).
* Detail Penulisan:
  - Kotak Atas (Nama Class): Tuliskan nama model, misal `Event`.
  - Kotak Tengah (Atribut): Ambil dari daftar `$fillable`.
    * Penulisan: `- atribut: tipe_data` (Gunakan tanda `-` yang berarti private/protected).
    * Contoh: 
      - title: String
      - event_date: Date
      - status: String
  - Kotak Bawah (Method): Ambil dari fungsi relasi atau fungsi logika di dalam model.
    * Penulisan: `+ method(): return_type` (Gunakan tanda `+` yang berarti public).
    * Contoh:
      + organization(): Organization
      + creator(): User
      + isFull(): Boolean
* Relasi antar class:
  - Association / Directed Association (Garis panah biasa): Menunjukkan relasi "Has Many" 
    atau "Belongs To" antar entitas. (Misal: 1 Organization memiliki banyak (N) Event).

-----------------------------------------------------------------------------
2. CONTROLLER CLASS DIAGRAM (Fokus pada Logika / Proses Bisnis)
-----------------------------------------------------------------------------
Diagram ini berfokus pada aksi yang bisa dilakukan oleh sistem melalui antarmuka 
controller. Biasanya berguna jika pembimbing skripsi meminta detail aksi.

* Class yang dilibatkan: Semua yang ada di folder `app/Http/Controllers`.
* Detail Penulisan:
  - Kotak Atas: Nama Controller (Contoh: `EventOrganisasiController`)
  - Kotak Tengah: Biasanya kosong atau berisi dependency yang di-inject.
  - Kotak Bawah (Method): Berisi fungsi aksi CRUD.
    * Contoh:
      + index(): View
      + store(Request): RedirectResponse
      + update(Request, id): RedirectResponse
      + publish(): Response
* Relasi: 
  - Dependency (Garis putus-putus dengan panah): Mengarah dari Controller ke Model 
    yang diaksesnya. (Misal: `EventOrganisasiController` ---> `Event`).

-----------------------------------------------------------------------------
3. MODULE-SPECIFIC CLASS DIAGRAMS (Diagram Berdasarkan Modul/Fitur)
-----------------------------------------------------------------------------
Daripada membuat 1 diagram besar yang rumit, pecahlah menjadi beberapa subsistem:

a) Modul Autentikasi & Pengguna (User Management):
   - Terdiri dari: `User`, `Member`, `Organization`, `AuthController`, `LoginController`.
   - Relasi: User adalah (atau memiliki) Member, User memimpin Organization.

b) Modul Manajemen Kegiatan (Event Management):
   - Terdiri dari: `Organization`, `Event`, `EventOrganisasiController`.
   - Relasi: Organization membuat Event. EventOrganisasiController mengelola Event.

c) Modul Tugas & Laporan (Task & Reporting):
   - Terdiri dari: `Task`, `Submission`, `Report`, `Member`.
   - Relasi: Task diselesaikan melalui Submission (Pengajuan) dan diverifikasi melalui Report.

d) Modul Barang Hilang & Ditemukan (Lost & Found):
   - Terdiri dari: `LostFoundItem`, `LostFoundController`, `User` (sebagai pelapor/pengklaim).

e) Modul Pengumuman & Proposal:
   - Terdiri dari: `Announcement`, `Proposal`, `PengumumanController`, `ProposalController`.

-----------------------------------------------------------------------------
CARA / TIPS PENULISAN DI APLIKASI DIAGRAM (Misal: Draw.io, StarUML, Enterprise Architect)
-----------------------------------------------------------------------------
1. Tanda Visibilitas (Visibility):
   - `-` (Private/Protected): Gunakan untuk semua Atribut dari Model. Karena atribut 
     tersebut sebenarnya merupakan kolom database atau hidden property di Laravel.
   - `+` (Public): Gunakan untuk semua Method yang ada di Controller maupun Model.

2. Tipe Data:
   - Sesuaikan dengan tipe kolom database, misal: String, Integer, DateTime, Text, Boolean.
   - Untuk relasi, tipe datanya adalah nama Class dari entitas tersebut. 
     (Contoh atribut pengembalian dari relasi `organization()` adalah `Organization`).

3. Multiplicity (Kardinalitas):
   - Cantumkan angka `1` dan `0..*` atau `*` pada ujung garis relasi.
   - Contoh: Antara `Organization` dan `Event`. Di ujung Organization tulis `1`, di ujung 
     Event tulis `*` (karena satu organisasi bisa memiliki banyak event).
=============================================================================
*/
?>
