@extends('layouts.pengurus')

@section('title', 'Manajemen Pendaftaran')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1>Manajemen Pendaftaran</h1>
    <p>Review dan kelola pendaftaran anggota baru</p>
</div>

<!-- Statistics -->
<div class="row mb-5">
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%); color: white;">
            <div class="card-stat-icon"><i class="fas fa-users"></i></div>
            <div class="card-stat-number">8</div>
            <div class="card-stat-label">Total Pendaftaran</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #FBBF24 0%, #D97706 100%); color: white;">
            <div class="card-stat-icon"><i class="fas fa-clock"></i></div>
            <div class="card-stat-number">3</div>
            <div class="card-stat-label">Pending Review</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #22C55E 0%, #15803D 100%); color: white;">
            <div class="card-stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="card-stat-number">4</div>
            <div class="card-stat-label">Diterima</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #EF4444 0%, #991B1B 100%); color: white;">
            <div class="card-stat-icon"><i class="fas fa-times-circle"></i></div>
            <div class="card-stat-number">1</div>
            <div class="card-stat-label">Ditolak</div>
        </div>
    </div>
</div>

<!-- Filter and Search -->
<div class="row mb-4">
    <div class="col-md-6">
        <input type="text" class="form-control" placeholder="Cari nama atau NIM...">
    </div>
    <div class="col-md-6">
        <select class="form-select">
            <option>Semua Status</option>
            <option>Pending</option>
            <option>Diterima</option>
            <option>Ditolak</option>
        </select>
    </div>
</div>

<!-- Applications List -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title">Muhammad Rizki</h5>
                        <small class="text-muted">
                            <i class="fas fa-id-card"></i> 20251001 | <i class="fas fa-graduation-cap"></i> Teknik Informatika
                        </small>
                    </div>
                    <span class="badge bg-warning text-dark">Pending</span>
                </div>
                <p class="text-muted mb-2">
                    <strong>Email:</strong> m.rizki@email.com<br>
                    <strong>No. HP:</strong> +62 812-3456-7890<br>
                    <strong>Alasan Bergabung:</strong> Ingin mengembangkan skill dan belajar dari senior organisasi
                </p>
                <small class="text-muted">
                    <i class="fas fa-calendar"></i> Pendaftaran: 26 Feb 2026
                </small>
                <div class="mt-3">
                    <button class="btn btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#detailModal">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </button>
                    <button class="btn btn-sm btn-success"><i class="fas fa-check"></i> Terima</button>
                    <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Tolak</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title">Eka Pratiwi</h5>
                        <small class="text-muted">
                            <i class="fas fa-id-card"></i> 20251002 | <i class="fas fa-graduation-cap"></i> Manajemen Bisnis
                        </small>
                    </div>
                    <span class="badge bg-warning text-dark">Pending</span>
                </div>
                <p class="text-muted mb-2">
                    <strong>Email:</strong> eka.pratiwi@email.com<br>
                    <strong>No. HP:</strong> +62 812-9876-5432<br>
                    <strong>Alasan Bergabung:</strong> Tertarik dengan kegiatan organisasi dan ingin berkontribusi
                </p>
                <small class="text-muted">
                    <i class="fas fa-calendar"></i> Pendaftaran: 25 Feb 2026
                </small>
                <div class="mt-3">
                    <button class="btn btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#detailModal">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </button>
                    <button class="btn btn-sm btn-success"><i class="fas fa-check"></i> Terima</button>
                    <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Tolak</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title">Rido Firmandan</h5>
                        <small class="text-muted">
                            <i class="fas fa-id-card"></i> 20251003 | <i class="fas fa-graduation-cap"></i> Teknik Sipil
                        </small>
                    </div>
                    <span class="badge bg-warning text-dark">Pending</span>
                </div>
                <p class="text-muted mb-2">
                    <strong>Email:</strong> rido.firmandan@email.com<br>
                    <strong>No. HP:</strong> +62 815-1111-2222<br>
                    <strong>Alasan Bergabung:</strong> Ingin mendapatkan pengalaman organisasi dan networking
                </p>
                <small class="text-muted">
                    <i class="fas fa-calendar"></i> Pendaftaran: 24 Feb 2026
                </small>
                <div class="mt-3">
                    <button class="btn btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#detailModal">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </button>
                    <button class="btn btn-sm btn-success"><i class="fas fa-check"></i> Terima</button>
                    <button class="btn btn-sm btn-danger"><i class="fas fa-times"></i> Tolak</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title">Siti Nurhaliza</h5>
                        <small class="text-muted">
                            <i class="fas fa-id-card"></i> 20251004 | <i class="fas fa-graduation-cap"></i> Sastra Inggris
                        </small>
                    </div>
                    <span class="badge bg-success">Diterima</span>
                </div>
                <p class="text-muted mb-2">
                    <strong>Email:</strong> siti.nur@email.com<br>
                    <strong>No. HP:</strong> +62 816-3333-4444<br>
                    <strong>Alasan Bergabung:</strong> Ingin meningkatkan soft skills dan komunikasi
                </p>
                <small class="text-muted">
                    <i class="fas fa-calendar"></i> Pendaftaran: 20 Feb 2026 | <i class="fas fa-check"></i> Disetujui: 23 Feb 2026
                </small>
                <div class="mt-3">
                    <button class="btn btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#detailModal">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </button>
                    <button class="btn btn-sm btn-danger"><i class="fas fa-undo"></i> Batalkan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pendaftaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">NAMA LENGKAP</small>
                        <h6>Muhammad Rizki</h6>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">NIM</small>
                        <h6>20251001</h6>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">PROGRAM STUDI</small>
                        <h6>Teknik Informatika</h6>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">TAHUN ANGKATAN</small>
                        <h6>2025</h6>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">EMAIL</small>
                        <h6>m.rizki@email.com</h6>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">TELEPON</small>
                        <h6>+62 812-3456-7890</h6>
                    </div>
                </div>

                <div class="mb-4">
                    <small class="text-muted d-block mb-1">ALASAN BERGABUNG</small>
                    <p>Ingin mengembangkan skill dan belajar dari senior organisasi. Saya tertarik untuk berkontribusi pada organisasi yang fokus pada pengembangan sumber daya manusia.</p>
                </div>

                <div class="mb-4">
                    <small class="text-muted d-block mb-1">TANGGAL PENDAFTARAN</small>
                    <h6>26 Februari 2026</h6>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-danger me-2"><i class="fas fa-times"></i> Tolak</button>
                <button type="button" class="btn btn-success"><i class="fas fa-check"></i> Terima</button>
            </div>
        </div>
    </div>
</div>

@endsection
