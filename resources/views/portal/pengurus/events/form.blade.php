@extends('layouts.portal.pengurus')

@section('title', 'Buat Event')

@section('content')
<section class="ufo-pg-event-create-page">
    <div class="ufo-pg-event-create-head">
        <h1 class="ufo-pg-event-create-title">Buat Event Baru</h1>
        <p class="ufo-pg-event-create-subtitle">Buat event UKM dari data organisasi pengurus saat ini</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success ufo-pg-feedback" role="alert">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger ufo-pg-feedback" role="alert">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger ufo-pg-feedback" role="alert">{{ $errors->first() }}</div>
    @endif

    @if(!($hasPengurusContext ?? false))
        <div class="alert alert-warning ufo-pg-warning-banner" role="alert">
            Akun pengurus belum terhubung ke data organisasi/member di database.
        </div>
    @elseif(!($hasApprovedIzin ?? false))
        <div class="alert alert-warning ufo-pg-warning-banner" role="alert">
            Pembuatan event dikunci. Organisasi harus memiliki minimal satu izin kegiatan berstatus <strong>Disetujui</strong>.
        </div>
    @else
        <div class="alert ufo-pg-status-banner" role="alert">
            Izin kegiatan sudah disetujui. Anda dapat membuat event baru.
        </div>
    @endif

    @php
        $canCreateEvent = ($hasPengurusContext ?? false) && ($hasApprovedIzin ?? false);
    @endphp

    <form method="POST" action="{{ route('portal.pengurus.events.store') }}" class="ufo-pg-event-create-form">
        @csrf

        <article class="ufo-pg-event-create-card">
            <div class="ufo-pg-card-head">
                <h2>Informasi Event</h2>
                <span class="ufo-pg-event-chip">Draft Baru</span>
            </div>

            @php $todayDate = now()->format('Y-m-d\TH:i'); @endphp

            <div class="ufo-pg-event-field-grid">
                <div class="ufo-pg-field-group ufo-pg-col-span-6">
                    <label for="event-name">Nama Event</label>
                    <input id="event-name" type="text" name="name" value="{{ old('name') }}" placeholder="{{ $eventNamePlaceholder ?? '' }}" required @disabled(!$canCreateEvent)>
                </div>

                <div class="ufo-pg-field-group ufo-pg-col-span-3">
                    <label for="event-start">Mulai</label>
                    <input id="event-start" type="datetime-local" name="start_date" value="{{ old('start_date', $todayDate) }}" min="{{ $todayDate }}" required @disabled(!$canCreateEvent)>
                </div>

                <div class="ufo-pg-field-group ufo-pg-col-span-3">
                    <label for="event-end">Selesai (opsional)</label>
                    <input id="event-end" type="datetime-local" name="end_date" value="{{ old('end_date') }}" @disabled(!$canCreateEvent)>
                </div>

                <div class="ufo-pg-field-group ufo-pg-col-span-6">
                    <label for="event-location">Lokasi</label>
                    <input id="event-location" type="text" name="location" value="{{ old('location') }}" placeholder="{{ $eventLocationPlaceholder ?? '' }}" required @disabled(!$canCreateEvent)>
                </div>

                <div class="ufo-pg-field-group ufo-pg-col-span-3">
                    <label for="event-quota">Kuota Peserta</label>
                    <input id="event-quota" type="number" name="quota" min="1" value="{{ old('quota', 50) }}" required @disabled(!$canCreateEvent)>
                </div>

                <div class="ufo-pg-field-group ufo-pg-col-span-3">
                    <label for="event-status">Status Event</label>
                    <input id="event-status" type="text" value="Draft (Belum Publik)" readonly @disabled(!$canCreateEvent)>
                    <input type="hidden" name="status" value="draft">
                </div>

                <div class="ufo-pg-field-group ufo-pg-col-span-12">
                    <label for="event-description">Deskripsi Event</label>
                    <textarea id="event-description" name="description" placeholder="{{ $eventDescriptionPlaceholder ?? '' }}" required @disabled(!$canCreateEvent)>{{ old('description') }}</textarea>
                </div>

                <div class="ufo-pg-field-group ufo-pg-col-span-12">
                    <label for="news-title">Judul Berita Event</label>
                    <input id="news-title" type="text" name="news_title" value="{{ old('news_title') }}" placeholder="Contoh: Kegiatan berjalan lancar dan meriah" required @disabled(!$canCreateEvent)>
                </div>

                <div class="ufo-pg-field-group ufo-pg-col-span-12">
                    <label for="news-content">Konten Berita Event</label>
                    <textarea id="news-content" name="news_content" placeholder="Ceritakan rangkaian kegiatan dan hasil event..." required @disabled(!$canCreateEvent)>{{ old('news_content') }}</textarea>
                </div>
            </div>

            <div class="ufo-pg-event-actions">
                <button type="submit" class="btn-primary-org" @disabled(!$canCreateEvent)>
                    <i class="bi bi-check-circle" aria-hidden="true"></i>
                    <span>Simpan Event & Berita</span>
                </button>
                <a href="{{ route('portal.pengurus.events') }}" class="btn-secondary-org">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </article>
    </form>
</section>
@endsection
