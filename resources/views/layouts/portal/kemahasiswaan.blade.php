<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portal Kemahasiswaan - UFO')</title>
    <link rel="icon" type="image/png" href="{{ asset('logoufo.png') }}">
    <link rel="shortcut icon" href="{{ asset('logoufo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logoufo.png') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/kemahasiswaan.css') }}">
    @stack('styles')
</head>
<body class="kemahasiswaan-layout kmh-sidebar-hidden @yield('page_class', '')">
    <div class="role-layout">
        @include('components.kemahasiswaan.sidebar')

        <div class="role-content-shell">
            @include('components.kemahasiswaan.header')

            <main class="kmh-shell-main role-main-content">
                @yield('content')
            </main>
        </div>
    </div>

    <div id="sidebar-overlay" class="sidebar-overlay hidden" aria-hidden="true"></div>

    @includeIf('components.kemahasiswaan.burger')

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/core/utils.js') }}"></script>
    <script src="{{ asset('js/core/navigation.js') }}"></script>
    <script src="{{ asset('js/kemahasiswaan/dashboard.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
