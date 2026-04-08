<div id="sidebar-overlay" class="sidebar-overlay hidden"></div>
<aside id="sidebar" class="sidebar kemahasiswaan-sidebar" aria-hidden="false">
    <div class="sidebar-top">
        <div class="dept-title">
            <h2>Departemen<br>Kemahasiswaan</h2>
            <div class="university">Universitas Klabat</div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" data-close>
                    <span class="nav-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 11.5L12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-8.5z" fill="currentColor"/></svg>
                    </span>
                    <span class="nav-text">Landing Page</span>
                </a>
            </li>
            <li>
                <a href="{{ route('portal.kemahasiswaan.dashboard') }}" class="nav-link {{ request()->is('portal/kemahasiswaan') ? 'active' : '' }}" data-close>
                    <span class="nav-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="3" width="8" height="8" rx="2" fill="currentColor"/><rect x="13" y="3" width="8" height="8" rx="2" fill="currentColor"/><rect x="3" y="13" width="8" height="8" rx="2" fill="currentColor"/><rect x="13" y="13" width="8" height="8" rx="2" fill="currentColor"/></svg>
                    </span>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <!-- Notifikasi Sistem moved to bottom of menu -->
            <li>
                <a href="{{ route('portal.kemahasiswaan.organisasi') }}" class="nav-link {{ request()->is('portal/kemahasiswaan/organisasi') ? 'active' : '' }}" data-close>
                    <span class="nav-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 21h18v-2H3v2zM7 10h2v2H7v-2zM11 10h2v2h-2v-2zM15 10h2v2h-2v-2zM7 6h2v2H7V6zM11 6h2v2h-2V6z" fill="currentColor"/></svg>
                    </span>
                    <span class="nav-text">Manajemen Organisasi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('portal.kemahasiswaan.pengajuan') }}" class="nav-link {{ request()->is('portal/kemahasiswaan/pengajuan') ? 'active' : '' }}" data-close>
                    <span class="nav-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 2h7l5 5v13a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1z" fill="currentColor"/></svg>
                    </span>
                    <span class="nav-text">Manajemen Pengajuan Kegiatan &amp; Laporan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('portal.kemahasiswaan.pengumuman') }}" class="nav-link {{ request()->is('portal/kemahasiswaan/pengumuman') ? 'active' : '' }}" data-close>
                    <span class="nav-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 11v2h2l7 4V7L5 11H3z" fill="currentColor"/><path d="M20 7a2 2 0 0 1 0 4" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                    <span class="nav-text">Manajemen Pengumuman &amp; Distribusi Email</span>
                </a>
            </li>

            <li class="sidebar-divider"></li>
            <li>
                <a href="{{ route('portal.kemahasiswaan.notifikasi') }}" class="nav-link {{ request()->is('portal/kemahasiswaan/notifikasi') ? 'active' : '' }}" data-close>
                    <span class="nav-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 22a2 2 0 0 0 2-2H10a2 2 0 0 0 2 2z" fill="currentColor"/><path d="M18 16v-5a6 6 0 1 0-12 0v5l-2 2h16l-2-2z" fill="currentColor"/></svg>
                    </span>
                    <span class="nav-text">Notifikasi Sistem</span>
                    <span class="notif-badge" aria-hidden="true">3</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
