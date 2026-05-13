<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - UFO</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #5e3191 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .reset-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 460px;
            overflow: hidden;
        }
        .reset-header {
            background: linear-gradient(135deg, #5e3191 0%, #7c3aed 50%, #a78bfa 100%);
            padding: 32px 28px;
            text-align: center;
        }
        .reset-header h1 {
            color: #fff;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 3px;
            margin-bottom: 4px;
        }
        .reset-header p {
            color: rgba(255,255,255,0.8);
            font-size: 13px;
        }
        .reset-body {
            padding: 32px 28px;
        }
        .reset-title {
            text-align: center;
            margin-bottom: 28px;
        }
        .reset-title .icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            background: #f0e7ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }
        .reset-title h2 {
            color: #1e1b4b;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .reset-title p {
            color: #6b7280;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-group input:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
        }
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #5e3191 0%, #7c3aed 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
            margin-top: 4px;
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(94, 49, 145, 0.35);
        }
        .btn-submit:active {
            transform: translateY(0);
        }
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 16px;
        }
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 16px;
            text-align: center;
        }
        .alert-success a {
            color: #5e3191;
            font-weight: 700;
            text-decoration: none;
        }
        .field-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 4px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #6b7280;
            font-size: 13px;
            text-decoration: none;
        }
        .back-link:hover {
            color: #5e3191;
        }
        .org-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0e7ff;
            color: #5e3191;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="reset-card">
        <div class="reset-header">
            <h1>UFO</h1>
            <p>UNKLAB Forum Organization</p>
        </div>
        <div class="reset-body">
            <div class="reset-title">
                <div class="icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#5e3191" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                </div>
                <h2>Buat Password Baru</h2>
                <p>Masukkan password baru untuk akun Anda</p>
                @if(isset($orgName))
                    <div class="org-badge">{{ $orgName }}</div>
                @endif
            </div>

            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}<br>
                    <a href="{{ route('login') }}">Klik di sini untuk login →</a>
                </div>
            @else
                @if($errors->any())
                    <div class="alert-error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="form-group">
                        <label>Password Baru *</label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter" autofocus>
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi Password Baru *</label>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi password baru">
                    </div>

                    <button type="submit" class="btn-submit">Simpan Password Baru</button>
                </form>

                <a href="{{ route('login') }}" class="back-link">← Kembali ke halaman login</a>
            @endif
        </div>
    </div>
</body>
</html>
