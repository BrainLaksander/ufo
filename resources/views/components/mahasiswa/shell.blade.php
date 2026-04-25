<header class="header mahasiswa-header">
    <div class="header-container">
        <div class="header-left">
            <button id="burger-btn" class="burger-toggle" aria-label="Open menu"><i class="bi bi-list"></i></button>
            <a href="{{ route('home') }}" class="header-brand" aria-label="Kembali ke landing page UFO">
                <span class="brand-icon"><img src="{{ asset('logoufo.png') }}" alt="Logo UFO" class="brand-logo-image"></span>
                <div class="brand-text">
                    <div class="brand-title">UFO</div>
                    <div class="brand-subtitle">UNKLAB Forum Organization</div>
                </div>
            </a>
        </div>
        <div class="header-right">
            <button class="notification-btn" aria-label="Notifications"><i class="bi bi-bell-fill"></i><span class="notification-dot"></span></button>
        </div>
    </div>
</header>

<div id="burger-overlay" class="overlay hidden"></div>
<aside id="burger-menu" class="burger-menu burger-mahasiswa" aria-hidden="true">
    <div class="burger-header">
        <div class="burger-header-content">
            <span class="burger-icon"><i class="bi bi-stars"></i></span>
            <div class="burger-title">Menu UFO</div>
        </div>
        <button id="burger-close" class="burger-close" aria-label="Close menu"><i class="bi bi-x-lg"></i></button>
    </div>
    <nav class="burger-nav">
        <ul>
            <li><a href="{{ route('home') }}" class="burger-link" data-close>
                <span class="burger-link-icon"><i class="bi bi-house-door-fill"></i></span>
                <span class="burger-link-text">Landing Page</span>
            </a></li>
            <li><a href="{{ route('mahasiswa.organisasi.index') }}" class="burger-link organisasi-link" data-close>
                <span class="burger-link-icon"><i class="bi bi-people-fill"></i></span>
                <span class="burger-link-text">Organisasi</span>
            </a></li>
            <li><a href="{{ route('mahasiswa.event') }}" class="burger-link" data-close>
                <span class="burger-link-icon"><i class="bi bi-calendar-event-fill"></i></span>
                <span class="burger-link-text">Event</span>
            </a></li>
            <li><a href="{{ route('mahasiswa.lost-found') }}" class="burger-link" data-close>
                <span class="burger-link-icon"><i class="bi bi-search"></i></span>
                <span class="burger-link-text">Lost & Found</span>
            </a></li>
            <li><a href="{{ route('mahasiswa.pengumuman') }}" class="burger-link" data-close>
                <span class="burger-link-icon"><i class="bi bi-megaphone-fill"></i></span>
                <span class="burger-link-text">Pengumuman</span>
            </a></li>
            <li><a href="{{ route('mahasiswa.tentang') }}" class="burger-link" data-close>
                <span class="burger-link-icon"><i class="bi bi-info-circle-fill"></i></span>
                <span class="burger-link-text">Tentang UFO</span>
            </a></li>
            <li><a href="{{ route('portal.login') }}" class="burger-link" data-close>
                <span class="burger-link-icon"><i class="bi bi-box-arrow-in-right"></i></span>
                <span class="burger-link-text">Portal Internal</span>
            </a></li>
        </ul>
    </nav>
</aside>
