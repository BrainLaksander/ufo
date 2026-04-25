@extends('layouts.portal.pengurus')

@section('title', 'Profil Organisasi')

@php
    $kategoriOptions = $kategoriOptions ?? [];
    $activeKategori = $activeKategori ?? 'Organisasi Umum';
    $programKegiatan = $programKegiatan ?? [];
    $struktur = $struktur ?? [];
    $kontakPengurus = $kontakPengurus ?? [];
    $socialMedia = $socialMedia ?? [];
    $values = $values ?? [];
    $profileCompletion = $profileCompletion ?? 0;
    $visionText = $visionText ?? '';
    $missionText = $missionText ?? '';
    $cultureText = $cultureText ?? '';
@endphp

@section('content')
<div class="ufo-kboard-page">
    <section class="ufo-kboard-section">
        <h1 class="ufo-kboard-heading">Profil Organisasi</h1>
        <p class="ufo-kboard-lead">Kelola informasi organisasi yang ditampilkan kepada mahasiswa.</p>

        <div class="ufo-kboard-row three mt-3">
            <article class="ufo-kboard-stat blue">
                <h3>{{ $profileCompletion }}%</h3>
                <p>Kelengkapan Profil</p>
            </article>
            <article class="ufo-kboard-stat green">
                <h3>{{ count($programKegiatan) }}</h3>
                <p>Program Aktif</p>
            </article>
            <article class="ufo-kboard-stat purple">
                <h3>{{ count($socialMedia) }}</h3>
                <p>Kanal Komunikasi</p>
            </article>
        </div>
    </section>

    <section class="ufo-kboard-section">
        <div class="ufo-kboard-item-head">
            <h3 class="ufo-kboard-item-title">Kategori Organisasi</h3>
            <button class="ufo-kboard-btn primary" type="button">
                <i class="bi bi-pencil-square"></i>
                Edit
            </button>
        </div>

        <div class="ufo-kboard-row two">
            <label>
                <span class="ufo-kboard-item-meta">Pilih Kategori</span>
                <select class="ufo-kboard-select">
                    @foreach($kategoriOptions as $kategori)
                        <option value="{{ $kategori }}" @selected($kategori === $activeKategori)>{{ $kategori }}</option>
                    @endforeach
                </select>
            </label>

            <div class="ufo-pg-chip-wrap">
                <span class="ufo-pg-chip">{{ $activeKategori }}</span>
            </div>
        </div>
    </section>

    <section class="ufo-kboard-section">
        <div class="ufo-kboard-item-head">
            <h3 class="ufo-kboard-item-title">Logo &amp; Banner</h3>
            <button class="ufo-kboard-btn primary" type="button">
                <i class="bi bi-upload"></i>
                Edit Media
            </button>
        </div>

        <div class="ufo-pg-media-grid">
            <article>
                <p class="ufo-kboard-item-meta mb-2">Logo Organisasi</p>
                <div class="ufo-pg-upload-box">
                    <i class="bi bi-image"></i>
                    <p class="mb-0">Klik untuk upload logo</p>
                    <small>PNG, JPG (maks. 2MB)</small>
                </div>
            </article>

            <article>
                <p class="ufo-kboard-item-meta mb-2">Banner Organisasi</p>
                <div class="ufo-pg-upload-box">
                    <i class="bi bi-card-image"></i>
                    <p class="mb-0">Klik untuk upload banner</p>
                    <small>PNG, JPG (maks. 5MB)</small>
                </div>
            </article>
        </div>
    </section>

    <div class="ufo-kboard-row two">
        <section class="ufo-kboard-section">
            <div class="ufo-kboard-item-head">
                <h3 class="ufo-kboard-item-title">Visi &amp; Misi</h3>
                <button class="ufo-kboard-btn primary" type="button">Edit</button>
            </div>

            <label class="d-block mb-3">
                <span class="ufo-kboard-item-meta">Visi</span>
                <textarea class="ufo-kboard-textarea">{{ $visionText }}</textarea>
            </label>

            <label class="d-block">
                <span class="ufo-kboard-item-meta">Misi</span>
                <textarea class="ufo-kboard-textarea">{{ $missionText }}</textarea>
            </label>
        </section>

        <section class="ufo-kboard-section">
            <div class="ufo-kboard-item-head">
                <h3 class="ufo-kboard-item-title">Culture</h3>
                <button class="ufo-kboard-btn primary" type="button">Edit</button>
            </div>

            <textarea class="ufo-kboard-textarea">{{ $cultureText }}</textarea>
        </section>
    </div>

    <div class="ufo-kboard-row two">
        <section class="ufo-kboard-section">
            <div class="ufo-kboard-item-head">
                <h3 class="ufo-kboard-item-title">Values</h3>
                <button class="ufo-kboard-btn primary" type="button">Edit</button>
            </div>

            <div class="ufo-kboard-list">
                @forelse($values as $value)
                    <article class="ufo-kboard-item ufo-pg-soft-card">
                        <h4 class="ufo-kboard-item-title">{{ $value['name'] }}</h4>
                        <p class="ufo-kboard-item-text">{{ $value['desc'] }}</p>
                    </article>
                @empty
                    <p class="ufo-kboard-item-meta mb-0">Belum ada nilai organisasi yang tersimpan.</p>
                @endforelse
            </div>
        </section>

        <section class="ufo-kboard-section">
            <div class="ufo-kboard-item-head">
                <h3 class="ufo-kboard-item-title">Program Kegiatan</h3>
                <button class="ufo-kboard-btn primary" type="button">Edit</button>
            </div>

            <div class="ufo-kboard-list">
                @forelse($programKegiatan as $program)
                    <details class="ufo-kboard-item ufo-pg-program-card">
                        <summary>
                            <div>
                                <h4 class="ufo-kboard-item-title">{{ $program['nama'] }}</h4>
                                <p class="ufo-kboard-item-meta">{{ $program['periode'] }}</p>
                            </div>
                            <span class="ufo-kboard-pill draft">Detail</span>
                        </summary>

                        <div class="ufo-pg-program-detail">
                            <p><strong>Tujuan:</strong> {{ $program['tujuan'] }}</p>
                            <p><strong>Kegiatan:</strong> {{ $program['kegiatan'] }}</p>
                            <p><strong>Output:</strong> {{ $program['output'] }}</p>
                        </div>
                    </details>
                @empty
                    <p class="ufo-kboard-item-meta mb-0">Belum ada program kegiatan dari data event.</p>
                @endforelse
            </div>
        </section>
    </div>

    <div class="ufo-kboard-row two">
        <section class="ufo-kboard-section">
            <div class="ufo-kboard-item-head">
                <h3 class="ufo-kboard-item-title">Struktur Kepengurusan</h3>
                <button class="ufo-kboard-btn primary" type="button">Edit</button>
            </div>

            <div class="ufo-kboard-list">
                @forelse($struktur as $row)
                    <article class="ufo-kboard-item">
                        <div class="ufo-kboard-item-head mb-0">
                            <h4 class="ufo-kboard-item-title">{{ $row['jabatan'] }}</h4>
                            <span class="ufo-kboard-pill pending">{{ $row['nama'] }}</span>
                        </div>
                    </article>
                @empty
                    <p class="ufo-kboard-item-meta mb-0">Struktur kepengurusan belum tersedia.</p>
                @endforelse
            </div>
        </section>

        <section class="ufo-kboard-section">
            <div class="ufo-kboard-item-head">
                <h3 class="ufo-kboard-item-title">Kontak Pengurus</h3>
                <button class="ufo-kboard-btn primary" type="button">Edit</button>
            </div>

            <div class="ufo-kboard-list">
                @forelse($kontakPengurus as $contact)
                    <article class="ufo-kboard-item ufo-pg-contact-mini">
                        <h4 class="ufo-kboard-item-title">{{ $contact['nama'] }}</h4>
                        <p class="ufo-kboard-item-meta">{{ $contact['jabatan'] }}</p>
                        <p class="ufo-kboard-item-text"><i class="bi bi-whatsapp"></i> {{ $contact['whatsapp'] }}</p>
                    </article>
                @empty
                    <p class="ufo-kboard-item-meta mb-0">Kontak pengurus belum tersedia.</p>
                @endforelse
            </div>
        </section>
    </div>

    <section class="ufo-kboard-section">
        <div class="ufo-kboard-item-head">
            <h3 class="ufo-kboard-item-title">Social Media &amp; Kontak</h3>
            <button class="ufo-kboard-btn primary" type="button">Simpan</button>
        </div>

        <div class="ufo-kboard-row two">
            @forelse($socialMedia as $item)
                <label>
                    <span class="ufo-kboard-item-meta">{{ $item['platform'] }}</span>
                    <input class="ufo-kboard-field" value="{{ $item['value'] }}">
                </label>
            @empty
                <p class="ufo-kboard-item-meta mb-0">Belum ada kanal sosial media/kontak yang tersimpan.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
