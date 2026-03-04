<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Kemahasiswaan - UFO')</title>
    {{-- Load Bootstrap and local assets (CDN + public files) so npm install / Vite not required --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-+qdLa6k7Yp6U9gKcY6aZ3e1ZfQ2n9m3l3IqC+0G1v+YqF0s6jrH8sKQ3w2w5yXg5" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/kemahasiswaan.css') }}">

    <style>
        /* layout wrapper for kemahasiswaan */
        .layout-wrapper { display: flex; }
        /* match sidebar width defined elsewhere */
        .layout-wrapper .kemahasiswaan-sidebar { width: 280px; height: 100vh; position: fixed; top:0; left:0; overflow-y:auto; z-index: 1020; }
        .layout-wrapper .main-content { margin-left: 280px !important; padding: 30px; width: calc(100% - 280px) !important; position: relative; z-index: 1; }
        @media (max-width: 768px) {
            .layout-wrapper .kemahasiswaan-sidebar { position: relative; width: 100%; height: auto; }
            .layout-wrapper .main-content { margin-left: 0; width: 100%; }
        }
    </style>
</head>
<body class="kemahasiswaan-layout">
    @include('components.kemahasiswaan.header')

    <div class="layout-wrapper">
        <!-- Sidebar -->
        @include('components.kemahasiswaan.sidebar')

        <!-- Main content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    {{-- Legacy scripts (kept for non-SPA behavior) --}}
    <script src="/js/global.js"></script>
    <script src="/js/sidebar-toggle.js"></script>

    {{-- Load Chart.js + Bootstrap JS from CDN and local dashboard script (no npm required) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoA6VQZR0sVx1kzE2nq8T7V9Zz0z1rP6FQ6Y2vMZ2j9F3g7" crossorigin="anonymous"></script>
    <script src="{{ asset('js/kemahasiswaan-dashboard.js') }}"></script>
</body>
</html>
