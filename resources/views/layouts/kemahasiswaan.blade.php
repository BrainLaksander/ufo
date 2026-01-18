<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Kemahasiswaan - UFO')</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="kemahasiswaan-layout">
    @include('components.kemahasiswaan.header')
    @include('components.kemahasiswaan.sidebar')
    
    <main class="main-content">
        @yield('content')
    </main>

    <script src="/js/global.js"></script>
    <script src="/js/sidebar-toggle.js"></script>
</body>
</html>
