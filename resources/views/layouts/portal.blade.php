<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Login - UFO')</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="portal-layout">
    <main class="main-content">
        @yield('content')
    </main>

    <script src="/js/core/utils.js"></script>
    <script src="/js/portal/auth.js"></script>
</body>
</html>
