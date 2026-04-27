@extends('layouts.public.mahasiswa')

@section('title', ($event['name'] ?? '') . ' - UFO')

@section('content')
@php
    $ui = $pageContent ?? [];
@endphp
<section class="figma-article py-3">
    <a href="{{ route('mahasiswa.organisasi.show', ['id' => $org['id'] ?? 0]) }}" class="figma-link-back">
        <i class="bi bi-arrow-left"></i>
        {{ $ui['back_label'] ?? '' }}
    </a>

    <article class="figma-article-cover">
        <img id="org-event-cover" src="{{ $event['images'][0] ?? '' }}" alt="{{ $event['name'] ?? '' }}">

        <div class="figma-article-cover-content">
            <span class="figma-badge-danger">{{ $ui['badge'] ?? '' }}</span>
            <h1>{{ $event['name'] ?? '' }}</h1>
            <div class="figma-article-meta">
                <span><i class="bi bi-calendar-event"></i> {{ $event['full_date'] ?? '' }}</span>
                <span><i class="bi bi-people"></i> {{ $event['organizer'] ?? '' }}</span>
            </div>
        </div>
    </article>

    <article class="figma-article-content">
        <h2>{{ $ui['about_title'] ?? '' }}</h2>
        <p>{{ $event['description'] ?? '' }}</p>

        <h2 class="mt-4">{{ $ui['activities_title'] ?? '' }}</h2>
        <div class="figma-step-list">
            @foreach(($event['activities'] ?? []) as $index => $activity)
                <div class="figma-step-item">
                    <span class="figma-step-number">{{ $index + 1 }}</span>
                    <p class="mb-0">{{ $activity }}</p>
                </div>
            @endforeach
        </div>

        <div class="figma-highlight-box mt-4">
            <h3>{{ $ui['highlight_title'] ?? '' }}</h3>
            <p class="mb-0">{{ $event['highlights'] ?? '' }}</p>
        </div>

        <h2 class="mt-4">{{ $ui['gallery_title'] ?? '' }}</h2>
        <div class="figma-gallery-grid" id="org-event-gallery">
            @foreach(($event['images'] ?? []) as $index => $image)
                <button type="button" class="figma-gallery-item {{ $index === 0 ? 'is-active' : '' }}" data-org-event-image="{{ $image }}">
                    <img src="{{ $image }}" alt="{{ $ui['gallery_alt_prefix'] ?? '' }} {{ $index + 1 }}">
                </button>
            @endforeach
        </div>
    </article>

    <div class="text-center mt-3">
        <a href="{{ route('mahasiswa.organisasi.show', ['id' => $org['id'] ?? 0]) }}" class="figma-btn-primary">{{ $ui['back_to_org_button'] ?? '' }}</a>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    var cover = document.getElementById('org-event-cover');
    var buttons = Array.from(document.querySelectorAll('[data-org-event-image]'));

    if (!cover || buttons.length === 0) {
        return;
    }

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            var image = button.getAttribute('data-org-event-image');
            if (!image) {
                return;
            }

            cover.src = image;
            buttons.forEach(function (item) { item.classList.remove('is-active'); });
            button.classList.add('is-active');
        });
    });
})();
</script>
@endpush
