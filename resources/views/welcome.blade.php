@extends('layouts.app')

@section('title', 'UFO - Beranda')

@section('content')
<section class="py-5">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <h1 class="display-5 fw-bold mb-3">Platform Organisasi Kampus UFO</h1>
            <p class="lead text-muted mb-4">
                Sistem manajemen organisasi, pengumuman, event, dan layanan mahasiswa dalam satu platform terintegrasi.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('mahasiswa.beranda') }}" class="btn btn-primary">Masuk Beranda Mahasiswa</a>
                <a href="{{ route('portal.login') }}" class="btn btn-outline-primary">Masuk Portal Internal</a>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-3">Navigasi Cepat</h2>
                    <div class="d-grid gap-2">
                        <a href="{{ route('mahasiswa.organisasi.index') }}" class="btn btn-sm btn-outline-dark">Organisasi</a>
                        <a href="{{ route('mahasiswa.event') }}" class="btn btn-sm btn-outline-dark">Event</a>
                        <a href="{{ route('mahasiswa.pengumuman') }}" class="btn btn-sm btn-outline-dark">Pengumuman</a>
                        <a href="{{ route('mahasiswa.lost-found') }}" class="btn btn-sm btn-outline-dark">Lost &amp; Found</a>
                        <a href="{{ route('mahasiswa.tentang') }}" class="btn btn-sm btn-outline-dark">Tentang UFO</a>
                        <a href="{{ route('portal.internal.login') }}" class="btn btn-sm btn-outline-dark">Login Internal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
