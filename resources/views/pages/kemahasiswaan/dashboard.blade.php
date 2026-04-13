@extends('layouts.kemahasiswaan')

@section('title', 'Dashboard Kemahasiswaan - UFO')

@php
    $stats = [
        [
            'label' => 'Total Organisasi Aktif',
            'value' => '22',
            'icon' => 'bi-buildings',
            'tone' => 'primary',
        ],
        [
            'label' => 'Total Kegiatan Berjalan',
            'value' => '12',
            'icon' => 'bi-card-list',
            'tone' => 'primary',
        ],
        [
            'label' => 'Total Kegiatan Menunggu Persetujuan',
            'value' => '8',
            'icon' => 'bi-clock-history',
            'tone' => 'warning',
        ],
        [
            'label' => 'Total Laporan Belum Direview',
            'value' => '5',
            'icon' => 'bi-clipboard-check',
            'tone' => 'primary',
        ],
    ];

    $monthlyActivity = [
        ['month' => 'Jan', 'value' => 8],
        ['month' => 'Feb', 'value' => 12],
        ['month' => 'Mar', 'value' => 15],
        ['month' => 'Apr', 'value' => 10],
        ['month' => 'Mei', 'value' => 18],
        ['month' => 'Jun', 'value' => 14],
        ['month' => 'Jul', 'value' => 20],
        ['month' => 'Agu', 'value' => 16],
        ['month' => 'Sep', 'value' => 22],
        ['month' => 'Okt', 'value' => 19],
        ['month' => 'Nov', 'value' => 17],
        ['month' => 'Des', 'value' => 13],
    ];

    $upcomingEvents = [
        [
            'title' => 'Seminar Nasional HMTI',
            'date' => '20 Desember 2024',
            'status' => 'Aktif',
            'tone' => 'active',
        ],
        [
            'title' => 'Festival Seni UKM',
            'date' => '22 Desember 2024',
            'status' => 'Aktif',
            'tone' => 'active',
        ],
        [
            'title' => 'Rapat Koordinasi BEM',
            'date' => '25 Desember 2024',
            'status' => 'Pending',
            'tone' => 'pending',
        ],
    ];

    $chartMax = 24;
