<header class="figma-ufo-header">
    <div class="figma-ufo-header-inner">
        <div class="figma-ufo-header-left">
            <button type="button" class="figma-ufo-icon-btn" data-burger-open aria-label="Buka menu">
                <i class="bi bi-list"></i>
            </button>

            <a href="{{ route('mahasiswa.organisasi.index') }}" class="figma-ufo-brand">
                <span class="figma-ufo-brand-mark" aria-hidden="true"><img src="{{ asset('logoufo.png') }}" alt="Logo UFO" class="figma-ufo-brand-logo"></span>
                <span class="figma-ufo-brand-text">
                    <strong>UFO</strong>
                    <small>UNKLAB Forum Organization</small>
                </span>
            </a>
        </div>

        <button type="button" class="figma-ufo-icon-btn figma-ufo-notif-btn" data-notification-open aria-label="Buka notifikasi">
            <i class="bi bi-bell"></i>
            <span class="figma-ufo-dot"></span>
        </button>
    </div>
</header>
