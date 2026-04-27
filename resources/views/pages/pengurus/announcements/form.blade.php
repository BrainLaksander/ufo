@extends('layouts.portal.pengurus')

@section('title', 'Buat Pengumuman')

@section('content')
<div class="ufo-kboard-page">
    <section class="ufo-kboard-section">
        <a href="{{ route('portal.pengurus.announcements') }}" class="ufo-kboard-btn ghost">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Daftar Pengumuman
        </a>

        <div class="ufo-kboard-item-head mt-3">
            <div>
                <h1 class="ufo-kboard-heading">Buat Pengumuman Baru</h1>
                <p class="ufo-kboard-lead">Susun pengumuman organisasi sebelum diajukan untuk peninjauan.</p>
            </div>
            <span class="ufo-kboard-pill draft">Draft</span>
        </div>
    </section>

    <section class="ufo-kboard-section">
        <h3 class="ufo-kboard-item-title mb-3">Form Pengumuman</h3>

        <form class="ufo-kboard-row two">
            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Judul Pengumuman</span>
                <input type="text" class="ufo-kboard-field" placeholder="{{ $announcementTitlePlaceholder ?? '' }}" required>
            </label>

            <label>
                <span class="ufo-kboard-item-meta">Kategori</span>
                <select class="ufo-kboard-select" required>
                    <option value="">Pilih kategori</option>
                    <option value="rapat">Rapat</option>
                    <option value="event">Event</option>
                    <option value="deadline">Deadline</option>
                    <option value="informasi">Informasi Umum</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </label>

            <label>
                <span class="ufo-kboard-item-meta">Status Pengumuman</span>
                <select class="ufo-kboard-select" required>
                    <option value="">Pilih status</option>
                    <option value="draft">Draft (Belum Publish)</option>
                    <option value="published">Publish Sekarang</option>
                </select>
            </label>

            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Konten Pengumuman</span>
                <textarea class="ufo-kboard-textarea" placeholder="{{ $announcementDescriptionPlaceholder ?? '' }}" required></textarea>
            </label>

            <div class="ufo-kboard-span-full">
                <label class="ufo-kboard-item-meta mb-2 d-block">Lampiran (Opsional)</label>
                <div class="ufo-pg-upload-box">
                    <i class="bi bi-paperclip"></i>
                    <p class="mb-0">Klik untuk upload lampiran</p>
                    <small>PDF, DOC, PNG, JPG (maks. 10MB)</small>
                </div>
            </div>

            <div class="ufo-kboard-item-actions mt-2 ufo-kboard-span-full">
                <button type="submit" class="ufo-kboard-btn gold">
                    <i class="bi bi-send-check"></i>
                    Simpan & Publish
                </button>
                <button type="button" class="ufo-kboard-btn primary">
                    <i class="bi bi-file-earmark"></i>
                    Draft Saja
                </button>
                <a href="{{ route('portal.pengurus.announcements') }}" class="ufo-kboard-btn ghost">Batal</a>
            </div>
        </form>

        <div class="alert alert-warning mt-3 mb-0" role="alert">
            Pengumuman akan ditinjau terlebih dahulu oleh Kemahasiswaan sebelum tampil ke mahasiswa.
        </div>
    </section>
</div>
@endsection
