@extends('layouts.public.mahasiswa')

@section('title', 'Pengumuman - UFO')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/mahasiswa-pengumuman.css') }}">
@endpush

@section('content')
@php
    $ui = $pageContent ?? [];
    $detailUrlTemplate = route('mahasiswa.pengumuman.show', ['id' => '__ANN_ID__']);
@endphp
<section class="figma-page-container figma-org-page py-3 figma-ann-page" aria-label="Halaman pengumuman">
    <header class="figma-page-header">
        <h1>{{ $ui['title'] ?? '' }}</h1>
        <p>{{ $ui['subtitle'] ?? '' }}</p>
    </header>

    <div class="figma-search">
        <i class="bi bi-search"></i>
        <input type="text" id="ann-search" placeholder="{{ $ui['search_placeholder'] ?? '' }}">
    </div>

    <div class="figma-chip-row" id="ann-categories">
        @foreach($categories as $index => $category)
            <button type="button" class="figma-chip {{ $index === 0 ? 'is-active' : '' }}" data-ann-category="{{ $category }}">{{ $category }}</button>
        @endforeach
    </div>

    <section class="figma-ann-priority" id="ann-priority-section">
        <h2>{{ $ui['priority_title'] ?? '' }}</h2>
        <div class="d-grid gap-2" id="ann-priority-grid"></div>
    </section>

    <section class="figma-ann-all">
        <h2>{{ $ui['all_title'] ?? '' }}</h2>
        <p class="figma-muted mb-3"><span id="ann-count">0</span> {{ $ui['count_suffix'] ?? '' }}</p>
        <div class="d-grid gap-2" id="ann-grid"></div>
    </section>

    <div class="figma-empty-state d-none" id="ann-empty">
        {{ $ui['empty_message'] ?? '' }}
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    var announcements = @json($announcements);
    var allCategory = @json($categories[0] ?? '');
    var detailUrlTemplate = @json($detailUrlTemplate);

    var searchInput = document.getElementById('ann-search');
    var categoryButtons = Array.from(document.querySelectorAll('[data-ann-category]'));

    var prioritySection = document.getElementById('ann-priority-section');
    var priorityGrid = document.getElementById('ann-priority-grid');
    var listGrid = document.getElementById('ann-grid');
    var count = document.getElementById('ann-count');
    var empty = document.getElementById('ann-empty');

    var currentCategory = allCategory;

    function detailUrl(id) {
        return detailUrlTemplate.replace('__ANN_ID__', String(id));
    }

    function renderPriority(item) {
        return `
            <a href="${detailUrl(item.id)}" class="figma-card figma-ann-priority-item">
                <img src="${item.image}" alt="${item.title}">
                <div>
                    <h3>${item.title}</h3>
                    <p>${item.summary}</p>
                    <div class="figma-ann-meta">
                        <span><i class="bi bi-building"></i> ${item.source}</span>
                        <span><i class="bi bi-calendar-event"></i> ${item.date}</span>
                    </div>
                </div>
                <i class="bi bi-chevron-right figma-ann-arrow"></i>
            </a>
        `;
    }

    function renderItem(item) {
        return `
            <a href="${detailUrl(item.id)}" class="figma-card figma-ann-item">
                <img src="${item.image}" alt="${item.title}">
                <div>
                    <span class="figma-ann-badge">${item.category}</span>
                    <h3>${item.title}</h3>
                    <p>${item.summary}</p>
                    <div class="figma-ann-meta">
                        <span><i class="bi bi-building"></i> ${item.source}</span>
                        <span><i class="bi bi-calendar-event"></i> ${item.date}</span>
                    </div>
                </div>
                <i class="bi bi-chevron-right figma-ann-arrow"></i>
            </a>
        `;
    }

    function render() {
        var query = (searchInput.value || '').trim().toLowerCase();

        var filtered = announcements.filter(function (item) {
            var matchSearch = (item.title || '').toLowerCase().includes(query) ||
                (item.source || '').toLowerCase().includes(query);
            var matchCategory = currentCategory === allCategory || item.category === currentCategory;
            return matchSearch && matchCategory;
        });

        var highPriority = filtered.filter(function (item) { return item.priority === 'high'; });
        var others = filtered.filter(function (item) { return item.priority !== 'high'; });

        priorityGrid.innerHTML = highPriority.map(renderPriority).join('');
        listGrid.innerHTML = others.map(renderItem).join('');
        count.textContent = String(filtered.length);

        prioritySection.classList.toggle('d-none', highPriority.length === 0);
        empty.classList.toggle('d-none', filtered.length > 0);
    }

    categoryButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            currentCategory = button.getAttribute('data-ann-category') || allCategory;
            categoryButtons.forEach(function (item) { item.classList.remove('is-active'); });
            button.classList.add('is-active');
            render();
        });
    });

    searchInput.addEventListener('input', render);
    render();
})();

</script>
@endpush
