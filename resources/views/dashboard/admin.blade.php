@extends('layouts.admin')

@section('title', 'Manajemen Pengumuman & Distribusi Email')

@section('content')
<style>
    .stat-card {
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    .btn-action {
        border: none;
        background: none;
        color: #6c757d;
        font-size: 18px;
        padding: 4px 8px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }
    .btn-action:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }
    .btn-danger-action {
        color: #dc3545;
    }
    .btn-danger-action:hover {
        background-color: rgba(220, 53, 69, 0.1);
    }
</style>

<div class="container-fluid py-4">
    <!-- Bagian Statistik -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title text-muted mb-1">Total Pengumuman</h5>
                        <h2 class="text-primary mb-0">45</h2>
                    </div>
                    <div class="text-primary" style="font-size: 2rem;">
                        <i class="bi bi-megaphone"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title text-muted mb-1">Terpublikasi</h5>
                        <h2 class="text-success mb-0">32</h2>
                    </div>
                    <div class="text-success" style="font-size: 2rem;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title text-muted mb-1">Terjadwal</h5>
                        <h2 class="text-primary mb-0">8</h2>
                    </div>
                    <div class="text-primary" style="font-size: 2rem;">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title text-muted mb-1">Draft</h5>
                        <h2 class="text-secondary mb-0">5</h2>
                    </div>
                    <div class="text-secondary" style="font-size: 2rem;">
                        <i class="bi bi-pencil"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Filter & Aksi -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" placeholder="Cari pengumuman..." id="searchInput">
                </div>
                <select class="form-select" style="max-width: 200px;">
                    <option selected>Semua Status</option>
                    <option>Terpublikasi</option>
                    <option>Terjadwal</option>
                    <option>Draft</option>
                </select>
                <button class="btn btn-primary ms-auto">
                    <i class="bi bi-plus-circle me-2"></i>Buat Pengumuman Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Info Box Distribusi Email -->
    <div class="alert alert-info border-0 rounded-3 mb-4" role="alert">
        <div class="d-flex align-items-start">
            <i class="bi bi-info-circle-fill me-3 mt-1" style="font-size: 1.5rem;"></i>
            <div>
                <strong>Sistem Distribusi Email:</strong> Semua pengumuman yang disetujui akan dikirim otomatis ke email resmi UNKLAB dari organisasi atau mahasiswa yang ditargetkan. Pastikan target distribusi sudah benar sebelum mempublikasikan.
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Pengumuman -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 ps-4">Judul Pengumuman</th>
                            <th class="border-0">Kategori</th>
                            <th class="border-0">Target</th>
                            <th class="border-0">Jadwal Publish</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-4">
                                <div>
                                    <a href="#" class="text-primary fw-semibold text-decoration-none">Pengumuman Seminar Nasional</a>
                                    <div class="text-muted small">Dibuat: 15 Nov 2023</div>
                                </div>
                            </td>
                            <td>Event</td>
                            <td><i class="bi bi-people me-1"></i>Semua Mahasiswa</td>
                            <td>
                                <div>20 Nov 2023</div>
                                <div class="text-muted small">14:00</div>
                            </td>
                            <td><span class="badge bg-success">Terpublikasi</span></td>
                            <td class="pe-4">
                                <button class="btn-action me-2" title="Lihat"><i class="bi bi-eye"></i></button>
                                <button class="btn-action me-2" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button class="btn-action btn-danger-action" title="Hapus"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4">
                                <div>
                                    <a href="#" class="text-primary fw-semibold text-decoration-none">Pendaftaran UKM Baru</a>
                                    <div class="text-muted small">Dibuat: 10 Nov 2023</div>
                                </div>
                            </td>
                            <td>Informasi</td>
                            <td><i class="bi bi-building me-1"></i>Organisasi</td>
                            <td>
                                <div>25 Nov 2023</div>
                                <div class="text-muted small">10:00</div>
                            </td>
                            <td><span class="badge bg-primary">Terjadwal</span></td>
                            <td class="pe-4">
                                <button class="btn-action me-2" title="Lihat"><i class="bi bi-eye"></i></button>
                                <button class="btn-action me-2" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button class="btn-action btn-danger-action" title="Hapus"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4">
                                <div>
                                    <a href="#" class="text-primary fw-semibold text-decoration-none">Update Sistem Portal</a>
                                    <div class="text-muted small">Dibuat: 5 Nov 2023</div>
                                </div>
                            </td>
                            <td>Teknis</td>
                            <td><i class="bi bi-person me-1"></i>Admin & Pengurus</td>
                            <td>
                                <div>-</div>
                                <div class="text-muted small">-</div>
                            </td>
                            <td><span class="badge bg-secondary">Draft</span></td>
                            <td class="pe-4">
                                <button class="btn-action me-2" title="Lihat"><i class="bi bi-eye"></i></button>
                                <button class="btn-action me-2" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button class="btn-action btn-danger-action" title="Hapus"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Simple search functionality (vanilla JS)
document.getElementById('searchInput').addEventListener('input', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const title = row.cells[0].textContent.toLowerCase();
        row.style.display = title.includes(filter) ? '' : 'none';
    });
});
</script>
@endsection
