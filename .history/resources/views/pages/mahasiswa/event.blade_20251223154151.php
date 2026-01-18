@extends('layouts.mahasiswa')

@section('title', 'Event Organisasi - UFO')

@section('content')
<div class="mahasiswa-event">
    <div class="page-header">
        <h1>Event Organisasi</h1>
        <p class="page-subtitle">Temukan event menarik dari berbagai organisasi</p>
    </div>

    <div class="filter-section">
        <input type="search" class="filter-input" placeholder="Cari event...">
    </div>

    <div class="event-grid">
        <div class="event-detail-card">
            <div class="event-detail-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
            <div class="event-detail-content">
                <span class="event-badge">Workshop</span>
                <h3>Workshop Coding & Web Development</h3>
                <div class="event-meta">
                    <span>📅 23 Desember 2024</span>
                    <span>⏰ 13:00 - 17:00</span>
                    <span>📍 Ruang Lab A</span>
                </div>
                <p class="event-desc">Belajar fundamental web development dengan HTML, CSS, dan JavaScript dari praktisi industri.</p>
                <button class="btn btn-primary">Daftar</button>
            </div>
        </div>

        <div class="event-detail-card">
            <div class="event-detail-image" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);"></div>
            <div class="event-detail-content">
                <span class="event-badge">Seminar</span>
                <h3>Seminar Karir & Entrepreneurship</h3>
                <div class="event-meta">
                    <span>📅 28 Desember 2024</span>
                    <span>⏰ 10:00 - 12:00</span>
                    <span>📍 Aula Utama</span>
                </div>
                <p class="event-desc">Dengarkan kisah sukses entrepreneur dan profesional tentang persiapan memasuki dunia kerja.</p>
                <button class="btn btn-primary">Daftar</button>
            </div>
        </div>

        <div class="event-detail-card">
            <div class="event-detail-image" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"></div>
            <div class="event-detail-content">
                <span class="event-badge">Gathering</span>
                <h3>Gathering & Networking BEM UNKLAB</h3>
                <div class="event-meta">
                    <span>📅 30 Desember 2024</span>
                    <span>⏰ 18:00 - 21:00</span>
                    <span>📍 Outdoor Area</span>
                </div>
                <p class="event-desc">Acara gathering untuk mempererat silaturahmi antar organisasi di UNKLAB.</p>
                <button class="btn btn-primary">Daftar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="/js/mahasiswa.js"></script>
@endsection
