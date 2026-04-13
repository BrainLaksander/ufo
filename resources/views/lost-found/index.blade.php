@extends('layouts.app')

@section('title', 'Lost & Found - UFO')

@push('styles')
<style>
    .ufo-lf-page {
        padding: 1.25rem 0 2rem;
    }

    .ufo-lf-hero {
        border-radius: 14px;
        background: var(--primary);
        color: #fff;
        padding: 1.1rem 1.2rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 0.95rem;
        display: flex;
        justify-content: space-between;
        align-items: end;
        gap: 0.8rem;
        flex-wrap: wrap;
    }

    .ufo-lf-hero h1 {
        margin: 0 0 0.35rem;
        font-size: 1.55rem;
        font-weight: 700;
    }

    .ufo-lf-hero p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.92rem;
    }

    .ufo-lf-report-btn {
        border: 1px solid #f7dd76;
        background: var(--accent);
        color: #1f2937;
        border-radius: 9px;
        padding: 0.5rem 0.8rem;
        font-size: 0.82rem;
        font-weight: 700;
        text-decoration: none;
    }

    .ufo-lf-toolbar {
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: #fff;
        padding: 0.85rem;
        margin-bottom: 1rem;
    }

    .ufo-lf-tabs,
    .ufo-lf-categories {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .ufo-lf-tab,
    .lf-category {
        border: 1px solid var(--border-color);
        background: #fff;
        color: #333;
        padding: 0.45rem 0.68rem;
        border-radius: 9px;
        font-size: 0.82rem;
        font-weight: 600;
        line-height: 1;
    }

    .ufo-lf-tab.active[data-tab="hilang"] {
        border-color: #b91c1c;
        background: #b91c1c;
        color: #fff;
    }

    .ufo-lf-tab.active[data-tab="ditemukan"],
    .lf-category.active {
        border-color: var(--primary);
        background: rgba(95, 58, 116, 0.1);
        color: var(--primary);
    }

    .ufo-lf-search {
        border-radius: 10px;
        border-color: #d9deea;
    }

    .ufo-lf-search:focus {
        border-color: #7f4da7;
        box-shadow: 0 0 0 0.18rem rgba(127, 77, 167, 0.16);
    }

    .ufo-lf-count {
        margin: 0 0 0.75rem;
        color: var(--text-secondary);
        font-size: 0.83rem;
    }

    .ufo-lf-item {
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: #fff;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        height: 100%;
    }

    .ufo-lf-image {
        width: 100%;
        height: 154px;
        object-fit: cover;
        background: #eff2f7;
    }

    .ufo-lf-content {
        padding: 0.9rem;
    }

    .ufo-lf-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.45rem;
        margin-bottom: 0.45rem;
    }

    .ufo-lf-category {
        background: rgba(95, 58, 116, 0.1);
        color: var(--primary);
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.2rem 0.5rem;
    }

    .ufo-lf-date {
        color: #6b7280;
        font-size: 0.76rem;
    }

    .ufo-lf-title {
        margin: 0 0 0.3rem;
        font-size: 1rem;
        font-weight: 700;
        color: #1f2937;
    }

    .ufo-lf-meta {
        margin: 0;
        font-size: 0.82rem;
        color: #5f697a;
    }

    .ufo-lf-meta strong {
        color: #374151;
    }

    .ufo-lf-reporter {
        margin-top: 0.38rem;
        font-size: 0.79rem;
        color: var(--text-secondary);
    }

    .ufo-lf-status {
        display: inline-flex;
        margin-top: 0.55rem;
        border-radius: 999px;
        padding: 0.22rem 0.56rem;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .ufo-lf-status.lost {
        background: #fee2e2;
        color: #9f1239;
    }

    .ufo-lf-status.found {
        background: #fef3c7;
        color: #92400e;
    }

    .ufo-lf-actions {
        margin-top: 0.65rem;
    }

    .ufo-lf-action-btn {
        border: 1px solid var(--border-color);
        background: #fff;
        color: var(--primary);
        border-radius: 8px;
        width: 100%;
        padding: 0.45rem 0.7rem;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .ufo-lf-action-btn:hover {
        border-color: var(--primary);
        background: rgba(95, 58, 116, 0.08);
    }

    .ufo-lf-empty {
        border-radius: 12px;
        border: 1px dashed #ccd4e0;
        background: #fff;
        padding: 0.95rem;
        color: #5f697a;
        text-align: center;
    }

    .ufo-lf-page .hidden {
        display: none !important;
    }

    @media (max-width: 768px) {
        .ufo-lf-page {
            padding-top: 1rem;
        }

        .ufo-lf-hero h1 {
            font-size: 1.3rem;
        }

        .ufo-lf-image {
            height: 136px;
        }
    }
</style>
@endpush

@section('content')
<section class="ufo-lf-page">
    <div class="ufo-lf-hero">
        <div>
            <h1>Lost & Found</h1>
            <p>Laporkan barang hilang atau temuan Anda di sini.</p>
        </div>
        <a href="{{ route('login') }}" class="ufo-lf-report-btn">Laporkan</a>
    </div>

    <div class="ufo-lf-toolbar">
        <div class="ufo-lf-tabs mb-3">
            <button data-tab="hilang" type="button" class="ufo-lf-tab active">Barang Hilang</button>
            <button data-tab="ditemukan" type="button" class="ufo-lf-tab">Barang Ditemukan</button>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-12">
                <input id="lf-search" type="text" class="form-control ufo-lf-search" placeholder="Cari barang">
            </div>
        </div>

        <div id="lf-categories" class="ufo-lf-categories">
            <button class="lf-category active" data-category="all">Semua</button>
            <button class="lf-category" data-category="Elektronik">Elektronik</button>
            <button class="lf-category" data-category="Dompet">Dompet</button>
            <button class="lf-category" data-category="Tas">Tas</button>
            <button class="lf-category" data-category="Alat Tulis">Alat Tulis</button>
            <button class="lf-category" data-category="Botol Minum">Botol Minum</button>
            <button class="lf-category" data-category="Kunci">Kunci</button>
            <button class="lf-category" data-category="Lainnya">Lainnya</button>
        </div>
    </div>

    <p id="lf-count" class="ufo-lf-count">3 barang ditemukan</p>

    <div class="row g-3">
        <div class="col-md-6 col-lg-4 lf-card" data-type="hilang" data-category="Kunci" data-title="Kunci Motor Hitam" data-location="Parkiran Utama" data-reporter="D. Rahman" data-date="2026-01-20">
            <article class="ufo-lf-item">
                <img src="/images/dummy/keys.jpg" class="ufo-lf-image" alt="Kunci" onerror="this.style.display='none'">
                <div class="ufo-lf-content">
                    <div class="ufo-lf-top">
                        <span class="ufo-lf-category">Kunci</span>
                        <span class="ufo-lf-date">2026-01-20</span>
                    </div>
                    <h3 class="ufo-lf-title">Kunci Motor - Hitam</h3>
                    <p class="ufo-lf-meta"><strong>Lokasi:</strong> Parkiran Utama</p>
                    <p class="ufo-lf-reporter">Pelapor: D. Rahman</p>
                    <span class="ufo-lf-status lost">Belum ditemukan</span>
                    <div class="ufo-lf-actions">
                        <button type="button" class="ufo-lf-action-btn">Hubungi Pelapor</button>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-md-6 col-lg-4 lf-card" data-type="ditemukan" data-category="Elektronik" data-title="Handphone Hitam" data-location="Cafetaria" data-reporter="L. Sari" data-date="2026-01-29">
            <article class="ufo-lf-item">
                <img src="/images/dummy/phone2.jpg" class="ufo-lf-image" alt="Handphone" onerror="this.style.display='none'">
                <div class="ufo-lf-content">
                    <div class="ufo-lf-top">
                        <span class="ufo-lf-category">Elektronik</span>
                        <span class="ufo-lf-date">2026-01-29</span>
                    </div>
                    <h3 class="ufo-lf-title">Handphone - Hitam</h3>
                    <p class="ufo-lf-meta"><strong>Lokasi:</strong> Cafetaria</p>
                    <p class="ufo-lf-reporter">Pelapor: L. Sari</p>
                    <span class="ufo-lf-status found">Ditemukan</span>
                    <div class="ufo-lf-actions">
                        <button type="button" class="ufo-lf-action-btn">Hubungi Pelapor</button>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-md-6 col-lg-4 lf-card" data-type="hilang" data-category="Elektronik" data-title="Earbuds Putih" data-location="Aula" data-reporter="M. Nabil" data-date="2026-01-25">
            <article class="ufo-lf-item">
                <img src="/images/dummy/earbuds.jpg" class="ufo-lf-image" alt="Earbuds" onerror="this.style.display='none'">
                <div class="ufo-lf-content">
                    <div class="ufo-lf-top">
                        <span class="ufo-lf-category">Elektronik</span>
                        <span class="ufo-lf-date">2026-01-25</span>
                    </div>
                    <h3 class="ufo-lf-title">Earbuds - Putih</h3>
                    <p class="ufo-lf-meta"><strong>Lokasi:</strong> Aula</p>
                    <p class="ufo-lf-reporter">Pelapor: M. Nabil</p>
                    <span class="ufo-lf-status lost">Belum ditemukan</span>
                    <div class="ufo-lf-actions">
                        <button type="button" class="ufo-lf-action-btn">Hubungi Pelapor</button>
                    </div>
                </div>
            </article>
        </div>
    </div>

    <div id="lf-empty" class="ufo-lf-empty hidden mt-4">
        Tidak ada laporan yang sesuai.
    </div>
</section>
@endsection

@push('scripts')
<script src="/js/mahasiswa/lost-found.js"></script>
@endpush
