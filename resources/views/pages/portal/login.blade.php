@extends('layouts.portal')

@section('title', 'Portal Login - UFO')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="login-icon">🛸</div>
            <h1>UFO Portal</h1>
            <p>UNKLAB Forum Organization - Sistem Internal</p>
        </div>

        <div class="role-selector">
            <p class="role-label">Pilih Role Anda:</p>
            
            <div class="role-options">
                <button class="role-card" data-role="pengurus">
                    <div class="role-icon">👥</div>
                    <div class="role-name">Pengurus Organisasi</div>
                    <div class="role-desc">Kelola organisasi Anda</div>
                </button>

                <button class="role-card" data-role="admin">
                    <div class="role-icon">⚙️</div>
                    <div class="role-name">Admin Sistem</div>
                    <div class="role-desc">Kelola sistem secara keseluruhan</div>
                </button>

                <button class="role-card" data-role="kemahasiswaan">
                    <div class="role-icon">📚</div>
                    <div class="role-name">Kemahasiswaan</div>
                    <div class="role-desc">Pantau kegiatan mahasiswa</div>
                </button>
            </div>
        </div>

        <div class="login-footer">
            <p>Kembali ke <a href="/">halaman utama</a></p>
        </div>
    </div>
</div>
@endsection
