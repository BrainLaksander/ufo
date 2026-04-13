@extends('layouts.app')

@section('title', 'Beranda Mahasiswa - UFO')

@push('styles')
<style>
    .ufo-student-page {
        padding: 1.25rem 0 2rem;
    }

    .ufo-student-hero {
        border-radius: 14px;
        background: var(--primary);
        color: #fff;
        padding: 1.2rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 0.95rem;
    }

    .ufo-student-hero h1 {
        margin: 0 0 0.35rem;
        font-size: 1.55rem;
        font-weight: 700;
    }

    .ufo-student-hero p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.92rem;
    }

    .ufo-student-actions {
        margin-top: 0.85rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .ufo-student-btn {
        border-radius: 9px;
        padding: 0.46rem 0.75rem;
        font-size: 0.82rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border: 1px solid transparent;
    }

    .ufo-student-btn.primary {
        background: var(--accent);
        border-color: #f5d96a;
        color: #1f2937;
    }

    .ufo-student-btn.light {
        background: #fff;
        color: var(--primary);
        border-color: #e5e7eb;
    }

    .ufo-student-block {
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: #fff;
        box-shadow: var(--shadow-sm);
        padding: 0.95rem;
        margin-bottom: 0.95rem;
    }

    .ufo-student-block h2 {
        margin: 0 0 0.6rem;
        font-size: 1.02rem;
        font-weight: 700;
        color: var(--primary);
    }

    .ufo-student-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.55rem;
    }

    .ufo-student-link {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background: #fff;
        color: var(--text-primary);
        text-decoration: none;
        padding: 0.5rem 0.65rem;
        font-size: 0.84rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.35rem;
    }

    .ufo-student-link:hover {
        border-color: var(--primary);
        background: rgba(95, 58, 116, 0.08);
        color: var(--primary);
    }

    .ufo-student-notes {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.55rem;
    }

    .ufo-student-note {
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 0.68rem;
        background: #fff;
    }

    .ufo-student-note h3 {
        margin: 0 0 0.32rem;
        font-size: 0.9rem;
        color: #1f2937;
        font-weight: 700;
    }

    .ufo-student-note p {
        margin: 0;
        font-size: 0.81rem;
        color: #5f697a;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .ufo-student-page {
            padding-top: 1rem;
        }

        .ufo-student-hero h1 {
            font-size: 1.3rem;
        }

        .ufo-student-grid,
        .ufo-student-notes {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="ufo-student-page">
    <div class="ufo-student-hero">
        <h1>Selamat datang mahasiswa Universitas Klabat</h1>
        <p>Sistem informasi organisasi mahasiswa (UFO) untuk mendukung aktivitas kampus secara lebih terstruktur.</p>

        <div class="ufo-student-actions">
            <a href="{{ route('mahasiswa.organisasi.index') }}" class="ufo-student-btn primary">
                <i class="bi bi-people-fill"></i>
                Daftar Organisasi
            </a>
            <a href="{{ route('mahasiswa.pengumuman') }}" class="ufo-student-btn light">
                <i class="bi bi-megaphone-fill"></i>
                Lihat Pengumuman
            </a>
        </div>
    </div>

    <section class="ufo-student-block" aria-label="Menu Mahasiswa">
        <h2>Akses Cepat</h2>
        <div class="ufo-student-grid">
            <a href="{{ route('mahasiswa.organisasi.index') }}" class="ufo-student-link">Organisasi <i class="bi bi-arrow-up-right"></i></a>
            <a href="{{ route('mahasiswa.event') }}" class="ufo-student-link">Kegiatan <i class="bi bi-arrow-up-right"></i></a>
            <a href="{{ route('mahasiswa.pengumuman') }}" class="ufo-student-link">Pengumuman <i class="bi bi-arrow-up-right"></i></a>
            <a href="{{ route('mahasiswa.lost-found') }}" class="ufo-student-link">Lost &amp; Found <i class="bi bi-arrow-up-right"></i></a>
            <a href="{{ route('mahasiswa.tentang') }}" class="ufo-student-link">Tentang UFO <i class="bi bi-arrow-up-right"></i></a>
            <a href="{{ route('login') }}" class="ufo-student-link">Portal Internal <i class="bi bi-arrow-up-right"></i></a>
        </div>
    </section>

    <section class="ufo-student-block" aria-label="Ringkasan layanan">
        <h2>Layanan Utama</h2>
        <div class="ufo-student-notes">
            <article class="ufo-student-note">
                <h3>Organisasi</h3>
                <p>Cari UKM atau komunitas kampus berdasarkan minat dan kategori.</p>
            </article>
            <article class="ufo-student-note">
                <h3>Informasi</h3>
                <p>Akses pengumuman terbaru terkait akademik, event, dan kegiatan mahasiswa.</p>
            </article>
            <article class="ufo-student-note">
                <h3>Lost &amp; Found</h3>
                <p>Bantu melaporkan dan menemukan barang hilang di area kampus.</p>
            </article>
        </div>
    </section>
</section>
@endsection
