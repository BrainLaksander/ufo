@extends('layouts.pengurus')

@section('title', 'Manajemen Event')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1>Manajemen Event</h1>
    <p>Kelola semua event dan kegiatan organisasi UFO</p>
</div>

<!-- Page Actions -->
<div class="row mb-4">
    <div class="col-12">
        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#eventModal">
            <i class="bi bi-plus-circle-fill"></i> Buat Event Baru
        </button>
    </div>
</div>

<!-- Filter and Search -->
<div class="row mb-4">
    <div class="col-md-6">
        <input type="text" class="form-control" placeholder="Cari event...">
    </div>
    <div class="col-md-6">
        <select class="form-select">
            <option>Semua Status</option>
            <option>Draft</option>
            <option>Terjadwal</option>
            <option>Berlangsung</option>
            <option>Selesai</option>
        </select>
    </div>
</div>

<!-- Events List -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title">Workshop Python Basics</h5>
                        <small class="text-muted">
                            <i class="bi bi-calendar-date-fill"></i> 15 Mar 2026 - <i class="bi bi-clock-fill"></i> 14:00
                        </small>
                    </div>
                    <span class="badge bg-success">Terjadwal</span>
                </div>
                <p class="text-muted mb-3">Pembelajaran dasar Python untuk pemula dengan fokus pada syntax dan konsep fundamental.</p>
                <div class="mb-3">
                    <small class="d-block mb-2"><strong>Peserta: 45/100</strong></small>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar" style="width: 45%"></div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal">
                        <i class="bi bi-eye-fill"></i> Lihat
                    </button>
                    <button class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</button>
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i> Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title">Seminar Digital Marketing</h5>
                        <small class="text-muted">
                            <i class="bi bi-calendar-date-fill"></i> 20 Mar 2026 - <i class="bi bi-clock-fill"></i> 10:00
                        </small>
                    </div>
                    <span class="badge bg-success">Confirmed</span>
                </div>
                <p class="text-muted mb-3">Strategi digital marketing modern dan case study dari industri berbeda.</p>
                <div class="mb-3">
                    <small class="d-block mb-2"><strong>Peserta: 78/200</strong></small>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar" style="width: 39%"></div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal">
                        <i class="bi bi-eye-fill"></i> Lihat
                    </button>
                    <button class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</button>
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i> Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title">Gathering Bulanan UFO</h5>
                        <small class="text-muted">
                            <i class="bi bi-calendar-date-fill"></i> 25 Mar 2026 - <i class="bi bi-clock-fill"></i> 15:30
                        </small>
                    </div>
                    <span class="badge bg-warning text-dark">Planning</span>
                </div>
                <p class="text-muted mb-3">Pertemuan bulanan semua anggota organisasi UFO untuk diskusi dan sinergi.</p>
                <div class="mb-3">
                    <small class="d-block mb-2"><strong>Peserta: 12/200</strong></small>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-warning" style="width: 6%"></div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal">
                        <i class="bi bi-eye-fill"></i> Lihat
                    </button>
                    <button class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</button>
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i> Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title">Konferensi Tahunan UFO 2026</h5>
                        <small class="text-muted">
                            <i class="bi bi-calendar-date-fill"></i> 30 Mar 2026 - <i class="bi bi-clock-fill"></i> 09:00
                        </small>
                    </div>
                    <span class="badge bg-primary">Draft</span>
                </div>
                <p class="text-muted mb-3">Konferensi tahunan besar dengan pembicara nasional dan sesi diskusi penting.</p>
                <div class="mb-3">
                    <small class="d-block mb-2"><strong>Peserta: 0/500</strong></small>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar" style="width: 0%"></div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal">
                        <i class="bi bi-eye-fill"></i> Lihat
                    </button>
                    <button class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</button>
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i> Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Modal -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Event Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Nama Event</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama event">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Waktu</label>
                            <input type="time" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" class="form-control" placeholder="Masukkan lokasi event">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kuota Peserta</label>
                        <input type="number" class="form-control" placeholder="Berapa orang?">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" rows="4" placeholder="Deskripsi event..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">PIC / Koordinator</label>
                        <input type="text" class="form-control" placeholder="Nama koordinator">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary-custom">Simpan Event</button>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 class="mb-3">Workshop Python Basics</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Tanggal & Waktu</small>
                        <p><strong>15 Maret 2026, 14:00 - 16:00</strong></p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Lokasi</small>
                        <p><strong>Gedung B, Ruang 201</strong></p>
                    </div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Kuota Peserta</small>
                    <p><strong>45 dari 100 peserta</strong></p>
                </div>
                <div class="mb-3">
                    <small class="text-muted">Deskripsi</small>
                    <p>Pembelajaran dasar Python untuk pemula dengan fokus pada syntax dan konsep fundamental. Materi akan mencakup variabel, tipe data, control flow, dan fungsi.</p>
                </div>
                <div class="mb-3">
                    <small class="text-muted">PIC</small>
                    <p><strong>Siti Nurhaliza</strong></p>
                </div>
                <div>
                    <h6>Daftar Peserta</h6>
                    <div class="list-group list-group-sm">
                        <div class="list-group-item">
                            <strong>Ahmad Rifki</strong> - 20211001
                        </div>
                        <div class="list-group-item">
                            <strong>Muhammad Hendra</strong> - 20211003
                        </div>
                        <div class="list-group-item">
                            <strong>Budi Santoso</strong> - 20211004
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
