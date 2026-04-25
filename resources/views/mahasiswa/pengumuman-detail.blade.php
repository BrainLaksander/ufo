@extends('layouts.public.mahasiswa')

@section('title', ($announcement['title'] ?? 'Detail Pengumuman') . ' - UFO')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/mahasiswa-pengumuman.css') }}">
@endpush

@section('content')
@php
    $ui = $pageContent ?? [];
@endphp
<section class="figma-page-container py-3 figma-pengumuman-detail-page">
    <a href="{{ route('mahasiswa.pengumuman') }}" class="figma-link-back">
        <i class="bi bi-arrow-left"></i>
        {{ $ui['back_to_list'] ?? '' }}
    </a>

    <article class="figma-ann-detail-cover">
        <img src="{{ $announcement['image'] ?? '' }}" alt="{{ $announcement['title'] ?? 'Pengumuman' }}">
    </article>

    <article class="figma-card p-3 figma-ann-detail-header mb-3">
        <span class="figma-ann-badge">{{ $announcement['category'] ?? '-' }}</span>
        <h1 class="h2 mb-3">{{ $announcement['title'] ?? '-' }}</h1>

        <div class="figma-ann-meta mb-3">
            <span><i class="bi bi-building"></i> {{ $announcement['source'] ?? '-' }}</span>
            <span><i class="bi bi-calendar-event"></i> {{ $announcement['date'] ?? '-' }}</span>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="figma-btn-primary" id="share-announcement">
                <i class="bi bi-share"></i>
                {{ $ui['share_button'] ?? '' }}
            </button>
            <button type="button" class="figma-btn-secondary" id="download-announcement">
                <i class="bi bi-download"></i>
                {{ $ui['download_button'] ?? '' }}
            </button>
        </div>
    </article>

    <article class="figma-card p-3 mb-3">
        <div class="figma-ann-content">
            {!! $announcement['content_html'] ?? '' !!}
        </div>
    </article>

    <article class="figma-highlight text-center">
        <p class="mb-2">{{ $ui['contact_prompt'] ?? '' }}</p>
        <button type="button" class="figma-btn-secondary">{{ $ui['contact_button_prefix'] ?? '' }} {{ $announcement['source'] ?? '' }}</button>
    </article>
</section>
@endsection

@push('scripts')
<script>
(function () {
    var ui = @json($ui);
    var shareButton = document.getElementById('share-announcement');
    var downloadButton = document.getElementById('download-announcement');

    if (shareButton) {
        shareButton.addEventListener('click', function () {
            if (navigator.share) {
                navigator.share({
                    title: @json($announcement['title'] ?? 'Pengumuman'),
                    text: @json($announcement['summary'] ?? ''),
                    url: window.location.href
                }).catch(function () {
                    window.alert(ui.share_fallback_unavailable || '');
                });
            } else {
                navigator.clipboard.writeText(window.location.href).then(function () {
                    window.alert(ui.share_copy_success || '');
                }).catch(function () {
                    window.alert(ui.share_copy_failed || '');
                });
            }
        });
    }

    if (downloadButton) {
        downloadButton.addEventListener('click', function () {
            window.print();
        });
    }
})();
</script>
@endpush
