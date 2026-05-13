<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'UFO Forum Student Interface')</title>
    <link rel="stylesheet" href="/css/mahasiswa.css">
    @if(request()->routeIs('kemahasiswaan.*'))
        <link rel="stylesheet" href="/css/kemahasiswaan.css">
    @endif
    @if(request()->routeIs('pengurus-ukm.*'))
        <link rel="stylesheet" href="/css/pengurus-ukm.css">
    @endif
    @stack('head')
</head>
<body>
    <div class="app">
        @include('partials.sidebar')
        @include('partials.header')

        <aside id="ufo-notification-panel" class="notification-panel" aria-hidden="true">
            <div class="notification-panel-head">
                <h2>Notifikasi</h2>
                <button type="button" class="notification-close" aria-label="Tutup notifikasi">&times;</button>
            </div>

            <div class="notification-panel-body">
                @isset($notifications)
                    @if(is_iterable($notifications) && count($notifications))
                        <ul class="notification-list">
                            @foreach($notifications as $notification)
                                <li class="notification-item">
                                    <div class="notification-dot"></div>
                                    <div>
                                        <strong>{{ $notification->title ?? '' }}</strong>
                                        <p>{{ $notification->message ?? '' }}</p>
                                        @if(!empty($notification->time))
                                            <span style="font-size: 12px; color: #94a3b8;">{{ $notification->time }}</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        @auth
                            <form action="{{ route('notifications.read-all') }}" method="POST" style="padding: 12px 20px 0; text-align: center;">
                                @csrf
                                <button type="submit" style="background: none; border: none; color: #6f3ba7; font-size: 13px; font-weight: 600; cursor: pointer; padding: 8px 16px;">Tandai Semua Sudah Dibaca</button>
                            </form>
                        @endauth
                    @else
                        <div class="notification-empty">
                            <strong>Belum ada notifikasi</strong>
                            <p>Nanti notifikasi terbaru akan muncul di sini.</p>
                        </div>
                    @endif
                @else
                    <div class="notification-empty">
                        <strong>Belum ada notifikasi</strong>
                        <p>Nanti notifikasi terbaru akan muncul di sini.</p>
                    </div>
                @endisset
            </div>
        </aside>
        <div class="notification-backdrop" data-notification-backdrop></div>

        @if (session('error'))
            <div class="flash-alert flash-alert--error" role="alert" aria-live="polite">
                <span>{{ session('error') }}</span>
                <button type="button" class="flash-alert__close" aria-label="Tutup" onclick="this.parentElement.remove()">×</button>
            </div>
        @endif

        @if (session('success'))
            <div class="flash-alert flash-alert--success" role="alert" aria-live="polite">
                <span>{{ session('success') }}</span>
                <button type="button" class="flash-alert__close" aria-label="Tutup" onclick="this.parentElement.remove()">×</button>
            </div>
        @endif

        @yield('content')

        <script src="/js/ui.js" defer></script>
        <script src="/js/mahasiswa.js" defer></script>
        @stack('scripts')
    </div>
</body>
</html>
