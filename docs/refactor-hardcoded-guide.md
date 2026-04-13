# Refactor Plan: Eliminasi Hardcoded di Project UFO

Tanggal: 2026-04-09
Status: Draft eksekusi
Pemilik: Tim pengembang UFO

## 1. Tujuan
Dokumen ini menjadi panduan implementasi untuk mengurangi dan menghapus hardcoded value berisiko tinggi pada aplikasi, terutama untuk:
- Secret dan credential
- Akun demo dan password plaintext
- Data bisnis statis di controller dan view
- Magic string status, role, kategori
- URL eksternal yang tersebar di banyak file

## 2. Prinsip Eksekusi
1. Kerjakan dari risiko keamanan tertinggi ke risiko maintainability.
2. Setiap fase harus punya acceptance criteria.
3. Perubahan harus aman untuk rollback.
4. Semua perubahan utama harus dibarengi test.
5. Semua nilai environment dipindahkan ke config dan env, bukan literal di business logic.

## 3. Ringkasan Prioritas
P0 (Kritis): Secret, token, password, akun demo runtime.
P1 (Tinggi): Dummy data bisnis pada controller/view yang dipakai halaman aktif.
P2 (Menengah): Status/role/kategori string berulang di banyak file.
P3 (Menengah): URL eksternal tersebar tanpa pusat konfigurasi.
P4 (Rendah-Menengah): Pembersihan legacy code dan konsolidasi arsitektur.

## 4. Roadmap Fase Refactor

## Fase 0 - Security Stabilization (Target: 1 hari)
Tujuan: menutup potensi kebocoran dan akses tidak aman secepat mungkin.

Checklist:
- [ ] Rotate semua token yang pernah tersimpan di file lokal atau deploy script. (perlu revoke manual di provider)
- [x] Pindahkan credential deploy SQL ke mekanisme secret runtime.
- [x] Pastikan file environment sensitif tidak pernah ter-commit.
- [x] Tambahkan pre-commit secret scan.
- [x] Tambahkan panduan onboarding secret di README internal.

File fokus:
- deploy/mysql-init-ufounk.sql
- .env (lokal, tidak di-commit)
- .env.example

Acceptance criteria:
- [x] Tidak ada secret plaintext di file yang terlacak git.
- [ ] Token lama sudah tidak valid. (butuh revoke token lama di provider)
- [x] Pipeline atau pre-commit menolak commit yang mengandung pattern secret.

## Fase 1 - Auth Hardcoded Removal (Target: 1-2 hari)
Tujuan: menghapus akun demo/password statis dari runtime flow.

Checklist:
- [ ] Hapus login berbasis array akun statis dari AuthController.
- [ ] Hapus validasi password literal string yang dipakai langsung.
- [ ] Implement hash password untuk akun pengurus UKM/BEM.
- [ ] Pindahkan mode demo ke feature flag env (contoh: AUTH_DEMO_MODE=false).
- [ ] Sembunyikan kredensial demo dari UI login production.
- [ ] Tambahkan alur fallback aman untuk local development saja.

File fokus:
- app/Http/Controllers/AuthController.php
- app/Http/Controllers/KemahasiswaanWorkflowController.php
- resources/views/auth/login.blade.php
- database/seeders/DatabaseSeeder.php
- database/factories/UserFactory.php

Acceptance criteria:
- Tidak ada password plaintext di alur login runtime.
- Login pengurus menggunakan data persisten + verifikasi hash.
- UI production tidak menampilkan akun dan password demo.

## Fase 2 - Pusatkan Constant Domain (Target: 2-3 hari)
Tujuan: menghilangkan magic string berulang untuk status, role, kategori.

Checklist:
- [ ] Buat enum atau constant class untuk status Submission, Report, Event, Proposal, Announcement, LostFound.
- [ ] Buat enum atau constant class untuk role user.
- [ ] Refactor query dan validasi agar memakai constant tunggal.
- [ ] Refactor mapper label status agar berbasis enum/translator.
- [ ] Tambahkan unit test untuk transisi status.

File fokus prioritas:
- app/Http/Controllers/IzinKegiatanWorkflowController.php
- app/Http/Controllers/LostFoundController.php
- app/Http/Controllers/ContactController.php
- app/Http/Controllers/AnnouncementController.php
- app/Http/Controllers/EventOrganisasiController.php
- app/Http/Controllers/ProposalController.php
- app/Models/Submission.php
- app/Models/Proposal.php
- app/Models/Event.php
- app/Models/Announcement.php
- app/Models/LostFoundItem.php
- app/Models/ContactMessage.php
- app/Models/Organization.php

Acceptance criteria:
- Semua status/role/kategori utama berasal dari enum atau constant class.
- Tidak ada typo status antar modul.
- Validasi request dan query DB sinkron memakai sumber constant yang sama.

## Fase 3 - Migrasi Dummy Data ke Data Layer (Target: 3-5 hari)
Tujuan: memindahkan data statis dari controller/view ke database dan seeder terstruktur.

