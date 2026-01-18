@extends('layouts.mahasiswa')

@section('title', 'Organisasi & Kegiatan - UFO')

@section('content')
<div class="mahasiswa-dashboard">
    <div class="page-header">
        <h1>Organisasi & Kegiatan UNKLAB</h1>
        <p class="page-subtitle">Temukan informasi tentang semua organisasi dan kegiatan di kampus</p>
    </div>

    <section class="section featured">
        <h2>Organisasi Unggulan</h2>
        <div class="org-grid">
            <div class="org-card">
                <div class="org-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                <h3>HIMA Teknik</h3>
                <p>Himpunan Mahasiswa Teknik</p>
                <button class="btn btn-primary">Lihat</button>
            </div>
            <div class="org-card">
                <div class="org-image" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);"></div>
                <h3>HIMA Bisnis</h3>
                <p>Himpunan Mahasiswa Bisnis</p>
                <button class="btn btn-primary">Lihat</button>
            </div>
            <div class="org-card">
                <div class="org-image" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"></div>
                <h3>BEM UNKLAB</h3>
                <p>Badan Eksekutif Mahasiswa</p>
                <button class="btn btn-primary">Lihat</button>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Event Mendatang</h2>
        <div class="event-list">
            <div class="event-card">
                <div class="event-date">23 Des</div>
                <div class="event-content">
                    <h4>Workshop Coding 2024</h4>
                    <p>Belajar dasar-dasar pemrograman web</p>
                </div>
            </div>
            <div class="event-card">
                <div class="event-date">28 Des</div>
                <div class="event-content">
                    <h4>Seminar Karir</h4>
                    <p>Persiapan dunia kerja</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
