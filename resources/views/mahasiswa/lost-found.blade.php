@extends('layouts.public.mahasiswa')

@section('title', 'Lost and Found - UFO')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/mahasiswa-lost-found.css') }}">
@endpush

@section('content')
@php
    $ui = $pageContent ?? [];
    $allCategory = $categories[0] ?? '';
@endphp
<section class="figma-page-container py-3 figma-lost-found-page">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <section class="figma-lf-urgent">
        <h2><i class="bi bi-exclamation-triangle"></i> {{ $ui['urgent_title'] ?? '' }}</h2>

        <div class="figma-lf-slider" data-lf-slider>
            <div class="figma-lf-slider-track" data-lf-slider-track>
                @foreach($urgentItems as $urgent)
                    <div class="figma-lf-slide">
                        <article class="figma-lf-urgent-card">
                            <img src="{{ $urgent['image'] ?? '' }}" alt="{{ $urgent['name'] ?? 'Barang penting' }}">
                            <div>
                                <h4>{{ $urgent['name'] ?? '-' }}</h4>
                                <p>{{ $ui['urgent_subtitle'] ?? '' }}</p>
                            </div>
                            <button type="button">{{ $ui['urgent_contact_button'] ?? '' }}</button>
                        </article>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center gap-2 mt-3" data-lf-slider-dots>
                @foreach($urgentItems as $index => $urgent)
                    <button type="button" class="figma-org-carousel-dot {{ $index === 0 ? 'is-active' : '' }}" data-lf-slider-dot="{{ $index }}"></button>
                @endforeach
            </div>
        </div>
    </section>

    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
        <header class="figma-page-header mb-0">
            <h1>{{ $ui['main_title'] ?? '' }}</h1>
            <p>{{ $ui['main_subtitle'] ?? '' }}</p>
        </header>

        <button type="button" class="figma-btn-primary" data-bs-toggle="modal" data-bs-target="#lostFoundFormModal">
            <i class="bi bi-plus-lg"></i>
            {{ $ui['report_button'] ?? '' }}
        </button>
    </div>

    <div class="figma-chip-row" id="lf-tabs">
        <button type="button" class="figma-chip is-active" data-lf-tab="lost">{{ $ui['tab_lost'] ?? '' }}</button>
        <button type="button" class="figma-chip" data-lf-tab="found">{{ $ui['tab_found'] ?? '' }}</button>
    </div>

    <div class="figma-search">
        <i class="bi bi-search"></i>
        <input type="text" id="lf-search" placeholder="{{ $ui['search_placeholder'] ?? '' }}">
    </div>

    <div class="figma-chip-row" id="lf-categories">
        @foreach($categories as $index => $category)
            <button type="button" class="figma-chip {{ $index === 0 ? 'is-active' : '' }}" data-lf-category="{{ $category }}">{{ $category }}</button>
        @endforeach
    </div>

    <p class="figma-muted mb-3"><span id="lf-count">0</span> {{ $ui['count_suffix'] ?? '' }}</p>

    <div class="figma-grid-3" id="lf-grid"></div>

    <div class="figma-empty-state d-none" id="lf-empty">
        {{ $ui['empty_message'] ?? '' }}
    </div>
</section>

