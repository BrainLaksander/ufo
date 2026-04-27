@extends('layouts.app')

@section('title', '403 - Akses Ditolak')

@section('content')
<div class="error-page-container">
    <div class="error-content">
        <div class="error-code">
            <h1>403</h1>
        </div>
        
        <div class="error-message">
            <h2>Akses Ditolak</h2>
            <p>Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        </div>

        <div class="error-description">
            <p>
                @if(auth()->check())
                    Akun Anda tidak memiliki level akses yang diperlukan untuk melihat konten ini.
                    @if(auth()->user()->hasRole('admin_kemahasiswaan'))
                        Silakan hubungi <strong>Super Administrator</strong> jika Anda merasa ini adalah kesalahan.
                    @else
                        Silakan hubungi <strong>Administrator Kemahasiswaan</strong> atau <strong>Pengurus Organisasi</strong> untuk mendapatkan akses.
                    @endif
                @else
                    Silakan <a href="{{ route('login') }}">login terlebih dahulu</a> untuk mengakses halaman ini.
                @endif
            </p>
        </div>

        <div class="error-actions">
            <a href="{{ url()->previous() }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                <i class="bi bi-house"></i> Ke Halaman Login
            </a>
        </div>
    </div>
</div>

<style>
.error-page-container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}

.error-content {
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    padding: 60px 40px;
    text-align: center;
    max-width: 500px;
}

.error-code h1 {
    font-size: 120px;
    font-weight: 900;
    margin: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.error-message h2 {
    font-size: 28px;
    color: #333;
    margin: 20px 0 10px 0;
}

.error-message p {
    color: #666;
    font-size: 16px;
    margin: 0;
}

.error-description {
    margin: 20px 0;
    font-size: 14px;
    color: #666;
    line-height: 1.6;
}

.error-description a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.error-description a:hover {
    text-decoration: underline;
}

.error-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.btn-outline-primary {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-outline-primary:hover {
    background: #f5f7ff;
}
</style>
@endsection
