@extends('layouts.app')

@section('title', 'Beranda Mahasiswa - UFO')

@section('content')
<div class="mahasiswa-home-wrapper">
    <!-- Hero / Slider Section -->
    <section class="mahasiswa-hero">
        <div class="hero-slider">
            <div class="hero-slide active">
                <div class="hero-slide-content">
                    <h2>Jelajahi Organisasi Mahasiswa</h2>
                    <p>Temukan organisasi yang sesuai dengan minat Anda</p>
                    <a href="{{ route('organisasi.index') }}" class="hero-btn">Lihat Semua</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Search & Filter Section -->
    <section class="mahasiswa-search-section">
        <!-- Search Bar -->
        <div class="mahasiswa-search-wrapper">
            <span class="search-icon"><i class="bi bi-search"></i></span>
            <input
                type="text"
                class="mahasiswa-search-input"
                placeholder="Cari organisasi..."
                id="searchInput"
            />
        </div>

        <!-- Filter Chips -->
        <div class="mahasiswa-filter-chips">
            <button class="filter-chip active" data-filter="all">
                Semua
            </button>
            <button class="filter-chip" data-filter="umum">
                Organisasi Umum
            </button>
            <button class="filter-chip" data-filter="choir">
                Choir
            </button>
            <button class="filter-chip" data-filter="event">
                Event
            </button>
        </div>
    </section>

    <!-- Info & Sorting -->
    <section class="mahasiswa-info-section">
        <div class="mahasiswa-info-left">
            <p class="mahasiswa-org-count">
                <span id="orgCount">8</span> organisasi ditemukan
            </p>
        </div>
        <div class="mahasiswa-info-right">
            <select class="mahasiswa-sort-dropdown">
                <option value="a-z">Urutkan: A-Z</option>
                <option value="z-a">Urutkan: Z-A</option>
                <option value="newest">Terbaru</option>
            </select>
        </div>
    </section>

    <!-- Organization Cards Grid -->
    <section class="mahasiswa-org-section">
        <div class="mahasiswa-org-grid">
            <!-- Organization Card 1 -->
            <div class="mahasiswa-org-card">
                <div class="org-card-header">
                    <div class="org-card-logo"><i class="bi bi-stars"></i></div>
                    <button class="org-card-favorite"><i class="bi bi-heart"></i></button>
                </div>
                <div class="org-card-body">
                    <h3 class="org-card-name">Organisasi Rohani</h3>
                    <p class="org-card-tagline">Mengembangkan nilai spiritual mahasiswa</p>
                </div>
                <button class="org-card-btn">Lihat Detail</button>
            </div>

            <!-- Organization Card 2 -->
            <div class="mahasiswa-org-card">
                <div class="org-card-header">
                    <div class="org-card-logo"><i class="bi bi-music-note-beamed"></i></div>
                    <button class="org-card-favorite"><i class="bi bi-heart"></i></button>
                </div>
                <div class="org-card-body">
                    <h3 class="org-card-name">Paduan Suara</h3>
                    <p class="org-card-tagline">Komunitas vokal dan musik</p>
                </div>
                <button class="org-card-btn">Lihat Detail</button>
            </div>

            <!-- Organization Card 3 -->
            <div class="mahasiswa-org-card">
                <div class="org-card-header">
                    <div class="org-card-logo"><i class="bi bi-mask"></i></div>
                    <button class="org-card-favorite"><i class="bi bi-heart"></i></button>
                </div>
                <div class="org-card-body">
                    <h3 class="org-card-name">Teater</h3>
                    <p class="org-card-tagline">Seni pertunjukan dan drama</p>
                </div>
                <button class="org-card-btn">Lihat Detail</button>
            </div>

            <!-- Organization Card 4 -->
            <div class="mahasiswa-org-card">
                <div class="org-card-header">
                    <div class="org-card-logo"><i class="bi bi-trophy-fill"></i></div>
                    <button class="org-card-favorite"><i class="bi bi-heart"></i></button>
                </div>
                <div class="org-card-body">
                    <h3 class="org-card-name">Olahraga</h3>
                    <p class="org-card-tagline">Kegiatan olahraga dan kebugaran</p>
                </div>
                <button class="org-card-btn">Lihat Detail</button>
            </div>

            <!-- Organization Card 5 -->
            <div class="mahasiswa-org-card">
                <div class="org-card-header">
                    <div class="org-card-logo"><i class="bi bi-cpu-fill"></i></div>
                    <button class="org-card-favorite"><i class="bi bi-heart"></i></button>
                </div>
                <div class="org-card-body">
                    <h3 class="org-card-name">Tech Club</h3>
                    <p class="org-card-tagline">Komunitas teknologi dan inovasi</p>
                </div>
                <button class="org-card-btn">Lihat Detail</button>
            </div>

            <!-- Organization Card 6 -->
            <div class="mahasiswa-org-card">
                <div class="org-card-header">
                    <div class="org-card-logo"><i class="bi bi-tree-fill"></i></div>
                    <button class="org-card-favorite"><i class="bi bi-heart"></i></button>
                </div>
                <div class="org-card-body">
                    <h3 class="org-card-name">Lingkungan</h3>
                    <p class="org-card-tagline">Kepedulian lingkungan dan sosial</p>
                </div>
                <button class="org-card-btn">Lihat Detail</button>
            </div>
        </div>
    </section>

    <!-- Floating Chat Button -->
    <div class="mahasiswa-floating-chat">
        <button class="floating-chat-btn">
            <i class="bi bi-chat-dots-fill"></i>
            <span class="chat-badge">3</span>
        </button>
    </div>
