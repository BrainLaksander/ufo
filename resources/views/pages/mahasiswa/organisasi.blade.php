@extends('layouts.public.mahasiswa')

@section('title', (($pageContent['title'] ?? '') !== '' ? $pageContent['title'] : 'Organisasi') . ' - UFO')

@section('content')
@php
    $ui = $pageContent ?? [];
    $orgDetailUrlTemplate = route('mahasiswa.organisasi.show', ['id' => '__ORG_ID__']);
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
        @php
            $categoryFilters = ['Semua', 'BEM', 'Choir', 'Ministries', 'Creative Club', 'Umum', 'Ikatan Daerah'];
        @endphp
        @foreach($categoryFilters as $index => $category)
            <button type="button" class="figma-chip {{ $index === 0 ? 'is-active' : '' }}" data-org-category="{{ $category }}">{{ $category }}</button>
        @endforeach
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <p class="figma-muted mb-0"><span id="org-count">0</span> {{ $ui['count_suffix'] ?? '' }}</p>

        <select class="figma-select" id="org-status">
            <option value="aktif">Aktif</option>
            <option value="tidak-aktif">Tidak aktif</option>
        </select>
    </div>

    <div class="figma-grid-3" id="org-grid"></div>

    <div class="d-flex justify-content-end mt-3">
        <nav class="figma-pagination" aria-label="Navigasi halaman organisasi">
            <ul id="org-pagination" class="figma-pagination-list"></ul>
        </nav>
    </div>

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
    var orgDetailUrlTemplate = @json($orgDetailUrlTemplate);

    var searchInput = document.getElementById('org-search');
    var statusSelect = document.getElementById('org-status');
    var categoryButtons = Array.from(document.querySelectorAll('[data-org-category]'));
    var grid = document.getElementById('org-grid');
    var pagination = document.getElementById('org-pagination');
    var count = document.getElementById('org-count');
    var empty = document.getElementById('org-empty');

    var currentCategory = categoryButtons.length > 0
        ? String(categoryButtons[0].getAttribute('data-org-category') || '')
        : '';
    var currentStatus = statusSelect ? statusSelect.value : 'aktif';
    var currentPage = 1;
    var itemsPerPage = 9;

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
        var detailUrl = orgDetailUrlTemplate.replace('__ORG_ID__', String(org.id));

        return `
            <article class="figma-card figma-org-card" data-org-id="${org.id}">
                <div class="figma-org-logo"><i class="bi ${categoryIcon(org)}"></i></div>
                <h3>${org.name}</h3>
                <p>${org.tagline || ''}</p>
                <a href="${detailUrl}" class="figma-btn-primary mt-1">${ui.detail_button || ''}</a>
            </article>
        `;
    }

    function normalizeText(value) {
        return String(value || '').trim().toLowerCase();
    }

    function isCategoryMatch(org, category) {
        var orgCategory = normalizeText(org.category);
        var orgName = normalizeText(org.name);

        if (category === 'Semua') {
            return true;
        }
        if (category === 'BEM') {
            return orgCategory.includes('bem') || orgName.includes('bem');
        }
        if (category === 'Choir') {
            return orgCategory.includes('choir') || orgName.includes('choir') || orgCategory.includes('paduan') || orgName.includes('paduan suara');
        }
        if (category === 'Ministries') {
            return orgCategory.includes('ministry') || orgCategory.includes('ministries') || orgCategory.includes('kerohanian') || orgName.includes('kerohanian') || orgName.includes('rohani');
        }
        if (category === 'Creative Club') {
            return orgCategory.includes('creative') || orgCategory.includes('kreatif') || orgCategory.includes('club') || orgCategory.includes('teknologi') || orgName.includes('creative') || orgName.includes('kreatif') || orgName.includes('teknologi');
        }
        if (category === 'Umum') {
            var isKnown = orgCategory.includes('bem') || orgCategory.includes('choir') || orgCategory.includes('ministry') || orgCategory.includes('ministries') || orgCategory.includes('kreatif') || orgCategory.includes('creative') || orgCategory.includes('kedaerahan') || orgCategory.includes('ikatan daerah') || orgName.includes('bem') || orgName.includes('choir') || orgName.includes('paduan suara') || orgName.includes('kerohanian') || orgName.includes('rohani') || orgName.includes('kreatif') || orgName.includes('creative') || orgName.includes('teknologi') || orgName.includes('kedaerahan') || orgName.includes('ikatan daerah');
            return !isKnown;
        }
        if (category === 'Ikatan Daerah') {
            return orgCategory.includes('ikatan daerah') || orgCategory.includes('kedaerahan') || orgCategory.includes('daerah') || orgName.includes('ikatan daerah') || orgName.includes('kedaerahan') || orgName.includes('daerah');
        }

        return false;
    }

    function isStatusMatch(org, status) {
        var orgStatus = normalizeText(org.status);
        var active = orgStatus === 'active' || orgStatus === 'aktif';
        if (status === 'aktif') {
            return active;
        }
        if (status === 'tidak-aktif') {
            return !active;
        }
        return true;
    }

    function applyPagination(items) {
        var total = items.length;
        var pages = Math.max(1, Math.ceil(total / itemsPerPage));
        currentPage = Math.min(Math.max(1, currentPage), pages);
        var start = (currentPage - 1) * itemsPerPage;
        var end = start + itemsPerPage;
        var pagedItems = items.slice(start, end);

        if (pagination) {
            pagination.innerHTML = '';
            for (var i = 1; i <= pages; i += 1) {
                var item = document.createElement('li');
                item.className = 'figma-pagination-item' + (i === currentPage ? ' is-active' : '');
                item.innerHTML = '<button type="button" class="figma-pagination-button" data-page="' + i + '">' + i + '</button>';
                pagination.appendChild(item);
            }

            Array.from(pagination.querySelectorAll('[data-page]')).forEach(function (button) {
                button.addEventListener('click', function () {
                    currentPage = Number(button.getAttribute('data-page') || 1);
                    render();
                });
            });
        }

        return pagedItems;
    }

    function render() {
        var query = normalizeText(searchInput.value || '');

        var filtered = organizations.filter(function (org) {
            var matchesSearch = normalizeText(org.name).includes(query);
            var matchesCategory = isCategoryMatch(org, currentCategory);
            var matchesStatus = isStatusMatch(org, currentStatus);
            return matchesSearch && matchesCategory && matchesStatus;
        });

        filtered.sort(function (a, b) {
            return normalizeText(a.name).localeCompare(normalizeText(b.name));
        });

        var paged = applyPagination(filtered);
        grid.innerHTML = paged.map(buildCard).join('');
        count.textContent = String(filtered.length);
        empty.classList.toggle('d-none', filtered.length > 0);
    }

    categoryButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            currentCategory = button.getAttribute('data-org-category') || '';
            currentPage = 1;
            categoryButtons.forEach(function (item) { item.classList.remove('is-active'); });
            button.classList.add('is-active');
            render();
        });
    });

    searchInput.addEventListener('input', function () {
        currentPage = 1;
        render();
    });
    statusSelect.addEventListener('change', function () {
        currentStatus = statusSelect.value;
        currentPage = 1;
        render();
    });

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
