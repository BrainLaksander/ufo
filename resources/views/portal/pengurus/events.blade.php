@extends('layouts.portal.pengurus')

@section('title', 'Event Organisasi')

@php
    $activeEvents = $activeEvents ?? [];
    $completedEvents = $completedEvents ?? [];
@endphp

@section('content')
<div class="ufo-kboard-page">
    <section class="ufo-kboard-section">
        <div class="ufo-kboard-item-head">
            <div>
                <h1 class="ufo-kboard-heading">Event Organisasi</h1>
                <p class="ufo-kboard-lead">Kelola event dan berita kegiatan organisasi Anda.</p>
            </div>

            <div class="ufo-kboard-item-actions mt-0">
                <button class="ufo-kboard-btn primary" data-bs-toggle="collapse" data-bs-target="#eventCreateForm" type="button">
                    <i class="bi bi-plus-lg"></i>
                    Buat Event Baru
                </button>
                <button class="ufo-kboard-btn gold" data-bs-toggle="collapse" data-bs-target="#eventNewsForm" type="button">
                    <i class="bi bi-file-earmark-text"></i>
                    Buat Berita Event
                </button>
            </div>
        </div>
    </section>

    <section class="ufo-kboard-section collapse" id="eventCreateForm">
        <h3 class="ufo-kboard-item-title mb-3">Buat Event Baru</h3>

        <div class="ufo-kboard-row two">
            <div class="ufo-kboard-span-full">
                <label class="ufo-kboard-item-meta mb-2 d-block">Poster Event</label>
                <div class="ufo-pg-upload-box">
                    <i class="bi bi-image"></i>
                    <p class="mb-0">Klik untuk upload poster</p>
                    <small>PNG, JPG (maks. 5MB)</small>
                </div>
            </div>

            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Nama Event</span>
                <input type="text" class="ufo-kboard-field" placeholder="{{ $eventNamePlaceholder ?? 'Workshop AI & Machine Learning' }}">
            </label>

            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Deskripsi</span>
                <textarea class="ufo-kboard-textarea" placeholder="{{ $eventDescriptionPlaceholder ?? 'Jelaskan tujuan, agenda, dan manfaat event.' }}"></textarea>
            </label>

            <label>
                <span class="ufo-kboard-item-meta">Tanggal</span>
                <input type="date" class="ufo-kboard-field">
            </label>

            <label>
                <span class="ufo-kboard-item-meta">Waktu</span>
                <input type="time" class="ufo-kboard-field">
            </label>

            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Lokasi / Platform</span>
                <input type="text" class="ufo-kboard-field" placeholder="{{ $eventLocationPlaceholder ?? 'Aula Gedung A atau Zoom Meeting' }}">
            </label>

            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Link Pendaftaran</span>
                <input type="url" class="ufo-kboard-field" placeholder="https://forms.gle/...">
            </label>
        </div>

        <div class="ufo-kboard-item-actions mt-3">
            <button class="ufo-kboard-btn primary" type="button">Publikasikan Event</button>
            <button class="ufo-kboard-btn ghost" type="button" data-bs-toggle="collapse" data-bs-target="#eventCreateForm">Batal</button>
        </div>
    </section>

    <section class="ufo-kboard-section collapse" id="eventNewsForm">
        <h3 class="ufo-kboard-item-title mb-3">Buat Berita Event</h3>

        <div class="ufo-kboard-row two">
            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Judul Berita</span>
                <input type="text" class="ufo-kboard-field" placeholder="{{ $eventNewsTitlePlaceholder ?? 'Berita Event Organisasi' }}">
            </label>

            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Tanggal Pelaksanaan</span>
                <input type="date" class="ufo-kboard-field">
            </label>

            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Cerita Kegiatan</span>
                <textarea class="ufo-kboard-textarea" placeholder="{{ $eventNewsDescriptionPlaceholder ?? 'Ceritakan bagaimana event berlangsung dan apa highlight-nya.' }}"></textarea>
            </label>

            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Highlight / Kesan Event</span>
                <textarea class="ufo-kboard-textarea" placeholder="{{ $eventNewsHighlightPlaceholder ?? 'Sorot momen utama, capaian, atau hasil paling penting.' }}"></textarea>
            </label>

            <div class="ufo-kboard-span-full">
                <label class="ufo-kboard-item-meta mb-2 d-block">Dokumentasi Foto (Opsional)</label>
                <div class="ufo-pg-upload-box">
                    <i class="bi bi-cloud-upload"></i>
                    <p class="mb-0">Klik untuk upload foto dokumentasi</p>
                    <small>JPG, PNG (maks. 10MB total)</small>
                </div>
            </div>
        </div>

        <div class="ufo-kboard-item-actions mt-3">
            <button class="ufo-kboard-btn primary" type="button">Publikasikan Berita</button>
            <button class="ufo-kboard-btn ghost" type="button" data-bs-toggle="collapse" data-bs-target="#eventNewsForm">Batal</button>
        </div>

        <div class="alert alert-info mt-3 mb-0" role="alert">
            Berita event akan ditampilkan sebagai histori di profil organisasi dan dapat dilihat oleh mahasiswa.
        </div>
    </section>

    <section class="ufo-kboard-section">
        <ul class="nav nav-pills mb-3" id="eventTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="event-active-tab" data-bs-toggle="pill" data-bs-target="#event-active-pane" type="button" role="tab" aria-controls="event-active-pane" aria-selected="true">
                    Event Aktif ({{ count($activeEvents) }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="event-completed-tab" data-bs-toggle="pill" data-bs-target="#event-completed-pane" type="button" role="tab" aria-controls="event-completed-pane" aria-selected="false">
                    Event Selesai ({{ count($completedEvents) }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="eventTabsContent">
            <div class="tab-pane fade show active" id="event-active-pane" role="tabpanel" aria-labelledby="event-active-tab" tabindex="0">
                <div class="ufo-pg-event-grid">
                    @foreach($activeEvents as $event)
                        <article class="ufo-pg-event-card">
                            <div class="ufo-pg-event-cover">
                                <i class="bi bi-image"></i>
                            </div>

                            <div class="ufo-pg-event-card-body">
                                <div class="ufo-kboard-item-head mb-2">
                                    <h3 class="ufo-kboard-item-title">{{ $event['title'] }}</h3>
                                    <span class="ufo-kboard-pill {{ $event['pill'] }}">{{ $event['status'] }}</span>
                                </div>

                                <p class="ufo-kboard-item-meta mb-1"><i class="bi bi-calendar3"></i> {{ $event['date'] }} • {{ $event['time'] }}</p>
                                <p class="ufo-kboard-item-meta mb-1"><i class="bi bi-geo-alt"></i> {{ $event['location'] }}</p>
                                <p class="ufo-kboard-item-meta"><i class="bi bi-people"></i> {{ $event['registrants'] }} pendaftar</p>

                                <div class="ufo-kboard-item-actions mt-3">
                                    <a href="{{ route('portal.pengurus.events.create') }}" class="ufo-kboard-btn primary">Edit Event</a>
                                    <a href="{{ route('portal.pengurus.events.detail', ['id' => $event['id']]) }}" class="ufo-kboard-btn ghost">Lihat Detail</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="tab-pane fade" id="event-completed-pane" role="tabpanel" aria-labelledby="event-completed-tab" tabindex="0">
                <div class="ufo-pg-event-grid">
                    @foreach($completedEvents as $event)
                        <article class="ufo-pg-event-card">
                            <div class="ufo-pg-event-cover muted">
                                <i class="bi bi-image"></i>
                            </div>

                            <div class="ufo-pg-event-card-body">
                                <div class="ufo-kboard-item-head mb-2">
                                    <h3 class="ufo-kboard-item-title">{{ $event['title'] }}</h3>
                                    @if($event['has_news'])
                                        <span class="ufo-kboard-pill approved">Ada Berita</span>
                                    @else
                                        <span class="ufo-kboard-pill draft">Belum Ada Berita</span>
                                    @endif
                                </div>

                                <p class="ufo-kboard-item-meta mb-1"><i class="bi bi-calendar3"></i> {{ $event['date'] }}</p>
                                <p class="ufo-kboard-item-meta mb-1"><i class="bi bi-geo-alt"></i> {{ $event['location'] }}</p>
                                <p class="ufo-kboard-item-meta"><i class="bi bi-people"></i> {{ $event['participants'] }} peserta</p>

                                <div class="ufo-kboard-item-actions mt-3">
                                    @if($event['has_news'])
                                        <button class="ufo-kboard-btn primary" type="button">Lihat Berita</button>
                                    @else
                                        <button class="ufo-kboard-btn gold" type="button" data-bs-toggle="collapse" data-bs-target="#eventNewsForm">Buat Berita Event</button>
                                    @endif
                                    <button class="ufo-kboard-btn ghost" type="button">Detail</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
