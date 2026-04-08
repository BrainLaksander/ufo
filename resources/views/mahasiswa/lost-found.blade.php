@extends('layouts.mahasiswa')

@section('title', 'Lost & Found - Mahasiswa')

@section('content')
<div class="container py-4">
    <h1 class="h2 fw-bold mb-3">Lost & Found</h1>
    <p class="text-muted">Silakan gunakan halaman Lost & Found terbaru.</p>
    <a href="{{ route('mahasiswa.lost-found') }}" class="btn btn-primary">Buka Lost & Found</a>
</div>
@endsection
