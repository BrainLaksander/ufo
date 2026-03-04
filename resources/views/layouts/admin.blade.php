<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin - UFO')</title>
    <link rel="stylesheet" href="/css/app.css">

    <style>
        /* sidebar/master layout helpers */
        .layout-wrapper {
            display: flex;
        }
        .layout-wrapper .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            background-color: #fff; /* or whatever background */
            z-index: 1020;
        }
        .layout-wrapper .main-content {
            margin-left: 260px;
            padding: 30px;
            width: calc(100% - 260px);
        }
        @media (max-width: 768px) {
            .layout-wrapper .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }
            .layout-wrapper .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body class="admin-layout">
    @include('components.admin.header')

    <div class="layout-wrapper">
        <!-- Sidebar -->
        @include('components.admin.sidebar')

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script src="/js/global.js"></script>
    <script src="/js/sidebar-toggle.js"></script>
</body>
</html>
