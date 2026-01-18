@extends('layouts.mahasiswa')

@section('title', 'Daftar Organisasi - UFO')

@section('content')
<div class="mahasiswa-organisasi">
    <div class="page-header">
        <h1>Daftar Organisasi</h1>
        <p class="page-subtitle">Pilih organisasi yang ingin Anda ikuti</p>
    </div>

    <div class="filter-section">
        <select id="filter-kategori" class="filter-input">
            <option value="">Semua Kategori</option>
            <option value="himpunan">Himpunan</option>
            <option value="bem">BEM</option>
            <option value="ukm">UKM</option>
        </select>
    </div>

    <div class="org-list">
        <div class="org-item">
            <div class="org-item-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
            <div class="org-item-content">
                <h3>HIMA Teknik Informatika</h3>
                <p class="org-category">Himpunan</p>
                <p class="org-desc">Organisasi untuk mahasiswa Teknik Informatika yang peduli dengan pengembangan teknologi.</p>
                <div class="org-stats">
                    <span>👥 245 Anggota</span>
                    <span>📅 20 Event/Tahun</span>
                </div>
                <button class="btn btn-primary">Lihat Profil</button>
            </div>
        </div>

        <div class="org-item">
            <div class="org-item-image" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);"></div>
            <div class="org-item-content">
                <h3>HIMA Bisnis</h3>
                <p class="org-category">Himpunan</p>
                <p class="org-desc">Organisasi untuk mahasiswa program studi Bisnis.</p>
                <div class="org-stats">
                    <span>👥 180 Anggota</span>
                    <span>📅 15 Event/Tahun</span>
                </div>
                <button class="btn btn-primary">Lihat Profil</button>
            </div>
        </div>

        <div class="org-item">
            <div class="org-item-image" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"></div>
            <div class="org-item-content">
                <h3>BEM UNKLAB 2024</h3>
                <p class="org-category">BEM</p>
                <p class="org-desc">Badan Eksekutif Mahasiswa UNKLAB untuk tahun 2024.</p>
                <div class="org-stats">
                    <span>👥 50 Anggota</span>
                    <span>📅 25 Event/Tahun</span>
                </div>
                <button class="btn btn-primary">Lihat Profil</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="/js/mahasiswa.js"></script>
@endsection
