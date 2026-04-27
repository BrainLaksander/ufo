<div class="ufo-pengurus-overlay" data-burger-overlay hidden></div>
<aside class="ufo-pengurus-burger" data-burger-panel aria-hidden="true">
    <div class="ufo-pengurus-burger-head">
        <div class="ufo-pengurus-burger-title-wrap">
            <span class="ufo-pengurus-burger-mark">🛸</span>
            <div>
                <strong>Menu Pengurus</strong>
                <small>UFO Dashboard</small>
            </div>
        </div>

        <button type="button" class="ufo-pengurus-icon-btn" data-burger-close aria-label="Tutup menu pengurus">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="ufo-pengurus-burger-nav" aria-label="Navigasi pengurus">
        <a href="{{ route('portal.pengurus.dashboard') }}" class="ufo-pengurus-menu-link {{ request()->routeIs('portal.pengurus.dashboard') ? 'is-active' : '' }}">
            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard Organisasi</span>
        </a>
        <a href="{{ route('portal.pengurus.members') }}" class="ufo-pengurus-menu-link {{ request()->routeIs('portal.pengurus.members') || request()->routeIs('portal.pengurus.settings') ? 'is-active' : '' }}">
            <i class="bi bi-building"></i>
            <span>Profil Organisasi</span>
        </a>
        <a href="{{ route('portal.pengurus.events') }}" class="ufo-pengurus-menu-link {{ request()->routeIs('portal.pengurus.events') || request()->routeIs('portal.pengurus.events.*') ? 'is-active' : '' }}">
            <i class="bi bi-calendar-event"></i>
            <span>Event Organisasi</span>
        </a>
        <a href="{{ route('portal.pengurus.announcements') }}" class="ufo-pengurus-menu-link {{ request()->routeIs('portal.pengurus.announcements') || request()->routeIs('portal.pengurus.announcements.*') ? 'is-active' : '' }}">
            <i class="bi bi-megaphone"></i>
            <span>Pengumuman</span>
        </a>
        <a href="{{ route('portal.pengurus.proposals') }}" class="ufo-pengurus-menu-link {{ request()->routeIs('portal.pengurus.proposals') || request()->routeIs('portal.pengurus.proposals.*') ? 'is-active' : '' }}">
            <i class="bi bi-journal-check"></i>
            <span>Pengajuan & Laporan Kegiatan</span>
        </a>
        @if($canAccessLostAndFound ?? false)
        <a href="{{ route('portal.pengurus.lostandfound') }}" class="ufo-pengurus-menu-link {{ request()->routeIs('portal.pengurus.lostandfound') || request()->routeIs('portal.pengurus.lostandfound.*') ? 'is-active' : '' }}">
            <i class="bi bi-search"></i>
            <span>Lost & Found</span>
        </a>
        @endif
        <a href="{{ route('portal.pengurus.kontak') }}" class="ufo-pengurus-menu-link {{ request()->routeIs('portal.pengurus.kontak') || request()->routeIs('portal.pengurus.applications') ? 'is-active' : '' }}">
            <i class="bi bi-telephone"></i>
            <span>Kontak Organisasi</span>
        </a>
    </nav>

    <div class="ufo-pengurus-burger-foot">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="ufo-kboard-btn danger w-100 justify-content-center">
                <i class="bi bi-box-arrow-right"></i>
                Keluar
            </button>
        </form>
    </div>
</aside>
