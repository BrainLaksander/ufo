@extends('layouts.pengurus')

@section('title', 'Lost & Found')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1>Lost & Found</h1>
    <p>Kelola laporan barang hilang dan barang ditemukan</p>
</div>

<!-- Statistics -->
<div class="row mb-5">
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%); color: white;">
            <div class="card-stat-icon"><i class="fas fa-box"></i></div>
            <div class="card-stat-number">23</div>
            <div class="card-stat-label">Total Laporan</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #EF4444 0%, #991B1B 100%); color: white;">
            <div class="card-stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="card-stat-number">8</div>
            <div class="card-stat-label">Barang Hilang</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #22C55E 0%, #15803D 100%); color: white;">
            <div class="card-stat-icon"><i class="fas fa-box-open"></i></div>
            <div class="card-stat-number">12</div>
            <div class="card-stat-label">Barang Ditemukan</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #A78BFA 0%, #7C3AED 100%); color: white;">
            <div class="card-stat-icon"><i class="fas fa-check-double"></i></div>
            <div class="card-stat-number">3</div>
            <div class="card-stat-label">Terselesaikan</div>
        </div>
    </div>
</div>

<!-- Filter and Search -->
<div class="row mb-4">
    <div class="col-md-4">
        <input type="text" class="form-control" placeholder="Cari barang...">
    </div>
    <div class="col-md-4">
        <select class="form-select">
            <option>Semua Tipe</option>
            <option>Barang Hilang</option>
            <option>Barang Ditemukan</option>
        </select>
    </div>
    <div class="col-md-4">
        <select class="form-select">
            <option>Semua Status</option>
            <option>Open</option>
            <option>Resolved</option>
            <option>Archived</option>
        </select>
    </div>
</div>

<!-- Lost & Found Items -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title"><i class="fas fa-exclamation-triangle"></i> Dompet Kulit Hitam (HILANG)</h5>
                        <small class="text-muted">
                            <i class="fas fa-map-marker"></i> Gedung A, Lantai 2 | <i class="fas fa-calendar"></i> 24 Feb 2026
                        </small>
                    </div>
                    <span class="badge bg-danger">Open</span>
                </div>
                <p class="text-muted mb-2">
                    <strong>Pelapor:</strong> Ahmad Rifki<br>
                    <strong>Email:</strong> rifki@email.com<br>
                    <strong>Telepon:</strong> +62 812-3456-7890<br>
                    <strong>Deskripsi:</strong> Dompet kulit hitam berisi KTM dan uang tunai
                </p>
                <div class="alert alert-light mb-3">
                    <strong class="text-muted">Catatan Internal:</strong><br>
                    <small>Status masih open, belum ada yang melaporkan menemukan item ini</small>
                </div>
                <div>
                    <button class="btn btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#editNoteModal">
                        <i class="fas fa-edit"></i> Edit Catatan
                    </button>
                    <button class="btn btn-sm btn-success"><i class="fas fa-check"></i> Tandai Selesai</button>
                    <button class="btn btn-sm btn-danger"><i class="fas fa-archive"></i> Archive</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title"><i class="fas fa-box-open"></i> Kunci Loker Kuning (DITEMUKAN)</h5>
                        <small class="text-muted">
                            <i class="fas fa-map-marker"></i> Perpustakaan | <i class="fas fa-calendar"></i> 20 Feb 2026
                        </small>
                    </div>
                    <span class="badge bg-success">Resolved</span>
                </div>
                <p class="text-muted mb-2">
                    <strong>Pelapor:</strong> Siti Nurhaliza<br>
                    <strong>Email:</strong> siti.nur@email.com<br>
                    <strong>Telepon:</strong> +62 812-9876-5432<br>
                    <strong>Deskripsi:</strong> Kunci loker kuning dengan nomor 45
                </p>
                <div class="alert alert-light mb-3">
                    <strong class="text-muted">Catatan Internal:</strong><br>
                    <small>Sudah diambil oleh pemilik pada 22 Feb 2026</small>
                </div>
                <div>
                    <button class="btn btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#editNoteModal">
                        <i class="fas fa-edit"></i> Edit Catatan
                    </button>
                    <button class="btn btn-sm btn-danger"><i class="fas fa-archive"></i> Archive</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title"><i class="fas fa-exclamation-triangle"></i> Airpods Pro (HILANG)</h5>
                        <small class="text-muted">
                            <i class="fas fa-map-marker"></i> Ruang Rapat UFO | <i class="fas fa-calendar"></i> 18 Feb 2026
                        </small>
                    </div>
                    <span class="badge bg-warning text-dark">Open</span>
                </div>
                <p class="text-muted mb-2">
                    <strong>Pelapor:</strong> Muhammad Hendra<br>
                    <strong>Email:</strong> hendra.m@email.com<br>
                    <strong>Telepon:</strong> +62 815-7777-8888<br>
                    <strong>Deskripsi:</strong> Airpods Pro warna putih dalam charging case
                </p>
                <div class="alert alert-light mb-3">
                    <strong class="text-muted">Catatan Internal:</strong><br>
                    <small>Follow up dengan pengguna lain yang mungkin melihat item ini</small>
                </div>
                <div>
                    <button class="btn btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#editNoteModal">
                        <i class="fas fa-edit"></i> Edit Catatan
                    </button>
                    <button class="btn btn-sm btn-success"><i class="fas fa-check"></i> Tandai Selesai</button>
                    <button class="btn btn-sm btn-danger"><i class="fas fa-archive"></i> Archive</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title"><i class="fas fa-box-open"></i> Laptop Lenovo (DITEMUKAN)</h5>
                        <small class="text-muted">
                            <i class="fas fa-map-marker"></i> Kafeteria Kampus | <i class="fas fa-calendar"></i> 15 Feb 2026
                        </small>
                    </div>
                    <span class="badge bg-success">Resolved</span>
                </div>
                <p class="text-muted mb-2">
                    <strong>Pelapor:</strong> Budi Santoso<br>
                    <strong>Email:</strong> budi.s@email.com<br>
                    <strong>Telepon:</strong> +62 816-5555-6666<br>
                    <strong>Deskripsi:</strong> Laptop Lenovo hitam dengan stiker UFO
                </p>
                <div class="alert alert-light mb-3">
                    <strong class="text-muted">Catatan Internal:</strong><br>
                    <small>Diklaim oleh pemilik, berhasil dikembalikan dengan identitas terverifikasi</small>
                </div>
                <div>
                    <button class="btn btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#editNoteModal">
                        <i class="fas fa-edit"></i> Edit Catatan
                    </button>
                    <button class="btn btn-sm btn-danger"><i class="fas fa-archive"></i> Archive</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Note Modal -->
<div class="modal fade" id="editNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Catatan Internal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Catatan Moderator</label>
                    <textarea class="form-control" rows="5">Status masih open, belum ada yang melaporkan menemukan item ini</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Update Status</label>
                    <select class="form-select">
                        <option>Open - Sedang Dicari</option>
                        <option>Investigating - Sedang Diinvestigasi</option>
                        <option>Resolved - Selesai Dimana</option>
                        <option>Archived - Diarsipkan</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary-custom">Simpan Catatan</button>
            </div>
        </div>
    </div>
</div>

@endsection
