@extends('layouts.app')

@section('title', 'Daftar Organisasi - UFO')
@section('bodyClass', 'ufo-org-browser-page')
@section('mainClass', 'ufo-org-browser-main')

@push('styles')
<style>
    .ufo-org-browser-page .ufo-shell-header,
    .ufo-org-browser-page .ufo-shell-footer {
        display: none;
    }

    .ufo-org-browser-main {
        margin: 0;
        max-width: 100%;
        padding: 0;
        background: #ebeaf2;
    }

    .ufo-org-app {
        min-height: 100vh;
        padding: 1.2rem;
    }

    .ufo-org-frame {
        max-width: 980px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 16px 28px rgba(24, 20, 38, 0.14);
    }

    .ufo-org-topbar {
        min-height: 66px;
        background: linear-gradient(135deg, #5b2c86 0%, #6e3fa0 100%);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.65rem 1rem;
        color: #fff;
    }

    .ufo-org-topbar-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .ufo-org-topbar-icon-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.18);
        color: #fff;
        font-size: 1.15rem;
    }

    .ufo-org-brand {
        display: flex;
        align-items: center;
        gap: 0.55rem;
    }

    .ufo-org-brand-mark {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #efe8f8;
        color: #5d2e89;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 700;
    }

    .ufo-org-brand-title {
        margin: 0;
        color: #ffffff;
        font-size: 1.35rem;
        font-weight: 700;
        line-height: 1.1;
    }

    .ufo-org-brand-subtitle {
        margin: 0;
        color: rgba(255, 255, 255, 0.88);
        font-size: 0.77rem;
        line-height: 1.1;
    }

    .ufo-org-topbar-right {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ufo-org-notif {
        position: relative;
    }

    .ufo-org-notif-dot {
        position: absolute;
        top: 2px;
        right: 2px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ff4b55;
        border: 1px solid #fff;
    }

    .ufo-org-body {
        background: #f2f2f7;
        padding: 0 0 1.2rem;
    }

    .ufo-org-hero {
        position: relative;
        height: 280px;
        background: #d9d9e0;
        overflow: hidden;
    }

    .ufo-org-hero-slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 0.35s ease;
        pointer-events: none;
    }

    .ufo-org-hero-slide.is-active {
        opacity: 1;
        pointer-events: auto;
    }

    .ufo-org-hero-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .ufo-org-hero-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 34px;
        height: 34px;
        border: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.86);
        color: #5a2f86;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        z-index: 2;
    }

    .ufo-org-hero-nav.prev {
        left: 0.8rem;
    }

    .ufo-org-hero-nav.next {
        right: 0.8rem;
    }

    .ufo-org-hero-dots {
        position: absolute;
        left: 50%;
        bottom: 0.7rem;
        transform: translateX(-50%);
        display: flex;
        align-items: center;
        gap: 0.35rem;
        z-index: 2;
    }

    .ufo-org-hero-dot {
        width: 9px;
        height: 9px;
        border-radius: 999px;
        border: 0;
        background: rgba(255, 255, 255, 0.66);
        transition: width 0.2s ease;
    }

    .ufo-org-hero-dot.is-active {
        width: 24px;
        background: #ffcc00;
    }

    .ufo-org-content {
        padding: 0.95rem;
    }

    .ufo-org-search-wrap {
        position: relative;
        margin-bottom: 0.8rem;
    }

    .ufo-org-search-wrap .bi {
        position: absolute;
        top: 50%;
        left: 0.9rem;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .ufo-org-search {
        width: 100%;
        border: 1px solid #d9dfeb;
        border-radius: 999px;
        background: #fff;
        padding: 0.66rem 0.9rem 0.66rem 2.35rem;
        font-size: 0.92rem;
    }

    .ufo-org-search:focus {
        border-color: #7f56a8;
        outline: none;
        box-shadow: 0 0 0 0.18rem rgba(127, 86, 168, 0.14);
    }

    .ufo-org-chips {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 0.2rem;
        margin-bottom: 0.95rem;
        scrollbar-width: none;
    }

    .ufo-org-chips::-webkit-scrollbar {
        display: none;
    }

    .ufo-org-chip {
        border: 0;
        background: #e8e8ed;
        color: #666d7a;
        border-radius: 999px;
        padding: 0.45rem 0.85rem;
        font-size: 0.82rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .ufo-org-chip.is-active {
        background: linear-gradient(135deg, #5e3190 0%, #6f43a0 100%);
        color: #fff;
    }

    .ufo-org-count {
        margin: 0;
        color: #616b7d;
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.9rem;
    }

    .ufo-org-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.85rem;
    }

    .ufo-org-card {
        background: #fff;
        border: 1px solid #e3e7f0;
        border-radius: 16px;
        box-shadow: 0 8px 18px rgba(16, 24, 40, 0.06);
        padding: 0.85rem;
        display: flex;
        flex-direction: column;
        min-height: 222px;
    }

    .ufo-org-card-head {
        display: flex;
        justify-content: center;
        margin-bottom: 0.55rem;
    }

    .ufo-org-card-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.55rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #6f43a0 0%, #ac79cf 100%);
    }

    .ufo-org-card-title {
        margin: 0 0 0.25rem;
        font-size: 1rem;
        font-weight: 700;
        color: #2f3444;
        text-align: center;
    }

    .ufo-org-card-tagline {
        margin: 0 0 0.45rem;
        text-align: center;
        color: #6f7583;
        font-size: 0.8rem;
    }

    .ufo-org-card-badges {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        flex-wrap: wrap;
        margin-bottom: 0.45rem;
    }

    .ufo-org-badge {
        border-radius: 8px;
        padding: 0.18rem 0.46rem;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .ufo-org-badge.category {
        color: #5f358a;
        background: #efe8f8;
    }

    .ufo-org-badge.members {
        color: #0b6e46;
        background: #e6f8f0;
    }

    .ufo-org-card-desc {
        margin: 0 0 0.55rem;
        color: #596172;
        font-size: 0.79rem;
        text-align: center;
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .ufo-org-card-foot {
        margin-top: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .ufo-org-status {
        font-size: 0.74rem;
        font-weight: 700;
    }

    .ufo-org-status.open {
        color: #087443;
    }

    .ufo-org-status.closed {
        color: #9f1239;
    }

    .ufo-org-link {
        text-decoration: none;
        border: 1px solid #d9deea;
        color: #5e3590;
        background: #fff;
        border-radius: 999px;
        padding: 0.28rem 0.65rem;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .ufo-org-empty {
        border-radius: 14px;
        border: 1px dashed #cbd4e3;
        background: #fff;
        padding: 1.05rem;
        text-align: center;
        color: #647188;
        font-size: 0.9rem;
    }

    .ufo-org-chat {
        position: fixed;
        right: 1.7rem;
        bottom: 1.3rem;
        width: 52px;
        height: 52px;
        border-radius: 50%;
        border: 0;
        color: #fff;
        background: linear-gradient(135deg, #5e3590 0%, #7e56af 100%);
        font-size: 1.25rem;
        box-shadow: 0 12px 22px rgba(53, 27, 82, 0.28);
    }

    .ufo-org-chat-dot {
        position: absolute;
        top: 5px;
        right: 7px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #ff4b55;
        border: 2px solid #fff;
    }

    @media (max-width: 900px) {
        .ufo-org-app {
            padding: 0;
        }

        .ufo-org-frame {
            max-width: 100%;
            min-height: 100vh;
            border-radius: 0;
            box-shadow: none;
        }

        .ufo-org-grid {
            grid-template-columns: 1fr;
        }

        .ufo-org-brand-title {
            font-size: 1.15rem;
        }

        .ufo-org-chat {
            right: 1rem;
            bottom: 0.95rem;
            width: 48px;
            height: 48px;
        }
    }

    @media (max-width: 575px) {
        .ufo-org-topbar {
            padding: 0.6rem 0.72rem;
        }

        .ufo-org-content {
            padding: 0.75rem;
        }

        .ufo-org-brand-mark {
            width: 34px;
            height: 34px;
        }

        .ufo-org-hero {
            height: 220px;
        }

        .ufo-org-count {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<section class="ufo-org-app">
    <div class="ufo-org-frame">
        <header class="ufo-org-topbar">
            <div class="ufo-org-topbar-left">
                <button type="button" class="ufo-org-topbar-icon-btn" aria-label="Buka menu">
                    <i class="bi bi-list"></i>
                </button>

                <div class="ufo-org-brand">
                    <span class="ufo-org-brand-mark"><i class="bi bi-send-fill"></i></span>
                    <div>
                        <h1 class="ufo-org-brand-title">UFO</h1>
                        <p class="ufo-org-brand-subtitle">UNKLAB Forum Organization</p>
                    </div>
                </div>
            </div>

            <div class="ufo-org-topbar-right">
                <button type="button" class="ufo-org-topbar-icon-btn ufo-org-notif" aria-label="Notifikasi">
                    <i class="bi bi-bell"></i>
                    <span class="ufo-org-notif-dot" aria-hidden="true"></span>
                </button>
                <button type="button" class="ufo-org-topbar-icon-btn" aria-label="Profil">
                    <i class="bi bi-person-circle"></i>
                </button>
            </div>
        </header>

        <div class="ufo-org-body">
            <section class="ufo-org-hero" aria-label="Hero organisasi">
                <div class="ufo-org-hero-slide is-active">
                    <img src="https://images.unsplash.com/photo-1452802447250-470a88ac82bc?auto=format&fit=crop&w=1400&q=80" alt="Kegiatan organisasi kampus">
                </div>
                <div class="ufo-org-hero-slide">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1400&q=80" alt="Kolaborasi mahasiswa">
                </div>
                <div class="ufo-org-hero-slide">
                    <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=1400&q=80" alt="Komunitas mahasiswa">
                </div>

                <button type="button" class="ufo-org-hero-nav prev" id="hero-prev" aria-label="Slide sebelumnya">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button type="button" class="ufo-org-hero-nav next" id="hero-next" aria-label="Slide berikutnya">
                    <i class="bi bi-chevron-right"></i>
                </button>

                <div class="ufo-org-hero-dots" id="hero-dots" aria-hidden="true">
                    <button class="ufo-org-hero-dot is-active" type="button"></button>
                    <button class="ufo-org-hero-dot" type="button"></button>
                    <button class="ufo-org-hero-dot" type="button"></button>
                </div>
            </section>

            <div class="ufo-org-content">
                <div class="ufo-org-search-wrap">
                    <i class="bi bi-search"></i>
                    <input id="search-input" type="text" class="ufo-org-search" placeholder="Cari organisasi...">
                </div>

                <div id="org-chips" class="ufo-org-chips" role="tablist" aria-label="Filter organisasi"></div>

                <p id="org-count" class="ufo-org-count">0 organisasi ditemukan</p>

                <div id="org-grid" class="ufo-org-grid"></div>

                <div id="org-empty" class="ufo-org-empty d-none mt-3">
                    Tidak ada organisasi yang sesuai.
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="ufo-org-chat" aria-label="Buka bantuan chat">
        <i class="bi bi-chat-dots"></i>
        <span class="ufo-org-chat-dot" aria-hidden="true"></span>
    </button>
</section>
@endsection

@push('scripts')
<script>
window.orgsData = @json($organisasi);

document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('org-grid');
    const empty = document.getElementById('org-empty');
    const count = document.getElementById('org-count');
    const chips = document.getElementById('org-chips');
    const search = document.getElementById('search-input');
    const detailBaseUrl = @json(url('/organisasi'));
    const slides = Array.from(document.querySelectorAll('.ufo-org-hero-slide'));
    const dots = Array.from(document.querySelectorAll('.ufo-org-hero-dot'));
    const prevBtn = document.getElementById('hero-prev');
    const nextBtn = document.getElementById('hero-next');

    let activeFilter = 'all';
    let activeSlide = 0;
    let slideTimer = null;

    function escapeHtml(value) {
        return String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function getChipLabel(name) {
        const trimmed = String(name || '').trim();
        if (trimmed === '') {
            return 'Organisasi';
        }

        if (trimmed.length <= 16) {
            return trimmed;
        }

        const words = trimmed.split(' ');
        if (words.length > 1) {
            return words.slice(0, 2).join(' ');
        }

        return trimmed.slice(0, 16) + '...';
    }

    function buildChips() {
        const topOrgs = window.orgsData.slice(0, 7);
        const items = [{ key: 'all', label: 'Semua' }].concat(
            topOrgs.map(function (org) {
                return {
                    key: 'org-' + org.id,
                    label: getChipLabel(org.nama),
                };
            })
        );

        chips.innerHTML = items.map(function (item) {
            const isActive = item.key === activeFilter;
            return '<button type="button" class="ufo-org-chip ' + (isActive ? 'is-active' : '') + '" data-filter="' + escapeHtml(item.key) + '">' + escapeHtml(item.label) + '</button>';
        }).join('');

        chips.querySelectorAll('.ufo-org-chip').forEach(function (btn) {
            btn.addEventListener('click', function () {
                activeFilter = btn.dataset.filter || 'all';
                buildChips();
                render();
            });
        });
    }

    function setSlide(index) {
        if (!slides.length) {
            return;
        }

        activeSlide = (index + slides.length) % slides.length;

        slides.forEach(function (slide, idx) {
            slide.classList.toggle('is-active', idx === activeSlide);
        });

        dots.forEach(function (dot, idx) {
            dot.classList.toggle('is-active', idx === activeSlide);
        });
    }

    function startSlideTimer() {
        if (!slides.length) {
            return;
        }

        if (slideTimer) {
            clearInterval(slideTimer);
        }

        slideTimer = setInterval(function () {
            setSlide(activeSlide + 1);
        }, 4500);
    }

    if (prevBtn && nextBtn) {
        prevBtn.addEventListener('click', function () {
            setSlide(activeSlide - 1);
            startSlideTimer();
        });

        nextBtn.addEventListener('click', function () {
            setSlide(activeSlide + 1);
            startSlideTimer();
        });
    }

    dots.forEach(function (dot, idx) {
        dot.addEventListener('click', function () {
            setSlide(idx);
            startSlideTimer();
        });
    });

    function render() {
        const q = search.value.trim().toLowerCase();
        const filtered = window.orgsData.filter(function (org) {
            const activeOrgId = activeFilter.startsWith('org-')
                ? parseInt(activeFilter.replace('org-', ''), 10)
                : null;

            const matchCategory = activeFilter === 'all' || org.id === activeOrgId;
            const matchSearch =
                org.nama.toLowerCase().includes(q) ||
                org.tagline.toLowerCase().includes(q) ||
                org.deskripsi.toLowerCase().includes(q);
            return matchCategory && matchSearch;
        });

        count.textContent = filtered.length + ' organisasi ditemukan';

        grid.innerHTML = filtered.map(function (org) {
            const statusHtml = org.registrationOpen
                ? '<span class="ufo-org-status open">Buka Pendaftaran</span>'
                : '<span class="ufo-org-status closed">Pendaftaran Tutup</span>';

            const orgInitial = escapeHtml((org.nama || 'O').charAt(0).toUpperCase());

            return `
                <article class="ufo-org-card">
                    <div class="ufo-org-card-head">
                        <span class="ufo-org-card-icon">${orgInitial}</span>
                    </div>

                    <h3 class="ufo-org-card-title">${escapeHtml(org.nama)}</h3>
                    <p class="ufo-org-card-tagline">${escapeHtml(org.tagline)}</p>

                    <div class="ufo-org-card-badges">
                        <span class="ufo-org-badge category">${escapeHtml(org.kategori)}</span>
                        <span class="ufo-org-badge members">${escapeHtml(String(org.members))} anggota</span>
                    </div>

                    <p class="ufo-org-card-desc">${escapeHtml(org.deskripsi)}</p>

                    <div class="ufo-org-card-foot">
                        ${statusHtml}
                        <a href="${detailBaseUrl}/${org.id}" class="ufo-org-link">Lihat Detail</a>
                    </div>
                </article>
            `;
        }).join('');

        empty.classList.toggle('d-none', filtered.length > 0);
    }

    search.addEventListener('input', render);

    buildChips();
    render();
    setSlide(0);
    startSlideTimer();
});
</script>
@endpush
