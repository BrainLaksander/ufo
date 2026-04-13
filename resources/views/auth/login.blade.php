@extends('layouts.app')

@section('title', 'Login')
@section('bodyClass', 'ufo-login-page')
@section('mainClass', 'ufo-login-main')

@push('styles')
<style>
    .ufo-login-page .ufo-shell-header,
    .ufo-login-page .ufo-shell-footer {
        display: none;
    }

    .ufo-login-main {
        margin: 0;
        max-width: 100%;
        padding: 0;
    }

    .ufo-login-shell {
        min-height: 100vh;
        background:
            radial-gradient(circle at 95% 10%, rgba(164, 125, 202, 0.5) 0%, rgba(164, 125, 202, 0) 32%),
            radial-gradient(circle at 8% 92%, rgba(126, 84, 174, 0.35) 0%, rgba(126, 84, 174, 0) 36%),
            linear-gradient(135deg, #4c2b6e 0%, #6f4a95 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .ufo-login-wrap {
        width: 100%;
        max-width: 460px;
    }

    .ufo-login-brand {
        text-align: center;
        color: #fff;
        margin-bottom: 1rem;
    }

    .ufo-login-brand-icon {
        width: 62px;
        height: 62px;
        border-radius: 50%;
        margin: 0 auto 0.75rem;
        background: #ffcf00;
        color: #4f2a73;
        font-size: 1.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2);
    }

    .ufo-login-brand h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
    }

    .ufo-login-brand p {
        margin: 0.2rem 0 0;
        color: rgba(255, 255, 255, 0.92);
    }

    .ufo-login-card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 24px 40px rgba(19, 13, 30, 0.35);
        overflow: hidden;
    }

    .ufo-login-card .card-body {
        padding: 1.6rem;
    }

    .ufo-login-title {
        font-size: 2rem;
        font-weight: 700;
        color: #3d225c;
        text-align: center;
        margin-bottom: 0.15rem;
    }

    .ufo-login-subtitle {
        color: #6b7280;
        text-align: center;
        margin-bottom: 1.4rem;
    }

    .ufo-login-card .form-label {
        font-weight: 600;
        color: #4b5563;
        margin-bottom: 0.4rem;
    }

    .ufo-login-input-group {
        position: relative;
    }

    .ufo-login-input-group .bi {
        position: absolute;
        left: 0.78rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 1rem;
        pointer-events: none;
    }

    .ufo-login-card .form-control,
    .ufo-login-card .form-select {
        border-radius: 10px;
        border: 1px solid #d7deea;
        padding: 0.68rem 0.84rem 0.68rem 2.35rem;
        font-size: 0.95rem;
        color: #374151;
    }

    .ufo-login-card .form-control:focus,
    .ufo-login-card .form-select:focus {
        border-color: #6f4a95;
        box-shadow: 0 0 0 0.2rem rgba(111, 74, 149, 0.14);
    }

    .ufo-login-btn {
        border-radius: 10px;
        font-weight: 700;
        font-size: 1rem;
        padding: 0.74rem 1rem;
        background: linear-gradient(135deg, #4a276f 0%, #5e338b 100%);
        border: none;
    }

    .ufo-login-btn:hover,
    .ufo-login-btn:focus {
        background: linear-gradient(135deg, #412263 0%, #542e7b 100%);
    }

    .ufo-login-foot {
        border-top: 1px solid #e8ecf4;
        margin-top: 0.9rem;
        padding-top: 0.9rem;
        text-align: center;
        color: #9aa3b2;
        font-size: 0.82rem;
        line-height: 1.4;
    }

    .ufo-demo-box {
        margin-top: 0.95rem;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.28);
        padding: 0.85rem 1rem;
        color: #fff;
        font-size: 0.85rem;
    }

    .ufo-demo-box code {
        color: #ffef9d;
        font-weight: 700;
    }

    .ufo-demo-box hr {
        border-color: rgba(255, 255, 255, 0.28);
        margin: 0.5rem 0;
    }

    .ufo-back-link {
        display: block;
        text-align: center;
        margin-top: 0.8rem;
        color: rgba(255, 255, 255, 0.95);
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 600;
    }

    .ufo-back-link:hover {
        color: #fff;
        text-decoration: underline;
    }

    @media (max-width: 575px) {
        .ufo-login-shell {
            padding: 1rem 0.8rem;
        }

        .ufo-login-brand h1 {
            font-size: 1.65rem;
        }

        .ufo-login-title {
            font-size: 1.7rem;
        }

        .ufo-login-card .card-body {
            padding: 1.2rem;
        }
    }
</style>
@endpush

@section('content')
@php
    $showDemoCredentials = app()->environment('local') && config('auth.demo_mode');
    $demoAccounts = config('auth.demo_accounts', []);
@endphp
<section class="ufo-login-shell">
    <div class="ufo-login-wrap">
        <div class="ufo-login-brand">
            <div class="ufo-login-brand-icon"><i class="bi bi-person-gear"></i></div>
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
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih role Anda</option>
                                <option value="kemahasiswaan" {{ old('role') === 'kemahasiswaan' ? 'selected' : '' }}>Departemen Kemahasiswaan</option>
                                <option value="pengurus" {{ old('role') === 'pengurus' ? 'selected' : '' }}>Pengurus UKM/BEM</option>
                            </select>
                        </div>
                        @error('role')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 ufo-login-btn">Masuk</button>
                </form>

                <div class="ufo-login-foot">
                    Sistem Administrasi &amp; Kontrol Organisasi Mahasiswa<br>
                    Departemen Kemahasiswaan Universitas Klabat
                </div>
            </div>
        </div>

        @if($showDemoCredentials)
            <div class="ufo-demo-box">
                <div><strong>Demo Accounts</strong></div>
                <hr>

                @php
                    $roleLabels = [
                        'kemahasiswaan' => 'Kemahasiswaan',
                        'pengurus' => 'Pengurus UKM/BEM',
                    ];
                @endphp

                @foreach($roleLabels as $roleKey => $roleLabel)
                    @php $email = data_get($demoAccounts, $roleKey . '.email'); @endphp
                    @if(!empty($email))
                        <div><strong>{{ $roleLabel }}:</strong> <code>{{ $email }}</code></div>
                    @endif
                @endforeach
            </div>
        @endif

        <a href="{{ route('home') }}" class="ufo-back-link">Kembali ke landing page</a>
    </div>
</section>
@endsection
