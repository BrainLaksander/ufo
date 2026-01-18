@extends('layouts.app')

@section('title', 'Dashboard Kemahasiswaan - UFO Portal')

@section('header')
    @include('components.header-portal')
@endsection

@section('burger')
    @include('components.burger-kemahasiswaan')
@endsection

@section('content')
<div class="portal-dashboard kemahasiswaan-dashboard">
    <div class="page-header">
        <h1>Dashboard Departemen Kemahasiswaan</h1>
        <p class="page-subtitle">Pantau dan kelola kegiatan kemahasiswaan</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">🏢</div>
            <div class="stat-content">
                <div class="stat-value">24</div>
                <div class="stat-label">Organisasi Terdaftar</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📝</div>
            <div class="stat-content">
                <div class="stat-value">7</div>
                <div class="stat-label">Permohonan Baru</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-content">
                <div class="stat-value">32</div>
                <div class="stat-label">Event Bulan Ini</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-content">
                <div class="stat-value">92%</div>
                <div class="stat-label">Kehadiran Rata-rata</div>
            </div>
        </div>
    </div>

    <section class="section">
        <h2>Monitoring Organisasi</h2>
        <div class="monitoring-list">
            <div class="monitoring-item">
                <div class="org-name">HIMA Teknik Informatika</div>
                <div class="monitoring-content">
                    <span class="badge badge-active">Aktif</span>
                    <span>Anggota: 245</span>
                    <span>Event: 12/20 target</span>
                </div>
            </div>
            <div class="monitoring-item">
                <div class="org-name">BEM UNKLAB</div>
                <div class="monitoring-content">
                    <span class="badge badge-active">Aktif</span>
                    <span>Anggota: 50</span>
                    <span>Event: 25/25 target</span>
                </div>
            </div>
            <div class="monitoring-item">
                <div class="org-name">HIMA Bisnis</div>
                <div class="monitoring-content">
                    <span class="badge badge-warning">Warning</span>
                    <span>Anggota: 180</span>
                    <span>Event: 8/15 target</span>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script src="/js/portal.js"></script>
@endsection
