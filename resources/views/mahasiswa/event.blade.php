@extends('layouts.app')

@section('title', 'Event Mahasiswa - UFO')

@push('styles')
<style>
  .ufo-event-page {
    padding: 1.25rem 0 2rem;
  }

  .ufo-event-hero {
    border-radius: 14px;
    background: var(--primary);
    color: #fff;
    padding: 1.1rem 1.2rem;
    box-shadow: var(--shadow-sm);
    margin-bottom: 0.95rem;
  }

  .ufo-event-hero h1 {
    margin: 0 0 0.35rem;
    font-size: 1.55rem;
    font-weight: 700;
  }

  .ufo-event-hero p {
    margin: 0;
    opacity: 0.9;
    font-size: 0.92rem;
  }

  .ufo-event-card {
    border-radius: 12px;
    border: 1px solid var(--border-color);
    background: #fff;
    box-shadow: var(--shadow-sm);
    padding: 1rem;
  }

  .ufo-event-card h2 {
    margin: 0 0 0.4rem;
    font-size: 1.08rem;
    color: var(--primary);
    font-weight: 700;
  }

  .ufo-event-card p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
  }

  .ufo-event-actions {
    margin-top: 0.8rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
  }

  .ufo-event-link {
    text-decoration: none;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.42rem 0.66rem;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--primary);
    background: #fff;
  }

  .ufo-event-link:hover {
    border-color: var(--primary);
    background: rgba(95, 58, 116, 0.08);
    color: var(--primary);
  }

  @media (max-width: 768px) {
    .ufo-event-page {
      padding-top: 1rem;
    }

    .ufo-event-hero h1 {
      font-size: 1.3rem;
    }
  }
</style>
@endpush

@section('content')
<section class="ufo-event-page">
  <div class="ufo-event-hero">
    <h1>Event Mahasiswa</h1>
    <p>Informasi event kampus akan ditampilkan di sini.</p>
  </div>

  <article class="ufo-event-card">
    <h2>Belum ada event publik</h2>
    <p>Cek kembali nanti atau lihat update terbaru dari menu pengumuman.</p>

    <div class="ufo-event-actions">
      <a href="{{ route('mahasiswa.pengumuman') }}" class="ufo-event-link">Lihat Pengumuman</a>
      <a href="{{ route('mahasiswa.organisasi.index') }}" class="ufo-event-link">Lihat Organisasi</a>
    </div>
  </article>
</section>
@endsection
