@extends('layouts.public.mahasiswa')

@section('title', 'Event - UFO')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/mahasiswa-event.css') }}">
@endpush

@section('content')
@php
    $ui = $pageContent ?? [];
@endphp
<section class="figma-page-container figma-event-page py-3" aria-label="Halaman event">
    <header class="figma-page-header">
        <h1>{{ $ui['title'] ?? '' }}</h1>
        <p>{{ $ui['subtitle'] ?? '' }}</p>
    </header>

    <div class="figma-search">
        <i class="bi bi-search"></i>
        <input type="text" id="event-search" placeholder="{{ $ui['search_placeholder'] ?? '' }}">
    </div>

    <div class="figma-chip-row" id="event-categories">
        @foreach($categories as $index => $category)
            <button type="button" class="figma-chip {{ $index === 0 ? 'is-active' : '' }}" data-event-category="{{ $category }}">{{ $category }}</button>
        @endforeach
    </div>

    <p class="figma-muted mb-3"><span id="event-count">0</span> {{ $ui['count_suffix'] ?? '' }}</p>

    <div class="figma-grid-3" id="event-grid"></div>

    <div class="figma-empty-state d-none" id="event-empty">
        <h3 class="h5 mb-2">{{ $ui['empty_title'] ?? '' }}</h3>
        <p id="event-empty-message" class="mb-3">{{ $ui['empty_message'] ?? '' }}</p>
        <a href="{{ route('mahasiswa.event') }}" class="figma-btn-primary d-none" id="event-show-all">{{ $ui['show_all_label'] ?? '' }}</a>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    var events = @json($events);
    var orgFilter = @json($orgFilter);
    var organizations = @json($organizations);
    var ui = @json($ui);
    var allCategory = @json($categories[0] ?? '');
    var eventDetailUrlTemplate = @json(route('mahasiswa.event.show', ['id' => '__EVENT_ID__']));

    var searchInput = document.getElementById('event-search');
    var categoryButtons = Array.from(document.querySelectorAll('[data-event-category]'));
    var grid = document.getElementById('event-grid');
    var count = document.getElementById('event-count');
    var empty = document.getElementById('event-empty');
    var emptyMessage = document.getElementById('event-empty-message');
    var showAll = document.getElementById('event-show-all');

    var currentCategory = allCategory;

    function byOrgName(orgId) {
        var found = organizations.find(function (item) {
            return Number(item.id) === Number(orgId);
        });

        return found ? found.name : null;
    }

    function renderCard(event) {
        var eventDetailUrl = eventDetailUrlTemplate.replace('__EVENT_ID__', String(event.id));

        return `
            <article class="figma-card figma-event-card">
                <div class="figma-event-card-image">
                    <img src="${event.poster}" alt="${event.title}">
                    <span class="figma-event-category">${event.category}</span>
                </div>
                <div class="figma-event-card-body">
                    <h3>${event.title}</h3>
                    <div class="figma-meta-row"><i class="bi bi-people"></i> ${event.organizer}</div>
                    <div class="figma-meta-row"><i class="bi bi-calendar-event"></i> ${event.date} | ${event.time}</div>
                    <div class="figma-meta-row"><i class="bi bi-geo-alt"></i> ${event.location}</div>
                    <p class="figma-muted mb-0">${event.participants} peserta terdaftar</p>
                    <a href="${eventDetailUrl}" class="figma-btn-primary mt-2">${ui.detail_button_label || ''}</a>
                </div>
            </article>
        `;
    }

    function render() {
        var query = (searchInput.value || '').trim().toLowerCase();

        var filtered = events.filter(function (event) {
            var matchSearch = (event.title || '').toLowerCase().includes(query) ||
                (event.organizer || '').toLowerCase().includes(query);
            var matchCategory = currentCategory === allCategory || event.category === currentCategory;
            var matchOrg = !orgFilter || String(event.organizer_id) === String(orgFilter);

            return matchSearch && matchCategory && matchOrg;
        });

        grid.innerHTML = filtered.map(renderCard).join('');
        count.textContent = String(filtered.length);

        var hasResult = filtered.length > 0;
        empty.classList.toggle('d-none', hasResult);

        if (!hasResult) {
            if (orgFilter) {
                var orgName = byOrgName(orgFilter);
                if (orgName) {
                    emptyMessage.textContent = orgName + ' belum memiliki event yang sesuai filter saat ini.';
                    showAll.classList.remove('d-none');
                } else {
                    emptyMessage.textContent = ui.empty_title || '';
                }
            } else {
                emptyMessage.textContent = ui.empty_message || '';
                showAll.classList.add('d-none');
            }
        }
    }

    categoryButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            currentCategory = button.getAttribute('data-event-category') || allCategory;
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
