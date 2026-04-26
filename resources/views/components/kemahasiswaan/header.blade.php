@php
    $pageTitle = html_entity_decode(trim($__env->yieldContent('page_title', 'Dashboard')), ENT_QUOTES, 'UTF-8');
    $pageSubtitle = html_entity_decode(trim($__env->yieldContent('page_subtitle', 'Sistem Administrasi & Kontrol Organisasi Mahasiswa')), ENT_QUOTES, 'UTF-8');
    $notificationCount = (int) ($headerNotificationCount ?? 0);
@endphp

<header class="header kemahasiswaan-header">
    <div class="header-container kmh-shell-header-container">
        <div class="kmh-shell-header-left">
            <button id="sidebar-toggle" type="button" class="sidebar-toggle" data-kmh-sidebar-toggle aria-controls="sidebar" aria-expanded="false" aria-label="Tampilkan sidebar">
                <i class="bi bi-list"></i>
            </button>
            <div class="kmh-shell-heading">
                <h1 class="kmh-shell-page-title">{{ $pageTitle }}</h1>
                <p class="kmh-shell-page-subtitle">{{ $pageSubtitle }}</p>
            </div>
        </div>

        <div class="kmh-shell-header-actions">
            <a href="{{ route('portal.kemahasiswaan.notifikasi') }}" class="notification-btn kmh-header-notification-btn" aria-label="Notifikasi sistem">
                <i class="bi bi-bell-fill"></i>
                @if($notificationCount > 0)
                    <span class="kmh-header-notification-count">{{ min($notificationCount, 99) }}</span>
                @endif
            </a>
        </div>
    </div>
</header>
