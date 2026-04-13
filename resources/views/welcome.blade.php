@extends('layouts.app')

@section('title', 'UFO - Beranda')
@section('mainClass', 'ufo-home-main')

@push('styles')
<style>
    .ufo-home-main {
        margin: 0;
        max-width: 100%;
        padding: 0;
        background: var(--bg-light);
    }

    .ufo-home {
        padding: 1.5rem 0 2rem;
    }

    .ufo-home .container {
        max-width: 1000px;
    }

    .ufo-hero {
        background: var(--primary);
        color: #fff;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
    }

    .ufo-hero__eyebrow {
        display: inline-block;
        font-size: .8rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        font-weight: 700;
        opacity: .85;
        margin-bottom: .6rem;
    }

    .ufo-hero h1 {
        font-size: clamp(1.7rem, 4vw, 2.5rem);
        line-height: 1.2;
        margin-bottom: .85rem;
        color: #fff;
    }

    .ufo-hero__actions {
        display: flex;
        flex-wrap: wrap;
        gap: .6rem;
    }

    .ufo-btn-primary,
    .ufo-btn-accent {
        border-radius: 10px;
        padding: .65rem 1rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        transition: transform .2s ease, opacity .2s ease;
    }

    .ufo-btn-primary {
        background: #fff;
        color: var(--primary);
    }

    .ufo-btn-accent {
        background: var(--accent);
        color: var(--text-primary);
    }

    .ufo-btn-primary:hover,
    .ufo-btn-accent:hover {
        transform: translateY(-2px);
        opacity: .92;
    }

    .ufo-block {
        border-radius: 16px;
        border: 1px solid var(--border-color);
        background: #fff;
        box-shadow: var(--shadow-sm);
        padding: 1.25rem;
        height: 100%;
    }

    .ufo-block h2 {
        font-size: 1rem;
        color: var(--primary);
        margin-bottom: .6rem;
    }

    .ufo-quick-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .55rem;
    }

    .ufo-quick-link {
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: #fff;
        color: var(--text-primary);
        font-weight: 600;
        font-size: .88rem;
        text-decoration: none;
        padding: .55rem .7rem;
        transition: all .2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
    }

    .ufo-quick-link:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        color: var(--primary);
    }

    @media (max-width: 768px) {
        .ufo-home {
            padding-top: 1rem;
        }

        .ufo-quick-grid {
            grid-template-columns: 1fr;
        }

        .ufo-hero {
            padding: 1.4rem;
        }
    }
</style>
@endpush

@section('content')
<section class="ufo-home" aria-label="Landing Page UFO">
    <div class="container">
        <div class="row g-4">
            <div class="col-12">
                <article class="ufo-hero">
                    <span class="ufo-hero__eyebrow">UFO UNKLAB</span>
                    <h1>Portal organisasi kampus.</h1>

                    <div class="ufo-hero__actions">
                        <a href="{{ route('mahasiswa.beranda') }}" class="ufo-btn-accent">
                            <i class="bi bi-compass"></i>
                            Mahasiswa
                        </a>
                        <a href="{{ route('login') }}" class="ufo-btn-primary">
                            <i class="bi bi-shield-lock"></i>
                            Internal
                        </a>
                    </div>
                </article>
            </div>

            <div class="col-12">
                <article class="ufo-block">
                    <h2>Menu</h2>
                    <div class="ufo-quick-grid">
                        <a href="{{ route('mahasiswa.organisasi.index') }}" class="ufo-quick-link">Organisasi <i class="bi bi-arrow-up-right"></i></a>
                        <a href="{{ route('mahasiswa.event') }}" class="ufo-quick-link">Event <i class="bi bi-arrow-up-right"></i></a>
                        <a href="{{ route('mahasiswa.pengumuman') }}" class="ufo-quick-link">Pengumuman <i class="bi bi-arrow-up-right"></i></a>
                        <a href="{{ route('mahasiswa.lost-found') }}" class="ufo-quick-link">L&amp;F <i class="bi bi-arrow-up-right"></i></a>
                        <a href="{{ route('mahasiswa.tentang') }}" class="ufo-quick-link">Tentang UFO <i class="bi bi-arrow-up-right"></i></a>
                        <a href="{{ route('login') }}" class="ufo-quick-link">Internal <i class="bi bi-arrow-up-right"></i></a>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
