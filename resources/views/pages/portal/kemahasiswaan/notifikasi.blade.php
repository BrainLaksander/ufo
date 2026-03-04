@extends('layouts.kemahasiswaan')

@section('title', 'Notifikasi Sistem - Kemahasiswaan')

@section('content')
  <div class="page-header">
    <h1>Notifikasi Sistem</h1>
    <p class="page-subtitle">Daftar notifikasi terbaru dan sistem</p>
  </div>

  <div class="content-grid">
    <div class="chart-card">
      <h3>Notifikasi Terbaru</h3>
      <p class="text-muted">(Contoh notifikasi statis — akan diganti dengan data dinamis nantinya)</p>
      <ul class="list-unstyled mt-3">
        <li><strong>[5] </strong> Pembaruan sistem tersedia</li>
        <li><strong>[3] </strong> Pengumuman baru telah dipublikasikan</li>
        <li><strong>[1] </strong> Laporan menunggu tinjauan</li>
      </ul>
    </div>
  </div>
@endsection