Checklist:
- [ ] Refactor OrganisasiController agar ambil data dari model/repository.
- [ ] Refactor PengumumanController agar ambil data dari tabel pengumuman.
- [ ] Refactor PengurusController dan DashboardOrganisasiController agar tidak menyimpan data bisnis statis di method.
- [ ] Pindahkan data awal ke seeder terpisah (dev seeder dan sample seeder).
- [ ] Perbaiki halaman view yang masih memuat data statis langsung.

File fokus:
- app/Http/Controllers/OrganisasiController.php
- app/Http/Controllers/PengumumanController.php
- app/Http/Controllers/PengurusController.php
- app/Http/Controllers/DashboardOrganisasiController.php
- resources/data/organizationData.php
- resources/views/portal/pengurus/dashboard.blade.php
- resources/views/portal/pengurus/members.blade.php
- resources/views/lost-found/index.blade.php

Acceptance criteria:
- Controller tidak lagi berisi array data bisnis besar.
- Data halaman utama berasal dari DB/service layer.
- Seeder sample bisa dinyalakan hanya untuk local/dev.

## Fase 4 - External URL and Integration Config Cleanup (Target: 1 hari)
Tujuan: memusatkan endpoint dan URL pihak ketiga.

Checklist:
- [ ] Buat file config khusus integrasi eksternal.
- [ ] Pindahkan URL ui-avatars, wa.me, domain sosial default ke config/env.
- [ ] Ganti pemakaian literal URL di model/view menjadi helper config.

File fokus:
- app/Models/User.php
- resources/views/layouts/pengurus.blade.php
- resources/views/layouts/mahasiswa.blade.php
- resources/views/mahasiswa/organisasi-detail.blade.php
- resources/data/organizationData.php
- config/services.php
- config/app.php

Acceptance criteria:
- Tidak ada URL eksternal strategis yang tersebar sebagai literal di logic inti.
- Perubahan endpoint bisa dilakukan tanpa edit banyak file.

## Fase 5 - Legacy Cleanup and Quality Gate (Target: 2 hari)
Tujuan: merapikan sisa technical debt dan mengunci kualitas.

Checklist:
- [ ] Audit dan rapikan file model/controller yang berpotensi duplikasi definisi class.
- [ ] Tambahkan test coverage minimum untuk auth, workflow approval, status transition.
- [ ] Tambahkan static analysis dan style check ke pipeline.
- [ ] Buat checklist release hardening sebelum deploy.

Acceptance criteria:
- Test skenario kritis lolos.
- Tidak ada warning fatal terkait duplikasi class atau method.
- CI menolak regresi untuk area auth dan workflow.

## 5. Urutan Eksekusi Mingguan (Saran)
Minggu 1:
- Fase 0 selesai
- Fase 1 selesai

Minggu 2:
- Fase 2 selesai untuk domain prioritas (auth, submission, proposal, event)

Minggu 3:
- Fase 3 selesai untuk halaman aktif yang dipakai user

Minggu 4:
- Fase 4 dan Fase 5 selesai
- Stabilization dan dokumentasi final

## 6. Strategy Branching dan Commit
Gunakan branch per fase agar rollback mudah.

Contoh:
- refactor/security-phase-0
- refactor/auth-phase-1
- refactor/constants-phase-2
- refactor/data-layer-phase-3

Aturan commit:
- 1 commit = 1 scope perubahan jelas.
- Tambahkan catatan dampak dan rollback note pada setiap PR.

## 7. Checklist Definition of Done
- [ ] Hardcoded kritis terkait secret/password sudah hilang dari code yang terlacak git.
- [ ] Hardcoded akun demo tidak aktif di production.
- [ ] Status dan role sudah terstandarisasi.
- [ ] Data bisnis statis besar dipindahkan ke DB/seeder/service.
- [ ] URL eksternal sudah tersentralisasi di config.
- [ ] Test inti lulus untuk auth dan workflow.
- [ ] Dokumentasi operasional diperbarui.

## 8. Risiko dan Mitigasi
Risiko:
- Perubahan auth bisa mengganggu login existing.
Mitigasi:
- Gunakan feature flag dan rollout bertahap.

Risiko:
- Refactor status bisa memecahkan query lama.
Mitigasi:
- Refactor bertahap per modul + test transisi status.

Risiko:
- Migrasi dummy data ke DB memengaruhi tampilan halaman.
Mitigasi:
- Siapkan seed data dev yang kompatibel dengan UI saat ini.

## 9. Output Minimal per Fase
Fase 0:
- PR security sanitization.

Fase 1:
- PR auth cleanup + migration password jika diperlukan.

Fase 2:
- PR enum/constants + refactor query/validation.

Fase 3:
- PR data layer migration + sample seeder.

Fase 4:
- PR external config centralization.

Fase 5:
- PR quality gate + test stabilization.

---
Dokumen ini bisa dipakai sebagai checklist kerja harian. Jika dibutuhkan, tahap berikutnya adalah membuat task breakdown lebih detail per file dan estimasi jam per task.
