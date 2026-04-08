@extends('layouts.app')

@section('title', 'Login')

@push('styles')
<style>
    .ufo-login-shell {
        min-height: calc(100vh - 240px);
        display: flex;
        align-items: center;
        padding: 2rem 0;
    }

    .ufo-login-hero {
        background: linear-gradient(140deg, #2f1c47 0%, #4f2a73 55%, #7f4da7 100%);
        color: #fff;
        border-radius: 18px;
        padding: 2rem;
        height: 100%;
        box-shadow: 0 14px 28px rgba(47, 28, 71, 0.22);
    }

    .ufo-login-hero h1 {
        font-size: 1.9rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .ufo-login-hero p {
        margin-bottom: 1.25rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .ufo-login-points {
        margin: 0;
        padding-left: 1rem;
        color: rgba(255, 255, 255, 0.94);
    }

    .ufo-login-points li {
        margin-bottom: 0.55rem;
    }

    .ufo-login-card {
        border: 0;
        border-radius: 18px;
        box-shadow: 0 14px 26px rgba(15, 20, 35, 0.11);
        overflow: hidden;
    }

    .ufo-login-card .card-body {
        padding: 2rem;
    }

    .ufo-login-title {
        font-size: 1.65rem;
        font-weight: 700;
        color: #1d2433;
        margin-bottom: 0.25rem;
    }

    .ufo-login-subtitle {
        color: #5f697a;
        margin-bottom: 1.5rem;
    }

    .ufo-login-card .form-label {
        font-weight: 600;
        color: #283141;
    }

    .ufo-login-card .form-control {
        border-radius: 10px;
        padding: 0.65rem 0.85rem;
        border-color: #d9deea;
    }

    .ufo-login-card .form-control:focus {
        border-color: #7f4da7;
        box-shadow: 0 0 0 0.2rem rgba(127, 77, 167, 0.18);
    }

    .ufo-login-btn {
        border-radius: 10px;
        font-weight: 700;
        padding: 0.7rem 1rem;
        background: linear-gradient(135deg, #4f2a73 0%, #7f4da7 100%);
        border: none;
    }

    .ufo-login-btn:hover,
    .ufo-login-btn:focus {
        background: linear-gradient(135deg, #452466 0%, #704695 100%);
    }

    .ufo-demo-box {
        margin-top: 1rem;
        background: #f6f7fb;
        border: 1px solid #e6e9f2;
        border-radius: 12px;
        padding: 0.85rem 1rem;
        font-size: 0.9rem;
        color: #3a4252;
    }

    .ufo-demo-box code {
        color: #4f2a73;
        font-weight: 700;
    }

    @media (max-width: 991px) {
        .ufo-login-shell {
            min-height: auto;
            padding: 1.25rem 0;
        }

        .ufo-login-hero,
        .ufo-login-card .card-body {
            padding: 1.35rem;
        }
    }
</style>
@endpush

@section('content')
<section class="ufo-login-shell">
    <div class="container-fluid px-0">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <article class="ufo-login-hero h-100">
                    <h1>Portal Login UFO</h1>
                    <p>Akses dashboard sesuai peran untuk mengelola organisasi, pengajuan, pengumuman, dan aktivitas kemahasiswaan.</p>

                    <ul class="ufo-login-points">
                        <li>Masuk sebagai Admin, Pengurus, Kemahasiswaan, atau Mahasiswa.</li>
                        <li>Gunakan akun demo untuk pengujian tampilan dan alur role.</li>
                        <li>Setelah login, kamu langsung diarahkan ke dashboard sesuai role.</li>
                    </ul>
                </article>
            </div>

            <div class="col-lg-6">
                <div class="card ufo-login-card h-100">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h2 class="ufo-login-title">Masuk ke Sistem UFO</h2>
                        <p class="ufo-login-subtitle">Silakan isi email dan password untuk melanjutkan.</p>

                        @if ($errors->any())
                            <div class="alert alert-danger py-2" role="alert">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ request()->routeIs('portal.*') ? route('portal.login.perform') : route('login.perform') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="nama@contoh.com"
                                    autocomplete="email"
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password"
                                    autocomplete="current-password"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 ufo-login-btn">Login</button>
                        </form>

                        <div class="ufo-demo-box">
                            <div><strong>Akun demo:</strong> <code>admin@example.com</code>, <code>kemahasiswaan@example.com</code>, <code>pengurus@example.com</code>, <code>mahasiswa@example.com</code></div>
                            <div><strong>Password:</strong> <code>password</code></div>
                        </div>

                        <a href="{{ route('home') }}" class="btn btn-link px-0 mt-2 text-decoration-none">Kembali ke landing page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
