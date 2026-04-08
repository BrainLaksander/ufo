@extends('layouts.mahasiswa')

@section('title', 'Lost & Found')

@section('content')
<div class="container py-4 lostfound-page">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Lost & Found</h1>
            <p class="text-muted mb-0">Laporkan barang hilang atau temuan Anda di sini.</p>
        </div>
        <button class="btn btn-primary" type="button">Laporkan</button>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 mb-3">
                <button data-tab="hilang" type="button" class="btn btn-danger btn-sm">Barang Hilang</button>
                <button data-tab="ditemukan" type="button" class="btn btn-outline-primary btn-sm">Barang Ditemukan</button>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Cari</span>
                <input id="lf-search" type="text" class="form-control" placeholder="Cari barang...">
            </div>

            <div id="lf-categories" class="d-flex flex-wrap gap-2">
                <button class="lf-category btn btn-primary btn-sm active" data-category="all">Semua</button>
                <button class="lf-category btn btn-outline-secondary btn-sm" data-category="Elektronik">Elektronik</button>
                <button class="lf-category btn btn-outline-secondary btn-sm" data-category="Dompet">Dompet</button>
                <button class="lf-category btn btn-outline-secondary btn-sm" data-category="Tas">Tas</button>
                <button class="lf-category btn btn-outline-secondary btn-sm" data-category="Alat Tulis">Alat Tulis</button>
                <button class="lf-category btn btn-outline-secondary btn-sm" data-category="Botol Minum">Botol Minum</button>
                <button class="lf-category btn btn-outline-secondary btn-sm" data-category="Kunci">Kunci</button>
                <button class="lf-category btn btn-outline-secondary btn-sm" data-category="Lainnya">Lainnya</button>
            </div>
        </div>
    </div>

    <p id="lf-count" class="text-muted small mb-3">3 barang ditemukan</p>

    <div class="row g-4">
        <div class="col-md-6 col-lg-4 lf-card" data-type="hilang" data-category="Kunci" data-title="Kunci Motor Hitam" data-location="Parkiran Utama" data-reporter="D. Rahman" data-date="2026-01-20">
            <div class="card h-100 shadow-sm border-0">
                <img src="/images/dummy/keys.jpg" class="card-img-top" alt="Kunci" onerror="this.style.display='none'">
                <div class="card-body">
                    <h5 class="card-title">Kunci Motor - Hitam</h5>
                    <p class="card-text small mb-1">Lokasi: Parkiran Utama</p>
                    <p class="card-text small mb-1">Tanggal: 2026-01-20</p>
                    <p class="card-text small text-muted">Pelapor: D. Rahman</p>
                    <span class="badge text-bg-danger mb-3">Belum ditemukan</span>
                    <div class="d-grid">
                        <button class="btn btn-primary btn-sm">Hubungi Pelapor</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 lf-card" data-type="ditemukan" data-category="Elektronik" data-title="Handphone Hitam" data-location="Cafetaria" data-reporter="L. Sari" data-date="2026-01-29">
            <div class="card h-100 shadow-sm border-0">
                <img src="/images/dummy/phone2.jpg" class="card-img-top" alt="Handphone" onerror="this.style.display='none'">
                <div class="card-body">
                    <h5 class="card-title">Handphone - Hitam</h5>
                    <p class="card-text small mb-1">Lokasi: Cafetaria</p>
                    <p class="card-text small mb-1">Tanggal: 2026-01-29</p>
                    <p class="card-text small text-muted">Pelapor: L. Sari</p>
                    <span class="badge text-bg-warning mb-3">Ditemukan</span>
                    <div class="d-grid">
                        <button class="btn btn-primary btn-sm">Hubungi Pelapor</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 lf-card" data-type="hilang" data-category="Elektronik" data-title="Earbuds Putih" data-location="Aula" data-reporter="M. Nabil" data-date="2026-01-25">
            <div class="card h-100 shadow-sm border-0">
                <img src="/images/dummy/earbuds.jpg" class="card-img-top" alt="Earbuds" onerror="this.style.display='none'">
                <div class="card-body">
                    <h5 class="card-title">Earbuds - Putih</h5>
                    <p class="card-text small mb-1">Lokasi: Aula</p>
                    <p class="card-text small mb-1">Tanggal: 2026-01-25</p>
                    <p class="card-text small text-muted">Pelapor: M. Nabil</p>
                    <span class="badge text-bg-danger mb-3">Belum ditemukan</span>
                    <div class="d-grid">
                        <button class="btn btn-primary btn-sm">Hubungi Pelapor</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="lf-empty" class="alert alert-light border text-center mt-4 d-none">
        Tidak ada laporan yang sesuai.
    </div>
</div>
@endsection

@push('scripts')
<script src="/js/mahasiswa/lost-found.js"></script>
@endpush
