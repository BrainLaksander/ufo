<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Kemahasiswaan</title>
    <link rel="stylesheet" href="/css/auth-login.css">
</head>
<body>
    <div class="page-header">
        <div class="ufo-logo" style="margin-bottom: 16px; text-align: center;">
            <img src="/logoufo.png" alt="UFO Logo" style="height: 64px; width: auto; object-fit: contain;">
        </div>
        <h1 class="page-title">Sistem Kemahasiswaan</h1>
        <p class="page-subtitle">Universitas Klabat</p>
    </div>

    <div class="login-container">
        <div class="login-header">
            <h2>Selamat Datang</h2>
            <p>Silakan masuk ke akun Anda</p>
        </div>

        @if (session('error'))
            <div style="max-width: 480px; margin: 0 auto 16px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; border-radius: 12px; padding: 12px 14px; font-size: 14px; line-height: 1.5;">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="max-width: 480px; margin: 0 auto 16px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; border-radius: 12px; padding: 12px 14px; font-size: 14px; line-height: 1.5;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <div class="input-wrapper">
                    <div class="input-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/><path d="m22 6-10 5L2 6"/>
                        </svg>
                    </div>
                    <input type="email" name="email" placeholder="kemahasiswaan@unklab.ac.id" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <div class="input-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M3 11V9a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v2"/><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M12 15v2"/>
                        </svg>
                    </div>
                    <input type="password" name="password" id="passwordInput" placeholder="•••••••" required>
                    <button type="button" class="toggle-password" id="togglePassword" aria-label="Tampilkan password" tabindex="-1">
                        <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg id="eyeOffIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20" style="display:none;">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                            <path d="m14.12 14.12a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label>Pilih Role</label>
                <div class="input-wrapper">
                    <div class="input-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M4 9.5A3.5 3.5 0 1 1 7.5 6 3.5 3.5 0 0 1 4 9.5Zm0 0v2.5"/><path d="M20 10.5A3.5 3.5 0 1 1 16.5 7 3.5 3.5 0 0 1 20 10.5Zm0 0v1.5"/><path d="M8 20v-2a4 4 0 0 1 8 0v2"/>
                        </svg>
                    </div>
                    <select name="role" required>
                        <option value="" selected disabled>Pilih role Anda</option>
                        <option value="kemahasiswaan" {{ old('role') === 'kemahasiswaan' ? 'selected' : '' }}>Kemahasiswaan</option>
                        <option value="pengurus_ukm">Pengurus UKM</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="login-button">Masuk</button>
        </form>

        <div class="login-footer">
            <a href="/" class="back-link">Kembali ke halaman mahasiswa</a>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            var input = document.getElementById('passwordInput');
            var eyeIcon = document.getElementById('eyeIcon');
            var eyeOffIcon = document.getElementById('eyeOffIcon');
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
                this.setAttribute('aria-label', 'Sembunyikan password');
            } else {
                input.type = 'password';
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
                this.setAttribute('aria-label', 'Tampilkan password');
            }
        });
    </script>
</body>
</html>
