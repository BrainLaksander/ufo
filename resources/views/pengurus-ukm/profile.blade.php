@extends('layouts.app')

@section('title', 'Profil Organisasi - Pengurus UKM')

@push('head')
<style>
</style>
@endpush

@section('content')
<div class="content" style="padding: 36px 40px;">
    <div style="margin-bottom: 28px;">
        <h1 style="color: #5e3191; font-size: 28px; margin: 0 0 6px;">Profil Organisasi</h1>
        <p style="color: #6b7280; font-size: 15px; margin: 0;">Kelola informasi dan struktur pengurus organisasi Anda</p>
    </div>

    @if(session('status'))
        <div style="background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; border-radius: 12px; padding: 12px 16px; font-size: 14px; margin-bottom: 20px;">
            ✓ {{ session('status') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; border-radius: 12px; padding: 12px 16px; font-size: 14px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; border-radius: 12px; padding: 12px 16px; font-size: 14px; margin-bottom: 20px;">
            {{ $errors->first() }}
        </div>
    @endif

    @if(!$organization)
        <div style="text-align: center; padding: 60px 20px; background: #f9fafb; border-radius: 16px;">
            <h3 style="color: #4b5563; margin-bottom: 8px;">Organisasi Tidak Ditemukan</h3>
            <p style="color: #6b7280; font-size: 14px;">Akun Anda belum dikaitkan dengan organisasi mana pun. Hubungi pihak Kemahasiswaan.</p>
        </div>
    @else
        {{-- Tabs --}}
        <div class="profile-tabs" style="display: flex; gap: 0; margin-bottom: 28px; border-bottom: 2px solid #e5e7eb; overflow-x: auto;">
            <button type="button" class="profile-tab active" data-tab="info" style="padding: 12px 24px; border: none; background: transparent; font-size: 15px; font-weight: 700; color: #6b7280; cursor: pointer; border-bottom: 3px solid transparent; margin-bottom: -2px; transition: all .2s ease; white-space: nowrap;">
                Informasi Dasar
            </button>
            <button type="button" class="profile-tab" data-tab="sosmed" style="padding: 12px 24px; border: none; background: transparent; font-size: 15px; font-weight: 700; color: #6b7280; cursor: pointer; border-bottom: 3px solid transparent; margin-bottom: -2px; transition: all .2s ease; white-space: nowrap;">
                Kontak & Sosmed
            </button>

            <button type="button" class="profile-tab" data-tab="struktur" style="padding: 12px 24px; border: none; background: transparent; font-size: 15px; font-weight: 700; color: #6b7280; cursor: pointer; border-bottom: 3px solid transparent; margin-bottom: -2px; transition: all .2s ease; white-space: nowrap;">
                Struktur Organisasi
            </button>
            <button type="button" class="profile-tab" data-tab="rekrutmen" style="padding: 12px 24px; border: none; background: transparent; font-size: 15px; font-weight: 700; color: #6b7280; cursor: pointer; border-bottom: 3px solid transparent; margin-bottom: -2px; transition: all .2s ease; white-space: nowrap;">
                Info Pendaftaran (Oprec)
            </button>
            <button type="button" class="profile-tab" data-tab="password" style="padding: 12px 24px; border: none; background: transparent; font-size: 15px; font-weight: 700; color: #6b7280; cursor: pointer; border-bottom: 3px solid transparent; margin-bottom: -2px; transition: all .2s ease; white-space: nowrap;">
                Ganti Password
            </button>
        </div>

        {{-- Tab 1: Informasi Dasar --}}
        <div class="profile-panel" data-panel="info">
            <form method="POST" action="{{ route('pengurus-ukm.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Banner Upload --}}
                <div style="position: relative; background: #fff; border-radius: 16px; box-shadow: 0 1px 6px rgba(0,0,0,.06); margin-bottom: 20px;">
                    <div id="bannerArea" style="position: relative; width: 100%; height: 200px; background: linear-gradient(135deg, #6f3ba7 0%, #a476d1 50%, #f0b84c 100%); cursor: pointer; overflow: hidden; border-top-left-radius: 16px; border-top-right-radius: 16px;" onclick="document.getElementById('bannerInput').click();">
                        @if($organization->banner_path)
                            <img id="bannerPreview" src="{{ Storage::url($organization->banner_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <img id="bannerPreview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                        @endif
                        <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,.25); opacity: 0; transition: opacity .2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
                            <span style="color: #fff; font-weight: 700; font-size: 14px; background: rgba(0,0,0,.4); padding: 8px 16px; border-radius: 8px;">Ganti Banner</span>
                        </div>
                        <input type="file" id="bannerInput" name="banner" accept="image/*" style="display: none;" onchange="previewImage(this, 'bannerPreview')">
                    </div>

                    {{-- Logo Upload --}}
                    <div style="position: absolute; top: 155px; left: 28px; z-index: 10;">
                        <div id="logoArea" style="width: 90px; height: 90px; border-radius: 50%; border: 4px solid #fff; background: linear-gradient(135deg, #6f3ba7, #a476d1); cursor: pointer; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,.15); display: flex; align-items: center; justify-content: center; position: relative;" onclick="document.getElementById('logoInput').click();">
                            @if($organization->logo_path)
                                <img id="logoPreview" src="{{ Storage::url($organization->logo_path) }}" style="width: 100%; height: 100%; object-fit: cover; position: relative; z-index: 1;">
                            @else
                                <span id="logoInitial" style="color: #fff; font-size: 32px; font-weight: 800; position: relative; z-index: 1;">{{ strtoupper(substr($organization->name, 0, 1)) }}</span>
                                <img id="logoPreview" style="width: 100%; height: 100%; object-fit: cover; display: none; position: absolute; inset: 0; z-index: 2;">
                            @endif
                        </div>
                        <input type="file" id="logoInput" name="logo" accept="image/*" style="display: none;" onchange="previewLogo(this)">
                    </div>

                    <div style="padding: 55px 28px 24px;">
                        <h3 style="color: #1e1b4b; font-size: 18px; font-weight: 700; margin: 0 0 4px;">{{ $organization->name }}</h3>
                        <p style="color: #6b7280; font-size: 14px; margin: 0;">{{ $organization->kategori }}</p>
                    </div>
                </div>

                <div style="background: #fff; border-radius: 16px; padding: 28px; box-shadow: 0 1px 6px rgba(0,0,0,.06); margin-bottom: 20px;">
                    <h3 style="color: #1e1b4b; font-size: 18px; font-weight: 700; margin: 0 0 20px;">Informasi Organisasi</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Nama Organisasi *</label>
                            <input type="text" name="name" value="{{ old('name', $organization->name) }}" required
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s;">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Kategori</label>
                            <input type="text" value="{{ $organization->kategori }}" disabled
                                style="width: 100%; padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 15px; background: #f3f4f6; color: #6b7280; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Level</label>
                            <input type="text" value="{{ $organization->level }}" disabled
                                style="width: 100%; padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 15px; background: #f3f4f6; color: #6b7280; box-sizing: border-box;">
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Motto Organisasi</label>
                            <input type="text" name="motto" value="{{ old('motto', $organization->motto) }}" placeholder="Contoh: Bersama Membangun, Bersama Melayani"
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s;">
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Deskripsi Singkat</label>
                            <textarea name="description" rows="3" placeholder="Deskripsi singkat mengenai organisasi"
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; font-family: inherit; resize: vertical; box-sizing: border-box;">{{ old('description', $organization->description) }}</textarea>
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Visi</label>
                            <textarea name="visi" rows="3" placeholder="Visi organisasi"
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; font-family: inherit; resize: vertical; box-sizing: border-box;">{{ old('visi', $organization->visi) }}</textarea>
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Misi (pisahkan dengan enter atau baris baru)</label>
                            <textarea name="misi" rows="4" placeholder="- Misi pertama&#10;- Misi kedua"
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; font-family: inherit; resize: vertical; box-sizing: border-box;">{{ old('misi', $organization->misi) }}</textarea>
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Budaya & Nilai</label>
                            <textarea name="budaya_nilai" rows="3" placeholder="Contoh: Integritas, Kolaborasi, Inovasi, dan Pelayanan"
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; font-family: inherit; resize: vertical; box-sizing: border-box;">{{ old('budaya_nilai', $organization->budaya_nilai) }}</textarea>
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Program Kegiatan (pisahkan dengan enter)</label>
                            <textarea name="program_kegiatan" rows="4" placeholder="- Orientasi Mahasiswa Baru&#10;- Kampanye Sosial"
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; font-family: inherit; resize: vertical; box-sizing: border-box;">{{ old('program_kegiatan', $organization->program_kegiatan) }}</textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" style="padding: 12px 32px; background: #5e3191; color: #fff; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; transition: background .2s;">
                    Simpan Informasi
                </button>
            </form>
        </div>

        {{-- Tab 2: Kontak & Sosmed --}}
        <div class="profile-panel" data-panel="sosmed" style="display: none;">
            <form method="POST" action="{{ route('pengurus-ukm.profile.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="name" value="{{ $organization->name }}">
                
                <div style="background: #fff; border-radius: 16px; padding: 28px; box-shadow: 0 1px 6px rgba(0,0,0,.06); margin-bottom: 20px;">
                    <h3 style="color: #1e1b4b; font-size: 18px; font-weight: 700; margin: 0 0 20px;">Kontak & Media Sosial</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Instagram URL</label>
                            <input type="url" name="instagram" value="{{ old('instagram', $organization->instagram) }}" placeholder="https://instagram.com/bemunklab"
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s;">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">WhatsApp (Nomor)</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $organization->whatsapp) }}" placeholder="08xx-xxxx-xxxx"
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s;">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Website URL</label>
                            <input type="url" name="website" value="{{ old('website', $organization->website) }}" placeholder="https://bem.unklab.ac.id"
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s;">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Jumlah Anggota Aktif</label>
                            <input type="number" name="member_count" value="{{ old('member_count', $organization->member_count) }}" placeholder="0" min="0"
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s;">
                        </div>
                    </div>
                </div>

                <button type="submit" style="padding: 12px 32px; background: #5e3191; color: #fff; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; transition: background .2s;">
                    Simpan Kontak & Sosmed
                </button>
            </form>
        </div>


        {{-- Tab 3: Struktur Organisasi --}}
        <div class="profile-panel" data-panel="struktur" style="display: none;">
            <form method="POST" action="{{ route('pengurus-ukm.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="name" value="{{ $organization->name }}">

                <div style="background: #fff; border-radius: 16px; padding: 28px; box-shadow: 0 1px 6px rgba(0,0,0,.06); margin-bottom: 20px;">
                    <h3 style="color: #1e1b4b; font-size: 18px; font-weight: 700; margin: 0 0 20px;">Ketua Organisasi</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Nama Lengkap</label>
                            <select name="ketua_name" style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s; background-color: #fff;">
                                <option value="" selected>Pilih Ketua</option>
                                @foreach($students as $student)
                                    <option value="{{ $student }}" {{ old('ketua_name', $organization->ketua_name) == $student ? 'selected' : '' }}>{{ $student }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Email</label>
                            <input type="email" name="chair_email" value="{{ old('chair_email', $organization->chair_email) }}" placeholder="Email Ketua"
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s;">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">No. HP / WhatsApp</label>
                            <input type="text" name="chair_phone" value="{{ old('chair_phone', $organization->chair_phone) }}" placeholder="08xx..."
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s;">
                        </div>
                    </div>
                </div>

                <div style="background: #fff; border-radius: 16px; padding: 28px; box-shadow: 0 1px 6px rgba(0,0,0,.06); margin-bottom: 20px;">
                    <h3 style="color: #1e1b4b; font-size: 18px; font-weight: 700; margin: 0 0 20px;">Sekretaris</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Nama Lengkap</label>
                            <select name="secretary_name" style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s; background-color: #fff;">
                                <option value="" selected>Pilih Sekretaris</option>
                                @foreach($students as $student)
                                    <option value="{{ $student }}" {{ old('secretary_name', $organization->secretary_name) == $student ? 'selected' : '' }}>{{ $student }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Email</label>
                            <input type="email" name="secretary_email" value="{{ old('secretary_email', $organization->secretary_email) }}" placeholder="Email Sekretaris"
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s;">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">No. HP / WhatsApp</label>
                            <input type="text" name="secretary_phone" value="{{ old('secretary_phone', $organization->secretary_phone) }}" placeholder="08xx..."
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s;">
                        </div>
                    </div>
                </div>

                <div style="background: #fff; border-radius: 16px; padding: 28px; box-shadow: 0 1px 6px rgba(0,0,0,.06); margin-bottom: 20px;">
                    <h3 style="color: #1e1b4b; font-size: 18px; font-weight: 700; margin: 0 0 20px;">Bendahara</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Nama Lengkap</label>
                            <select name="treasurer_name" style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s; background-color: #fff;">
                                <option value="" selected>Pilih Bendahara</option>
                                @foreach($students as $student)
                                    <option value="{{ $student }}" {{ old('treasurer_name', $organization->treasurer_name) == $student ? 'selected' : '' }}>{{ $student }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Email</label>
                            <input type="email" name="treasurer_email" value="{{ old('treasurer_email', $organization->treasurer_email) }}" placeholder="Email Bendahara"
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s;">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">No. HP / WhatsApp</label>
                            <input type="text" name="treasurer_phone" value="{{ old('treasurer_phone', $organization->treasurer_phone) }}" placeholder="08xx..."
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s;">
                        </div>
                    </div>
                </div>

                <button type="submit" style="padding: 12px 32px; background: #5e3191; color: #fff; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; transition: background .2s;">
                    Simpan Struktur Organisasi
                </button>
            </form>
        </div>


        {{-- Tab 4: Info Pendaftaran (Oprec) --}}
        <div class="profile-panel" data-panel="rekrutmen" style="display: none;">
            <form method="POST" action="{{ route('pengurus-ukm.profile.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="name" value="{{ $organization->name }}">

                <div style="background: #fff; border-radius: 16px; padding: 28px; box-shadow: 0 1px 6px rgba(0,0,0,.06); margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="color: #1e1b4b; font-size: 18px; font-weight: 700; margin: 0;">Pengaturan Rekrutmen Anggota Baru</h3>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="hidden" name="is_open_recruitment" value="0">
                            <input type="checkbox" id="is_open_recruitment_checkbox" name="is_open_recruitment" value="1" {{ $organization->is_open_recruitment ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: #10b981;" onchange="document.getElementById('is_open_recruitment_text').style.color = this.checked ? '#10b981' : '#6b7280'">
                            <span id="is_open_recruitment_text" style="font-weight: 700; font-size: 15px; color: {{ $organization->is_open_recruitment ? '#10b981' : '#6b7280' }}; transition: color 0.2s;">
                                Buka Pendaftaran (Open Recruitment)
                            </span>
                        </label>
                    </div>

                    <div style="display: grid; gap: 16px;">
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Link Form Pendaftaran (Google Form, dll)</label>
                            <input type="url" name="recruitment_link" value="{{ old('recruitment_link', $organization->recruitment_link ?? '') }}" placeholder="https://forms.gle/..."
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; box-sizing: border-box; transition: border .2s;">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 600; font-size: 14px; color: #374151; margin-bottom: 6px;">Persyaratan Pendaftaran</label>
                            <textarea name="recruitment_req" rows="5" placeholder="- Mahasiswa aktif semester 1-3&#10;- Memiliki minat di bidang IT"
                                style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; font-family: inherit; resize: vertical; box-sizing: border-box;">{{ old('recruitment_req', $organization->recruitment_req ?? '') }}</textarea>
                            <small style="color: #6b7280; font-size: 13px; display: block; margin-top: 6px;">Pisahkan dengan garis baru (Enter) agar berbentuk list yang rapi.</small>
                        </div>
                    </div>
                </div>

                <button type="submit" style="padding: 12px 32px; background: #10b981; color: #fff; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; transition: background .2s;">
                    Simpan Pengaturan Pendaftaran
                </button>
            </form>
        </div>
        {{-- Tab 4: Ganti Password --}}
        <div class="profile-panel" data-panel="password" style="display: none;">
            <div style="background: #fff; border-radius: 16px; padding: 28px; box-shadow: 0 1px 6px rgba(0,0,0,.06);">
                <div style="display: flex; align-items: flex-start; gap: 16px;">
                    <div style="width: 48px; height: 48px; min-width: 48px; background: linear-gradient(135deg, #5e3191, #7c3aed); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                    <div style="flex: 1;">
                        <h3 style="color: #1e1b4b; font-size: 18px; font-weight: 700; margin: 0 0 6px;">Reset Password via Email</h3>
                        <p style="color: #6b7280; font-size: 14px; margin: 0 0 20px; line-height: 1.6;">
                            Untuk keamanan akun, perubahan password dilakukan melalui email. Kami akan mengirimkan link reset password ke email terdaftar akun organisasi Anda. Link berlaku selama <strong>60 menit</strong>.
                        </p>

                        {{-- Show registered email --}}
                        <div style="display: flex; align-items: center; gap: 10px; background: #f5f3ff; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; max-width: 500px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#5e3191" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <span style="font-size: 14px; color: #374151; font-weight: 500;">{{ auth()->user()->email }}</span>
                        </div>

                        @if(session('status'))
                            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 16px; max-width: 500px;">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 16px; max-width: 500px;">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('pengurus-ukm.profile.send-reset-link') }}" style="display: inline;">
                            @csrf
                            <button type="submit" style="padding: 12px 28px; background: linear-gradient(135deg, #5e3191 0%, #7c3aed 100%); color: #fff; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; transition: transform .15s, box-shadow .15s; box-shadow: 0 4px 12px rgba(94,49,145,0.25);"
                                onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(94,49,145,0.35)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(94,49,145,0.25)'">
                                Kirim Link Reset Password
                            </button>
                        </form>

                        <div style="margin-top: 20px; padding: 14px 16px; background: #fffbeb; border: 1px solid #fcd34d; border-radius: 10px; max-width: 500px;">
                            <p style="margin: 0; color: #92400e; font-size: 13px; line-height: 1.5;">
                                <strong>Catatan:</strong> Pastikan Anda memiliki akses ke email di atas. Jika email tidak diterima, periksa folder spam atau hubungi Kemahasiswaan untuk bantuan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tabs = document.querySelectorAll('.profile-tab');
        var panels = document.querySelectorAll('.profile-panel');

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var target = this.getAttribute('data-tab');

                tabs.forEach(function(t) {
                    t.classList.remove('active');
                    t.style.color = '#6b7280';
                    t.style.borderBottomColor = 'transparent';
                });

                panels.forEach(function(p) {
                    p.style.display = 'none';
                });

                this.classList.add('active');
                this.style.color = '#5e3191';
                this.style.borderBottomColor = '#5e3191';

                var panel = document.querySelector('[data-panel="' + target + '"]');
                if (panel) panel.style.display = 'block';
            });
        });

        // Set initial active tab style
        var activeTab = document.querySelector('.profile-tab.active');
        if (activeTab) {
            activeTab.style.color = '#5e3191';
            activeTab.style.borderBottomColor = '#5e3191';
        }
    });

    function previewImage(input, previewId) {
        var preview = document.getElementById(previewId);
        if (input.files && input.files[0] && preview) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewLogo(input) {
        var preview = document.getElementById('logoPreview');
        var initial = document.getElementById('logoInitial');
        if (input.files && input.files[0] && preview) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (initial) initial.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewPengurusImg(input, previewId, placeholderId) {
        var preview = document.getElementById(previewId);
        var placeholder = document.getElementById(placeholderId);
        if (input.files && input.files[0] && preview) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }


</script>
@endpush
