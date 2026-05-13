@extends('layouts.app')

@section('title', 'Edit Organisasi - Manajemen Organisasi')

@section('content')
<div style="padding:24px; max-width:900px; margin:0 auto">
    <h1 style="font-size:22px; font-weight:800; margin-bottom:12px">Edit Organisasi</h1>
    <p style="margin:0 0 18px; color:#6b7280">Ubah data organisasi dan struktur pengurus.</p>

    <form method="POST" action="{{ route('kemahasiswaan.organizations.update', $organization->id) }}">
        @csrf
        @method('PUT')

        <div style="display:grid; gap:12px;">
            <label>Nama Organisasi</label>
            <input name="name" value="{{ old('name', $organization->name) }}" required>
            @error('name') <div style="color:#dc2626">{{ $message }}</div> @enderror

            <label>Kategori</label>
            <input name="kategori" value="{{ old('kategori', $organization->kategori) }}" required>
            @error('kategori') <div style="color:#dc2626">{{ $message }}</div> @enderror

            <label>Bidang</label>
            <input name="field" value="{{ old('field', $organization->field) }}">

            <label>Level</label>
            <input name="level" value="{{ old('level', $organization->level) }}">

            <label>Deskripsi</label>
            <textarea name="description">{{ old('description', $organization->description) }}</textarea>

            <h3 style="margin-top:12px">Dosen Pembina / Advisor</h3>

            <label>Pembina/Advisor (Pilih dari Data User)</label>
            <select name="advisor_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="">-- Pilih Pembina --</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ old('advisor_id', $organization->advisor_id) == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->email }})
                    </option>
                @endforeach
            </select>
            @error('advisor_id') <div style="color:#dc2626">{{ $message }}</div> @enderror

            <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:18px">
                <a href="{{ route('kemahasiswaan.organizations.index') }}" class="btn-secondary" style="padding:10px 14px; border-radius:8px; text-decoration:none; background:#f3f4f6; color:#111827">Batal</a>
                <button type="submit" class="btn-primary" style="padding:10px 14px; border-radius:8px">Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
@endsection
