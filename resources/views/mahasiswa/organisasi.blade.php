@extends('layouts.public.mahasiswa')

@section('title', 'UNKLAB Forum Organization')

@section('content')
@php
    $ui = $pageContent ?? [];
@endphp
<section class="figma-page-container figma-org-page py-3" aria-label="Halaman organisasi">
    <div class="figma-org-carousel" data-org-carousel>
        <div class="figma-org-carousel-track" data-org-carousel-track>
            @foreach($carouselImages as $image)
                <div class="figma-org-carousel-slide">
                    <img src="{{ $image }}" alt="Aktivitas organisasi">
                </div>
            @endforeach
        </div>

        <button type="button" class="figma-org-carousel-nav prev" data-org-carousel-prev aria-label="Slide sebelumnya">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button type="button" class="figma-org-carousel-nav next" data-org-carousel-next aria-label="Slide berikutnya">
            <i class="bi bi-chevron-right"></i>
        </button>

        <div class="figma-org-carousel-dots" data-org-carousel-dots>
            @foreach($carouselImages as $index => $image)
                <button type="button" class="figma-org-carousel-dot {{ $index === 0 ? 'is-active' : '' }}" data-org-carousel-dot="{{ $index }}" aria-label="Pindah ke slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
    </div>

    <div class="figma-search">
        <i class="bi bi-search"></i>
        <input type="text" id="org-search" placeholder="{{ $ui['search_placeholder'] ?? '' }}">
    </div>

    <div class="figma-chip-row" id="org-categories">
        @foreach($categories as $index => $category)
            <button type="button" class="figma-chip {{ $index === 0 ? 'is-active' : '' }}" data-org-category="{{ $category }}">{{ $category }}</button>
        @endforeach
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <p class="figma-muted mb-0"><span id="org-count">0</span> {{ $ui['count_suffix'] ?? '' }}</p>

        <select class="figma-select" id="org-sort">
            <option value="A-Z">{{ $ui['sort_az'] ?? '' }}</option>
            <option value="Z-A">{{ $ui['sort_za'] ?? '' }}</option>
            <option value="baru">{{ $ui['sort_newest'] ?? '' }}</option>
            <option value="aktif">{{ $ui['sort_active'] ?? '' }}</option>
        </select>
    </div>

    <div class="figma-grid-3" id="org-grid"></div>

    <div class="figma-empty-state d-none mt-3" id="org-empty">
        {{ $ui['empty_message'] ?? '' }}
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    var organizations = @json($organizations);
    var ui = @json($ui);

    var searchInput = document.getElementById('org-search');
    var sortSelect = document.getElementById('org-sort');
    var categoryButtons = Array.from(document.querySelectorAll('[data-org-category]'));
    var grid = document.getElementById('org-grid');
    var count = document.getElementById('org-count');
    var empty = document.getElementById('org-empty');

    var currentCategory = categoryButtons.length > 0
        ? String(categoryButtons[0].getAttribute('data-org-category') || '')
        : '';

    function logoText(value) {
        if (!value) return 'ORG';
        return String(value).toUpperCase();
    }

    function categoryIcon(org) {
        var category = String(org.category || '').toLowerCase();
        var name = String(org.name || '').toLowerCase();

        if (category.includes('bem') || name.includes('bem')) return 'bi-bank2';
        if (category.includes('choir') || name.includes('choir')) return 'bi-mic-fill';
        if (category.includes('creative') || name.includes('creative')) return 'bi-palette-fill';
        if (category.includes('ministry') || category.includes('ministries')) return 'bi-heart-pulse-fill';
        if (category.includes('ikatan daerah') || name.includes('ikatan') || name.includes('manado')) return 'bi-geo-alt-fill';
        if (name.includes('english')) return 'bi-chat-dots-fill';

        return 'bi-people-fill';
    }

    function buildCard(org) {
        return `
            <article class="figma-card figma-org-card" data-org-id="${org.id}">
                <div class="figma-org-logo"><i class="bi ${categoryIcon(org)}"></i></div>
                <h3>${org.name}</h3>
                <p>${org.tagline || ''}</p>
                <a href="/organisasi/${org.id}" class="figma-btn-primary mt-1">${ui.detail_button || ''}</a>
            </article>
        `;
    }

    function applySort(items) {
        var mode = sortSelect.value;
        var sorted = items.slice();

        if (mode === 'A-Z') {
            sorted.sort(function (a, b) { return a.name.localeCompare(b.name); });
        } else if (mode === 'Z-A') {
            sorted.sort(function (a, b) { return b.name.localeCompare(a.name); });
        } else if (mode === 'aktif') {
            sorted.sort(function (a, b) { return (b.active_members || 0) - (a.active_members || 0); });
        } else if (mode === 'baru') {
            sorted.sort(function (a, b) { return (b.id || 0) - (a.id || 0); });
        }

        return sorted;
    }

    function render() {
        var query = (searchInput.value || '').trim().toLowerCase();

        var filtered = organizations.filter(function (org) {
            var matchesSearch = (org.name || '').toLowerCase().includes(query);
            var matchesCategory = currentCategory === '' || org.category === currentCategory;
            return matchesSearch && matchesCategory;
        });

        filtered = applySort(filtered);

        grid.innerHTML = filtered.map(buildCard).join('');
        count.textContent = String(filtered.length);
        empty.classList.toggle('d-none', filtered.length > 0);
    }

    categoryButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            currentCategory = button.getAttribute('data-org-category') || '';
            categoryButtons.forEach(function (item) { item.classList.remove('is-active'); });
            button.classList.add('is-active');
            render();
        });
    });

    searchInput.addEventListener('input', render);
    sortSelect.addEventListener('change', render);

    render();
})();

(function () {
    var carousel = document.querySelector('[data-org-carousel]');
    if (!carousel) return;

    var track = carousel.querySelector('[data-org-carousel-track]');
    var dots = Array.from(carousel.querySelectorAll('[data-org-carousel-dot]'));
    var prev = carousel.querySelector('[data-org-carousel-prev]');
    var next = carousel.querySelector('[data-org-carousel-next]');

    var count = dots.length;

    if (count <= 1) {
        if (prev) prev.classList.add('d-none');
        if (next) next.classList.add('d-none');
    }
    var current = 0;
    var timer = null;

    function update() {
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        dots.forEach(function (dot, index) {
            dot.classList.toggle('is-active', index === current);
        });
    }

    function move(step) {
        if (count <= 1) return;
        current = (current + step + count) % count;
        update();
    }

    function startAutoplay() {
        if (count <= 1) return;
        stopAutoplay();
        timer = window.setInterval(function () { move(1); }, 4000);
    }

    function stopAutoplay() {
        if (timer) {
            window.clearInterval(timer);
            timer = null;
        }
    }

    if (prev) prev.addEventListener('click', function () { move(-1); startAutoplay(); });
    if (next) next.addEventListener('click', function () { move(1); startAutoplay(); });

    dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            var value = Number(dot.getAttribute('data-org-carousel-dot') || 0);
            current = value;
            update();
            startAutoplay();
        });
    });

    update();
    startAutoplay();
})();
</script>
@endpush
