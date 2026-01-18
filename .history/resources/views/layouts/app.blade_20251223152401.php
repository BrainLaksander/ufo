<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'UFO - UNKLAB Forum Organization')</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="@yield('body-class')">
    @yield('header')
    @yield('burger')
    
    <main class="main-content">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'UFO')</title>
    {{-- place to add global CSS links (public/css/app.css) --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    @include('partials.header')

    <main class="container">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