@endphp

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body.kemahasiswaan-layout {
        background: #ececf1;
    }

    body.kemahasiswaan-layout .kemahasiswaan-header {
        display: none;
    }

    body.kemahasiswaan-layout .kemahasiswaan-sidebar {
        width: 300px;
        background: linear-gradient(180deg, #3f1f61 0%, #34154f 100%);
        color: #f8f6fc;
        z-index: 96;
        transform: translateX(0);
    }

    body.kemahasiswaan-layout .kemahasiswaan-sidebar .sidebar-top {
        padding: 1.35rem 1.2rem 0;
    }

    body.kemahasiswaan-layout .kemahasiswaan-sidebar .dept-title h2 {
        font-size: 2rem;
        line-height: 1.02;
        margin-bottom: 0.65rem;
        letter-spacing: -0.01em;
    }

    body.kemahasiswaan-layout .kemahasiswaan-sidebar .university {
        color: #facc15;
        font-size: 0.86rem;
    }

    body.kemahasiswaan-layout .kemahasiswaan-sidebar .sidebar-nav {
        padding: 1rem 1.02rem 1.7rem;
    }

    body.kemahasiswaan-layout .kemahasiswaan-sidebar .sidebar-nav .nav-link {
        border-radius: 10px;
        padding: 0.62rem 0.7rem;
        gap: 0.65rem;
        color: rgba(255, 255, 255, 0.92);
    }

    body.kemahasiswaan-layout .kemahasiswaan-sidebar .sidebar-nav .nav-link:hover {
        background: rgba(255, 255, 255, 0.09);
    }

    body.kemahasiswaan-layout .kemahasiswaan-sidebar .sidebar-nav .nav-link.active {
        background: rgba(255, 255, 255, 0.16);
        color: #ffffff;
        box-shadow: none;
    }

    body.kemahasiswaan-layout .kemahasiswaan-sidebar .sidebar-nav .nav-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.55rem;
        background: rgba(255, 255, 255, 0.08);
    }

    body.kemahasiswaan-layout .main-content.role-main-content {
        margin-left: 300px;
        min-height: 100vh;
        padding: 6.25rem 1.15rem 1.2rem;
        background: #ececf1;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .kmh-dashboard-topbar {
        position: fixed;
        top: 0;
        left: 300px;
        right: 0;
        z-index: 90;
        background: #4f256f;
        color: #ffffff;
        box-shadow: 0 6px 14px rgba(28, 11, 45, 0.2);
    }

    .kmh-dashboard-topbar-inner {
        min-height: 5.35rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.8rem;
        padding: 0.95rem 1.25rem;
    }

    .kmh-dashboard-menu-btn {
        width: 2.2rem;
        height: 2.2rem;
        border: 0;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        color: #ffffff;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
    }

    .kmh-dashboard-title {
        margin-right: auto;
    }

    .kmh-dashboard-title h1 {
        margin: 0;
        font-size: clamp(1.55rem, 2.4vw, 2rem);
        line-height: 1.1;
        font-weight: 700;
        color: #ffffff;
    }

    .kmh-dashboard-title p {
        margin: 0.24rem 0 0;
        font-size: 0.98rem;
        color: rgba(255, 255, 255, 0.86);
    }

    .kmh-dashboard-alert {
        width: 2.35rem;
        height: 2.35rem;
        border: 0;
        border-radius: 999px;
        background: transparent;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
        font-size: 1.16rem;
    }

    .kmh-dashboard-alert:hover {
        background: rgba(255, 255, 255, 0.14);
    }

    .kmh-dashboard-alert-badge {
        position: absolute;
        top: 0.08rem;
        right: 0.13rem;
        min-width: 1.05rem;
        height: 1.05rem;
        padding: 0 0.28rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.66rem;
        line-height: 1;
        font-weight: 700;
        background: #facc15;
        color: #442063;
    }

    .kmh-dashboard-wrap {
        width: min(100%, 1280px);
        margin: 0 auto;
    }

    .kmh-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.9rem;
        margin-bottom: 0.92rem;
    }

    .kmh-stat-card {
        background: #ffffff;
        border: 1px solid #dfdfeb;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(17, 24, 39, 0.06);
        padding: 0.95rem 0.95rem;
        min-height: 122px;
        display: flex;
        justify-content: space-between;
        gap: 0.8rem;
    }

    .kmh-stat-card p {
        margin: 0;
        color: #6b7280;
        font-size: 0.88rem;
        line-height: 1.35;
    }

    .kmh-stat-card strong {
        display: block;
        margin-top: 0.38rem;
        font-size: 2.2rem;
        line-height: 1;
        font-weight: 700;
        color: #111827;
    }

    .kmh-stat-card .kmh-stat-icon {
        flex-shrink: 0;
        width: 2.4rem;
        height: 2.4rem;
        border-radius: 0.52rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.12rem;
        color: #ffffff;
        background: #4f256f;
    }

    .kmh-stat-card.tone-warning .kmh-stat-icon {
        color: #3f2c02;
        background: #facc15;
    }

    .kmh-main-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 300px;
        gap: 0.9rem;
    }

    .kmh-card {
        background: #ffffff;
        border: 1px solid #dfdfeb;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(17, 24, 39, 0.06);
        overflow: hidden;
    }

    .kmh-card-head {
        padding: 0.8rem 0.9rem;
        border-bottom: 1px solid #ececf3;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.7rem;
    }

    .kmh-card-head h2 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
    }

    .kmh-chart-body {
        padding: 0.7rem 0.85rem 0.95rem;
    }

    .kmh-chart-shell {
        display: grid;
        grid-template-columns: 2.1rem minmax(0, 1fr);
        gap: 0.35rem;
        min-height: 306px;
    }

    .kmh-chart-y {
        display: grid;
        grid-template-rows: repeat(5, 1fr);
        align-items: end;
        color: #6b7280;
        font-size: 0.72rem;
        padding-bottom: 1.65rem;
    }

    .kmh-chart-plot {
        position: relative;
        border-left: 2px solid #d1d5db;
        border-bottom: 2px solid #d1d5db;
        padding: 0.3rem 0.35rem 1.65rem;
    }

    .kmh-chart-gridline {
        position: absolute;
        left: 0;
        right: 0;
        border-top: 1px dashed #e5e7eb;
    }

    .kmh-chart-gridline.y24 { top: 0; }
    .kmh-chart-gridline.y18 { top: 25%; }
    .kmh-chart-gridline.y12 { top: 50%; }
    .kmh-chart-gridline.y6 { top: 75%; }

    .kmh-chart-bars {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 0.28rem;
        align-items: end;
        height: 100%;
    }

    .kmh-chart-bar-col {
        display: grid;
        grid-template-rows: minmax(0, 1fr) 1.4rem;
        align-items: end;
        min-width: 0;
    }

    .kmh-chart-bar-wrap {
        display: flex;
        align-items: flex-end;
        height: 100%;
    }

    .kmh-chart-bar {
        width: 100%;
        border-radius: 0.35rem 0.35rem 0 0;
        background: linear-gradient(180deg, #55317c 0%, #3f1f61 100%);
        transition: filter 0.2s ease;
        cursor: default;
    }

    .kmh-chart-bar:hover {
        filter: brightness(1.08);
    }

    .kmh-chart-month {
        text-align: center;
        color: #6b7280;
        font-size: 0.74rem;
        line-height: 1.2;
        align-self: end;
        white-space: nowrap;
    }

    .kmh-chart-tooltip {
        position: absolute;
        left: 0;
        bottom: 0;
        transform: translate(-50%, -110%);
        background: #111827;
        color: #ffffff;
        border-radius: 8px;
        padding: 0.32rem 0.48rem;
        font-size: 0.74rem;
        line-height: 1;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.14s ease;
        z-index: 8;
        white-space: nowrap;
    }

    .kmh-chart-tooltip.is-visible {
        opacity: 1;
    }

    .kmh-side-stack {
        display: grid;
        gap: 0.9rem;
    }

    .kmh-actions-body {
        padding: 0.9rem;
    }

    .kmh-action-btn {
        width: 100%;
        border: 0;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        padding: 0.68rem 0.75rem;
        margin-bottom: 0.68rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        text-decoration: none;
    }

    .kmh-action-btn.primary {
        background: #4f256f;
        color: #ffffff;
    }

    .kmh-action-btn.warning {
        background: #facc15;
        color: #442063;
        margin-bottom: 0;
    }

    .kmh-event-list {
        padding: 0.2rem 0;
    }

    .kmh-event-item {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.7rem;
        padding: 0.8rem 0.9rem;
        border-top: 1px solid #ececf3;
    }

    .kmh-event-item:first-child {
        border-top: 0;
    }

    .kmh-event-item h3 {
        margin: 0;
        font-size: 0.96rem;
        font-weight: 600;
        color: #1f2937;
    }

    .kmh-event-item p {
        margin: 0.22rem 0 0;
        font-size: 0.8rem;
        color: #6b7280;
        display: inline-flex;
        align-items: center;
        gap: 0.32rem;
    }

    .kmh-pill {
        border-radius: 999px;
        padding: 0.22rem 0.58rem;
        font-size: 0.72rem;
        line-height: 1;
        font-weight: 700;
        white-space: nowrap;
    }

    .kmh-pill.active {
        background: #dcfce7;
        color: #166534;
    }

    .kmh-pill.pending {
        background: #fef9c3;
        color: #a16207;
    }

    @media (max-width: 1260px) {
        .kmh-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .kmh-main-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .kmh-side-stack {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 1024px) {
        body.kemahasiswaan-layout .main-content.role-main-content {
            margin-left: 0;
            padding-top: 5.15rem;
        }

        body.kemahasiswaan-layout .kemahasiswaan-sidebar {
            transform: translateX(-110%);
            width: min(84vw, 320px);
            box-shadow: 0 18px 34px rgba(10, 10, 10, 0.34);
        }

        body.kemahasiswaan-layout .kemahasiswaan-sidebar[aria-hidden="false"] {
            transform: translateX(0) !important;
        }

        .kmh-dashboard-topbar {
            left: 0;
        }

        .kmh-dashboard-menu-btn {
            display: inline-flex;
        }

        .kmh-dashboard-topbar-inner {
            min-height: 4.45rem;
            padding: 0.78rem 0.95rem;
        }

        .kmh-dashboard-title h1 {
            font-size: 1.52rem;
        }

        .kmh-dashboard-title p {
            font-size: 0.86rem;
        }

        .kmh-side-stack {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .kmh-stat-grid {
            grid-template-columns: 1fr;
        }

        .kmh-chart-shell {
            min-height: 250px;
        }

        .kmh-card-head h2 {
            font-size: 0.95rem;
        }
    }
</style>
@endpush

@section('content')
<header class="kmh-dashboard-topbar" aria-label="Header dashboard kemahasiswaan">
    <div class="kmh-dashboard-topbar-inner">
        <button id="kmh-sidebar-toggle" type="button" class="kmh-dashboard-menu-btn" aria-label="Buka navigasi samping">
            <i class="bi bi-list"></i>
        </button>

        <div class="kmh-dashboard-title">
            <h1>Dashboard</h1>
            <p>Sistem Administrasi &amp; Kontrol Organisasi Mahasiswa</p>
        </div>

        <button type="button" class="kmh-dashboard-alert" aria-label="Notifikasi sistem">
            <i class="bi bi-bell"></i>
            <span class="kmh-dashboard-alert-badge">5</span>
        </button>
    </div>
</header>

<div class="kmh-dashboard-wrap">
    <section class="kmh-stat-grid" aria-label="Ringkasan statistik dashboard">
        @foreach ($stats as $item)
            <article class="kmh-stat-card tone-{{ $item['tone'] }}">
                <div>
                    <p>{{ $item['label'] }}</p>
                    <strong>{{ $item['value'] }}</strong>
                </div>
                <span class="kmh-stat-icon">
                    <i class="bi {{ $item['icon'] }}"></i>
                </span>
            </article>
        @endforeach
    </section>

    <section class="kmh-main-grid" aria-label="Konten utama dashboard">
        <article class="kmh-card">
            <header class="kmh-card-head">
                <h2>Grafik Kegiatan Bulanan 2024</h2>
            </header>

            <div class="kmh-chart-body">
                <div class="kmh-chart-shell">
                    <div class="kmh-chart-y" aria-hidden="true">
                        <span>24</span>
                        <span>18</span>
                        <span>12</span>
                        <span>6</span>
                        <span>0</span>
                    </div>

                    <div class="kmh-chart-plot" id="kmh-chart-plot">
                        <span class="kmh-chart-gridline y24" aria-hidden="true"></span>
                        <span class="kmh-chart-gridline y18" aria-hidden="true"></span>
                        <span class="kmh-chart-gridline y12" aria-hidden="true"></span>
                        <span class="kmh-chart-gridline y6" aria-hidden="true"></span>

                        <div class="kmh-chart-bars">
                            @foreach ($monthlyActivity as $bar)
                                @php
                                    $height = max(6, min(100, round(($bar['value'] / $chartMax) * 100)));
                                @endphp
                                <div class="kmh-chart-bar-col">
                                    <div class="kmh-chart-bar-wrap">
                                        <span
                                            class="kmh-chart-bar"
                                            data-month="{{ $bar['month'] }}"
                                            data-value="{{ $bar['value'] }}"
                                            style="height: {{ $height }}%;"
                                        ></span>
                                    </div>
                                    <span class="kmh-chart-month">{{ $bar['month'] }}</span>
                                </div>
                            @endforeach
                        </div>

                        <span id="kmh-chart-tooltip" class="kmh-chart-tooltip"></span>
                    </div>
                </div>
            </div>
        </article>

        <div class="kmh-side-stack">
            <section class="kmh-card">
                <header class="kmh-card-head">
                    <h2>Quick Actions</h2>
                </header>

                <div class="kmh-actions-body">
                    <a href="{{ route('portal.kemahasiswaan.pengajuan') }}" class="kmh-action-btn primary">
                        <i class="bi bi-check-circle"></i>
                        Review Kegiatan
                    </a>
                    <a href="{{ route('portal.kemahasiswaan.pengumuman') }}" class="kmh-action-btn warning">
                        <i class="bi bi-megaphone"></i>
                        Buat Pengumuman
                    </a>
                </div>
            </section>

            <section class="kmh-card">
                <header class="kmh-card-head">
                    <h2>Kegiatan Mendatang</h2>
                </header>

                <div class="kmh-event-list">
                    @foreach ($upcomingEvents as $event)
                        <article class="kmh-event-item">
                            <div>
                                <h3>{{ $event['title'] }}</h3>
                                <p>
                                    <i class="bi bi-calendar3"></i>
                                    {{ $event['date'] }}
                                </p>
                            </div>
                            <span class="kmh-pill {{ $event['tone'] }}">{{ $event['status'] }}</span>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const toggleBtn = document.getElementById('kmh-sidebar-toggle');
    const chartBars = Array.from(document.querySelectorAll('.kmh-chart-bar'));
    const chartPlot = document.getElementById('kmh-chart-plot');
    const tooltip = document.getElementById('kmh-chart-tooltip');

    function setSidebarState(open) {
        if (!sidebar || !overlay) {
            return;
        }

        sidebar.setAttribute('aria-hidden', open ? 'false' : 'true');
        overlay.classList.toggle('hidden', !open);
    }

    function syncSidebarByViewport() {
        if (!sidebar || !overlay) {
            return;
        }

        if (window.matchMedia('(max-width: 1024px)').matches) {
            setSidebarState(false);
        } else {
            sidebar.setAttribute('aria-hidden', 'false');
            overlay.classList.add('hidden');
        }
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            if (!sidebar) {
                return;
            }

            const shouldOpen = sidebar.getAttribute('aria-hidden') !== 'false';
            setSidebarState(shouldOpen);
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function () {
            setSidebarState(false);
        });
    }

    if (sidebar) {
        sidebar.querySelectorAll('[data-close]').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.matchMedia('(max-width: 1024px)').matches) {
                    setSidebarState(false);
                }
            });
        });
    }

    window.addEventListener('resize', syncSidebarByViewport);
    syncSidebarByViewport();

    function showTooltip(bar) {
        if (!tooltip || !chartPlot || !bar) {
            return;
        }

        const month = bar.dataset.month || '';
        const value = bar.dataset.value || '0';
        tooltip.textContent = month + ': ' + value + ' kegiatan';

        const barRect = bar.getBoundingClientRect();
        const plotRect = chartPlot.getBoundingClientRect();
        const centerX = barRect.left - plotRect.left + (barRect.width / 2);
        const topY = plotRect.bottom - barRect.top;

        tooltip.style.left = centerX + 'px';
        tooltip.style.bottom = topY + 'px';
        tooltip.classList.add('is-visible');
    }

    function hideTooltip() {
        if (tooltip) {
            tooltip.classList.remove('is-visible');
        }
    }

    chartBars.forEach(function (bar) {
        bar.addEventListener('mouseenter', function () {
            showTooltip(bar);
        });

        bar.addEventListener('mouseleave', hideTooltip);

        bar.addEventListener('focus', function () {
            showTooltip(bar);
        });

        bar.addEventListener('blur', hideTooltip);
    });
});
</script>
@endpush