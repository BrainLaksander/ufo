@extends('layouts.mahasiswa')

@section('title', 'Tentang UFO')

@section('content')
<div class="container py-4">
  <div class="p-4 p-md-5 rounded-3 text-white mb-4" style="background: linear-gradient(90deg, #663399, #7c3db3);">
    <h1 class="h2 fw-bold">Tentang UFO</h1>
    <p class="mb-0">Unklab Forum Organization - Platform Digital untuk Mahasiswa Universitas Klabat.</p>
  </div>

  <div class="row g-4">
    <div class="col-md-6">
      <div class="card h-100 border-0 shadow-sm">
        <div class="card-body">
          <h2 class="h5 text-primary">Visi</h2>
          <p class="text-muted mb-0">Menjadi platform digital terpadu untuk kehidupan kampus yang lebih terorganisir, informatif, dan kolaboratif.</p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card h-100 border-0 shadow-sm">
        <div class="card-body">
          <h2 class="h5 text-primary">Misi</h2>
          <ul class="mb-0 text-muted">
            <li>Menyediakan informasi kampus yang akurat dan terkini.</li>
            <li>Memfasilitasi komunikasi mahasiswa dan organisasi.</li>
            <li>Membantu layanan kampus seperti Lost & Found.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
