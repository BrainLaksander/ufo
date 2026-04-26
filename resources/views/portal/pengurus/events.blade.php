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
                <button class="ufo-kboard-btn primary" data-bs-toggle="modal" data-bs-target="#createEventModal" type="button">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Event
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-3 mb-0">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mt-3 mb-0">{{ session('error') }}</div>
        @endif
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
                                    <a href="{{ route('portal.pengurus.events.detail', ['id' => $event['id']]) }}" class="ufo-kboard-btn ghost">Detail</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('portal.pengurus.events.store') }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="createEventModalLabel">Tambah Event Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="event_name" class="form-label">Nama Event <span class="text-danger">*</span></label>
                                <input id="event_name" name="name" type="text" class="form-control" required placeholder="Contoh: Workshop Web Development" value="{{ old('name') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="event_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input id="event_date" name="start_date" type="date" class="form-control" required value="{{ old('start_date') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="event_time" class="form-label">Waktu</label>
                                <input id="event_time" type="text" class="form-control" placeholder="Contoh: 09:00 - 12:00">
                            </div>

                            <div class="col-12">
                                <label for="event_location" class="form-label">Lokasi <span class="text-danger">*</span></label>
                                <input id="event_location" name="location" type="text" class="form-control" required placeholder="Contoh: Auditorium Utama" value="{{ old('location') }}">
                            </div>

                            <div class="col-12">
                                <label for="event_quota" class="form-label">Kapasitas <span class="text-danger">*</span></label>
                                <input id="event_quota" name="quota" type="number" class="form-control" min="1" required placeholder="Jumlah peserta maksimal" value="{{ old('quota') }}">
                            </div>

                            <div class="col-12">
                                <label for="event_description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                <textarea id="event_description" name="description" class="form-control" rows="4" required placeholder="Deskripsi singkat tentang event">{{ old('description') }}</textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="registration_open" checked>
                                    <label class="form-check-label" for="registration_open">Buka pendaftaran</label>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="status" value="approved">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="ufo-kboard-btn primary">Tambah Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
