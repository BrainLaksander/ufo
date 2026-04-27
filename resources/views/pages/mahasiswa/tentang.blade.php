@extends('layouts.public.mahasiswa')

@section('title', 'Tentang UFO')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/mahasiswa-tentang.css') }}">
@endpush

@section('content')
@php
    $hero = $aboutContent['hero'] ?? [];
    $intro = $aboutContent['intro'] ?? [];
    $vision = $aboutContent['vision'] ?? [];
    $mission = $aboutContent['mission'] ?? [];
    $features = $aboutContent['features'] ?? [];
    $contact = $aboutContent['contact'] ?? [];
    $bot = $aboutContent['bot'] ?? [];
@endphp

<section class="figma-page-container py-3 figma-tentang-page">
    <header class="figma-about-hero">
        <h1>{{ $hero['title'] ?? '' }}</h1>
        <p>{{ $hero['subtitle'] ?? '' }}</p>
    </header>

    <article class="figma-section figma-tentang-intro">
        <h2>
            @if(!empty($intro['icon']))
                <i class="{{ $intro['icon'] }}"></i>
            @endif
            {{ $intro['title'] ?? '' }}
        </h2>
        @foreach(($intro['paragraphs'] ?? []) as $paragraph)
            <p>{{ $paragraph }}</p>
        @endforeach
    </article>

    <div class="figma-grid-2 mb-3">
        <article class="figma-section mb-0 figma-tentang-vision">
            <h2>
                @if(!empty($vision['icon']))
                    <i class="{{ $vision['icon'] }}"></i>
                @endif
                {{ $vision['title'] ?? '' }}
            </h2>
            <p>{{ $vision['description'] ?? '' }}</p>
        </article>

        <article class="figma-section mb-0 figma-tentang-mission">
            <h2>
                @if(!empty($mission['icon']))
                    <i class="{{ $mission['icon'] }}"></i>
                @endif
                {{ $mission['title'] ?? '' }}
            </h2>
            <ul>
                @foreach(($mission['items'] ?? []) as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </article>
    </div>

    <article class="figma-section figma-tentang-feature-wrap">
        <h2 class="text-center">{{ $features['title'] ?? '' }}</h2>
        <div class="figma-grid-3">
            @foreach(($features['items'] ?? []) as $feature)
                <div class="figma-about-feature {{ $feature['tone_class'] ?? '' }}">
                    @if(!empty($feature['icon']))
                        <i class="{{ $feature['icon'] }}"></i>
                    @endif
                    <h3 class="h5">{{ $feature['title'] ?? '' }}</h3>
                    <p class="figma-muted">{{ $feature['description'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </article>

    <article class="figma-about-contact mb-3">
        <h2 class="text-center mb-3">{{ $contact['title'] ?? '' }}</h2>

        <div class="figma-about-contact-grid">
            @foreach(($contact['items'] ?? []) as $item)
                <div class="figma-about-contact-item">
                    @if(!empty($item['icon']))
                        <i class="{{ $item['icon'] }}"></i>
                    @endif
                    <h3 class="h6">{{ $item['title'] ?? '' }}</h3>
                    <p class="mb-0">{{ $item['value'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </article>

    <article class="figma-about-bot">
        <div class="figma-about-bot-wrap">
            <div class="figma-about-bot-mark">
                @if(!empty($bot['icon']))
                    <i class="{{ $bot['icon'] }}"></i>
                @endif
            </div>
            <div>
                <h3 class="h4">{{ $bot['title'] ?? '' }}</h3>
                <p class="mb-0">{{ $bot['description'] ?? '' }}</p>
            </div>
        </div>
    </article>
</section>
@endsection
