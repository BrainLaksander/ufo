<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'UFO - Mahasiswa')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    @include('components.header')
    @include('components.burger-menu')

    <div class="layout">
        <div class="layout-body container">
            <div class="layout-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/mahasiswa-ui.js') }}"></script>
    @stack('scripts')
</body>
</html>
