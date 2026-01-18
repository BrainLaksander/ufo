@extends('layouts.app')

@section('title', 'Dashboard Pengurus - UFO Portal')

@section('header')
    @include('components.header-portal')
@endsection

@section('burger')
    @include('components.burger-pengurus')
@endsection

@section('content')
<div class="portal-dashboard pengurus-dashboard">
    <div class="page-header">
        <h1>Dashboard Pengurus Organisasi</h1>
        <p class="page-subtitle">Kelola organisasi Anda dengan mudah</p>
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
        <div class="stat-card">
            <div class="stat-icon">💬</div>
            <div class="stat-content">
                <div class="stat-value">5</div>
                <div class="stat-label">Chat Baru</div>
            </div>
        </div>
    </div>

    <section class="section">
        <h2>Kegiatan Terbaru</h2>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon">✓</div>
                <div class="activity-content">
                    <h4>Event Workshop Coding berhasil diselenggarakan</h4>
                    <span class="activity-date">23 Desember 2024</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">📝</div>
                <div class="activity-content">
                    <h4>Pengajuan event "Seminar Karir" sedang direview</h4>
                    <span class="activity-date">22 Desember 2024</span>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">👤</div>
                <div class="activity-content">
                    <h4>5 anggota baru bergabung dengan organisasi</h4>
                    <span class="activity-date">20 Desember 2024</span>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script src="/js/portal.js"></script>
@endsection
