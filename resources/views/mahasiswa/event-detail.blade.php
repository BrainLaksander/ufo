@extends('layouts.public.mahasiswa')

@section('title', ($event['title'] ?? '') . ' - UFO')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/mahasiswa-event.css') }}">
@endpush

@section('content')
@php
    $ui = $pageContent ?? [];
@endphp
<section class="figma-page-container figma-event-detail-page py-3">
    <a href="{{ route('mahasiswa.event') }}" class="figma-link-back">
        <i class="bi bi-arrow-left"></i>
        {{ $ui['back_to_list'] ?? '' }}
    </a>

    <article class="figma-org-detail-hero">
        <img src="{{ $event['poster'] ?? '' }}" alt="{{ $event['title'] ?? '' }}">

        <div class="figma-org-detail-hero-content">
            <div>
                <span class="figma-ann-badge">{{ $event['category'] ?? '' }}</span>
                <h1>{{ $event['title'] ?? '' }}</h1>
                <p>{{ $ui['organizer_prefix'] ?? '' }} {{ $event['organizer'] ?? '' }}</p>
            </div>
        </div>
    </article>

    <div class="figma-grid-3 mb-3">
        <article class="figma-card figma-event-info-card p-3">
            <p class="figma-muted mb-1">{{ $ui['date_label'] ?? '' }}</p>
            <strong><i class="bi bi-calendar-event"></i> {{ $event['date'] ?? '' }}</strong>
        </article>

        <article class="figma-card figma-event-info-card p-3">
            <p class="figma-muted mb-1">{{ $ui['time_label'] ?? '' }}</p>
            <strong><i class="bi bi-clock"></i> {{ $event['time'] ?? '' }}</strong>
        </article>

        <article class="figma-card figma-event-info-card p-3">
            <p class="figma-muted mb-1">{{ $ui['location_label'] ?? '' }}</p>
            <strong><i class="bi bi-geo-alt"></i> {{ $event['location'] ?? '' }}</strong>
        </article>
    </div>

    <article class="figma-highlight mb-3">
        <p class="mb-1">{{ $ui['registration_status_label'] ?? '' }}</p>
        <h2 class="h4 mb-3">{{ $event['registration_status'] ?? '' }}</h2>
        <button type="button" class="figma-btn-secondary">{{ $ui['register_button'] ?? '' }}</button>
    </article>

    <article class="figma-section">
        <h2>{{ $ui['description_title'] ?? '' }}</h2>
        <p>{{ $event['description'] ?? '' }}</p>
    </article>

    <article class="figma-section">
        <h2>{{ $ui['benefits_title'] ?? '' }}</h2>
        <div class="figma-program-grid">
            @foreach(($event['benefits'] ?? []) as $benefit)
                <div class="figma-program-btn">
                    <i class="bi bi-award text-warning"></i>
                    {{ $benefit }}
                </div>
            @endforeach
        </div>
    </article>

    <article class="figma-section">
        <h2>{{ $ui['speakers_title'] ?? '' }}</h2>
        <div class="figma-grid-2">
            @foreach(($event['speakers'] ?? []) as $speaker)
                <div class="figma-structure-item">
                    <strong>{{ $speaker['name'] ?? '' }}</strong>
                    <p class="mb-1">{{ $speaker['title'] ?? '' }}</p>
                    <small class="figma-muted">{{ $speaker['credentials'] ?? '' }}</small>
                </div>
            @endforeach
        </div>
    </article>

    <article class="figma-section">
        <h2>{{ $ui['schedule_title'] ?? '' }}</h2>
        <div class="figma-step-list">
            @foreach(($event['schedule'] ?? []) as $row)
                <div class="figma-step-item">
                    <span class="figma-step-number"><i class="bi bi-clock"></i></span>
                    <div>
                        <strong>{{ $row['time'] ?? '' }}</strong>
                        <p class="mb-0">{{ $row['activity'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </article>
</section>
@endsection
