@extends('layouts.mahasiswa')

@section('title', 'Beranda Mahasiswa')

@section('content')
    <div class="container py-4">
        <h2 class="h3 fw-bold mb-2">Selamat datang mahasiswa Universitas Klabat</h2>
        <p class="text-muted">Sistem informasi organisasi mahasiswa (UFO) untuk mendukung aktivitas kampus.</p>

        <div class="d-flex flex-wrap gap-2 mt-3">
            <a href="{{ route('mahasiswa.organisasi.index') }}" class="btn btn-primary">Daftar Organisasi</a>
            <a href="{{ route('mahasiswa.event') }}" class="btn btn-outline-primary">Event Organisasi</a>
            <a href="{{ route('mahasiswa.pengumuman') }}" class="btn btn-outline-secondary">Pengumuman</a>
            <a href="{{ route('mahasiswa.lost-found') }}" class="btn btn-outline-dark">Lost &amp; Found</a>
            <a href="{{ route('mahasiswa.tentang') }}" class="btn btn-outline-dark">Tentang UFO</a>
        </div>
    </div>
@endsection