<div class="modal fade" id="lostFoundFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header ufo-modal-header-primary">
                <h5 class="modal-title">{{ $ui['modal_title'] ?? '' }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <form id="lost-found-form" class="figma-lf-modal-grid" method="POST" action="{{ route('mahasiswa.lost-found.report') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="figma-lf-form-section">
                        <label>{{ $ui['form_type_label'] ?? '' }}</label>
                        <div class="figma-lf-form-type-group" role="tablist" aria-label="{{ $ui['form_type_label'] ?? '' }}">
                            <button type="button" class="figma-lf-form-type is-active" data-lf-form-type="lost">{{ $ui['form_type_lost'] ?? '' }}</button>
                            <button type="button" class="figma-lf-form-type" data-lf-form-type="found">{{ $ui['form_type_found'] ?? '' }}</button>
                        </div>
                        <input id="lf-type" type="hidden" name="type" value="lost">
                    </div>

                    <div>
                        <label for="lf-image">{{ $ui['form_image_label'] ?? '' }}</label>
                        <label for="lf-image" class="figma-lf-upload-zone">
                            <i class="bi bi-upload"></i>
                            <strong>{{ $ui['form_image_hint_title'] ?? '' }}</strong>
                            <small>{{ $ui['form_image_hint_subtitle'] ?? '' }}</small>
                        </label>
                        <input id="lf-image" type="file" name="image" accept="image/png,image/jpeg" hidden>
                    </div>

                    <div>
                        <label for="lf-name">{{ $ui['form_name_label'] ?? '' }}</label>
                        <input id="lf-name" type="text" name="name" placeholder="{{ $ui['form_name_placeholder'] ?? '' }}" required>
                    </div>

                    <div>
                        <label for="lf-category">{{ $ui['form_category_label'] ?? '' }}</label>
                        <select id="lf-category" name="category">
                            @foreach($categories as $category)
                                @if($category !== $allCategory)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="lf-location">{{ $ui['form_location_label'] ?? '' }}</label>
                        <input id="lf-location" type="text" name="location" placeholder="{{ $ui['form_location_placeholder'] ?? '' }}" required>
                    </div>

                    <div>
                        <label for="lf-contact">{{ $ui['form_contact_label'] ?? '' }}</label>
                        <input id="lf-contact" type="text" name="contact" placeholder="{{ $ui['form_contact_placeholder'] ?? '' }}" required>
                    </div>

                    <div>
                        <label for="lf-notes">{{ $ui['form_notes_label'] ?? '' }}</label>
                        <textarea id="lf-notes" name="notes" rows="4" placeholder="{{ $ui['form_notes_placeholder'] ?? '' }}"></textarea>
                    </div>

                    <button type="submit" class="figma-btn-primary figma-lf-submit-btn">{{ $ui['modal_submit'] ?? '' }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var items = @json($items);
    var ui = @json($ui);
    var allCategory = @json($allCategory);

    var tabButtons = Array.from(document.querySelectorAll('[data-lf-tab]'));
    var categoryButtons = Array.from(document.querySelectorAll('[data-lf-category]'));
    var searchInput = document.getElementById('lf-search');
    var grid = document.getElementById('lf-grid');
    var count = document.getElementById('lf-count');
    var empty = document.getElementById('lf-empty');

    var currentTab = 'lost';
    var currentCategory = categoryButtons.length > 0
        ? String(categoryButtons[0].getAttribute('data-lf-category') || '')
        : allCategory;

    function statusClass(status) {
        if (status === 'Belum ditemukan') return 'is-danger';
        if (status === 'Selesai') return 'is-success';
        return 'is-warning';
    }

    function renderCard(item) {
        return `
            <article class="figma-card figma-lf-item-card">
                <div class="figma-lf-item-image">
                    <img src="${item.image}" alt="${item.name}">
                    <span class="figma-lf-status ${statusClass(item.status)}">${item.status}</span>
                </div>
                <div class="figma-lf-item-body">
                    <h3>${item.name}</h3>
                    <div class="figma-meta-row"><i class="bi bi-geo-alt"></i> ${item.location}</div>
                    <div class="figma-meta-row"><i class="bi bi-calendar-event"></i> ${item.date}</div>
                    <div class="figma-meta-row"><i class="bi bi-person"></i> ${ui.reporter_prefix || ''}: ${item.reporter}</div>
                    <button type="button" class="figma-btn-primary mt-2">${ui.contact_button || ''}</button>
                </div>
            </article>
        `;
    }

    function render() {
        var query = (searchInput.value || '').trim().toLowerCase();

        var filtered = items.filter(function (item) {
            var matchTab = item.type === currentTab;
            var matchCategory = currentCategory === allCategory || item.category === currentCategory;
            var matchQuery = (item.name || '').toLowerCase().includes(query);
            return matchTab && matchCategory && matchQuery;
        });

        grid.innerHTML = filtered.map(renderCard).join('');
        count.textContent = String(filtered.length);
        empty.classList.toggle('d-none', filtered.length > 0);
    }

    tabButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            currentTab = button.getAttribute('data-lf-tab') || 'lost';
            tabButtons.forEach(function (item) { item.classList.remove('is-active'); });
            button.classList.add('is-active');
            render();
        });
    });

    categoryButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            currentCategory = button.getAttribute('data-lf-category') || allCategory;
            categoryButtons.forEach(function (item) { item.classList.remove('is-active'); });
            button.classList.add('is-active');
            render();
        });
    });

    searchInput.addEventListener('input', render);
    render();
})();

(function () {
    var slider = document.querySelector('[data-lf-slider]');
    if (!slider) return;

    var track = slider.querySelector('[data-lf-slider-track]');
    var dots = Array.from(slider.querySelectorAll('[data-lf-slider-dot]'));
    var count = dots.length;
    var current = 0;
    var timer = null;

    function update() {
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        dots.forEach(function (dot, index) {
            dot.classList.toggle('is-active', index === current);
        });
    }

    function start() {
        if (count <= 1) return;
        stop();
        timer = window.setInterval(function () {
            current = (current + 1) % count;
            update();
        }, 4000);
    }

    function stop() {
        if (!timer) return;
        window.clearInterval(timer);
        timer = null;
    }

    dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            current = Number(dot.getAttribute('data-lf-slider-dot') || 0);
            update();
            start();
        });
    });

    update();
    start();
})();

(function () {
    var formTypeInput = document.getElementById('lf-type');
    var formTypeButtons = Array.from(document.querySelectorAll('[data-lf-form-type]'));

    formTypeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var value = button.getAttribute('data-lf-form-type') || 'lost';
            formTypeInput.value = value;
            formTypeButtons.forEach(function (item) { item.classList.remove('is-active'); });
            button.classList.add('is-active');
        });
    });
})();
</script>
@endpush
