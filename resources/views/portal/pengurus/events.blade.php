@extends('layouts.app')

@section('title', 'Manajemen Event - UFO')

@push('styles')
<style>
    .ufo-pge-page {
        padding: 1.25rem 0 2rem;
    }

    .ufo-pge-wrap {
        max-width: 1000px;
        margin: 0 auto;
    }

    .ufo-pge-hero {
        border-radius: 14px;
        background: var(--primary);
        color: #fff;
        box-shadow: var(--shadow-sm);
        padding: 1.15rem 1.2rem;
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 0.8rem;
        flex-wrap: wrap;
    }

    .ufo-pge-hero h1 {
        margin: 0 0 0.25rem;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
    }

    .ufo-pge-hero p {
        margin: 0;
        opacity: 0.92;
        font-size: 0.9rem;
    }

    .ufo-pge-btn-primary {
        border: 1px solid #f7dd76;
        background: var(--accent);
        color: #1f2937;
        border-radius: 9px;
        padding: 0.46rem 0.76rem;
        font-size: 0.82rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .ufo-pge-toolbar {
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: #fff;
        box-shadow: var(--shadow-sm);
        padding: 0.85rem;
        margin-bottom: 1rem;
    }

    .ufo-pge-input,
    .ufo-pge-select {
        border-radius: 10px;
        border-color: #d9deea;
    }

    .ufo-pge-input:focus,
    .ufo-pge-select:focus {
        border-color: #7f4da7;
        box-shadow: 0 0 0 0.18rem rgba(127, 77, 167, 0.16);
    }

    .ufo-pge-card {
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: #fff;
        box-shadow: var(--shadow-sm);
        padding: 0.9rem;
        height: 100%;
    }

    .ufo-pge-card-top {
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: 0.5rem;
        margin-bottom: 0.45rem;
    }

    .ufo-pge-title {
        margin: 0 0 0.2rem;
        font-size: 1rem;
        font-weight: 700;
        color: #1f2937;
    }

    .ufo-pge-meta {
        margin: 0;
        color: #6b7280;
        font-size: 0.79rem;
    }

    .ufo-pge-status {
        border-radius: 999px;
        padding: 0.22rem 0.55rem;
        font-size: 0.72rem;
        font-weight: 700;
        line-height: 1;
        white-space: nowrap;
    }

    .ufo-pge-status.ok {
        background: rgba(22, 163, 74, 0.14);
        color: #15803d;
    }

    .ufo-pge-status.warn {
        background: rgba(217, 119, 6, 0.14);
        color: #b45309;
    }

    .ufo-pge-status.draft {
        background: rgba(59, 130, 246, 0.14);
        color: #1d4ed8;
    }

    .ufo-pge-desc {
        margin: 0 0 0.6rem;
        font-size: 0.84rem;
        line-height: 1.5;
        color: #4b5563;
    }

    .ufo-pge-progress-meta {
        display: block;
        margin-bottom: 0.32rem;
        color: #5f697a;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .ufo-pge-progress {
        height: 10px;
        background: #eef2f8;
        border-radius: 999px;
        overflow: hidden;
    }

    .ufo-pge-progress-bar {
        height: 100%;
        background: var(--primary);
    }

    .ufo-pge-progress-bar.warn {
        background: #d97706;
    }

    .ufo-pge-actions {
        margin-top: 0.65rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }

    .ufo-pge-btn {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.34rem 0.56rem;
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        color: #1f2937;
        background: #fff;
    }

    .ufo-pge-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: rgba(95, 58, 116, 0.08);
    }

    @media (max-width: 768px) {
        .ufo-pge-page {
            padding-top: 1rem;
        }

        .ufo-pge-hero h1 {
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<section class="ufo-pge-page">
<div class="ufo-pge-wrap">
    <section class="ufo-pge-hero">
        <div>
            <h1>Manajemen Event</h1>
            <p>Kelola semua event dan kegiatan organisasi UFO.</p>
        </div>

        <button class="ufo-pge-btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal">
            <i class="bi bi-plus-circle-fill"></i>
            Buat Event Baru
        </button>
    </section>

    <section class="ufo-pge-toolbar">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" class="form-control ufo-pge-input" placeholder="Cari event">
            </div>
            <div class="col-md-6">
                <select class="form-select ufo-pge-select">
                    <option>Semua Status</option>
                    <option>Draft</option>
                    <option>Terjadwal</option>
                    <option>Berlangsung</option>
                    <option>Selesai</option>
                </select>
            </div>
        </div>
    </section>

    <div class="row g-3">
        <div class="col-lg-6">
            <article class="ufo-pge-card">
                <div class="ufo-pge-card-top">
                    <div>
                        <h5 class="ufo-pge-title">Workshop Python Basics</h5>
                        <p class="ufo-pge-meta"><i class="bi bi-calendar-date-fill"></i> 15 Mar 2026 | <i class="bi bi-clock-fill"></i> 14:00</p>
                    </div>
                    <span class="ufo-pge-status ok">Terjadwal</span>
                </div>
                <p class="ufo-pge-desc">Pembelajaran dasar Python untuk pemula dengan fokus pada syntax dan konsep fundamental.</p>
                <small class="ufo-pge-progress-meta">Peserta: 45/100</small>
                <div class="ufo-pge-progress"><div class="ufo-pge-progress-bar" style="width:45%"></div></div>
                <div class="ufo-pge-actions">
                    <button class="ufo-pge-btn" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="bi bi-eye-fill"></i>Lihat</button>
                    <button class="ufo-pge-btn"><i class="bi bi-pencil-square"></i>Edit</button>
                    <button class="ufo-pge-btn"><i class="bi bi-trash-fill"></i>Hapus</button>
                </div>
            </article>
        </div>

        <div class="col-lg-6">
            <article class="ufo-pge-card">
                <div class="ufo-pge-card-top">
                    <div>
                        <h5 class="ufo-pge-title">Seminar Digital Marketing</h5>
                        <p class="ufo-pge-meta"><i class="bi bi-calendar-date-fill"></i> 20 Mar 2026 | <i class="bi bi-clock-fill"></i> 10:00</p>
                    </div>
                    <span class="ufo-pge-status ok">Confirmed</span>
                </div>
                <p class="ufo-pge-desc">Strategi digital marketing modern dan case study dari industri berbeda.</p>
                <small class="ufo-pge-progress-meta">Peserta: 78/200</small>
                <div class="ufo-pge-progress"><div class="ufo-pge-progress-bar" style="width:39%"></div></div>
                <div class="ufo-pge-actions">
                    <button class="ufo-pge-btn" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="bi bi-eye-fill"></i>Lihat</button>
                    <button class="ufo-pge-btn"><i class="bi bi-pencil-square"></i>Edit</button>
                    <button class="ufo-pge-btn"><i class="bi bi-trash-fill"></i>Hapus</button>
                </div>
            </article>
        </div>

        <div class="col-lg-6">
            <article class="ufo-pge-card">
                <div class="ufo-pge-card-top">
                    <div>
                        <h5 class="ufo-pge-title">Gathering Bulanan UFO</h5>
                        <p class="ufo-pge-meta"><i class="bi bi-calendar-date-fill"></i> 25 Mar 2026 | <i class="bi bi-clock-fill"></i> 15:30</p>
                    </div>
                    <span class="ufo-pge-status warn">Planning</span>
                </div>
                <p class="ufo-pge-desc">Pertemuan bulanan semua anggota organisasi UFO untuk diskusi dan sinergi.</p>
                <small class="ufo-pge-progress-meta">Peserta: 12/200</small>
                <div class="ufo-pge-progress"><div class="ufo-pge-progress-bar warn" style="width:6%"></div></div>
                <div class="ufo-pge-actions">
                    <button class="ufo-pge-btn" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="bi bi-eye-fill"></i>Lihat</button>
                    <button class="ufo-pge-btn"><i class="bi bi-pencil-square"></i>Edit</button>
                    <button class="ufo-pge-btn"><i class="bi bi-trash-fill"></i>Hapus</button>
                </div>
            </article>
        </div>

        <div class="col-lg-6">
            <article class="ufo-pge-card">
                <div class="ufo-pge-card-top">
                    <div>
                        <h5 class="ufo-pge-title">Konferensi Tahunan UFO 2026</h5>
                        <p class="ufo-pge-meta"><i class="bi bi-calendar-date-fill"></i> 30 Mar 2026 | <i class="bi bi-clock-fill"></i> 09:00</p>
                    </div>
                    <span class="ufo-pge-status draft">Draft</span>
                </div>
                <p class="ufo-pge-desc">Konferensi tahunan besar dengan pembicara nasional dan sesi diskusi penting.</p>
                <small class="ufo-pge-progress-meta">Peserta: 0/500</small>
                <div class="ufo-pge-progress"><div class="ufo-pge-progress-bar" style="width:0%"></div></div>
                <div class="ufo-pge-actions">
                    <button class="ufo-pge-btn" data-bs-toggle="modal" data-bs-target="#detailModal"><i class="bi bi-eye-fill"></i>Lihat</button>
                    <button class="ufo-pge-btn"><i class="bi bi-pencil-square"></i>Edit</button>
                    <button class="ufo-pge-btn"><i class="bi bi-trash-fill"></i>Hapus</button>
                </div>
            </article>
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
                <button type="button" class="btn btn-primary">Simpan Event</button>
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

</div>
</section>
@endsection
