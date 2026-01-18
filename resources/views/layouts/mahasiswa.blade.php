<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UFO - UNKLAB Forum Organization')</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="mahasiswa-layout">
    @include('components.mahasiswa.header')
    
    <main class="main-content">
        @yield('content')
    </main>

    <script src="/js/global.js"></script>
    <script src="/js/sidebar-toggle.js"></script>
</body>
</html>
