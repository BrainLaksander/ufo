<div class="figma-ufo-overlay" data-burger-overlay hidden></div>
<aside class="figma-ufo-burger" data-burger-panel aria-hidden="true">
    <div class="figma-ufo-burger-head">
        <div class="figma-ufo-burger-title-wrap">
            <strong>Menu UFO</strong>
        </div>
        <button type="button" class="figma-ufo-icon-btn" data-burger-close aria-label="Tutup menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="figma-ufo-burger-nav" aria-label="Navigasi utama">
        <a href="{{ route('mahasiswa.organisasi.index') }}" class="figma-ufo-menu-link {{ request()->is('organisasi*') ? 'is-active' : '' }}">
            <i class="bi bi-people"></i>
            <span>Organisasi</span>
        </a>
        <a href="{{ route('mahasiswa.event') }}" class="figma-ufo-menu-link {{ request()->is('event*') ? 'is-active' : '' }}">
            <i class="bi bi-calendar-event"></i>
            <span>Event</span>
        </a>
        <a href="{{ route('mahasiswa.lost-found') }}" class="figma-ufo-menu-link {{ request()->is('lost-found') ? 'is-active' : '' }}">
            <i class="bi bi-search"></i>
            <span>Lost and Found</span>
        </a>
        <a href="{{ route('mahasiswa.pengumuman') }}" class="figma-ufo-menu-link {{ request()->is('pengumuman*') ? 'is-active' : '' }}">
            <i class="bi bi-megaphone"></i>
            <span>Pengumuman</span>
        </a>
        <a href="{{ route('mahasiswa.tentang') }}" class="figma-ufo-menu-link {{ request()->is('tentang') ? 'is-active' : '' }}">
            <i class="bi bi-info-circle"></i>
            <span>Tentang UFO</span>
        </a>
    </nav>

    <div class="figma-ufo-burger-portal" aria-label="Login portal internal">
        <a href="{{ route('portal.login') }}" class="figma-ufo-menu-link figma-ufo-menu-link-portal">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Login Portal</span>
        </a>
    </div>

    <div class="figma-ufo-burger-foot">
        <p>UNKLAB Forum Organization</p>
    </div>
</aside>
