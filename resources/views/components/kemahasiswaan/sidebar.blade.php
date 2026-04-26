<aside id="sidebar" class="sidebar kemahasiswaan-sidebar" aria-hidden="false">
    @php
        $notificationCount = (int) ($headerNotificationCount ?? 0);
        $isDashboard = request()->routeIs('portal.kemahasiswaan.dashboard') || request()->routeIs('dashboard.kemahasiswaan') || request()->is('kemahasiswaan');
        $isKontak = request()->routeIs('portal.kemahasiswaan.kontak') || request()->is('kemahasiswaan/kontak');
        $isKalender = request()->routeIs('portal.kemahasiswaan.kalender') || request()->is('kemahasiswaan/kalender');
    @endphp

    <div class="sidebar-top">
        <div class="dept-title">
            <h2>Departemen Kemahasiswaan</h2>
            <div class="university">Universitas Klabat</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="{{ route('portal.kemahasiswaan.dashboard') }}" class="nav-link {{ $isDashboard ? 'active' : '' }}" data-close>
                    <span class="nav-icon" aria-hidden="true"><i class="bi bi-grid-1x2-fill"></i></span>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('portal.kemahasiswaan.organisasi') }}" class="nav-link {{ request()->is('kemahasiswaan/organisasi') ? 'active' : '' }}" data-close>
                    <span class="nav-icon" aria-hidden="true"><i class="bi bi-building-fill"></i></span>
                    <span class="nav-text">Manajemen Organisasi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('portal.kemahasiswaan.pengajuan') }}" class="nav-link {{ request()->is('kemahasiswaan/pengajuan') ? 'active' : '' }}" data-close>
                    <span class="nav-icon" aria-hidden="true"><i class="bi bi-file-earmark-text-fill"></i></span>
                    <span class="nav-text">Manajemen Pengajuan Kegiatan & Laporan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('portal.kemahasiswaan.pengumuman') }}" class="nav-link {{ request()->is('kemahasiswaan/pengumuman') ? 'active' : '' }}" data-close>
                    <span class="nav-icon" aria-hidden="true"><i class="bi bi-megaphone-fill"></i></span>
                    <span class="nav-text">Manajemen Pengumuman & Distribusi Email</span>
                </a>
            </li>
            <li>
                <a href="{{ route('portal.kemahasiswaan.kontak') }}" class="nav-link {{ $isKontak ? 'active' : '' }}" data-close>
                    <span class="nav-icon" aria-hidden="true"><i class="bi bi-chat-left-text"></i></span>
                    <span class="nav-text">Kontak Pengurus UKM</span>
                </a>
            </li>
            <li>
                <a href="{{ route('portal.kemahasiswaan.kalender') }}" class="nav-link {{ $isKalender ? 'active' : '' }}" data-close>
                    <span class="nav-icon" aria-hidden="true"><i class="bi bi-calendar-event"></i></span>
                    <span class="nav-text">Kalender Kegiatan Kampus</span>
                </a>
            </li>

            <li class="sidebar-divider"></li>
            <li>
                <a href="{{ route('portal.kemahasiswaan.notifikasi') }}" class="nav-link {{ request()->is('kemahasiswaan/notifikasi') ? 'active' : '' }}" data-close>
                    <span class="nav-icon" aria-hidden="true"><i class="bi bi-bell-fill"></i></span>
                    <span class="nav-text">Notifikasi Sistem</span>
                    @if($notificationCount > 0)
                        <span class="notif-badge" aria-hidden="true">{{ min($notificationCount, 99) }}</span>
                    @endif
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="profile">
            <div class="avatar">AD</div>
            <div>
                <div class="name">Admin</div>
                <div class="role">Departemen Kemahasiswaan</div>
            </div>
        </div>

        <a href="{{ route('portal.login') }}" class="logout">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
