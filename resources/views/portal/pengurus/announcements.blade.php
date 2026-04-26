@extends('layouts.portal.pengurus')

@section('title', 'Pengumuman Organisasi')

@php
    $activeAnnouncements = $activeAnnouncements ?? [];
    $allAnnouncements = $allAnnouncements ?? [];
    $eventOptions = $eventOptions ?? [];
@endphp

@section('content')
<div class="ufo-kboard-page">
    <section class="ufo-kboard-section">
        <div class="ufo-kboard-item-head">
            <div>
                <h1 class="ufo-kboard-heading">Pengumuman Organisasi</h1>
                <p class="ufo-kboard-lead">Kelola pengumuman organisasi Anda dengan mudah.</p>
            </div>

            <button class="ufo-kboard-btn primary" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal" type="button">
                <i class="bi bi-plus-lg"></i>
                Buat Pengumuman
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-3 mb-0">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mt-3 mb-0">{{ session('error') }}</div>
        @endif
    </section>

    <section class="ufo-kboard-section">
        <ul class="nav nav-pills mb-3" id="announcementTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="announcement-active-tab" data-bs-toggle="pill" data-bs-target="#announcement-active-pane" type="button" role="tab" aria-controls="announcement-active-pane" aria-selected="true">
                    Pengumuman Aktif ({{ count($activeAnnouncements) }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="announcement-all-tab" data-bs-toggle="pill" data-bs-target="#announcement-all-pane" type="button" role="tab" aria-controls="announcement-all-pane" aria-selected="false">
                    Semua Pengumuman ({{ count($allAnnouncements) }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="announcementTabsContent">
            <div class="tab-pane fade show active" id="announcement-active-pane" role="tabpanel" aria-labelledby="announcement-active-tab" tabindex="0">
                <div class="ufo-kboard-list">
                    @foreach($activeAnnouncements as $item)
                        <article class="ufo-kboard-item">
                            <div class="ufo-kboard-item-head">
                                <div>
                                    <h3 class="ufo-kboard-item-title">{{ $item['title'] }}</h3>
                                    <p class="ufo-kboard-item-meta">{{ $item['start_date'] }} - {{ $item['end_date'] }}</p>
                                </div>
                                <span class="ufo-kboard-pill {{ $item['pill'] }}">{{ $item['status'] }}</span>
                            </div>

                            <p class="ufo-kboard-item-text">{{ $item['description'] }}</p>

                            <div class="ufo-kboard-item-actions">
                                <a href="#" class="ufo-kboard-btn primary">Edit</a>
                                <a href="#" class="ufo-kboard-btn ghost">Lihat</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="tab-pane fade" id="announcement-all-pane" role="tabpanel" aria-labelledby="announcement-all-tab" tabindex="0">
                <div class="ufo-kboard-list">
                    @foreach($allAnnouncements as $item)
                        <article class="ufo-kboard-item">
                            <div class="ufo-kboard-item-head">
                                <div>
                                    <h3 class="ufo-kboard-item-title">{{ $item['title'] }}</h3>
                                    <p class="ufo-kboard-item-meta">{{ $item['start_date'] }} - {{ $item['end_date'] }}</p>
                                </div>
                                <span class="ufo-kboard-pill {{ $item['pill'] }}">{{ $item['status'] }}</span>
                            </div>

                            <p class="ufo-kboard-item-text">{{ $item['description'] }}</p>

                            <div class="ufo-kboard-item-actions">
                                <a href="#" class="ufo-kboard-btn ghost">Lihat</a>
                                @if($item['status'] === 'Aktif')
                                    <a href="#" class="ufo-kboard-btn primary">Edit</a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="createAnnouncementModal" tabindex="-1" aria-labelledby="createAnnouncementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form method="POST" action="{{ route('portal.pengurus.announcements.store') }}">
                    @csrf

                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title" id="createAnnouncementModalLabel">Buat Pengumuman Baru</h5>
                            <small class="text-muted">Susun pengumuman organisasi sebelum dipublikasikan.</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Judul Pengumuman <span class="text-danger">*</span></label>
                                <input type="text" name="judul" class="form-control" maxlength="140" value="{{ old('judul') }}" placeholder="Masukkan judul pengumuman..." required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori" class="form-select" required>
                                    <option value="">Pilih kategori</option>
                                    <option value="Rapat">Rapat</option>
                                    <option value="Event">Event</option>
                                    <option value="Deadline">Deadline</option>
                                    <option value="Informasi Umum">Informasi Umum</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Target Distribusi <span class="text-danger">*</span></label>
                                <select name="target" class="form-select" required>
                                    <option value="">Pilih target</option>
                                    <option value="Seluruh anggota">Seluruh anggota</option>
                                    <option value="Anggota aktif">Anggota aktif</option>
                                    <option value="Pengurus">Pengurus</option>
                                    <option value="Mahasiswa umum">Mahasiswa umum</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Konten Pengumuman <span class="text-danger">*</span></label>
                                <textarea name="konten" class="form-control" rows="5" maxlength="10000" placeholder="Tulis isi pengumuman..." required>{{ old('konten') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jadwal Publish (Opsional)</label>
                                <input type="datetime-local" name="publish_at" class="form-control" value="{{ old('publish_at') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Aksi Simpan <span class="text-danger">*</span></label>
                                <select name="submit_action" class="form-select" required>
                                    <option value="draft">Simpan Draft</option>
                                    <option value="publish_now">Publikasikan Sekarang</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="ufo-kboard-btn primary">Simpan Pengumuman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
