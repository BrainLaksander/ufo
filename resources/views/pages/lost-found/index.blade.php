@extends('layouts.mahasiswa')

@section('title', 'Lost & Found')

@section('content')
<link rel="stylesheet" href="{{ asset('css/lost-found.css') }}">

<div class="lf-page">
  <!-- Main Container -->
  <div class="lf-container">
    
    <!-- Header Section -->
    <div class="lf-header">
      <div>
        <h1 class="lf-title">🔍 Lost & Found</h1>
        <p class="lf-subtitle">
          Sistem pelaporan barang hilang & ditemukan - Cari barang Anda atau laporkan barang yang ditemukan
        </p>
      </div>
      <button class="lf-btn-primary">
        + Laporkan Barang
      </button>
    </div>

    <!-- Priority Section - Red Background -->
    <section class="lf-priority-section">
      <div class="lf-priority-header">
        <h2 class="lf-priority-title">🔔 Barang Penting yang Hilang</h2>
        <p class="lf-priority-subtitle">Segera hubungi jika menemukan barang ini</p>
      </div>

      <div class="lf-priority-grid">
        <!-- Priority Item 1 -->
        <div class="lf-card-priority">
          <div class="lf-card-header">
            <span class="lf-badge-priority">PRIORITAS</span>
          </div>
          <div class="lf-card-icon">💼</div>
          <h3 class="lf-card-title">Dompet Kulit Hitam</h3>
          <p class="lf-card-category">Dompet</p>
          <p class="lf-card-description">Dompet kulit hitam dengan inisial AB di dalam</p>
          <div class="lf-card-meta">
            <small>📍 Aula Utama</small>
            <small>📅 2024-01-20</small>
          </div>
          <a href="mailto:aldi@example.test" class="lf-btn-contact">Hubungi Pelapor</a>
        </div>

        <!-- Priority Item 2 -->
        <div class="lf-card-priority">
          <div class="lf-card-header">
            <span class="lf-badge-priority">PRIORITAS</span>
          </div>
          <div class="lf-card-icon">🔑</div>
          <h3 class="lf-card-title">Kunci Gantungan Biru</h3>
          <p class="lf-card-category">Kunci</p>
          <p class="lf-card-description">Gantungan kunci biru dengan logo kampus UFO</p>
          <div class="lf-card-meta">
            <small>📍 Perpustakaan</small>
            <small>📅 2024-01-19</small>
          </div>
          <a href="mailto:humas@example.test" class="lf-btn-contact">Hubungi Pelapor</a>
        </div>

        <!-- Priority Item 3 -->
        <div class="lf-card-priority">
          <div class="lf-card-header">
            <span class="lf-badge-priority">PRIORITAS</span>
          </div>
          <div class="lf-card-icon">📱</div>
          <h3 class="lf-card-title">Handphone Samsung</h3>
          <p class="lf-card-category">Elektronik</p>
          <p class="lf-card-description">Handphone Samsung warna merah dengan casing biru</p>
          <div class="lf-card-meta">
            <small>📍 Kantin</small>
            <small>📅 2024-01-25</small>
          </div>
          <a href="mailto:nabil@example.test" class="lf-btn-contact">Hubungi Pelapor</a>
        </div>
      </div>
    </section>

    <!-- Filter Section -->
    <section class="lf-filter-section">
      <div class="lf-search-box">
        <input
          id="lf-search"
          type="text"
          placeholder="Cari barang, lokasi, atau deskripsi..."
          class="lf-search-input"
        />
      </div>

      <!-- Tabs -->
      <div class="lf-tabs">
        <button class="lf-tab active" data-tab="all">Semua</button>
        <button class="lf-tab" data-tab="hilang">Barang Hilang</button>
        <button class="lf-tab" data-tab="ditemukan">Barang Ditemukan</button>
      </div>

      <!-- Category Filter Chips -->
      <div class="lf-categories">
        <button class="lf-category-chip active" data-category="all">Semua Kategori</button>
        <button class="lf-category-chip" data-category="dompet">Dompet</button>
        <button class="lf-category-chip" data-category="kunci">Kunci</button>
        <button class="lf-category-chip" data-category="buku">Buku</button>
        <button class="lf-category-chip" data-category="elektronik">Elektronik</button>
      </div>
    </section>

    <!-- Items Grid -->
    <section class="lf-items-section">
      <div class="lf-grid">
        <!-- Card Item 1 - Hilang -->
        <article class="lf-card">
          <div class="lf-card-status">
            <span class="lf-status-badge lost">✗ Hilang</span>
          </div>
          <div class="lf-card-icon">🔑</div>
          <h3 class="lf-card-title">Kunci Kos Hitam</h3>
          <p class="lf-card-category">Kunci</p>
          <p class="lf-card-description">Kunci kos stainless steel dengan label nomor 42 di asrama A</p>
          <div class="lf-card-meta">
            <small>📍 Asrama A</small>
            <small>📅 2024-01-20</small>
          </div>
          <div class="lf-card-footer">
            <a href="mailto:rahman@example.test" class="lf-btn-small">Hubungi</a>
          </div>
        </article>

        <!-- Card Item 2 - Ditemukan -->
        <article class="lf-card">
          <div class="lf-card-status">
            <span class="lf-status-badge found">✓ Ditemukan</span>
          </div>
          <div class="lf-card-icon">📱</div>
          <h3 class="lf-card-title">Handphone Samsung Hitam</h3>
          <p class="lf-card-category">Elektronik</p>
          <p class="lf-card-description">Handphone Samsung A12 berwarna hitam, baterai masih 40%</p>
          <div class="lf-card-meta">
            <small>📍 Cafetaria</small>
            <small>📅 2024-01-29</small>
          </div>
          <div class="lf-card-footer">
            <a href="mailto:sari@example.test" class="lf-btn-small">Hubungi</a>
          </div>
        </article>

        <!-- Card Item 3 - Hilang -->
        <article class="lf-card">
          <div class="lf-card-status">
            <span class="lf-status-badge lost">✗ Hilang</span>
          </div>
          <div class="lf-card-icon">📖</div>
          <h3 class="lf-card-title">Buku Catatan Merah</h3>
          <p class="lf-card-category">Buku</p>
          <p class="lf-card-description">Catatan kuliah MATH101, kulit merah dengan notes pensil di tepi</p>
          <div class="lf-card-meta">
            <small>📍 Kantin</small>
            <small>📅 2024-01-23</small>
          </div>
          <div class="lf-card-footer">
            <a href="mailto:sinta@example.test" class="lf-btn-small">Hubungi</a>
          </div>
        </article>

        <!-- Card Item 4 - Ditemukan -->
        <article class="lf-card">
          <div class="lf-card-status">
            <span class="lf-status-badge found">✓ Ditemukan</span>
          </div>
          <div class="lf-card-icon">💼</div>
          <h3 class="lf-card-title">Dompet Kulit Cokelat</h3>
          <p class="lf-card-category">Dompet</p>
          <p class="lf-card-description">Dompet kulit asli warna cokelat muda, kondisi baik. Ada ID card di dalamnya</p>
          <div class="lf-card-meta">
            <small>📍 Parkir Motor Timur</small>
            <small>📅 2024-01-28</small>
          </div>
          <div class="lf-card-footer">
            <a href="mailto:hendra@example.test" class="lf-btn-small">Hubungi</a>
          </div>
        </article>

        <!-- Card Item 5 - Hilang -->
        <article class="lf-card">
          <div class="lf-card-status">
            <span class="lf-status-badge lost">✗ Hilang</span>
          </div>
          <div class="lf-card-icon">🎧</div>
          <h3 class="lf-card-title">Earbuds Wireless Putih</h3>
          <p class="lf-card-category">Elektronik</p>
          <p class="lf-card-description">Earbuds wireless putih dengan charging case merah, merk terkenal</p>
          <div class="lf-card-meta">
            <small>📍 Lab Komputer</small>
            <small>📅 2024-01-26</small>
          </div>
          <div class="lf-card-footer">
            <a href="mailto:indra@example.test" class="lf-btn-small">Hubungi</a>
          </div>
        </article>

        <!-- Card Item 6 - Ditemukan -->
        <article class="lf-card">
          <div class="lf-card-status">
            <span class="lf-status-badge found">✓ Ditemukan</span>
          </div>
          <div class="lf-card-icon">🪓</div>
          <h3 class="lf-card-title">ID Card Mahasiswa</h3>
          <p class="lf-card-category">Dokumen</p>
          <p class="lf-card-description">Kartu identitas mahasiswa a.n. Ahmad Rizki, tahun cetak 2022</p>
          <div class="lf-card-meta">
            <small>📍 Lobby Gedung A</small>
            <small>📅 2024-01-27</small>
          </div>
          <div class="lf-card-footer">
            <a href="mailto:security@example.test" class="lf-btn-small">Hubungi</a>
          </div>
        </article>
      </div>

      <!-- Empty State (hidden by default) -->
      <div id="lf-empty" class="lf-empty" style="display: none;">
        <p>📭</p>
        <p class="font-semibold">Tidak ada barang yang sesuai</p>
        <p class="text-sm text-gray-600">Coba ubah filter atau kata kunci pencarian Anda</p>
      </div>
    </section>

    <!-- Report Form -->
    <section class="lf-form-section">
      <h2 class="lf-form-title">Laporkan Barang Hilang / Ditemukan</h2>
      <form class="lf-form" id="lf-form">
        <input
          class="lf-form-input"
          type="text"
          placeholder="Nama barang"
          required
        />
        <input
          class="lf-form-input"
          type="text"
          placeholder="Lokasi"
          required
        />
        <input
          class="lf-form-input"
          type="email"
          placeholder="Kontak (email/telepon)"
          required
        />
        <textarea
          class="lf-form-textarea"
          placeholder="Deskripsi barang (min. 10 karakter)"
          required
        ></textarea>
        <div class="lf-form-actions">
          <button type="submit" class="lf-btn-submit">Kirim Laporan</button>
          <button type="reset" class="lf-btn-reset">Reset</button>
        </div>
        <p class="lf-form-note">
          Laporan Anda akan diverifikasi oleh pengurus sebelum ditampilkan di halaman ini.
        </p>
      </form>
    </section>

  </div>
</div>

<script src="{{ asset('js/lost-found.js') }}"></script>
@endsection
