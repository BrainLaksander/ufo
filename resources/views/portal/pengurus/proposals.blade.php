@extends('layouts.pengurus')

@section('title', 'Pengajuan Laporan & Arsip')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1>Pengajuan Laporan & Arsip</h1>
    <p>Kelola dokumen laporan dan arsip organisasi</p>
</div>

<!-- Page Actions -->
<div class="row mb-4">
    <div class="col-12">
        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-cloud-arrow-up-fill"></i> Upload Dokumen Baru
        </button>
    </div>
</div>

<!-- Filter and Search -->
<div class="row mb-4">
    <div class="col-md-6">
        <input type="text" class="form-control" placeholder="Cari dokumen...">
    </div>
    <div class="col-md-6">
        <select class="form-select">
            <option>Semua Tipe</option>
            <option>Laporan</option>
            <option>Proposal</option>
            <option>Arsip</option>
        </select>
    </div>
</div>

<!-- Documents List -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div style="font-size: 2.5rem; color: #3B82F6;">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1">Laporan Kegiatan Februari 2026</h5>
                            <small class="text-muted">
                                <i class="bi bi-calendar-date-fill"></i> 28 Feb 2026 | <i class="bi bi-person-fill"></i> Ahmad Rifki
                            </small>
                        </div>
                    </div>
                    <span class="badge bg-success">Approved</span>
                </div>
                <p class="text-muted mb-3">Laporan lengkap kegiatan organisasi UFO selama bulan Februari 2026 mencakup semua event dan kegiatan rutin.</p>
                <div>
                    <button class="btn btn-sm btn-info me-2"><i class="bi bi-download"></i> Download</button>
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
                    <div class="d-flex align-items-center gap-3">
                        <div style="font-size: 2.5rem; color: #22C55E;">
                            <i class="bi bi-file-earmark-word-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1">Proposal Program Kerja Tahunan 2026</h5>
                            <small class="text-muted">
                                <i class="bi bi-calendar-date-fill"></i> 15 Feb 2026 | <i class="bi bi-person-fill"></i> Siti Nurhaliza
                            </small>
                        </div>
                    </div>
                    <span class="badge bg-success">Approved</span>
                </div>
                <p class="text-muted mb-3">Proposal lengkap program kerja organisasi UFO untuk tahun 2026 dengan target dan timeline yang jelas.</p>
                <div>
                    <button class="btn btn-sm btn-info me-2"><i class="bi bi-download"></i> Download</button>
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
                    <div class="d-flex align-items-center gap-3">
                        <div style="font-size: 2.5rem; color: #FBBF24;">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1">Laporan Keuangan Januari 2026</h5>
                            <small class="text-muted">
                                <i class="bi bi-calendar-date-fill"></i> 05 Feb 2026 | <i class="bi bi-person-fill"></i> Muhammad Hendra
                            </small>
                        </div>
                    </div>
                    <span class="badge bg-info">Draft</span>
                </div>
                <p class="text-muted mb-3">Laporan keuangan detail untuk periode Januari 2026 menunggu review dan persetujuan dari bendahara.</p>
                <div>
                    <button class="btn btn-sm btn-info me-2"><i class="bi bi-download"></i> Download</button>
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
                    <div class="d-flex align-items-center gap-3">
                        <div style="font-size: 2.5rem; color: #A78BFA;">
                            <i class="bi bi-file-earmark-zip-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-1">Arsip Dokumen 2024</h5>
                            <small class="text-muted">
                                <i class="bi bi-calendar-date-fill"></i> 10 Jan 2026 | <i class="bi bi-person-fill"></i> Sistem
                            </small>
                        </div>
                    </div>
                    <span class="badge bg-secondary">Archived</span>
                </div>
                <p class="text-muted mb-3">Kumpulan dokumen arsip hasil kegiatan organisasi tahun 2024 untuk referensi dan dokumentasi.</p>
                <div>
                    <button class="btn btn-sm btn-info me-2"><i class="bi bi-download"></i> Download</button>
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i> Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Dokumen Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Judul Dokumen</label>
                        <input type="text" class="form-control" placeholder="Masukkan judul dokumen">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Dokumen</label>
                        <select class="form-select">
                            <option>Pilih tipe</option>
                            <option>Laporan</option>
                            <option>Proposal</option>
                            <option>Arsip</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" rows="3" placeholder="Deskripsi dokumen..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File Upload</label>
                        <div class="border border-2 border-dashed p-4 text-center rounded" style="border-color: #3B82F6;">
                            <i class="bi bi-cloud-arrow-up-fill" style="font-size: 2.5rem; color: #3B82F6; margin-bottom: 10px;"></i><br>
                            <p>Drag dan drop file di sini, atau <strong>klik untuk memilih</strong></p>
                            <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX (Max 10MB)</small>
                            <input type="file" class="form-control d-none">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="draft" id="draftDoc" checked>
                                <label class="form-check-label" for="draftDoc">Draft</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="approved" id="approvedDoc">
                                <label class="form-check-label" for="approvedDoc">Approve</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary-custom">Upload Dokumen</button>
            </div>
        </div>
    </div>
</div>

@endsection
