@extends('layouts.app')

@section('title', ($org['nama'] ?? 'Detail Organisasi') . ' - UFO')

@php
    $visi = $org['visiMisi']['visi'] ?? null;
    $misiList = $org['visiMisi']['misi'] ?? [];
    $rawPhone = preg_replace('/\D+/', '', $org['phone'] ?? '');
    $waPhone = str_starts_with($rawPhone, '0') ? '62' . substr($rawPhone, 1) : $rawPhone;
@endphp

@push('styles')
<style>
    .ufo-orgd-page {
        padding: 1.25rem 0 2rem;
    }

    .ufo-orgd-back {
        margin-bottom: 0.8rem;
    }

    .ufo-orgd-back a {
        text-decoration: none;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--primary);
        background: #fff;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.38rem 0.6rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .ufo-orgd-hero {
        border-radius: 14px;
        background: var(--primary);
        color: #fff;
        padding: 1.2rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1rem;
    }

    .ufo-orgd-hero h1 {
        margin: 0 0 0.35rem;
        font-size: 1.6rem;
        font-weight: 700;
    }

    .ufo-orgd-hero p {
        margin: 0;
        opacity: 0.92;
        font-size: 0.9rem;
    }

    .ufo-orgd-meta {
        margin-top: 0.75rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .ufo-orgd-pill {
        border-radius: 999px;
        padding: 0.22rem 0.54rem;
        font-size: 0.75rem;
        font-weight: 700;
        line-height: 1;
    }

    .ufo-orgd-pill.light {
        background: #fff;
        color: var(--primary);
    }

    .ufo-orgd-pill.soft {
        background: rgba(255, 255, 255, 0.18);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.26);
    }

    .ufo-orgd-card {
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: #fff;
        box-shadow: var(--shadow-sm);
        padding: 0.95rem;
        margin-bottom: 0.95rem;
    }

    .ufo-orgd-card h2 {
        margin: 0 0 0.55rem;
        font-size: 1rem;
        color: var(--primary);
        font-weight: 700;
    }

    .ufo-orgd-card p {
        margin: 0;
        font-size: 0.88rem;
        color: #465266;
        line-height: 1.55;
    }

    .ufo-orgd-list {
        display: grid;
        gap: 0.55rem;
    }

    .ufo-orgd-list-item {
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 0.65rem;
        background: #fff;
    }

    .ufo-orgd-list-item h3 {
        margin: 0 0 0.2rem;
        font-size: 0.92rem;
        color: #1f2937;
        font-weight: 700;
    }

    .ufo-orgd-list-item p {
        margin: 0;
        font-size: 0.82rem;
        color: #5f697a;
    }

    .ufo-orgd-visi {
        margin-bottom: 0.7rem;
    }

    .ufo-orgd-misi {
        margin: 0;
        padding-left: 1.1rem;
        display: grid;
        gap: 0.4rem;
    }

    .ufo-orgd-misi li {
        font-size: 0.85rem;
        color: #465266;
        line-height: 1.5;
    }

    .ufo-orgd-structure {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.55rem;
    }

    .ufo-orgd-structure-item {
        border: 1px solid var(--border-color);
        border-radius: 10px;
        background: #fff;
        padding: 0.62rem;
    }

    .ufo-orgd-structure-item h3 {
        margin: 0 0 0.16rem;
        color: var(--primary);
        font-size: 0.86rem;
        font-weight: 700;
    }

    .ufo-orgd-structure-item p {
        margin: 0;
        color: #5f697a;
        font-size: 0.81rem;
    }

    .ufo-orgd-sidebar {
        position: sticky;
        top: 86px;
    }

    .ufo-orgd-info-grid {
        display: grid;
        gap: 0.55rem;
    }

    .ufo-orgd-info-item {
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 0.58rem;
    }

    .ufo-orgd-info-item small {
        display: block;
        font-size: 0.7rem;
        color: #6b7280;
        font-weight: 700;
        margin-bottom: 0.15rem;
        letter-spacing: 0.03em;
    }

    .ufo-orgd-info-item p,
    .ufo-orgd-info-item a {
        margin: 0;
        font-size: 0.84rem;
        color: #1f2937;
        text-decoration: none;
    }

    .ufo-orgd-actions {
        margin-top: 0.75rem;
        display: grid;
        gap: 0.45rem;
    }

    .ufo-orgd-btn {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.46rem 0.72rem;
        background: #fff;
        color: var(--primary);
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
    }

    .ufo-orgd-btn.primary {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .ufo-orgd-btn.disabled {
        opacity: 0.55;
        pointer-events: none;
    }

    .ufo-orgd-empty {
        border-radius: 10px;
        border: 1px dashed #ccd4e0;
        padding: 0.7rem;
        color: #5f697a;
        font-size: 0.84rem;
        text-align: center;
    }

    @media (max-width: 991px) {
        .ufo-orgd-sidebar {
            position: static;
            top: auto;
        }
    }

    @media (max-width: 768px) {
        .ufo-orgd-page {
            padding-top: 1rem;
        }

        .ufo-orgd-hero h1 {
            font-size: 1.3rem;
        }

        .ufo-orgd-structure {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="ufo-orgd-page">
    <div class="ufo-orgd-back">
        <a href="{{ route('mahasiswa.organisasi.index') }}"><i class="bi bi-arrow-left"></i>Kembali ke Daftar</a>
    </div>

    <div class="ufo-orgd-hero">
        <h1>{{ $org['nama'] ?? 'Organisasi' }}</h1>
        <p>{{ $org['tagline'] ?? '-' }}</p>

        <div class="ufo-orgd-meta">
            <span class="ufo-orgd-pill light">{{ $org['kategori'] ?? 'Umum' }}</span>
            <span class="ufo-orgd-pill soft"><i class="bi bi-people-fill me-1"></i>{{ $org['members'] ?? 0 }} anggota</span>
            @if(!empty($org['registrationOpen']))
                <span class="ufo-orgd-pill soft"><i class="bi bi-unlock-fill me-1"></i>Pendaftaran Buka</span>
            @else
                <span class="ufo-orgd-pill soft"><i class="bi bi-lock-fill me-1"></i>Pendaftaran Tutup</span>
            @endif
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <article class="ufo-orgd-card">
                <h2>Tentang Organisasi</h2>
                <p>{{ $org['deskripsi'] ?? 'Deskripsi belum tersedia.' }}</p>
            </article>

            <article class="ufo-orgd-card">
                <h2>Visi &amp; Misi</h2>

                @if(!empty($visi))
                    <p class="ufo-orgd-visi"><strong>Visi:</strong> {{ $visi }}</p>
                @endif

                @if(!empty($misiList))
                    <ul class="ufo-orgd-misi">
                        @foreach($misiList as $misi)
                            <li>{{ $misi }}</li>
                        @endforeach
                    </ul>
                @else
                    <div class="ufo-orgd-empty">Misi belum tersedia.</div>
                @endif
            </article>

            <article class="ufo-orgd-card">
                <h2>Budaya Organisasi</h2>
                <p>{{ $org['budaya'] ?? '-' }}</p>
            </article>

            <article class="ufo-orgd-card">
                <h2>Program &amp; Kegiatan</h2>
                @if(!empty($org['programs']))
                    <div class="ufo-orgd-list">
                        @foreach($org['programs'] as $program)
                            <div class="ufo-orgd-list-item">
                                <h3>{{ $program['nama'] ?? '-' }}</h3>
                                <p>{{ $program['deskripsi'] ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="ufo-orgd-empty">Belum ada program yang dipublikasikan.</div>
                @endif
            </article>

            <article class="ufo-orgd-card">
                <h2>Acara Mendatang</h2>
                @if(!empty($org['events']))
                    <div class="ufo-orgd-list">
                        @foreach($org['events'] as $event)
                            <div class="ufo-orgd-list-item">
                                <h3>{{ $event['nama'] ?? '-' }}</h3>
                                <p>{{ $event['date'] ?? '-' }} | {{ $event['deskripsi'] ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="ufo-orgd-empty">Belum ada acara mendatang.</div>
                @endif
            </article>

            <article class="ufo-orgd-card">
                <h2>Struktur Organisasi</h2>
                @if(!empty($org['struktur']))
                    <div class="ufo-orgd-structure">
                        @foreach($org['struktur'] as $item)
                            <div class="ufo-orgd-structure-item">
                                <h3>{{ $item['posisi'] ?? '-' }}</h3>
                                <p>{{ $item['nama'] ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="ufo-orgd-empty">Struktur belum tersedia.</div>
                @endif
            </article>
        </div>

        <div class="col-lg-4">
            <aside class="ufo-orgd-sidebar">
                <article class="ufo-orgd-card">
                    <h2>Informasi</h2>

                    <div class="ufo-orgd-info-grid">
                        <div class="ufo-orgd-info-item">
                            <small>KATEGORI</small>
                            <p>{{ $org['kategori'] ?? '-' }}</p>
                        </div>

                        <div class="ufo-orgd-info-item">
                            <small>ANGGOTA</small>
                            <p>{{ $org['members'] ?? 0 }} Anggota</p>
                        </div>

                        <div class="ufo-orgd-info-item">
                            <small>KONTAK</small>
                            @if(!empty($org['contact']))
                                <a href="mailto:{{ $org['contact'] }}">{{ $org['contact'] }}</a>
                            @else
                                <p>-</p>
                            @endif
                        </div>

                        <div class="ufo-orgd-info-item">
                            <small>TELEPON</small>
                            @if(!empty($org['phone']))
                                <a href="tel:{{ $org['phone'] }}">{{ $org['phone'] }}</a>
                            @else
                                <p>-</p>
                            @endif
                        </div>
                    </div>

                    <div class="ufo-orgd-actions">
                        @if(!empty($org['registrationOpen']))
                            <button type="button" class="ufo-orgd-btn primary" data-bs-toggle="modal" data-bs-target="#registrationModal">
                                <i class="bi bi-pencil-square"></i>Daftar Sekarang
                            </button>
                        @else
                            <span class="ufo-orgd-btn disabled"><i class="bi bi-lock-fill"></i>Pendaftaran Tutup</span>
                        @endif

                        @if(!empty($waPhone))
                            <a href="https://wa.me/{{ $waPhone }}" class="ufo-orgd-btn" target="_blank" rel="noopener">
                                <i class="bi bi-whatsapp"></i>WhatsApp
                            </a>
                        @endif

                        @if(!empty($org['contact']))
                            <a href="mailto:{{ $org['contact'] }}" class="ufo-orgd-btn">
                                <i class="bi bi-envelope-fill"></i>Email
                            </a>
                        @endif
                    </div>
                </article>
            </aside>
        </div>
    </div>

    <div class="modal fade" id="registrationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pendaftaran {{ $org['nama'] ?? 'Organisasi' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registrationForm">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIM</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="tel" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Motivasi Bergabung</label>
                            <textarea class="form-control" rows="4" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="submitRegistration()">Kirim Pendaftaran</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function submitRegistration() {
        alert('Pendaftaran berhasil dikirim. Tim organisasi akan menghubungi Anda.');
        document.getElementById('registrationForm').reset();
        bootstrap.Modal.getInstance(document.getElementById('registrationModal')).hide();
    }
</script>
@endpush
