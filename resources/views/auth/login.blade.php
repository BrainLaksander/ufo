@extends('layouts.public.app')

@section('title', 'Login Departemen - UFO')
@section('bodyClass', 'ufo-login-page')
@section('mainClass', 'ufo-login-main')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/auth-login.css') }}">
@endpush

@section('content')
@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
@endphp
<section class="ufo-login-shell">
    <div class="ufo-login-wrap">
        <div class="ufo-login-brand">
            <div class="ufo-login-brand-icon"><img src="{{ asset('logoufo.png') }}" alt="Logo UFO" class="ufo-login-brand-logo"></div>
            <h1>Sistem Kemahasiswaan</h1>
            <p>Universitas Klabat</p>
        </div>

        <div class="card ufo-login-card">
            <div class="card-body">
                <h2 class="ufo-login-title">Selamat Datang</h2>
                <p class="ufo-login-subtitle">Silakan masuk ke akun Anda</p>

                @if ($errors->any())
                    <div class="alert alert-danger py-2" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.perform') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="ufo-login-input-group">
                            <i class="bi bi-envelope"></i>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Masukkan email"
                                autocomplete="email"
                                required
                            >
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="ufo-login-input-group">
                            <i class="bi bi-lock"></i>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required
                            >
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="role" class="form-label">Pilih Role</label>
                        <div class="ufo-login-input-group">
                            <i class="bi bi-person-check"></i>
                            <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="" disabled {{ $selectedRole ? '' : 'selected' }}>Pilih role Anda</option>
                                @foreach ($roleOptions as $role)
                                    <option value="{{ $role['value'] }}" {{ $selectedRole === $role['value'] ? 'selected' : '' }}>{{ $role['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('role')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 ufo-login-btn">Masuk</button>
                </form>
            </div>
        </div>
        <a href="{{ route('home') }}" class="ufo-back-link">Kembali ke halaman mahasiswa</a>
    </div>
</section>
@endsection
