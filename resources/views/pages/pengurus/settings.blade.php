@extends('layouts.portal.pengurus')

@section('title', 'Pengaturan Organisasi')

@section('content')
<section class="ufo-pg-settings-page">
<div class="ufo-pg-settings-head">
    <h1 class="ufo-pg-settings-title">Profil Organisasi</h1>
    <p class="ufo-pg-settings-subtitle">Kelola informasi organisasi yang ditampilkan kepada mahasiswa</p>
</div>

@if(session('success'))
    <div class="alert alert-success ufo-pg-feedback" role="alert">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger ufo-pg-feedback" role="alert">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger ufo-pg-feedback" role="alert">{{ $errors->first() }}</div>
@endif

@if(!($hasOrganizationContext ?? false))
    <div class="alert alert-warning ufo-pg-warning-banner" role="alert">
        Akun pengurus belum terhubung ke data organisasi pada database.
    </div>
@endif

@php
    $categoryLabel = trim((string) ($orgData['shortname'] ?? ''));
    if ($categoryLabel === '') {
        $categoryLabel = trim((string) ($orgData['name'] ?? ''));
    }
@endphp

<article class="ufo-pg-settings-card">
    <div class="ufo-pg-card-head">
        <h2>Kategori Organisasi</h2>
        <button type="button" class="ufo-pg-edit-btn" data-bs-toggle="modal" data-bs-target="#ufoActionModal" data-modal-title="Edit Kategori Organisasi" data-modal-message="Modal ini menampilkan pengaturan kategori organisasi.">
            <i class="bi bi-pencil-square" aria-hidden="true"></i>
            <span>Edit</span>
        </button>
    </div>
    <span class="ufo-pg-kategori-chip">{{ $categoryLabel }}</span>
</article>

<article class="ufo-pg-settings-card">
    <div class="ufo-pg-card-head">
        <h2>Logo &amp; Banner</h2>
        <button type="button" class="ufo-pg-edit-btn" data-bs-toggle="modal" data-bs-target="#ufoActionModal" data-modal-title="Edit Media" data-modal-message="Modal ini menampilkan pengaturan logo dan banner organisasi.">
            <i class="bi bi-pencil-square" aria-hidden="true"></i>
            <span>Edit</span>
        </button>
    </div>
    <div class="ufo-pg-upload-grid">
        <div class="ufo-pg-upload-item">
            <label>Logo Organisasi</label>
            <div class="ufo-pg-upload-box" aria-hidden="true">
                <i class="bi bi-upload"></i>
                <p>Klik untuk upload logo</p>
                <small>PNG, JPG (maks. 2MB)</small>
            </div>
        </div>
        <div class="ufo-pg-upload-item">
            <label>Banner Organisasi</label>
            <div class="ufo-pg-upload-box" aria-hidden="true">
                <i class="bi bi-upload"></i>
                <p>Klik untuk upload banner</p>
                <small>PNG, JPG (maks. 5MB)</small>
            </div>
        </div>
    </div>
</article>

<form method="POST" action="{{ route('portal.pengurus.settings.profile.update') }}" class="ufo-pg-settings-form">
        @csrf

        <article class="ufo-pg-settings-card">
            <div class="ufo-pg-card-head">
                <h2>Informasi Dasar Organisasi</h2>
            </div>
            <div class="ufo-pg-field-grid">
                <div class="ufo-pg-field-group">
                    <label>Nama Organisasi</label>
                    <input type="text" value="{{ $orgData['name'] }}" disabled class="is-readonly">
                </div>
                <div class="ufo-pg-field-group">
                    <label>Singkatan (Shortname)</label>
                    <input type="text" value="{{ $orgData['shortname'] }}" disabled class="is-readonly">
                </div>
                <div class="ufo-pg-field-group ufo-pg-field-group-full">
                    <label>Deskripsi Organisasi</label>
                    <textarea name="description" class="ufo-pg-textarea-md" @disabled(!($hasOrganizationContext ?? false))>{{ $orgData['description'] }}</textarea>
                </div>
            </div>
        </article>

        <article class="ufo-pg-settings-card">
            <div class="ufo-pg-card-head">
                <h2>Visi &amp; Misi</h2>
                <button type="button" class="ufo-pg-edit-btn" data-bs-toggle="modal" data-bs-target="#ufoActionModal" data-modal-title="Edit Visi & Misi" data-modal-message="Modal ini menampilkan form pengeditan visi dan misi.">
                    <i class="bi bi-pencil-square" aria-hidden="true"></i>
                    <span>Edit</span>
                </button>
            </div>
            <div class="ufo-pg-field-grid">
                <div class="ufo-pg-field-group ufo-pg-field-group-full">
                    <label>Visi Organisasi</label>
                    <textarea name="vision" class="ufo-pg-textarea-md" @disabled(!($hasOrganizationContext ?? false))>{{ $orgData['vision'] }}</textarea>
                </div>
                <div class="ufo-pg-field-group ufo-pg-field-group-full">
                    <label>Misi Organisasi</label>
                    <textarea name="mission" class="ufo-pg-textarea-lg" @disabled(!($hasOrganizationContext ?? false))>{{ $orgData['mission'] }}</textarea>
                    <small class="text-muted d-block mt-2">Pisahkan misi dengan baris baru jika diperlukan</small>
                </div>
            </div>
        </article>

        <article class="ufo-pg-settings-card">
            <div class="ufo-pg-card-head">
                <h2>Kontak &amp; Sosial Media</h2>
            </div>
            <div class="ufo-pg-field-grid">
                <div class="ufo-pg-field-group">
                    <label>Email Organisasi</label>
                    <input type="email" name="email" value="{{ $orgData['email'] }}" @disabled(!($hasOrganizationContext ?? false))>
                </div>
                <div class="ufo-pg-field-group">
                    <label>Nomor Telepon</label>
                    <input type="tel" name="phone" value="{{ $orgData['phone'] }}" @disabled(!($hasOrganizationContext ?? false))>
                </div>
                <div class="ufo-pg-field-group">
                    <label>Instagram</label>
                    <input type="text" name="instagram" value="{{ $orgData['instagram'] }}" @disabled(!($hasOrganizationContext ?? false))>
                </div>
                <div class="ufo-pg-field-group">
                    <label>LINE Official Account</label>
                    <input type="text" name="line" value="{{ $orgData['line'] }}" @disabled(!($hasOrganizationContext ?? false))>
                </div>
            </div>

            <div class="ufo-pg-settings-actions">
                    <button type="submit" class="btn-primary-org" data-bs-toggle="modal" data-bs-target="#ufoActionModal" data-modal-title="Simpan Perubahan" data-modal-message="Aksi simpan dibuka sebagai modal konfirmasi." @disabled(!($hasOrganizationContext ?? false))>Simpan Perubahan</button>
                <a href="{{ route('portal.pengurus.settings') }}" class="btn-secondary-org">Muat Ulang</a>
            </div>
        </article>

</form>
</section>
@endsection
