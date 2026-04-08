<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Kemahasiswaan - UFO')</title>
    {{-- Load Bootstrap and local assets directly from public files --}}
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/kemahasiswaan.css') }}">
    @stack('styles')
</head>
<body class="kemahasiswaan-layout">
    @include('components.kemahasiswaan.header')

    <div class="role-layout role-layout-kemahasiswaan">
        <!-- Sidebar -->
        @include('components.kemahasiswaan.sidebar')

        <!-- Main content -->
        <main class="main-content role-main-content">
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
