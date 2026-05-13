@extends('layouts.app')

@section('title', 'Dashboard - Sistem Kemahasiswaan')

@section('content')

<div class="org-page">
    <div>
        <h1>Dashboard</h1>
        <p>Sistem Administrasi & Kontrol Organisasi Mahasiswa.</p>
    </div>

<div class="dashboard-content">
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Organisasi Aktif</h3>
                <p class="stat-number">{{ $totalOrganisasiAktif }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Kegiatan Berjalan</h3>
                <p class="stat-number">{{ $totalKegiatanBerjalan }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Kegiatan Menunggu Persetujuan</h3>
                <p class="stat-number">{{ $totalProposalMenunggu }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3>Total Laporan Belum Direview</h3>
                <p class="stat-number">{{ $totalLaporanMenunggu }}</p>
            </div>
        </div>
    </div>

    <!-- Main Grid: Chart + Quick Actions -->
    <div class="main-grid">
        <!-- Chart -->
        <div class="card-section">
            <h2 class="section-title">Grafik Kegiatan Bulanan {{ date('Y') }}</h2>
            <div class="chart-container">
                @php
                    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ago', 'Sep', 'Okt', 'Nov', 'Des'];
                @endphp
                @for ($i = 1; $i <= 12; $i++)
                    @php
                        $height = ($chartData[$i] / $maxChartValue) * 100;
                        if ($height < 5 && $chartData[$i] > 0) $height = 5; // minimum visibility for > 0
                    @endphp
                    <div class="chart-bar" style="height: {{ $height }}%;">
                        <span class="chart-tooltip">{{ $chartData[$i] }}</span>
                        <span class="chart-bar-label">{{ $months[$i - 1] }}</span>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Quick Actions + Upcoming Events -->
        <div class="card-section">
            <div class="upcoming-section">
                <h2 class="section-title dashboard-note dashboard-section-title">Kegiatan Mendatang</h2>
                @forelse($kegiatanMendatang as $kegiatan)
                    <div class="upcoming-item">
                        <div>
                            <div class="upcoming-title">{{ $kegiatan->title }}</div>
                            <div class="upcoming-date">{{ $kegiatan->start_at->translatedFormat('d F Y') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state" style="padding: 1rem; text-align: center; color: #6b7280;">
                        Belum ada kegiatan mendatang
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Announcements Table -->
    <div class="announcements-section">
        <div class="section-header">
            <h2 class="section-title">Pengumuman Terbaru</h2>
            <a href="#" class="view-all-link">Lihat Semua</a>
        </div>

        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Judul Pengumuman</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAnnouncements as $announcement)
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: #111827;">{{ $announcement->title }}</div>
                            <div style="font-size: 12px; color: #6b7280; margin-top: 2px;">Dari: {{ $announcement->organization ? $announcement->organization->name : 'Kemahasiswaan' }}</div>
                        </td>
                        <td>{{ $announcement->published_at ? $announcement->published_at->format('d M Y') : '-' }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($announcement->status) }}">
                                {{ ucfirst($announcement->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #6b7280; padding: 2rem;">
                            Belum ada pengumuman
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>

@endsection
