@extends('layouts.public.mahasiswa')

@section('title', 'Lost and Found - UFO')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/mahasiswa-lost-found.css') }}">
@endpush

@section('content')
@php
    $ui = $pageContent ?? [];
    $categories = is_array($categories ?? null) ? $categories : [];
    $urgentItems = is_array($urgentItems ?? null)
        ? $urgentItems
        : (is_array($urgent_items ?? null) ? $urgent_items : []);
    $items = is_array($items ?? null) ? $items : [];
    $allCategory = $categories[0] ?? '';
@endphp
<section class="figma-page-container figma-org-page py-3 figma-lf-page" aria-label="Halaman lost and found">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(count($urgentItems) > 0)
        <section class="figma-lf-urgent" aria-label="Barang penting hilang">
            <h2><i class="bi bi-exclamation-triangle"></i> {{ $ui['urgent_title'] ?? '' }}</h2>

            <div class="figma-lf-slider" data-lf-slider>
                <div class="figma-lf-slider-track" data-lf-slider-track>
                    @foreach(array_slice($urgentItems, 0, 5) as $urgent)
                        <div class="figma-lf-slide">
                            <article class="figma-lf-urgent-card">
                                <img src="{{ $urgent['image'] ?? '' }}" alt="{{ $urgent['name'] ?? '' }}">
                                <div>
                                    <h4>{{ $urgent['name'] ?? '' }}</h4>
                                    <p>{{ $ui['urgent_subtitle'] ?? '' }}</p>
                                </div>
                                <button type="button">{{ $ui['urgent_contact_button'] ?? '' }}</button>
                            </article>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="figma-lf-slider-nav prev" data-lf-slider-prev aria-label="Slide sebelumnya">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button type="button" class="figma-lf-slider-nav next" data-lf-slider-next aria-label="Slide berikutnya">
                    <i class="bi bi-chevron-right"></i>
                </button>

                <div class="figma-lf-slider-dots" data-lf-slider-dots>
                    @foreach(array_slice($urgentItems, 0, 5) as $index => $urgent)
                        <button type="button" class="figma-lf-slider-dot {{ $index === 0 ? 'is-active' : '' }}" data-lf-slider-dot="{{ $index }}" aria-label="Pindah ke slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2">
        <header class="figma-page-header mb-0">
            <h1>{{ $ui['main_title'] ?? '' }}</h1>
            <p>{{ $ui['main_subtitle'] ?? '' }}</p>
        </header>

        <button type="button" class="figma-btn-primary" data-bs-toggle="modal" data-bs-target="#lostFoundFormModal">
            <i class="bi bi-plus-lg"></i>
            {{ $ui['report_button'] ?? '' }}
        </button>
    </div>

    <div class="figma-chip-row figma-lf-tabs" id="lf-tabs">
        <button type="button" class="figma-chip is-active" data-lf-tab="lost">{{ $ui['tab_lost'] ?? '' }}</button>
        <button type="button" class="figma-chip" data-lf-tab="found">{{ $ui['tab_found'] ?? '' }}</button>
    </div>

    <div class="figma-search">
        <i class="bi bi-search"></i>
        <input type="text" id="lf-search" placeholder="{{ $ui['search_placeholder'] ?? '' }}">
    </div>

    <div class="figma-chip-row figma-lf-categories" id="lf-categories">
        @foreach($categories as $index => $category)
            <button type="button" class="figma-chip {{ $index === 0 ? 'is-active' : '' }}" data-lf-category="{{ $category }}">{{ $category }}</button>
        @endforeach
    </div>

    <p class="figma-muted mb-3"><span id="lf-count">0</span> {{ $ui['count_suffix'] ?? '' }}</p>

    <div class="figma-grid-3 figma-lf-grid" id="lf-grid"></div>

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
                        <label>{{ $ui['form_type_label'] ?? '' }} <span class="figma-required">*</span></label>
                        <div class="figma-lf-form-type-group" role="tablist" aria-label="{{ $ui['form_type_label'] ?? '' }}">
                            <button type="button" class="figma-lf-form-type is-active" data-lf-form-type="lost">{{ $ui['form_type_lost'] ?? '' }}</button>
                            <button type="button" class="figma-lf-form-type" data-lf-form-type="found">{{ $ui['form_type_found'] ?? '' }}</button>
                        </div>
                        <input id="lf-type" type="hidden" name="type" value="lost">
                    </div>

                    <div>
                        <label for="lf-image">{{ $ui['form_image_label'] ?? '' }} <span class="figma-required">*</span></label>
                        <label for="lf-image" class="figma-lf-upload-zone">
                            <i class="bi bi-upload"></i>
                            <strong>{{ $ui['form_image_hint_title'] ?? '' }}</strong>
                            <small>{{ $ui['form_image_hint_subtitle'] ?? '' }}</small>
                        </label>
                        <input id="lf-image" type="file" name="image" accept="image/png,image/jpeg" required hidden>
                    </div>

                    <div>
                        <label for="lf-name">{{ $ui['form_name_label'] ?? '' }} <span class="figma-required">*</span></label>
                        <input id="lf-name" type="text" name="name" placeholder="{{ $ui['form_name_placeholder'] ?? '' }}" required>
                    </div>

                    <div>
                        <label for="lf-reporter-name">Nama Pelapor <span class="figma-required">*</span></label>
                        <input id="lf-reporter-name" type="text" name="reporter_name" placeholder="Contoh: John Doe" required>
                    </div>

                    <div>
                        <label for="lf-category">{{ $ui['form_category_label'] ?? '' }} <span class="figma-required">*</span></label>
                        <select id="lf-category" name="category" required>
                            @foreach($categories as $category)
                                @if($category !== $allCategory)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="lf-location">{{ $ui['form_location_label'] ?? '' }} <span class="figma-required">*</span></label>
                        <input id="lf-location" type="text" name="location" placeholder="{{ $ui['form_location_placeholder'] ?? '' }}" required>
                    </div>

                    <div>
                        <label for="lf-contact">{{ $ui['form_contact_label'] ?? '' }} <span class="figma-required">*</span></label>
                        <input id="lf-contact" type="text" name="contact" placeholder="{{ $ui['form_contact_placeholder'] ?? '' }}" inputmode="numeric" required>
                    </div>

                    <div>
                        <label for="lf-notes">{{ $ui['form_notes_label'] ?? '' }}</label>
                        <textarea id="lf-notes" name="notes" rows="4" placeholder="{{ $ui['form_notes_placeholder'] ?? '' }}"></textarea>
                    </div>

                    <small class="figma-required-note">* Wajib diisi</small>
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
        var reporterLabel = ui.reporter_prefix || 'Pelapor';
        var reporterValue = (item.reporter || '').trim() || 'Tidak dicantumkan';
        var contactRaw = (item.contact || '').trim();
        var waDigits = contactRaw.replace(/\D/g, '');
        var hasEmailContact = contactRaw.includes('@');

        if (waDigits.startsWith('0')) {
            waDigits = '62' + waDigits.slice(1);
        } else if (!waDigits.startsWith('62') && waDigits.startsWith('8')) {
            waDigits = '62' + waDigits;
        }

        var imageSrc = (item.image || '').trim();
        var cta = '';
        if (waDigits.length >= 10) {
            cta = `<a href="https://wa.me/${waDigits}" target="_blank" rel="noopener noreferrer" class="figma-btn-primary mt-2">${ui.contact_button || ''}</a>`;
        } else if (hasEmailContact) {
            cta = `<a href="mailto:${contactRaw}" class="figma-btn-primary mt-2">${ui.contact_button || ''}</a>`;
        } else {
            cta = `<button type="button" class="figma-btn-primary mt-2" data-lf-contact-missing="true" data-item-name="${String(item.name || '').replace(/"/g, '&quot;')}">${ui.contact_button || ''}</button>`;
        }

        return `
            <article class="figma-card figma-lf-item-card">
                <div class="figma-lf-item-image">
                    <img src="${imageSrc}" alt="${item.name}" loading="lazy" onerror="this.classList.add('is-broken'); this.nextElementSibling.classList.remove('d-none');">
                    <div class="figma-lf-image-fallback d-none" aria-hidden="true">
                        <i class="bi bi-image"></i>
                        <span>Foto tidak tersedia</span>
                    </div>
                    <span class="figma-lf-status ${statusClass(item.status)}">${item.status}</span>
                </div>
                <div class="figma-lf-item-body">
                    <h3>${item.name}</h3>
                    <div class="figma-meta-row"><i class="bi bi-geo-alt"></i> ${item.location}</div>
                    <div class="figma-meta-row"><i class="bi bi-calendar-event"></i> ${item.date}</div>
                    <div class="figma-meta-row"><i class="bi bi-person"></i> ${reporterLabel}: ${reporterValue}</div>
                    ${cta}
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

    grid.addEventListener('click', function (event) {
        var button = event.target.closest('[data-lf-contact-missing="true"]');
        if (!button) return;

        var itemName = button.getAttribute('data-item-name') || 'item ini';
        window.alert('Kontak untuk ' + itemName + ' belum tersedia. Silakan tunggu update dari pelapor.');
    });

    render();
})();

(function () {
    var slider = document.querySelector('[data-lf-slider]');
    if (!slider) return;

    var track = slider.querySelector('[data-lf-slider-track]');
    var slides = Array.from(slider.querySelectorAll('.figma-lf-slide'));
    var dots = Array.from(slider.querySelectorAll('[data-lf-slider-dot]'));
    var prev = slider.querySelector('[data-lf-slider-prev]');
    var next = slider.querySelector('[data-lf-slider-next]');

    var count = slides.length;
    var current = 0;
    var timer = null;

    if (count <= 1) {
        if (prev) prev.classList.add('d-none');
        if (next) next.classList.add('d-none');
    }

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

    function start() {
        if (count <= 1) return;
        stop();
        timer = window.setInterval(function () {
            move(1);
        }, 4200);
    }

    function stop() {
        if (!timer) return;
        window.clearInterval(timer);
        timer = null;
    }

    if (prev) prev.addEventListener('click', function () {
        move(-1);
        start();
    });

    if (next) next.addEventListener('click', function () {
        move(1);
        start();
    });

    dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
            current = Number(dot.getAttribute('data-lf-slider-dot') || 0);
            update();
            start();
        });
    });

    slider.addEventListener('mouseenter', stop);
    slider.addEventListener('mouseleave', start);

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
