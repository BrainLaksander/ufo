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
                <p class="ufo-kboard-lead">Kelola pengumuman event organisasi Anda dengan mudah.</p>
            </div>

            <button class="ufo-kboard-btn primary" data-bs-toggle="modal" data-bs-target="#ufoActionModal" data-modal-title="Buat Pengumuman" data-modal-message="Modal ini membuka konteks pembuatan pengumuman baru." type="button">
                <i class="bi bi-plus-lg"></i>
                Buat Pengumuman
            </button>
        </div>
    </section>

    <section class="ufo-kboard-section collapse" id="announcementCreateForm">
        <h3 class="ufo-kboard-item-title mb-3">Buat Pengumuman Baru</h3>

        <div class="ufo-kboard-row two">
            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Judul Pengumuman</span>
                <input type="text" class="ufo-kboard-field" placeholder="{{ $announcementTitlePlaceholder ?? '' }}">
            </label>

            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Deskripsi</span>
                <textarea class="ufo-kboard-textarea" placeholder="{{ $announcementDescriptionPlaceholder ?? '' }}"></textarea>
            </label>

            <label>
                <span class="ufo-kboard-item-meta">Tanggal Mulai</span>
                <input type="date" class="ufo-kboard-field">
            </label>

            <label>
                <span class="ufo-kboard-item-meta">Tanggal Berakhir</span>
                <input type="date" class="ufo-kboard-field">
            </label>

            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Pilih Event</span>
                <select class="ufo-kboard-select">
                    @forelse($eventOptions as $event)
                        <option value="{{ $event['id'] }}">{{ $event['name'] }}</option>
                    @empty
                        <option>Tidak ada event tersedia</option>
                    @endforelse
                </select>
            </label>
        </div>

        <div class="ufo-kboard-item-actions mt-3">
            <button class="ufo-kboard-btn primary" data-bs-toggle="modal" data-bs-target="#ufoActionModal" data-modal-title="Publikasikan Pengumuman" data-modal-message="Aksi publikasi pengumuman dibuka sebagai modal konfirmasi." type="button">Publikasikan</button>
            <button class="ufo-kboard-btn ghost" type="button" data-bs-toggle="modal" data-bs-target="#ufoActionModal" data-modal-title="Batal Buat Pengumuman" data-modal-message="Aksi batal dibuka sebagai modal konfirmasi.">Batal</button>
        </div>
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
                                <button class="ufo-kboard-btn primary" data-bs-toggle="modal" data-bs-target="#ufoActionModal" data-modal-title="Edit Pengumuman" data-modal-message="Modal ini menampilkan form edit pengumuman." type="button">Edit</button>
                                <button class="ufo-kboard-btn ghost" data-bs-toggle="modal" data-bs-target="#ufoActionModal" data-modal-title="Lihat Pengumuman" data-modal-message="Modal ini menampilkan detail pengumuman." type="button">Lihat</button>
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
                                <button class="ufo-kboard-btn ghost" data-bs-toggle="modal" data-bs-target="#ufoActionModal" data-modal-title="Lihat Pengumuman" data-modal-message="Modal ini menampilkan detail pengumuman." type="button">Lihat</button>
                                @if($item['status'] === 'Aktif')
                                    <button class="ufo-kboard-btn primary" data-bs-toggle="modal" data-bs-target="#ufoActionModal" data-modal-title="Edit Pengumuman" data-modal-message="Modal ini menampilkan form edit pengumuman." type="button">Edit</button>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