</div>

<!-- Styles khusus halaman home -->
<style>
    /* Color variables */
    :root {
        --primary: #663399;
        --accent: #ffcc00;
        --text-primary: #111;
        --text-secondary: #666;
        --border-color: #e6e6f0;
        --bg-light: #f7f7fb;
    }

    /* Hero Section */
    .mahasiswa-hero {
        margin: 20px 16px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .hero-slider {
        background: linear-gradient(135deg, var(--primary) 0%, #5a2d7f 100%);
        padding: 40px 24px;
        color: white;
        text-align: center;
        border-radius: 12px;
    }

    .hero-slide-content h2 {
        margin: 0 0 12px;
        font-size: 24px;
        font-weight: 700;
    }

    .hero-slide-content p {
        margin: 0 0 16px;
        font-size: 14px;
        opacity: 0.9;
    }

    .hero-btn {
        display: inline-block;
        padding: 10px 20px;
        background: var(--accent);
        color: var(--primary);
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .hero-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 204, 0, 0.3);
    }

    /* Search Section */
    .mahasiswa-search-section {
        padding: 20px 16px;
    }

    .mahasiswa-search-wrapper {
        position: relative;
        margin-bottom: 16px;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
    }

    .mahasiswa-search-input {
        width: 100%;
        padding: 12px 12px 12px 44px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .mahasiswa-search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(102, 51, 153, 0.1);
    }

    /* Filter Chips */
    .mahasiswa-filter-chips {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 8px;
    }

    .filter-chip {
        padding: 8px 16px;
        border: 1px solid var(--border-color);
        background: white;
        border-radius: 20px;
        font-size: 13px;
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .filter-chip:hover {
        border-color: var(--primary);
    }

    .filter-chip.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    /* Info Section */
    .mahasiswa-info-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        font-size: 13px;
    }

    .mahasiswa-org-count {
        margin: 0;
        color: var(--text-secondary);
    }

    .mahasiswa-sort-dropdown {
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 13px;
        background: white;
        cursor: pointer;
    }

    /* Organization Grid */
    .mahasiswa-org-section {
        padding: 16px;
    }

    .mahasiswa-org-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
    }

    @media (min-width: 640px) {
        .mahasiswa-org-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .mahasiswa-org-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Organization Card */
    .mahasiswa-org-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }

    .mahasiswa-org-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .org-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        background: var(--bg-light);
        border-bottom: 1px solid var(--border-color);
    }

    .org-card-logo {
        font-size: 32px;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .org-card-favorite {
        background: transparent;
        border: none;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .org-card-favorite:hover {
        transform: scale(1.2);
    }

    .org-card-body {
        padding: 16px;
        flex: 1;
    }

    .org-card-name {
        margin: 0 0 8px;
        font-size: 16px;
        font-weight: 600;
        color: var(--primary);
    }

    .org-card-tagline {
        margin: 0;
        font-size: 13px;
        color: var(--text-secondary);
        line-height: 1.4;
    }

    .org-card-btn {
        padding: 12px 16px;
        background: linear-gradient(135deg, var(--primary) 0%, #5a2d7f 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        margin: 0 16px 16px;
        transition: all 0.2s;
    }

    .org-card-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 51, 153, 0.2);
    }

    /* Floating Chat Button */
    .mahasiswa-floating-chat {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 50;
    }

    .floating-chat-btn {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, #5a2d7f 100%);
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(102, 51, 153, 0.3);
        transition: all 0.3s;
        position: relative;
    }

    .floating-chat-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(102, 51, 153, 0.4);
    }

    .chat-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 24px;
        height: 24px;
        background: #cc0000;
        color: white;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    /* Responsive */
    @media (max-width: 640px) {
        .mahasiswa-hero {
            margin: 12px 12px;
        }

        .hero-slider {
            padding: 32px 16px;
        }

        .hero-slide-content h2 {
            font-size: 20px;
        }

        .mahasiswa-search-section,
        .mahasiswa-info-section,
        .mahasiswa-org-section {
            padding: 16px 12px;
        }

        .mahasiswa-info-section {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }
    }
</style>
@endsection
