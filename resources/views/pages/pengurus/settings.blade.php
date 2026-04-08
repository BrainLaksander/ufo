@extends('layouts.pengurus')

@section('title', 'Pengaturan Organisasi')

@section('content')
<div class="page-header mb-4">
    <h1>Pengaturan Organisasi</h1>
    <p class="page-subtitle">Kelola informasi dan pengaturan organisasi Anda</p>
</div>

<div class="form-org">
    <h3 class="text-primary mb-4" style="font-weight: 700;"> Informasi Dasar Organisasi</h3>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Nama Organisasi</label>
                <input type="text" value="{{ $orgData['name'] }}" disabled style="background: #f0f0f0; cursor: not-allowed;">
                <small class="text-muted d-block mt-2"> Nama organisasi tidak dapat diubah</small>
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
        <textarea style="min-height: 100px;">{{ $orgData['description'] }}</textarea>
    </div>

    <hr class="my-4">

    <h3 class="text-primary mb-4" style="font-weight: 700;"> Visi & Misi</h3>

    <div class="form-group">
        <label>Visi Organisasi</label>
        <textarea style="min-height: 100px;">{{ $orgData['vision'] }}</textarea>
    </div>

    <div class="form-group">
        <label>Misi Organisasi</label>
        <textarea placeholder="Masukkan misi, pisahkan dengan baris baru..." style="min-height: 150px;">{{ implode("\n", $orgData['mission']) }}</textarea>
        <small class="text-muted d-block mt-2"> Setiap baris misi akan dipisahkan sebagai item berbeda</small>
    </div>

    <hr class="my-4">

    <h3 class="text-primary mb-4" style="font-weight: 700;"> Kontak & Sosial Media</h3>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Email Organisasi</label>
                <input type="email" value="{{ $orgData['email'] }}" placeholder="email@organisasi.ac.id">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="tel" value="{{ $orgData['phone'] }}" placeholder="+62 431 891 035">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Instagram</label>
                <input type="text" value="{{ $orgData['instagram'] }}" placeholder="@organisasi_unklab">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>LINE Official Account</label>
                <input type="text" value="{{ $orgData['line'] }}" placeholder="@organisasi_unklab">
            </div>
        </div>
    </div>

    <hr class="my-4">

    <h3 class="text-primary mb-4" style="font-weight: 700;"> Logo & Banner</h3>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Upload Logo Organisasi</label>
                <input type="file" accept="image/*">
                <small class="text-muted d-block mt-2">Format: PNG (recommended) | Max: 5MB</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Upload Banner Organisasi</label>
                <input type="file" accept="image/*">
                <small class="text-muted d-block mt-2">Format: JPG, PNG | Ukuran: 1200x400px | Max: 10MB</small>
            </div>
        </div>
    </div>

    <div style="display: flex; gap: 10px; margin-top: 30px;">
        <button type="submit" class="btn-primary-org"> Simpan Perubahan</button>
        <button type="button" class="btn-secondary-org">↻ Batal</button>
    </div>
</div>
@endsection
