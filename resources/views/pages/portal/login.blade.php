@extends('layouts.portal')

@section('title', 'Portal Login - UFO')

@section('content')
<!-- Portal Login Page Container -->
<div class="portal-login-wrapper">
    <!-- Header Section: Ungu dengan icon & text -->
    <div class="portal-login-header">
        <div class="portal-login-header-content">
            <div class="portal-login-header-icon">👤</div>
            <div>
                <h1 class="portal-login-header-title">Sistem Kemahasiswaan</h1>
                <p class="portal-login-header-subtitle">Universitas Klabat</p>
            </div>
        </div>
    </div>

    <!-- Main Login Container -->
    <div class="portal-login-container">
        <!-- Login Card: Putih, Rounded, Shadow -->
        <div class="portal-login-card">
            <!-- Card Header -->
            <div class="portal-login-card-header">
                <h2 class="portal-login-card-title">Selamat Datang</h2>
                <p class="portal-login-card-subtitle">Silakan masuk ke akun Anda</p>
                <p class="portal-login-note">Akses internal — hanya untuk Admin Sistem</p>
            </div>

            <!-- Login Form -->
            <form class="portal-login-form" method="POST" action="{{ route('portal.login') }}">
                @csrf

                <!-- Email Input -->
                <div class="portal-form-group">
                    <label for="email" class="portal-form-label">Email</label>
                    <div class="portal-form-input-wrapper">
                        <span class="portal-form-icon">✉️</span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="portal-form-input"
                            placeholder="Masukkan email"
                            required
                        />
                    </div>
                </div>

                <!-- Password Input -->
                <div class="portal-form-group">
                    <label for="password" class="portal-form-label">Password</label>
                    <div class="portal-form-input-wrapper">
                        <span class="portal-form-icon">🔒</span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="portal-form-input"
                            placeholder="Masukkan password"
                            required
                        />
                    </div>
                </div>

                <!-- Role Selector -->
                <div class="portal-form-group">
                    <label for="role" class="portal-form-label">Pilih Role</label>
                    <div class="portal-form-input-wrapper">
                        <span class="portal-form-icon">🎯</span>
                        <select
                            id="role"
                            name="role"
                            class="portal-form-input portal-form-select"
                            required
                        >
                            <option value="" disabled selected>Pilih role Anda</option>
                            <option value="pengurus">Pengurus Organisasi</option>
                            <option value="admin">Admin Sistem</option>
                            <option value="kemahasiswaan">Kemahasiswaan</option>
                        </select>
                    </div>
                </div>

                <!-- Login Button: Full width, Ungu, Rounded -->
                <button type="submit" class="portal-login-btn">
                    Masuk
                </button>
            </form>

            <!-- Footer Text -->
            <div class="portal-login-card-footer">
                <p class="portal-login-footer-text">
                    Kembali ke <a href="/" class="portal-login-link">halaman utama</a>
                </p>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="portal-login-page-footer">
            <p class="portal-login-footer-main">
                Sistem Administrasi & Kontrol Organisasi Mahasiswa
            </p>
            <p class="portal-login-footer-sub">
                Departemen Kemahasiswaan Universitas Klabat
            </p>
        </div>
    </div>
</div>

<!-- Internal logic: persist simple auth state in localStorage and redirect to appropriate portal -->
<script>
    (function(){
        var form = document.querySelector('.portal-login-form');
        if (!form) return;
        form.addEventListener('submit', function(e){
            e.preventDefault();
            var email = (document.querySelector('#email') || {}).value || null;
            var roleSelect = document.querySelector('#role');
            var role = roleSelect ? roleSelect.value : null;

            if (!role) {
                alert('Silakan pilih role');
                return;
            }

            // Store simple auth state (used by SPA client)
            try {
                var payload = { email: email, role: role, name: email };
                localStorage.setItem('ufo_auth', JSON.stringify(payload));
            } catch(err) {
                console.warn('Failed to persist auth', err);
            }

            // Redirect to SPA route depending on role
            if (role === 'pengurus') {
                window.location.href = '/portal/pengurus';
            } else if (role === 'admin') {
                window.location.href = '/portal/admin';
            } else if (role === 'kemahasiswaan') {
                window.location.href = '/portal/kemahasiswaan';
            } else {
                window.location.href = '/portal';
            }
        });
    })();
</script>
@endsection
