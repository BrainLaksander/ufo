<header class="header">
    <div class="header-left">
        <button type="button" class="menu-btn" aria-label="Buka menu" aria-controls="ufo-sidebar" aria-expanded="false">
            <span class="menu-lines" aria-hidden="true">
                <span></span><span></span><span></span>
            </span>
        </button>

        <div class="brand">
            <div class="logo">
                <img src="/logoufo.png" alt="Logo UFO">
            </div>
            <div>
                <div class="brand-title">UFO</div>
                <div class="brand-subtitle">UNKLAB Forum Organization</div>
            </div>
        </div>
    </div>

    <div class="icon-wrap">
        <button type="button" class="icon-btn" aria-label="Notifikasi" aria-controls="ufo-notification-panel" aria-expanded="false" data-notification-toggle>
            <svg viewBox="0 0 24 24" fill="none" width="22" height="22" stroke="currentColor">
                <path d="M15 17H5c1.5-1.4 2-3.1 2-5.8V10a5 5 0 1 1 10 0v1.2c0 2.7.5 4.4 2 5.8h-4" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M10 19a2 2 0 0 0 4 0" stroke-width="1.8" stroke-linecap="round"></path>
            </svg>
            @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
                <span class="badge" id="notification-badge">{{ $unreadNotifCount }}</span>
            @endif
        </button>
    </div>
</header>
