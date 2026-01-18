@extends('layouts.admin')

@section('title', 'Dashboard Admin - UFO')

@section('content')
<div class="dashboard-container">
    <div class="page-header">
        <h1>Dashboard Admin Sistem</h1>
        <p>Kelola seluruh sistem UFO</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">🏢</div>
            <div class="stat-content">
                <div class="stat-value">24</div>
                <div class="stat-label">Total Organisasi</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <div class="stat-value">1250</div>
                <div class="stat-label">Total Pengguna</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-content">
                <div class="stat-value">45</div>
                <div class="stat-label">Event Aktif</div>
            </div>
        </div>
    </div>

    <section class="dashboard-section">
        <h2>Pengajuan Menunggu Persetujuan</h2>
        <div class="approval-list">
            <div class="approval-item">
                <h4>Organisasi Baru: Robotics Club</h4>
                <p>Pengajuan pembentukan organisasi baru</p>
                <div class="approval-actions">
                    <button class="btn-success">Setujui</button>
                    <button class="btn-danger">Tolak</button>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
