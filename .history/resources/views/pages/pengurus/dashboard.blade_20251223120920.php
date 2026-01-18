@extends('layouts.app')

@section('content')
  <div class="pengurus-dashboard">
    <h1 class="page-title">Dashboard Organisasi</h1>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-value">128</div>
        <div class="stat-label">Total Anggota</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">12</div>
        <div class="stat-label">Total Event</div>
      </div>
      <div class="stat-card">
        <div class="stat-value">3</div>
        <div class="stat-label">Pengajuan Aktif</div>
      </div>
    </div>

    <div class="mt-6">
      <div class="card">
        <h2 class="card-title">Aktivitas Terbaru</h2>
        <p class="text-muted">(Placeholder) Tidak ada data real karena mode static.</p>
      </div>
    </div>
  </div>
@endsection
