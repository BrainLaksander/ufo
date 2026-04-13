@extends('layouts.app')

@section('title', 'Tentang UFO - Mahasiswa')

@push('styles')
<style>
  .ufo-about-page {
    padding: 1.25rem 0 2rem;
  }

  .ufo-about-hero {
    border-radius: 14px;
    background: var(--primary);
    color: #fff;
    padding: 1.1rem 1.2rem;
    box-shadow: var(--shadow-sm);
    margin-bottom: 0.95rem;
  }

  .ufo-about-hero h1 {
    margin: 0 0 0.35rem;
    font-size: 1.55rem;
    font-weight: 700;
  }

  .ufo-about-hero p {
    margin: 0;
    opacity: 0.9;
    font-size: 0.92rem;
  }

  .ufo-about-card {
    border-radius: 12px;
    border: 1px solid var(--border-color);
    background: #fff;
    box-shadow: var(--shadow-sm);
    padding: 0.95rem;
    height: 100%;
  }

  .ufo-about-card h2 {
    margin: 0 0 0.45rem;
    font-size: 1.02rem;
    font-weight: 700;
    color: var(--primary);
  }

  .ufo-about-card p,
  .ufo-about-card li {
    margin: 0;
    font-size: 0.88rem;
    color: #465266;
    line-height: 1.55;
  }

  .ufo-about-card ul {
    margin: 0;
    padding-left: 1.1rem;
    display: grid;
    gap: 0.4rem;
  }

  .ufo-about-links {
    border-radius: 12px;
    border: 1px solid var(--border-color);
    background: #fff;
    box-shadow: var(--shadow-sm);
    padding: 0.95rem;
    margin-top: 1rem;
  }

  .ufo-about-links h2 {
    margin: 0 0 0.6rem;
    font-size: 1.02rem;
    font-weight: 700;
    color: var(--primary);
  }

  .ufo-about-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 0.55rem;
  }

  .ufo-about-link {
    text-decoration: none;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.45rem 0.65rem;
    font-size: 0.83rem;
    font-weight: 700;
    color: var(--primary);
    background: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.35rem;
  }

  .ufo-about-link:hover {
    border-color: var(--primary);
    background: rgba(95, 58, 116, 0.08);
    color: var(--primary);
  }

  @media (max-width: 768px) {
    .ufo-about-page {
      padding-top: 1rem;
    }

    .ufo-about-hero h1 {
      font-size: 1.3rem;
    }

    .ufo-about-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
@endpush

@section('content')
<section class="ufo-about-page">
  <div class="ufo-about-hero">
    <h1>Tentang UFO</h1>
    <p>Unklab Forum Organization adalah platform digital untuk kegiatan organisasi mahasiswa Universitas Klabat.</p>
  </div>

  <div class="row g-3">
    <div class="col-md-6">
      <article class="ufo-about-card">
        <h2>Visi</h2>
        <p>Menjadi platform digital terpadu untuk kehidupan kampus yang lebih terorganisir, informatif, dan kolaboratif.</p>
      </article>
    </div>
    <div class="col-md-6">
      <article class="ufo-about-card">
        <h2>Misi</h2>
        <ul>
          <li>Menyediakan informasi kampus yang akurat dan terkini.</li>
          <li>Memfasilitasi komunikasi mahasiswa dan organisasi.</li>
          <li>Membantu layanan kampus seperti Lost & Found.</li>
        </ul>
      </article>
    </div>
  </div>

  <section class="ufo-about-links" aria-label="Navigasi cepat UFO">
    <h2>Jelajahi UFO</h2>
    <div class="ufo-about-grid">
      <a href="{{ route('mahasiswa.organisasi.index') }}" class="ufo-about-link">Organisasi <i class="bi bi-arrow-up-right"></i></a>
      <a href="{{ route('mahasiswa.pengumuman') }}" class="ufo-about-link">Pengumuman <i class="bi bi-arrow-up-right"></i></a>
      <a href="{{ route('mahasiswa.lost-found') }}" class="ufo-about-link">Lost &amp; Found <i class="bi bi-arrow-up-right"></i></a>
    </div>
  </section>
</section>
@endsection
