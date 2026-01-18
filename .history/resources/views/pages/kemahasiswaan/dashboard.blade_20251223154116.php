@extends('layouts.kemahasiswaan')

@section('title', 'Dashboard Kemahasiswaan - UFO')

@section('content')
<div class="dashboard-container">
    <div class="page-header">
        <h1>Dashboard Departemen Kemahasiswaan</h1>
        <p>Pantau dan kelola kegiatan kemahasiswaan</p>
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
    </div>

    <section class="dashboard-section">
        <h2>Monitoring Organisasi</h2>
        <div class="monitoring-list">
            <div class="monitoring-item">
                <h4>HIMA Teknik Informatika</h4>
                <p><span class="badge badge-active">Aktif</span> | Anggota: 245 | Event: 12/20</p>
            </div>
            <div class="monitoring-item">
                <h4>BEM UNKLAB</h4>
                <p><span class="badge badge-active">Aktif</span> | Anggota: 50 | Event: 25/25</p>
            </div>
        </div>
    </section>
</div>
@endsection
