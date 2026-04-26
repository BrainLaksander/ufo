@extends('layouts.portal.kemahasiswaan')

@section('title', 'Dashboard Kemahasiswaan - UFO')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Sistem Administrasi & Kontrol Organisasi Mahasiswa')
@section('page_class', 'kmh-page-dashboard')

@php
    $ui = $ui ?? [];
    $stats = $stats ?? [];
    $monthlyActivity = $monthlyActivity ?? [];
    $upcomingEvents = $upcomingEvents ?? [];
    $recentAnnouncements = $recentAnnouncements ?? [];

    $chartPayload = collect($monthlyActivity)
        ->map(fn ($item) => [
            'label' => (string) ($item['month'] ?? ''),
            'value' => (int) ($item['value'] ?? 0),
        ])
        ->values()
        ->all();
@endphp

@section('content')
<div class="kmh-page kmh-dashboard-page">
    <section class="kmh-stats-grid kmh-dashboard-stats" aria-label="Ringkasan statistik dashboard">
        @foreach ($stats as $item)
            <article class="kmh-stat-card tone-{{ $item['tone'] ?? '' }}">
                <div class="kmh-stat-content">
                    <p>{{ $item['label'] ?? '' }}</p>
                    <strong class="kmh-stat-value">{{ $item['value'] ?? '' }}</strong>
                </div>
                <span class="kmh-stat-icon">
                    <i class="bi {{ $item['icon'] ?? '' }}"></i>
                </span>
            </article>
        @endforeach
    </section>

    <section class="kmh-dashboard-main-grid" aria-label="Konten utama dashboard">
        <article class="kmh-card kmh-dashboard-chart-card">
            <header class="kmh-card-head">
                <div class="d-inline-flex align-items-center gap-2">
                    <i class="bi bi-graph-up-arrow text-primary"></i>
                    <h2 class="mb-0">{{ $ui['chart_title'] ?? '' }} {{ now()->year }}</h2>
                </div>
            </header>
            <div class="kmh-card-body">
                <div class="kmh-chart-shell">
                    <canvas id="activities-chart" aria-label="Grafik kegiatan bulanan" role="img"></canvas>
                </div>
            </div>
        </article>

        <aside class="kmh-card kmh-dashboard-side-panel" aria-label="Panel aksi dan kegiatan mendatang">
            <header class="kmh-card-head">
                <h3>{{ $ui['quick_actions_title'] ?? '' }}</h3>
            </header>
            <div class="kmh-card-body">
                <div class="kmh-quick-actions">
                    <a href="{{ route('portal.kemahasiswaan.pengajuan') }}" class="kmh-quick-btn is-primary">
                        <span>{{ $ui['quick_action_review'] ?? '' }}</span>
                    </a>
                    <a href="{{ route('portal.kemahasiswaan.pengumuman') }}" class="kmh-quick-btn is-accent">
                        <span>{{ $ui['quick_action_announcement'] ?? '' }}</span>
                    </a>
                </div>
            </div>

            <header class="kmh-card-head kmh-side-section-head">
                <h3>
                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                    {{ $ui['upcoming_events_title'] ?? '' }}
                </h3>
            </header>
            <div class="kmh-event-list" role="list">
                @forelse ($upcomingEvents as $event)
                    <article class="kmh-event-item" role="listitem">
                        <h4>{{ $event['title'] ?? '' }}</h4>
                        <p>{{ $event['date'] ?? '' }}</p>
                    </article>
                @empty
                    <article class="kmh-event-item is-empty" role="listitem">
                        <h4>{{ $ui['upcoming_empty_title'] ?? '' }}</h4>
                        <p>{{ $ui['upcoming_empty_message'] ?? '' }}</p>
                    </article>
                @endforelse
            </div>
        </aside>
    </section>

    <section class="kmh-card kmh-dashboard-announce-card">
        <header class="kmh-card-head">
            <h2>{{ $ui['recent_announcements_title'] ?? '' }}</h2>
            <a href="{{ route('portal.kemahasiswaan.pengumuman') }}" class="kmh-action-link is-primary">{{ $ui['view_all_label'] ?? '' }}</a>
        </header>
        <div class="kmh-card-body pt-0 kmh-dashboard-table-body">
            <div class="table-responsive kmh-announcement-table-wrap">
                <table class="table kmh-table">
                    <thead>
                        <tr>
                            <th>{{ $ui['table_col_title'] ?? '' }}</th>
                            <th>{{ $ui['table_col_date'] ?? '' }}</th>
                            <th>{{ $ui['table_col_status'] ?? '' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentAnnouncements as $announcement)
                            @php
                                $statusClass = match ($announcement['status'] ?? '') {
                                    'Terpublikasi' => 'is-success',
                                    'Terjadwal' => 'is-info',
                                    'Draft' => 'is-muted',
                                    default => 'is-warning',
                                };
                            @endphp
                            <tr>
                                <td>{{ $announcement['judul'] ?? '' }}</td>
                                <td>({{ $announcement['tanggal'] ?? '' }})</td>
                                <td>
                                    <span class="kmh-status-pill {{ $statusClass }}">{{ $announcement['status'] ?? '' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="kmh-empty-row">{{ $ui['recent_empty'] ?? '' }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
window.kemahasiswaanMonthlyActivity = @json($chartPayload);
</script>
@endpush
