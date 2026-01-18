@extends('layouts.app')

@section('title', 'Portal Login - UFO')

@section('body-class', 'portal-login-page')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <span class="login-icon">🛸</span>
            <h1>UFO Portal</h1>
            <p>UNKLAB Forum Organization - Akses Internal</p>
        </div>

        <form id="portal-login-form" class="login-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" class="form-input" placeholder="your@email.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-input" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label for="role">Pilih Role</label>
                <select id="role" class="form-input" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="pengurus">Pengurus Organisasi</option>
                    <option value="admin">Admin Sistem</option>
                    <option value="kemahasiswaan">Departemen Kemahasiswaan</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Login</button>
        </form>

        <div class="login-footer">
            <p>Kembali ke <a href="/">dashboard mahasiswa</a></p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="/js/portal.js"></script>
<script>
    document.getElementById('portal-login-form').addEventListener('submit', function(e){
        e.preventDefault();
        var role = document.getElementById('role').value;
        if(role){
            localStorage.setItem('portal_role', role);
            window.location.href = '/portal/' + role;
        }
    });
</script>
@endsection
