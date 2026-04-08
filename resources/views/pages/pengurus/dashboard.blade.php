@extends('layouts.pengurus')

@section('title', 'Dashboard Pengurus')

@section('content')
<div class="page-header">
    <h1>Dashboard Pengurus</h1>
    <p class="page-subtitle">Ringkasan aktivitas organisasi Anda</p>
</div>

<!-- STATISTICS -->
<div class="row g-3 mb-4">
    @foreach($stats as $stat)
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon"><i class="bi {{ $stat['icon'] }}"></i></div>
            <div class="stat-card-value">{{ $stat['value'] }}</div>
            <div class="stat-card-label">{{ $stat['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-3">
    <!-- QUICK ACTIONS -->
    <div class="col-lg-6">
        <div class="bg-white p-4 rounded-3 shadow-sm border" style="border: 2px solid #f0f0f0;">
            <h4 class="text-primary mb-3" style="font-weight: 700;"><i class="bi bi-lightning-charge-fill me-2"></i>Quick Actions</h4>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('portal.pengurus.events.create') }}" class="btn-primary-org">
                    <span><i class="bi bi-plus-circle-fill me-1"></i></span> Buat Event
                </a>
                <a href="{{ route('portal.pengurus.announcements.create') }}" class="btn-primary-org">
                    <span><i class="bi bi-plus-circle-fill me-1"></i></span> Buat Pengumuman
                </a>
                <a href="{{ route('portal.pengurus.lostandfound') }}" class="btn-primary-org">
                    <span><i class="bi bi-plus-circle-fill me-1"></i></span> Lapor Lost & Found
                </a>
            </div>
        </div>
    </div>

    <!-- RECENT APPLICATIONS -->
    <div class="col-lg-6">
        <div class="bg-white p-4 rounded-3 shadow-sm border" style="border: 2px solid #f0f0f0;">
            <h4 class="text-primary mb-3" style="font-weight: 700;"><i class="bi bi-clipboard-check me-2"></i>Pendaftaran Terbaru</h4>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <tbody>
                        @foreach($recent_applications as $app)
                        <tr>
                            <td>
                                <strong>{{ $app['name'] }}</strong><br>
                                <small class="text-muted">{{ $app['nim'] }}</small>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-success" style="background: var(--success); border: none; color: white; cursor: pointer;"><i class="bi bi-check-lg"></i></button>
                                <button class="btn btn-sm btn-danger" style="background: var(--danger); border: none; color: white; cursor: pointer;"><i class="bi bi-x-lg"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <a href="{{ route('portal.pengurus.applications') }}" class="btn-secondary-org" style="text-decoration: none; display: inline-block;">Lihat Semua →</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-3">
    <!-- RECENT EVENTS -->
    <div class="col-lg-6">
        <div class="bg-white p-4 rounded-3 shadow-sm border" style="border: 2px solid #f0f0f0;">
            <h4 class="text-primary mb-3" style="font-weight: 700;"><i class="bi bi-calendar-event me-2"></i>Event Terdekat</h4>
            <div class="d-flex flex-column gap-3">
                @foreach($recent_events as $event)
                <div style="border-left: 4px solid var(--primary); padding-left: 15px;">
                    <div style="font-weight: 600; color: var(--primary);">{{ $event['name'] }}</div>
                    <div style="font-size: 12px; color: var(--text-secondary);">
                        <i class="bi bi-calendar-date me-1"></i>{{ date('d M Y', strtotime($event['date'])) }} | <i class="bi bi-people me-1"></i>{{ $event['participants'] }} peserta
                    </div>
                    <div class="mt-1">
                        @if($event['status'] == 'Draft')
                            <span class="badge-org warning">Draft</span>
                        @elseif($event['status'] == 'Open')
                            <span class="badge-org success">Open</span>
                        @else
                            <span class="badge-org danger">{{ $event['status'] }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-3">
                <a href="{{ route('portal.pengurus.events') }}" class="btn-secondary-org" style="text-decoration: none; display: inline-block;">Lihat Semua →</a>
            </div>
        </div>
    </div>

    <!-- RECENT LOST & FOUND -->
    <div class="col-lg-6">
        <div class="bg-white p-4 rounded-3 shadow-sm border" style="border: 2px solid #f0f0f0;">
            <h4 class="text-primary mb-3" style="font-weight: 700;"><i class="bi bi-search me-2"></i>Lost & Found Terbaru</h4>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr style="background: var(--primary); color: white;">
                            <th style="border: none;">Barang</th>
                            <th style="border: none;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_lostandfound as $item)
                        <tr>
                            <td style="border-bottom: 1px solid #f0f0f0;">
                                <strong>{{ $item['item'] }}</strong><br>
                                <small class="text-muted">{{ $item['category'] }}</small>
                            </td>
                            <td style="border-bottom: 1px solid #f0f0f0;">
                                @if($item['status'] == 'Found')
                                    <span class="badge-org success">Found</span>
                                @else
                                    <span class="badge-org warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <a href="{{ route('portal.pengurus.lostandfound') }}" class="btn-secondary-org" style="text-decoration: none; display: inline-block;">Lihat Semua →</a>
            </div>
        </div>
    </div>
</div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-content">
                <div class="stat-value">128</div>
                <div class="stat-label">Total Anggota</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-calendar-event-fill"></i></div>
            <div class="stat-content">
                <div class="stat-value">12</div>
                <div class="stat-label">Total Event</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
            <div class="stat-content">
                <div class="stat-value">3</div>
                <div class="stat-label">Pengajuan Aktif</div>
            </div>
        </div>
    </div>

    <section class="dashboard-section">
        <h2>Kegiatan Terbaru</h2>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-dot"></div>
                <div class="activity-content">
                    <h4>Workshop Coding berhasil diselenggarakan</h4>
                    <span class="activity-date">23 Desember 2024</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-dot"></div>
                <div class="activity-content">
                    <h4>Pengajuan event sedang direview</h4>
                    <span class="activity-date">22 Desember 2024</span>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
