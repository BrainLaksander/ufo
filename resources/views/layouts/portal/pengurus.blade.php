<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard Organisasi') - UFO Pengurus</title>
    <link rel="icon" type="image/png" href="{{ asset('logoufo.png') }}">
    <link rel="shortcut icon" href="{{ asset('logoufo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logoufo.png') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pengurus.css') }}">
    @stack('styles')
</head>
@php
    $notifications = $notifications ?? [];
@endphp
<body class="ufo-pengurus-shell">
    @include('components.pengurus.header')
    @include('components.pengurus.burger')

    <div class="ufo-pengurus-overlay" data-notification-overlay hidden></div>
    <aside class="ufo-pengurus-notification" data-notification-panel aria-hidden="true">
        <div class="ufo-pengurus-notification-head">
            <div>
                <strong>Notifikasi</strong>
                <small>{{ count($notifications) }} notifikasi baru</small>
            </div>
            <button type="button" class="ufo-pengurus-icon-btn" data-notification-close aria-label="Tutup notifikasi">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="ufo-pengurus-notification-body">
            @forelse($notifications as $item)
                <article class="ufo-pengurus-notification-item tone-{{ $item['tone'] ?? 'info' }}">
                    <div class="ufo-pengurus-notification-icon">
                        <i class="bi {{ $item['icon'] ?? 'bi-info-circle' }}"></i>
                    </div>
                    <div>
                        <h6>{{ $item['title'] }}</h6>
                        <p>{{ $item['description'] }}</p>
                        <small>{{ $item['timestamp'] }}</small>
                    </div>
                </article>
            @empty
                <p class="ufo-kboard-item-meta mb-0">Belum ada notifikasi terbaru.</p>
            @endforelse
        </div>
    </aside>

    <main class="ufo-pengurus-main">
        @yield('content')
    </main>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/core/utils.js') }}"></script>
    <script src="{{ asset('js/core/pengurus-shell.js') }}"></script>
    @stack('scripts')
</body>
</html>
