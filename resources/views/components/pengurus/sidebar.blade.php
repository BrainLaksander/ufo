<div id="sidebar-overlay" class="sidebar-overlay hidden"></div>
<aside id="sidebar" class="sidebar pengurus-sidebar" aria-hidden="true">
    <div class="sidebar-header">
        <div class="header-icon">🏛️</div>
        <div class="header-text">
            <h3>UFO</h3>
            <p>Pengurus</p>
        </div>
        <button id="sidebar-close" class="sidebar-close position-absolute" style="top: 12px; right: 12px; background: none; border: none; color: black; font-size: 24px; cursor: pointer;" aria-label="Close sidebar">✕</button>
    </div>
    <nav class="sidebar-nav">
        <li>
            <a href="{{ route('portal.pengurus.dashboard') }}" class="nav-link {{ request()->routeIs('portal.pengurus.dashboard') ? 'active' : '' }}" data-close>
                <i class="fas fa-home"></i> Dashboard Organisasi
            </a>
        </li>
        <li>
            <a href="{{ route('portal.pengurus.members') }}" class="nav-link {{ request()->routeIs('portal.pengurus.members') ? 'active' : '' }}" data-close>
                <i class="fas fa-user-circle"></i> Profil Organisasi
            </a>
        </li>
        <li>
            <a href="{{ route('portal.pengurus.events') }}" class="nav-link {{ request()->routeIs('portal.pengurus.events') ? 'active' : '' }}" data-close>
                <i class="fas fa-calendar-alt"></i> Event Organisasi
            </a>
        </li>
        <li>
            <a href="{{ route('portal.pengurus.announcements') }}" class="nav-link {{ request()->routeIs('portal.pengurus.announcements') ? 'active' : '' }}" data-close>
                <i class="fas fa-bullhorn"></i> Pengumuman
            </a>
        </li>
        <li>
            <a href="{{ route('portal.pengurus.applications') }}" class="nav-link {{ request()->routeIs('portal.pengurus.applications') ? 'active' : '' }}" data-close>
                <i class="fas fa-file-alt"></i> Pengajuan & Laporan
            </a>
        </li>
        <li>
            <a href="#" class="nav-link" data-close>
                <i class="fas fa-clipboard-list"></i> Pendaftaran Organisasi
            </a>
        </li>
        <li>
            <a href="{{ route('portal.pengurus.lostandfound') }}" class="nav-link {{ request()->routeIs('portal.pengurus.lostandfound') ? 'active' : '' }}" data-close>
                <i class="fas fa-search"></i> Lost & Found
            </a>
        </li>
        <li>
            <a href="#" class="nav-link" data-close>
                <i class="fas fa-comments"></i> Chat & Konsultasi
            </a>
        </li>
        <li class="sidebar-divider"></li>
        <li>
            <a href="{{ route('portal.login') }}" class="nav-link" data-close>
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>
        </li>
    </nav>
</aside>
