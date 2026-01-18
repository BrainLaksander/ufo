<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UFO - Mahasiswa')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body class="bg-gray-50">
    @include('components.header')
    @include('components.burger-menu')
    
    <div class="pt-20">
        @yield('content')
    </div>

    <script src="{{ asset('js/mahasiswa.js') }}"></script>
    @stack('scripts')
</body>
</html>
