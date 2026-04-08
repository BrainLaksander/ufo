@extends('layouts.pengurus')

@section('title', 'Pengumuman')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1>Pengumuman</h1>
    <p>Kelola dan publikasikan pengumuman untuk semua anggota</p>
</div>

<!-- Page Actions -->
<div class="row mb-4">
    <div class="col-12">
        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#announcementModal">
            <i class="bi bi-plus-circle-fill"></i> Buat Pengumuman Baru
        </button>
    </div>
</div>

<!-- Filter and Search -->
<div class="row mb-4">
    <div class="col-md-6">
        <input type="text" class="form-control" placeholder="Cari pengumuman...">
    </div>
    <div class="col-md-6">
        <select class="form-select">
            <option>Semua Status</option>
            <option>Dipublikasikan</option>
            <option>Draft</option>
        </select>
    </div>
</div>

<!-- Announcements List -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title">Jadwal Rapat Rutin Bulan Maret</h5>
                        <small class="text-muted">
                            <i class="bi bi-calendar-date-fill"></i> Dipublikasikan 28 Feb 2026
                        </small>
                    </div>
                    <span class="badge bg-success">Dipublikasikan</span>
                </div>
                <p class="text-muted mb-3">Informasi jadwal rapat rutin semua anggota pengurus UFO untuk bulan Maret. Rapat akan membahas program kerja tahunan dan evaluasi kegiatan bulan Februari.</p>
                <small class="text-muted"><i class="bi bi-tag-fill"></i> Kategori: Jadwal</small>
                <div class="mt-3">
                    <button class="btn btn-sm btn-info me-2"><i class="bi bi-eye-fill"></i> Lihat</button>
                    <button class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</button>
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i> Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title">Pembukaan Pendaftaran Anggota Baru 2026</h5>
                        <small class="text-muted">
                            <i class="bi bi-calendar-date-fill"></i> Dipublikasikan 25 Feb 2026
                        </small>
                    </div>
                    <span class="badge bg-success">Dipublikasikan</span>
                </div>
                <p class="text-muted mb-3">Pendaftaran anggota baru UFO telah resmi dibuka! Jika Anda tertarik bergabung dengan organisasi kami, silakan daftar melalui portal ini sebelum 10 Maret 2026.</p>
                <small class="text-muted"><i class="bi bi-tag-fill"></i> Kategori: Pendaftaran</small>
                <div class="mt-3">
                    <button class="btn btn-sm btn-info me-2"><i class="bi bi-eye-fill"></i> Lihat</button>
                    <button class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</button>
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i> Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title">Workshop Python for Beginners - Minggu Depan</h5>
                        <small class="text-muted">
                            <i class="bi bi-calendar-date-fill"></i> Dipublikasikan 23 Feb 2026
                        </small>
                    </div>
                    <span class="badge bg-success">Dipublikasikan</span>
                </div>
                <p class="text-muted mb-3">Reminder: Workshop Python for Beginners akan dilaksanakan minggu depan. Jangan lupa untuk mendaftar dan hadir tepat waktu. Tempat terbatas hanya 100 peserta.</p>
                <small class="text-muted"><i class="bi bi-tag-fill"></i> Kategori: Event</small>
                <div class="mt-3">
                    <button class="btn btn-sm btn-info me-2"><i class="bi bi-eye-fill"></i> Lihat</button>
                    <button class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</button>
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i> Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title">Draft: Pengumuman Rapat Darurat</h5>
                        <small class="text-muted">
                            <i class="bi bi-calendar-date-fill"></i> Dibuat 20 Feb 2026
                        </small>
                    </div>
                    <span class="badge bg-warning text-dark">Draft</span>
                </div>
                <p class="text-muted mb-3">Pengumuman tentang rapat darurat untuk membahas situasi khusus. Masih dalam tahap draft dan belum dipublikasikan.</p>
                <small class="text-muted"><i class="bi bi-tag-fill"></i> Kategori: Pengumuman</small>
                <div class="mt-3">
                    <button class="btn btn-sm btn-info me-2"><i class="bi bi-eye-fill"></i> Lihat</button>
                    <button class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</button>
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i> Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Announcement Modal -->
<div class="modal fade" id="announcementModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Pengumuman Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Judul Pengumuman</label>
                        <input type="text" class="form-control" placeholder="Judul pengumuman">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select">
                            <option>Pilih kategori</option>
                            <option>Pengumuman</option>
                            <option>Jadwal</option>
                            <option>Pendaftaran</option>
                            <option>Event</option>
                            <option>Update</option>
                            <option>Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Pengumuman</label>
                        <textarea class="form-control" rows="6" placeholder="Tulis isi pengumuman..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Publikasi</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="draft" id="draft" checked>
                                <label class="form-check-label" for="draft">Draft (Belum dipublikasikan)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="published" id="published">
                                <label class="form-check-label" for="published">Publikasikan sekarang</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary-custom">Simpan Pengumuman</button>
            </div>
        </div>
    </div>
</div>

@endsection
