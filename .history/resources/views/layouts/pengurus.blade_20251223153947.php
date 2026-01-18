<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Pengurus - UFO')</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="pengurus-layout">
    @include('components.pengurus.header')
    @include('components.pengurus.sidebar')
    
    <main class="main-content">
        @yield('content')
    </main>

    <script src="/js/global.js"></script>
    <script src="/js/sidebar-toggle.js"></script>
</body>
</html>
