<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Sesi Kedaluwarsa</title>
    <link rel="stylesheet" href="/css/auth-login.css">
    <style>
        .error-message {
            text-align: center;
            margin-bottom: 32px;
            color: #4b5563;
            font-size: 15px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <div class="ufo-logo">
            <svg viewBox="0 0 48 48" role="img" aria-label="UFO Logo" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <ellipse cx="24" cy="31" rx="15" ry="5.5"></ellipse>
                <path d="M12 31c2.6-5.2 6.8-8.2 12-8.2S33.4 25.8 36 31"></path>
                <path d="M18 31c1.2-2.8 3.8-4.6 6-4.6s4.8 1.8 6 4.6"></path>
                <path d="M20 19.5c1.5-2.4 4.2-4 8-4 3.8 0 6.5 1.6 8 4"></path>
                <path d="M24 10.5v5"></path>
                <circle cx="24" cy="8" r="1.6"></circle>
            </svg>
        </div>
        <h1 class="page-title">Sistem Kemahasiswaan</h1>
        <p class="page-subtitle">Universitas Klabat</p>
    </div>

    <div class="login-container">
        <div class="login-header">
            <h2>419</h2>
            <p>Sesi Kedaluwarsa</p>
        </div>

        <div class="error-message">
            Sesi Anda telah berakhir karena Anda tidak beraktivitas selama beberapa waktu, atau status akun Anda baru saja diubah. Silakan masuk kembali untuk melanjutkan.
        </div>

        <a href="/login" class="login-button" style="text-decoration: none; display: flex; justify-content: center; align-items: center;">Login Ulang</a>

        <div class="login-footer">
            <a href="/" class="back-link">Ke Beranda Mahasiswa</a>
        </div>
    </div>
</body>
</html>
