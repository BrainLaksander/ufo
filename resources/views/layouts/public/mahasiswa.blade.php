<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'UFO')</title>
    <link rel="icon" type="image/png" href="{{ asset('logoufo.png') }}">
    <link rel="shortcut icon" href="{{ asset('logoufo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logoufo.png') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
@php
    $notifications = $notifications ?? [];
@endphp
<body class="figma-ufo-body">
    @include('components.mahasiswa.header')
    @include('components.mahasiswa.burger')

    <div class="figma-ufo-overlay" data-notification-overlay hidden></div>
    <aside class="figma-ufo-notification" data-notification-panel aria-hidden="true">
        <div class="figma-ufo-notification-head">
            <strong>Notifikasi</strong>
            <button type="button" class="figma-ufo-icon-btn" data-notification-close aria-label="Tutup notifikasi">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="figma-ufo-notification-body">
            @foreach($notifications as $item)
                @php
                    $icon = match($item['icon'] ?? '') {
                        'calendar-event' => 'bi-calendar-event',
                        'search' => 'bi-search',
                        'megaphone' => 'bi-megaphone',
                        default => '',
                    };
                @endphp
                <article class="figma-ufo-notification-item">
                    <div class="figma-ufo-notification-icon">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <div>
                        <h6>{{ $item['title'] ?? '' }}</h6>
                        <div class="figma-ufo-notification-meta">
                            <span>{{ $item['category'] ?? '' }}</span>
                            <small>{{ $item['timestamp'] ?? '' }}</small>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </aside>

    <main class="figma-ufo-main">
        @yield('content')
    </main>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/core/utils.js') }}"></script>
    <script src="{{ asset('js/core/navigation.js') }}"></script>
    @stack('scripts')
</body>
</html>
