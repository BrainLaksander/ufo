@extends('layouts.public.mahasiswa')

@section('title', 'Beranda Mahasiswa - UFO')

@section('content')
@php
    $hero = $homeContent['hero'] ?? [];
    $quickLinks = $homeContent['quick_links'] ?? [];
    $services = $homeContent['services'] ?? [];
@endphp

<section class="figma-page-container py-3">
    <header class="figma-about-hero">
        <h1>{{ $hero['title'] ?? '' }}</h1>
        <p>{{ $hero['subtitle'] ?? '' }}</p>

        <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
            @foreach(($hero['actions'] ?? []) as $action)
                <a href="{{ $action['url'] ?? '' }}" class="{{ $action['style'] ?? '' }}">
                    @if(!empty($action['icon']))
                        <i class="{{ $action['icon'] }}"></i>
                    @endif
                    {{ $action['label'] ?? '' }}
                </a>
            @endforeach
        </div>
    </header>

    <section class="figma-section" aria-label="Menu Mahasiswa">
        <h2>{{ $homeContent['quick_links_title'] ?? '' }}</h2>
        <div class="figma-grid-3">
            @foreach($quickLinks as $link)
                <a href="{{ $link['url'] ?? '' }}" class="figma-event-item">
                    {{ $link['label'] ?? '' }}
                    <i class="bi bi-arrow-up-right"></i>
                </a>
            @endforeach
        </div>
    </section>

    <section class="figma-section" aria-label="Ringkasan layanan">
        <h2>{{ $homeContent['services_title'] ?? '' }}</h2>
        <div class="figma-grid-3">
            @foreach($services as $service)
                <article class="figma-about-feature figma-card">
                    @if(!empty($service['icon']))
                        <i class="{{ $service['icon'] }}"></i>
                    @endif
                    <h3 class="h5">{{ $service['title'] ?? '' }}</h3>
                    <p class="figma-muted mb-0">{{ $service['description'] ?? '' }}</p>
                </article>
            @endforeach
        </div>
    </section>
</section>
@endsection
