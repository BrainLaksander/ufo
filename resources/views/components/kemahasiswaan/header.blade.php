<header class="header kemahasiswaan-header">
    <div class="header-container">
        <div class="header-left">
            <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Open sidebar"><i class="bi bi-list"></i></button>
            <a href="{{ route('home') }}" class="header-brand" aria-label="Kembali ke landing page UFO">
                <span class="brand-icon"><i class="bi bi-mortarboard-fill"></i></span>
                <div class="brand-text">
                    <div class="brand-title">Portal Kemahasiswaan</div>
                    <div class="brand-subtitle">UNKLAB Forum Organization</div>
                </div>
            </a>
        </div>
        <div class="header-right">
            <button class="notification-btn" aria-label="Notifications"><i class="bi bi-bell-fill"></i><span class="notification-dot"></span></button>
            <a href="{{ route('portal.login') }}" class="btn-logout">Keluar</a>
        </div>
    </div>
</header>
