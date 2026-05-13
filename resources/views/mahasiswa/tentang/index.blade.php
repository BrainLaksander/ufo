@extends('layouts.app')

@section('title', 'Tentang UFO')

@section('content')
    <div class="content about-page">
        <section class="about-hero about-hero-wide">
            <div class="about-hero-inner">
                <h1>Tentang UFO</h1>
                <p>Unklab Forum Organization - Platform Digital untuk Mahasiswa Universitas Klabat</p>
            </div>
        </section>

        <section class="about-intro about-card">
            <div class="about-card-head">
                <span class="about-badge about-badge-purple">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12h16"/><path d="M12 4v16"/><circle cx="12" cy="12" r="8"/></svg>
                </span>
                <h2>Apa itu UFO?</h2>
            </div>
            <p>UFO (Unklab Forum Organization) adalah platform digital yang dirancang khusus untuk mahasiswa Universitas Klabat. Sistem ini bertujuan untuk mempermudah akses informasi seputar organisasi kampus, acara-acara yang berlangsung, pengumuman penting, serta membantu mahasiswa dalam menemukan barang hilang.</p>
            <p>Dengan UFO, mahasiswa dapat dengan mudah menjelajahi berbagai organisasi yang ada di kampus, mendaftar ke acara-acara menarik, melihat pengumuman terkini, dan melaporkan atau mencari barang yang hilang.</p>
        </section>

        <section class="about-dual-grid">
            <article class="about-card about-card-small">
                <div class="about-card-head">
                    <span class="about-badge about-badge-gold">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8"/><path d="M12 8v4l3 2"/></svg>
                    </span>
                    <h2>Visi</h2>
                </div>
                <p>Menjadi platform digital terpadu yang memfasilitasi kehidupan kampus yang lebih terorganisir, informatif, dan kolaboratif untuk seluruh civitas akademika Universitas Klabat.</p>
            </article>

            <article class="about-card about-card-small">
                <div class="about-card-head">
                    <span class="about-badge about-badge-red">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2 9 8l-6 1 4 4-1 6 6-3 6 3-1-6 4-4-6-1-3-6Z"/></svg>
                    </span>
                    <h2>Misi</h2>
                </div>
                <ul class="about-list">
                    <li>Menyediakan informasi organisasi dan acara kampus yang akurat dan terkini</li>
                    <li>Memfasilitasi komunikasi antara mahasiswa dan organisasi</li>
                    <li>Membantu mahasiswa dalam menemukan barang hilang dengan cepat</li>
                </ul>
            </article>
        </section>

        <section class="about-feature-panel">
            <h2>Fitur Utama UFO</h2>
            <div class="feature-grid">
                <article class="feature-card feature-purple">
                    <span class="feature-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 9.5A3.5 3.5 0 1 1 7.5 6 3.5 3.5 0 0 1 4 9.5Zm0 0v2.5"/><path d="M20 10.5A3.5 3.5 0 1 1 16.5 7 3.5 3.5 0 0 1 20 10.5Zm0 0v1.5"/><path d="M8 20v-2a4 4 0 0 1 8 0v2"/></svg>
                    </span>
                    <h3>Organisasi</h3>
                    <p>Jelajahi berbagai organisasi kampus dan temukan komunitas yang sesuai dengan minatmu</p>
                </article>

                <article class="feature-card feature-gold">
                    <span class="feature-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="3" ry="3"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
                    </span>
                    <h3>Event</h3>
                    <p>Lihat dan daftar ke berbagai acara menarik yang diselenggarakan di kampus</p>
                </article>

                <article class="feature-card feature-red">
                    <span class="feature-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="11" r="4"/><path d="M12 21s6-4.5 6-10a6 6 0 0 0-12 0c0 5.5 6 10 6 10Z"/></svg>
                    </span>
                    <h3>Lost &amp; Found</h3>
                    <p>Laporkan barang hilang atau temukan pemilik barang yang kamu temukan</p>
                </article>

                <article class="feature-card feature-blue">
                    <span class="feature-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h9l6-3v16l-6-3H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2Z"/><path d="M8 16v3a2 2 0 0 0 2 2h1"/></svg>
                    </span>
                    <h3>Pengumuman</h3>
                    <p>Dapatkan informasi dan pengumuman penting dari kampus secara real-time</p>
                </article>
            </div>
        </section>

        <section class="contact-panel">
            <h2>Hubungi Kami</h2>
            <div class="contact-grid">
                <div class="contact-item">
                    <span class="contact-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16v12H4z"/><path d="m4 6 8 6 8-6"/></svg></span>
                    <h3>Email</h3>
                    <p>ufo@unklab.ac.id</p>
                </div>
                <div class="contact-item">
                    <span class="contact-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1A19.4 19.4 0 0 1 5.2 14 19.8 19.8 0 0 1 2.1 5.4 2 2 0 0 1 4.1 3h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.6 2.6a2 2 0 0 1-.5 2.1L8 10.6a16 16 0 0 0 5.4 5.4l1.2-1.2a2 2 0 0 1 2.1-.5c.8.3 1.7.5 2.6.6A2 2 0 0 1 22 16.9Z"/></svg></span>
                    <h3>Telepon</h3>
                    <p>+62 431 891 035</p>
                </div>
                <div class="contact-item">
                    <span class="contact-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s6-4.5 6-10a6 6 0 0 0-12 0c0 5.5 6 10 6 10Z"/><circle cx="12" cy="11" r="2.5"/></svg></span>
                    <h3>Alamat</h3>
                    <p>Universitas Klabat, Airmadidi, Sulawesi Utara</p>
                </div>
            </div>
        </section>
    </div>
@endsection
