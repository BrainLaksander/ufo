@extends('layouts.mahasiswa')

@section('title', 'Tentang UFO')

@section('content')
<div class="bg-light min-vh-100">
  <!-- Hero -->
  <div class="py-5 text-white" style="background:linear-gradient(90deg,#663399,#7c3db3);">
    <div class="container text-center py-5">
      <h1 class="display-5 fw-bold">Tentang UFO</h1>
      <p class="lead opacity-75">Unklab Forum Organization - Platform Digital untuk Mahasiswa Universitas Klabat</p>
    </div>
  </div>

  <div class="container py-5">
    <!-- What is UFO -->
    <div class="card shadow-sm rounded-3 mb-4">
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div class="me-3 d-flex align-items-center justify-content-center rounded-circle" style="width:48px;height:48px;background:#663399;color:#fff;">
            <span style="font-size:20px;">👥</span>
          </div>
          <h2 class="h4 mb-0 text-primary">Apa itu UFO?</h2>
        </div>
        <p class="text-muted">UFO (Unklab Forum Organization) adalah platform digital yang dirancang khusus untuk mahasiswa Universitas Klabat. Sistem ini bertujuan untuk mempermudah akses informasi seputar organisasi kampus, acara-acara yang berlangsung, pengumuman penting, serta membantu mahasiswa dalam menemukan barang hilang.</p>
        <p class="text-muted mb-0">Dengan UFO, mahasiswa dapat dengan mudah menjelajahi berbagai organisasi yang ada di kampus, mendaftar ke acara-acara menarik, melihat pengumuman terkini, dan melaporkan atau mencari barang yang hilang.</p>
      </div>
    </div>

    <!-- Vision & Mission -->
    <div class="row g-4 mb-4">
      <div class="col-md-6">
        <div class="card shadow-sm rounded-3 h-100">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
              <div class="me-3 d-flex align-items-center justify-content-center rounded-circle" style="width:48px;height:48px;background:#FFCC00;color:#663399;">
                <span style="font-size:20px;">🎯</span>
              </div>
              <h3 class="h5 mb-0 text-primary">Visi</h3>
            </div>
            <p class="text-muted">Menjadi platform digital terpadu yang memfasilitasi kehidupan kampus yang lebih terorganisir, informatif, dan kolaboratif untuk seluruh civitas akademika Universitas Klabat.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card shadow-sm rounded-3 h-100">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
              <div class="me-3 d-flex align-items-center justify-content-center rounded-circle" style="width:48px;height:48px;background:#CC0000;color:#fff;">
                <span style="font-size:20px;">🏆</span>
              </div>
              <h3 class="h5 mb-0 text-primary">Misi</h3>
            </div>
            <ul class="mb-0 text-muted">
              <li>Menyediakan informasi organisasi dan acara kampus yang akurat dan terkini</li>
              <li>Memfasilitasi komunikasi antara mahasiswa dan organisasi</li>
              <li>Membantu mahasiswa dalam menemukan barang hilang dengan cepat</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Features -->
    <div class="card shadow-sm rounded-3 mb-4">
      <div class="card-body">
        <h3 class="h4 text-center text-primary mb-4">Fitur Utama UFO</h3>
        <div class="row g-3">
          <div class="col-6 col-lg-3">
            <div class="p-3 text-center rounded-3" style="background:linear-gradient(180deg,#f3e9ff,#fff);border:1px solid rgba(102,51,153,0.06);">
              <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width:64px;height:64px;background:#663399;color:#fff;font-size:22px;">👥</div>
              <h5 class="h6">Organisasi</h5>
              <p class="small text-muted">Jelajahi berbagai organisasi kampus dan temukan komunitas yang sesuai dengan minatmu</p>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="p-3 text-center rounded-3" style="background:linear-gradient(180deg,#fff9e6,#fff);border:1px solid rgba(255,204,0,0.08);">
              <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width:64px;height:64px;background:#FFCC00;color:#663399;font-size:22px;">📅</div>
              <h5 class="h6">Event</h5>
              <p class="small text-muted">Lihat dan daftar ke berbagai acara menarik yang diselenggarakan di kampus</p>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="p-3 text-center rounded-3" style="background:linear-gradient(180deg,#fff6f6,#fff);border:1px solid rgba(204,0,0,0.06);">
              <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width:64px;height:64px;background:#CC0000;color:#fff;font-size:22px;">📍</div>
              <h5 class="h6">Lost & Found</h5>
              <p class="small text-muted">Laporkan barang hilang atau temukan pemilik barang yang kamu temukan</p>
            </div>
          </div>
          <div class="col-6 col-lg-3">
            <div class="p-3 text-center rounded-3" style="background:linear-gradient(180deg,#eaf4ff,#fff);border:1px solid rgba(0,123,255,0.06);">
              <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width:64px;height:64px;background:#663399;color:#fff;font-size:22px;">📣</div>
              <h5 class="h6">Pengumuman</h5>
              <p class="small text-muted">Dapatkan informasi dan pengumuman penting dari kampus secara real-time</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Contact -->
    <div class="card text-white mb-4" style="background:linear-gradient(90deg,#663399,#7c3db3);">
      <div class="card-body">
        <h3 class="h4 text-center mb-4">Hubungi Kami</h3>
        <div class="row text-center">
          <div class="col-md-4 mb-3">
            <div class="mb-2 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:64px;height:64px;background:rgba(255,255,255,0.15);">✉️</div>
            <h6 class="fw-bold">Email</h6>
            <p class="mb-0">ufo@unklab.ac.id</p>
          </div>
          <div class="col-md-4 mb-3">
            <div class="mb-2 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:64px;height:64px;background:rgba(255,255,255,0.15);">📞</div>
            <h6 class="fw-bold">Telepon</h6>
            <p class="mb-0">+62 431 891 035</p>
          </div>
          <div class="col-md-4 mb-3">
            <div class="mb-2 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:64px;height:64px;background:rgba(255,255,255,0.15);">📍</div>
            <h6 class="fw-bold">Alamat</h6>
            <p class="mb-0">Universitas Klabat, Airmadidi, Sulawesi Utara</p>
          </div>
        </div>
      </div>
    </div>

    <!-- UFO-Bot -->
    <div class="p-4 rounded-3" style="background:linear-gradient(90deg,#FFCC00,#FFD633);">
      <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:72px;height:72px;background:#663399;color:#fff;font-size:28px;">🛸</div>
        <div>
          <h5 class="mb-1 text-primary fw-bold">Kenalan dengan UFO-Bot!</h5>
          <p class="mb-0 text-muted">Butuh bantuan? Klik tombol UFO-Bot di pojok kanan bawah untuk mendapatkan bantuan instan. UFO-Bot siap membantu menjawab pertanyaan dan memberikan panduan seputar penggunaan platform UFO.</p>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
@extends('layouts.mahasiswa')

@section('title', 'Tentang UFO - Mahasiswa')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
  <h1 class="text-3xl font-bold text-gray-800 mb-6">Tentang UFO</h1>
  <div class="bg-white rounded-lg shadow-md p-8 text-center text-gray-500">
    <p class="text-lg">Coming Soon</p>
  </div>
</div>
@endsection
