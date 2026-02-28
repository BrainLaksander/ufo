@extends('layouts.portal')

@section('title', 'Portal Internal - UFO')

@section('content')
<!-- Portal Internal (Admin) Login Page -->
<div class="portal-login-wrapper">
    <!-- Header: Ungu dengan icon user in yellow circle -->
    <div class="portal-login-header">
        <div class="portal-login-header-content">
            <div class="portal-login-header-icon">👤</div>
            <div>
                <h1 class="portal-login-header-title">Sistem Kemahasiswaan</h1>
                <p class="portal-login-header-subtitle">Universitas Klabat</p>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="portal-login-container">
        <div class="portal-login-card" role="main" aria-labelledby="login-title">
            <div class="portal-login-card-header">
                <h2 id="login-title" class="portal-login-card-title">Selamat Datang</h2>
                <p class="portal-login-card-subtitle">Silakan masuk ke akun Anda</p>
            </div>

            <!-- Note: backend login logic not changed; this is a UI only page -->
            <form class="portal-login-form" method="POST" action="{{ route('portal.login') }}">
                @csrf

                <!-- Email -->
                <div class="portal-form-group">
                    <label for="email" class="portal-form-label">Email</label>
                    <div class="portal-form-input-wrapper">
                        <span class="portal-form-icon">✉️</span>
                        <input type="email" id="email" name="email" class="portal-form-input" placeholder="Masukkan email" required />
                    </div>
                </div>

                <!-- Password -->
                <div class="portal-form-group">
                    <label for="password" class="portal-form-label">Password</label>
                    <div class="portal-form-input-wrapper">
                        <span class="portal-form-icon">🔒</span>
                        <input type="password" id="password" name="password" class="portal-form-input" placeholder="Masukkan password" required />
                    </div>
                </div>

                <!-- Role selector (preselected Admin Sistem) -->
                <div class="portal-form-group">
                    <label for="role" class="portal-form-label">Pilih Role</label>
                    <div class="portal-form-input-wrapper">
                        <span class="portal-form-icon">🎯</span>
                        <select id="role" name="role" class="portal-form-input portal-form-select" required>
                            <option value="" disabled>Pilih role Anda</option>
                            <option value="pengurus">Pengurus Organisasi</option>
                            <option value="admin" selected>Admin Sistem</option>
                            <option value="kemahasiswaan">Kemahasiswaan</option>
                        </select>
                    </div>
                </div>

                <!-- Button -->
                <button type="submit" class="portal-login-btn">Masuk</button>
            </form>

            <!-- Small footer inside card -->
            <div class="portal-login-card-footer">
                <p class="portal-login-footer-text">Sistem Administrasi & Kontrol Organisasi Mahasiswa<br>Departemen Kemahasiswaan Universitas Klabat</p>
            </div>
        </div>
    </div>
</div>
@endsection