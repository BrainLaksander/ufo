@extends('layouts.dashboard-master')

@section('title', 'Dashboard')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="section-title">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard {{ auth()->user()->organization->name ?? 'Organisasi' }}
            </h2>
        </div>
        <div class="col-md-4 text-end">
            <span class="text-muted">{{ now()->format('d F Y') }}</span>
        </div>
    </div>

    {{-- STATISTIKA CARDS --}}
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            @include('components.stat-card', [
                'icon' => 'bi bi-people',
                'label' => 'Total Anggota',
                'value' => $stats['total_members'] ?? 0,
                'bgColor' => 'primary',
                'link' => '#'
            ])
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            @include('components.stat-card', [
                'icon' => 'bi bi-calendar-event',
                'label' => 'Event Aktif',
                'value' => $stats['active_events'] ?? 0,
                'bgColor' => 'success',
                'link' => route('events.index') ?? '#'
            ])
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            @include('components.stat-card', [
                'icon' => 'bi bi-megaphone',
                'label' => 'Pengumuman',
                'value' => $stats['announcements'] ?? 0,
                'bgColor' => 'info',
                'link' => route('announcements.index') ?? '#'
            ])
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            @include('components.stat-card', [
                'icon' => 'bi bi-exclamation-circle',
                'label' => 'Pengajuan Tertunda',
                'value' => $stats['pending_proposals'] ?? 0,
                'bgColor' => 'warning',
                'link' => route('proposals.index') ?? '#'
            ])
        </div>
    </div>

    <div class="row">
        {{-- UPCOMING EVENTS --}}
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="bi bi-calendar-event me-2"></i>Event Mendatang</h5>
                    <a href="{{ route('events.index') }}" class="btn btn-sm btn-soft-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($upcomingEvents->count() > 0)
                        <div class="timeline-wrapper">
                            @foreach($upcomingEvents as $event)
                                <div class="timeline-item mb-4 pb-3 border-bottom">
                                    <div class="d-flex gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle p-2"
                                                style="background-color: {{ $event->getCategoryColor() }}20; color: {{ $event->getCategoryColor() }};">
                                                <i class="bi bi-calendar3 fs-6"></i>
                                            </div>
                                        </div>

                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">{{ $event->title }}</h6>

                                            <div class="small text-muted mb-2">
                                                <p class="mb-1">
                                                    <i class="bi bi-calendar3"></i>
                                                    {{ $event->event_date->format('d M Y, H:i') }}
                                                </p>
                                                <p class="mb-0">
                                                    <i class="bi bi-geo-alt"></i>
                                                    {{ $event->location }}
                                                </p>
                                            </div>

                                            <div class="d-flex gap-2">
                                                <span class="badge"
                                                    style="background-color: {{ $event->getCategoryColor() }}; color: white;">
                                                    {{ $event->getCategoryLabel() }}
                                                </span>
                                                @if($event->isFull())
                                                    <span class="badge bg-danger">Penuh</span>
                                                @else
                                                    <span class="badge bg-success">
                                                        {{ $event->getAvailableSlots() }} tempat tersedia
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        @include('components.empty-state', [
                            'icon' => 'bi-calendar-event',
                            'title' => 'Tidak ada event mendatang',
                            'message' => 'Mulai buat event baru untuk organisasi Anda',
                            'actionLabel' => 'Buat Event',
                            'actionLink' => route('events.create') ?? '#'
                        ])
                    @endif
                </div>
            </div>
        </div>

        {{-- PENDING APPROVALS (ADMIN ONLY) --}}
        @if(auth()->user()->isAdmin())
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="bi bi-check-circle me-2"></i>Menunggu Persetujuan</h5>
                        <span class="badge bg-warning">{{ $pendingProposals->count() }}</span>
                    </div>
                    <div class="card-body">
                        @if($pendingProposals->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($pendingProposals as $proposal)
                                    <div class="list-group-item d-flex justify-content-between align-items-start py-3">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">{{ $proposal->title }}</h6>
                                            <p class="text-muted small mb-2">
                                                Diajukan oleh <strong>{{ $proposal->submitter->name ?? 'Tidak diketahui' }}</strong>
                                            </p>
                                            @include('components.status-badge', ['status' => $proposal->status])
                                        </div>
                                        <a href="{{ route('proposals.show', $proposal) }}"
                                            class="btn btn-sm btn-primary ms-2">
                                            Review
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            @include('components.empty-state', [
                                'icon' => 'bi-check-circle',
                                'title' => 'Semua pengajuan sudah di-review',
                                'message' => 'Tidak ada pengajuan yang menunggu persetujuan'
                            ])
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- RECENT ANNOUNCEMENTS --}}
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="bi bi-megaphone me-2"></i>Pengumuman Terbaru</h5>
                    <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-soft-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($recentAnnouncements->count() > 0)
                        @foreach($recentAnnouncements as $announcement)
                            @include('components.announcement-card', [
                                'announcement' => $announcement,
                                'showActions' => auth()->user()->can('update', $announcement)
                            ])
                        @endforeach
                    @else
                        @include('components.empty-state', [
                            'icon' => 'bi-megaphone',
                            'title' => 'Belum ada pengumuman',
                            'message' => 'Buat pengumuman baru untuk berbagi informasi dengan anggota',
                            'actionLabel' => 'Buat Pengumuman',
                            'actionLink' => route('announcements.create') ?? '#'
                        ])
                    @endif
                </div>
            </div>
        </div>

        {{-- CALENDAR PREVIEW --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-calendar3 me-2"></i>Kalender</h5>
                </div>
                <div class="card-body">
                    {{-- Simple calendar grid --}}
                    <div style="font-size: 0.85rem;">
                        <div class="row g-1 text-center mb-2">
                            <div class="col"><strong>Min</strong></div>
                            <div class="col"><strong>Sen</strong></div>
                            <div class="col"><strong>Sel</strong></div>
                            <div class="col"><strong>Rab</strong></div>
                            <div class="col"><strong>Kam</strong></div>
                            <div class="col"><strong>Jum</strong></div>
                            <div class="col"><strong>Sab</strong></div>
                        </div>

                        {{-- Calendar dates from calendarData --}}
                        @if($calendarData ?? false)
                            <div class="row g-1">
                                @foreach($calendarData['dates'] as $day)
                                    <div class="col">
                                        <div class="p-2 border rounded text-center"
                                            style="
                                                background-color: {{ $day['is_today'] ? '#6A1B9A20' : 'white' }};
                                                color: {{ !$day['current_month'] ? '#ccc' : 'black' }};
                                            ">
                                            <small class="fw-bold">{{ $day['date'] }}</small>
                                            @if($day['events_count'] > 0)
                                                <div class="mt-1">
                                                    <span class="badge bg-primary" style="font-size: 0.6rem;">
                                                        {{ $day['events_count'] }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- LOST & FOUND --}}
    @if($stats['lost_items'] ?? false)
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="bi bi-search me-2"></i>Barang Hilang/Ditemukan Terbaru</h5>
                        <a href="{{ route('lostfound.index') }}" class="btn btn-sm btn-soft-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse($latestLostFound ?? [] as $item)
                                <div class="col-md-6 mb-3">
                                    <div class="p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="fw-bold mb-1">{{ $item->title }}</h6>
                                                @if($item->type === 'lost')
                                                    <span class="badge bg-danger">Hilang</span>
                                                @else
                                                    <span class="badge bg-success">Ditemukan</span>
                                                @endif
                                            </div>
                                            @include('components.status-badge', ['status' => $item->status])
                                        </div>
                                        <p class="text-muted small mb-2">{{ $item->description }}</p>
                                        <small class="d-block text-muted">{{ $item->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    @include('components.empty-state', [
                                        'icon' => 'bi-search',
                                        'title' => 'Belum ada barang hilang/ditemukan',
                                        'message' => ''
                                    ])
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
