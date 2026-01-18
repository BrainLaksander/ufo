@extends('layouts.app')

@section('title', 'Dashboard Admin - UFO Portal')

@section('header')
    @include('components.header-portal')
@endsection

@section('burger')
    @include('components.burger-admin')
@endsection

@section('content')
<div class="portal-dashboard admin-dashboard">
    <div class="page-header">
        <h1>Dashboard Admin Sistem</h1>
        <p class="page-subtitle">Kelola seluruh sistem UFO</p>
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
        <div class="stat-card">
            <div class="stat-icon">⚠️</div>
            <div class="stat-content">
                <div class="stat-value">8</div>
                <div class="stat-label">Laporan Pending</div>
            </div>
        </div>
    </div>

    <section class="section">
        <h2>Pengajuan Menunggu Persetujuan</h2>
        <div class="approval-list">
            <div class="approval-item">
                <h4>Organisasi Baru: Robotics Club</h4>
                <p class="approval-desc">Pengajuan pembentukan organisasi baru</p>
                <div class="approval-actions">
                    <button class="btn btn-success">Setujui</button>
                    <button class="btn btn-danger">Tolak</button>
                </div>
            </div>
            <div class="approval-item">
                <h4>Event: Tech Talks 2025</h4>
                <p class="approval-desc">Pengajuan event dari HIMA Teknik</p>
                <div class="approval-actions">
                    <button class="btn btn-success">Setujui</button>
                    <button class="btn btn-danger">Tolak</button>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script src="/js/portal.js"></script>
@endsection
