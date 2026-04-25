@php
    $pageTitle = trim($__env->yieldContent('page_title', 'Dashboard'));
    $pageSubtitle = trim($__env->yieldContent('page_subtitle', 'Sistem Administrasi & Kontrol Organisasi Mahasiswa'));
    $notificationCount = (int) ($headerNotificationCount ?? 0);
@endphp

<header class="header kemahasiswaan-header">
    <div class="header-container kmh-shell-header-container">
        <div class="kmh-shell-header-left">
            <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Buka sidebar">
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
