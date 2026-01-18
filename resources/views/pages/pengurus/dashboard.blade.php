@extends('layouts.pengurus')

@section('title', 'Dashboard Pengurus - UFO')

@section('content')
<div class="dashboard-container">
    <div class="page-header">
        <h1>Dashboard Pengurus Organisasi</h1>
        <p>Kelola organisasi Anda dengan mudah</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <div class="stat-value">128</div>
                <div class="stat-label">Total Anggota</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-content">
                <div class="stat-value">12</div>
                <div class="stat-label">Total Event</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📝</div>
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
