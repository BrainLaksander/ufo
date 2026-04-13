@extends('layouts.pengurus')

@section('title', 'Pengaturan Organisasi')

@section('content')
<div class="page-header mb-4">
    <h1>Pengaturan Organisasi</h1>
    <p class="page-subtitle">Kelola profil UKM Anda</p>
</div>

@if(session('success'))
    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
@endif

<div class="form-org">
    @if(!($hasOrganizationContext ?? false))
        <div class="alert alert-warning" role="alert">
            Akun pengurus belum terhubung ke data organisasi pada database.
        </div>
    @endif

    <form method="POST" action="{{ route('portal.pengurus.settings.profile.update') }}">
        @csrf
        <h3 class="text-primary mb-4" style="font-weight: 700;">Informasi Dasar Organisasi</h3>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nama Organisasi</label>
                    <input type="text" value="{{ $orgData['name'] }}" disabled style="background: #f0f0f0; cursor: not-allowed;">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Singkatan (Shortname)</label>
                    <input type="text" value="{{ $orgData['shortname'] }}" disabled style="background: #f0f0f0; cursor: not-allowed;">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi Organisasi</label>
            <textarea name="description" style="min-height: 100px;" @disabled(!($hasOrganizationContext ?? false))>{{ $orgData['description'] }}</textarea>
        </div>

        <hr class="my-4">

        <h3 class="text-primary mb-4" style="font-weight: 700;">Visi & Misi</h3>

        <div class="form-group">
            <label>Visi Organisasi</label>
            <textarea name="vision" style="min-height: 100px;" @disabled(!($hasOrganizationContext ?? false))>{{ $orgData['vision'] }}</textarea>
        </div>

        <div class="form-group">
            <label>Misi Organisasi</label>
            <textarea name="mission" style="min-height: 150px;" @disabled(!($hasOrganizationContext ?? false))>{{ $orgData['mission'] }}</textarea>
            <small class="text-muted d-block mt-2">Pisahkan misi dengan baris baru jika diperlukan</small>
        </div>

        <hr class="my-4">

        <h3 class="text-primary mb-4" style="font-weight: 700;">Kontak & Sosial Media</h3>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email Organisasi</label>
                    <input type="email" name="email" value="{{ $orgData['email'] }}" @disabled(!($hasOrganizationContext ?? false))>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="tel" name="phone" value="{{ $orgData['phone'] }}" @disabled(!($hasOrganizationContext ?? false))>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Instagram</label>
                    <input type="text" name="instagram" value="{{ $orgData['instagram'] }}" @disabled(!($hasOrganizationContext ?? false))>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>LINE Official Account</label>
                    <input type="text" name="line" value="{{ $orgData['line'] }}" @disabled(!($hasOrganizationContext ?? false))>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn-primary-org" @disabled(!($hasOrganizationContext ?? false))>Simpan Perubahan</button>
            <a href="{{ route('portal.pengurus.settings') }}" class="btn-secondary-org" style="text-decoration: none;">Muat Ulang</a>
        </div>
    </form>
</div>
@endsection
