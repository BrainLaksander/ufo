@extends('layouts.public.mahasiswa')

@section('title', 'Pendaftaran ' . ($org['name'] ?? '') . ' - UFO')

@section('content')
@php
    $ui = $pageContent ?? [];
@endphp
<section class="figma-page-container py-3">
    <a href="{{ route('mahasiswa.organisasi.show', ['id' => $org['id'] ?? 0]) }}" class="figma-link-back">
        <i class="bi bi-arrow-left"></i>
        {{ $ui['back_label'] ?? '' }}
    </a>

    <header class="figma-register-header">
        <div class="figma-register-logo">{{ strtoupper($org['logo_text'] ?? '') }}</div>
        <h1 class="h3 mb-2">{{ $ui['title_prefix'] ?? '' }} {{ $org['name'] ?? '' }}</h1>
        <p class="figma-muted mb-0">{{ $ui['subtitle'] ?? '' }}</p>
    </header>

    <article class="figma-section">
        <h2>{{ $ui['status_title'] ?? '' }}</h2>

        @if(!empty($registration['open']))
            <span class="figma-status-pill open">
                <i class="bi bi-check-circle"></i>
                {{ $ui['status_open'] ?? '' }}
            </span>

            <p class="mt-3">{{ $ui['period_label'] ?? '' }} <strong>{{ $registration['period'] ?? '' }}</strong></p>

            @if(!empty($registration['form_link']))
                <a href="{{ $registration['form_link'] }}" target="_blank" rel="noopener" class="figma-btn-primary">
                    {{ $ui['open_form_button'] ?? '' }}
                </a>
            @endif
        @else
            <span class="figma-status-pill closed">
                <i class="bi bi-clock"></i>
                {{ $ui['status_closed'] ?? '' }}
            </span>

            <div class="figma-register-alert mt-3">
                <strong>{{ $ui['open_on_label'] ?? '' }}</strong>
                <p class="mb-0 mt-1">{{ $registration['open_date'] ?? ($ui['open_date_fallback'] ?? '') }}</p>
            </div>
        @endif
    </article>

    <article class="figma-section">
        <h2>{{ $ui['divisions_title'] ?? '' }}</h2>

        @if(!empty($registration['divisions']))
            <div class="figma-program-grid">
                @foreach($registration['divisions'] as $division)
                    <div class="figma-register-division">
                        <strong>{{ $division['name'] ?? '' }}</strong>
                        <p class="mb-0 mt-1 figma-muted">{{ $division['description'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="mb-0 figma-muted">{{ $ui['divisions_empty'] ?? '' }}</p>
        @endif
    </article>

    <article class="figma-register-guide">
        <h2 class="h4 mb-2">{{ $ui['guide_title'] ?? '' }}</h2>
        <p>{{ $ui['guide_description'] ?? '' }}</p>

        @if(!empty($registration['guidebook_url']) && $registration['guidebook_url'] !== '#')
            <a href="{{ $registration['guidebook_url'] }}" target="_blank" rel="noopener" class="figma-btn-secondary">
                <i class="bi bi-download"></i>
                {{ $ui['guide_download_button'] ?? '' }}
            </a>
        @endif
    </article>
</section>
@endsection
