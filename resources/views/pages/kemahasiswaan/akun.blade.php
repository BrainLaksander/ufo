@extends('layouts.portal.kemahasiswaan')

@section('title', 'Pengaturan Akun - UFO')
@section('page_title', 'Pengaturan Akun')
@section('page_subtitle', 'Kelola pengaturan akun dan profil Anda')
@section('page_class', 'kmh-page-account')

@php
    $user = auth()->user();
    $roles = $roles ?? [];
@endphp

@section('content')
<div class="kmh-page kmh-account-page">
    <div class="kmh-card">
        <div class="kmh-card-header">
            <h2>Profil Akun</h2>
        </div>
        <div class="kmh-card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="kmh-avatar-section">
                        <div class="kmh-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="kmh-profile-info">
                        <h3>{{ $user->name ?? 'Nama Pengguna' }}</h3>
                        <p class="text-muted">
                            <strong>Email:</strong> {{ $user->email ?? '-' }}
                        </p>
                        <p class="text-muted">
                            <strong>Role:</strong> 
                            @if($user->hasRole('super_admin'))
                                <span class="badge bg-danger">Super Admin</span>
                            @elseif($user->hasRole('admin_kemahasiswaan'))
                                <span class="badge bg-primary">Admin Kemahasiswaan</span>
                            @else
                                <span class="badge bg-secondary">Pengguna</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h4>Informasi Dasar</h4>
                    <form method="POST" action="#" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name ?? '' }}" disabled>
                            <small class="text-muted">Hubungi administrator untuk mengubah nama</small>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email ?? '' }}" disabled>
                            <small class="text-muted">Email terhubung dengan sistem</small>
                        </div>
                    </form>
                </div>

                <div class="col-md-6">
                    <h4>Keamanan</h4>
                    <div class="mt-3">
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="bi bi-key"></i> Ubah Password
                        </button>
                        <p class="text-muted mt-2 mb-0">
                            <small>Terakhir diubah: <strong>{{ $user->updated_at?->format('d M Y') ?? 'Tidak diketahui' }}</strong></small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Activity Log --}}
    <div class="kmh-card mt-4">
        <div class="kmh-card-header">
            <h2>Riwayat Aktivitas</h2>
        </div>
        <div class="kmh-card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Aktivitas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i>
                                <p>Tidak ada aktivitas tercatat</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="#" id="changePasswordForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ubah Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('changePasswordForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            // Add password change logic here
            alert('Fitur ubah password akan diimplementasikan segera');
        });
    }
});
</script>
@endpush
