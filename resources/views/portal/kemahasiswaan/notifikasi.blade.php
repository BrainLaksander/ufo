@extends('layouts.portal.kemahasiswaan')

@section('title', 'Notifikasi Sistem - Kemahasiswaan')
@section('page_title', 'Notifikasi Sistem')
@section('page_subtitle', 'Pantau update pengajuan, laporan, pengumuman, dan aktivitas akun UKM')
@section('page_class', 'kmh-page-notifikasi')

@php
    $notifikasiItems = $notifikasiItems ?? [];
    $notifikasiFilter = $notifikasiFilter ?? 'semua';
    $notifikasiTypes = $notifikasiTypes ?? [];
    $notifikasiSummary = $notifikasiSummary ?? ['total' => 0, 'belum_dibaca' => 0];

    $iconMap = [
        'pengajuan' => 'bi-file-earmark-text-fill',
        'laporan' => 'bi-clipboard-data-fill',
        'pengumuman' => 'bi-megaphone-fill',
        'akun' => 'bi-people-fill',
        'jadwal' => 'bi-calendar2-week-fill',
    ];
@endphp

@section('content')
<div class="kmh-page kmh-notifikasi-page">
    <section class="kmh-stats-grid is-two">
        <article class="kmh-stat-card tone-primary">
            <div>
                <p>Total Notifikasi</p>
                <strong class="kmh-stat-value">{{ $notifikasiSummary['total'] ?? 0 }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-bell-fill"></i></span>
        </article>

        <article class="kmh-stat-card tone-warning">
            <div>
                <p>Belum Dibaca</p>
                <strong class="kmh-stat-value">{{ $notifikasiSummary['belum_dibaca'] ?? 0 }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-exclamation-circle-fill"></i></span>
        </article>
    </section>

    <section class="kmh-card">
        <header class="kmh-card-head">
            <h2>Filter Notifikasi</h2>
            <small>Menampilkan {{ count($notifikasiItems) }} item</small>
        </header>
        <div class="kmh-card-body">
            <div class="kmh-filter-chips">
                @foreach($notifikasiTypes as $type)
                    @php
                        $query = $type['value'] === 'semua' ? [] : ['jenis' => $type['value']];
                        $isActive = $notifikasiFilter === $type['value'];
                    @endphp
                    <a href="{{ route('portal.kemahasiswaan.notifikasi', $query) }}" class="kmh-filter-chip {{ $isActive ? 'is-active' : '' }}">
                        {{ $type['label'] }} ({{ $type['count'] }})
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="kmh-card">
        <div class="kmh-card-body">
            <div class="kmh-toolbar">
                <p class="kmh-toolbar-caption mb-0">
                    Menampilkan {{ count($notifikasiItems) }} notifikasi untuk filter
                    <strong>{{ data_get(collect($notifikasiTypes)->firstWhere('value', $notifikasiFilter), 'label', 'Semua Notifikasi') }}</strong>.
                </p>

                <div class="kmh-toolbar-end">
                    @if($notifikasiFilter !== 'semua')
                        <a href="{{ route('portal.kemahasiswaan.notifikasi') }}" class="kmh-action-link is-primary">Reset Filter</a>
                    @endif

                    <a href="{{ route('portal.kemahasiswaan.pengajuan') }}" class="kmh-action-link is-primary">Buka Review Pengajuan</a>
                </div>
            </div>
        </div>
    </section>

    <section class="kmh-notification-list" aria-label="Daftar notifikasi sistem">
        @forelse($notifikasiItems as $item)
            @php
                $jenis = $item['jenis'] ?? 'jadwal';
                $iconClass = $iconMap[$jenis] ?? 'bi-bell-fill';
                $isUnread = ($item['status'] ?? 'sudah_dibaca') === 'belum_dibaca';
                $metaClass = $isUnread ? 'is-warning' : 'is-muted';
                $detailUrl = match ($jenis) {
                    'pengajuan', 'laporan', 'jadwal' => route('portal.kemahasiswaan.pengajuan'),
                    'pengumuman' => route('portal.kemahasiswaan.pengumuman'),
                    'akun' => route('portal.kemahasiswaan.organisasi'),
                    default => route('portal.kemahasiswaan.notifikasi'),
                };
            @endphp
            <article class="kmh-notification-item {{ $isUnread ? 'is-unread' : '' }}">
                <div class="kmh-notification-icon tone-{{ $jenis }}">
                    <i class="bi {{ $iconClass }}"></i>
                </div>

                <div class="kmh-notification-content">
                    <div class="kmh-notification-head">
                        <h3 class="kmh-notification-title">{{ $item['judul'] ?? '-' }}</h3>
                        <span class="kmh-notification-time">{{ $item['waktu'] ?? '-' }}</span>
                    </div>

                    <p class="kmh-notification-message">{{ $item['pesan'] ?? '-' }}</p>

                    <div class="kmh-notification-meta">
                        <span class="kmh-status-pill {{ $metaClass }}">{{ $item['status_label'] ?? '-' }}</span>
                        <a href="{{ $detailUrl }}" class="kmh-notification-action" role="button">
                            <i class="bi bi-eye"></i>
                            <span>Lihat Detail</span>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <article class="kmh-card">
                <div class="kmh-card-body">
                    <p class="mb-0 text-muted">Belum ada notifikasi untuk filter ini.</p>
                </div>
            </article>
        @endforelse
    </section>
</div>
@endsection
