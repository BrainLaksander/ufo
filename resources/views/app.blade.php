@extends('layouts.app')

@section('title', 'UFO - Halaman Utama')

@section('content')
<section class="py-5 text-center">
    <h1 class="display-5 fw-bold mb-3">UFO Frontend Laravel</h1>
    <p class="lead text-muted mb-4">Frontend aplikasi sudah dimigrasikan ke Laravel Blade dengan Bootstrap.</p>
    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">Kembali ke Beranda</a>
</section>
@endsection
