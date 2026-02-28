<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Kemahasiswaan - UFO')</title>
    {{-- Load Vite assets (JS/CSS) for React components --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="kemahasiswaan-layout">
    @include('components.kemahasiswaan.header')
    @include('components.kemahasiswaan.sidebar')
    
    <main class="main-content">
        @yield('content')
    </main>

    {{-- Legacy scripts (kept for non-SPA behavior) --}}
    <script src="/js/global.js"></script>
    <script src="/js/sidebar-toggle.js"></script>
</body>
</html>
