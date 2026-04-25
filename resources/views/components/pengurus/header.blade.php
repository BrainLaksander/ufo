<header class="ufo-pengurus-header">
    <div class="ufo-pengurus-header-inner">
        <div class="ufo-pengurus-header-left">
            <button type="button" class="ufo-pengurus-icon-btn" data-burger-open aria-label="Buka menu pengurus">
                <i class="bi bi-list"></i>
            </button>

            <a href="{{ route('portal.pengurus.dashboard') }}" class="ufo-pengurus-brand" aria-label="UFO Pengurus">
                <span class="ufo-pengurus-brand-mark" aria-hidden="true"><img src="{{ asset('logoufo.png') }}" alt="Logo UFO" class="ufo-pengurus-brand-logo"></span>
                <span class="ufo-pengurus-brand-text">
                    <strong>UFO</strong>
                    <small>UNKLAB Forum Organization</small>
                </span>
            </a>
        </div>

        <div class="ufo-pengurus-header-right">
            <button type="button" class="ufo-pengurus-icon-btn ufo-pengurus-bell" data-notification-open aria-label="Buka notifikasi">
                <i class="bi bi-bell"></i>
                <span class="ufo-pengurus-dot" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</header>
