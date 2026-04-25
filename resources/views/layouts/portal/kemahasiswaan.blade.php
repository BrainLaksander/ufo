<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Kemahasiswaan - UFO')</title>
    <link rel="icon" type="image/png" href="{{ asset('logoufo.png') }}">
    <link rel="shortcut icon" href="{{ asset('logoufo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logoufo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    {{-- Load Bootstrap and local assets directly from public files --}}
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/kemahasiswaan.css') }}">
    @stack('styles')
</head>
@php
    $pageClass = trim($__env->yieldContent('page_class', ''));
@endphp
<body class="kemahasiswaan-layout{{ $pageClass !== '' ? ' ' . $pageClass : '' }}">
    @include('components.kemahasiswaan.header')

    <div class="role-layout role-layout-kemahasiswaan">
        <!-- Sidebar -->
        @include('components.kemahasiswaan.sidebar')

        <!-- Main content -->
        <main class="main-content role-main-content kmh-shell-main">
            @yield('content')
        </main>
    </div>

    {{-- Legacy scripts (kept for non-SPA behavior) --}}
    <script src="/js/core/utils.js"></script>
    <script src="/js/core/navigation.js"></script>

    {{-- Load Chart.js + Bootstrap JS from local public assets --}}
    <script src="{{ asset('vendor/chart.js/chart.umd.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/kemahasiswaan/dashboard.js') }}"></script>
    @stack('scripts')
</body>
</html>
