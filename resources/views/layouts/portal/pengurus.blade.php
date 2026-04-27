<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portal Pengurus - UFO')</title>
    <link rel="icon" type="image/png" href="{{ asset('logoufo.png') }}">
    <link rel="shortcut icon" href="{{ asset('logoufo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logoufo.png') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pengurus.css') }}">
    <style>
        body.ufo-pengurus-shell .role-layout {
            display: block;
            min-height: 100vh;
        }

        body.ufo-pengurus-shell .role-content-shell {
            width: 100%;
            min-width: 0;
        }

        body.ufo-pengurus-shell .role-layout .role-main-content.ufo-pengurus-main {
            width: 100% !important;
            margin: 0 !important;
            padding: 96px 0 1.25rem !important;
            min-width: 0;
            max-width: none;
        }

        body.ufo-pengurus-shell .ufo-pengurus-main-inner {
            width: min(100%, 1120px) !important;
            max-width: 1120px !important;
            margin: 0 auto !important;
            padding: 0 1rem !important;
            box-sizing: border-box;
        }

        @media (max-width: 768px) {
            body.ufo-pengurus-shell .role-layout {
                display: block;
            }

            body.ufo-pengurus-shell .role-layout .role-main-content.ufo-pengurus-main {
                width: 100% !important;
                margin-left: 0 !important;
                padding-top: 84px !important;
            }

            body.ufo-pengurus-shell .ufo-pengurus-main-inner {
                padding-inline: 14px !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="ufo-pengurus-shell @yield('page_class', '')">
    <div class="role-layout">
        @include('components.pengurus.sidebar')

        <div class="role-content-shell">
            @include('components.pengurus.header')

            <main class="ufo-pengurus-main role-main-content">
                <div class="ufo-pengurus-main-inner">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <div id="sidebar-overlay" class="sidebar-overlay hidden" aria-hidden="true"></div>

    @includeIf('components.pengurus.burger')

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/core/utils.js') }}"></script>
    <script src="{{ asset('js/core/navigation.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        window.addEventListener('load', function () {
            window.scrollTo(0, 0);
            var scrollRoot = document.scrollingElement || document.documentElement;
            if (scrollRoot) {
                scrollRoot.scrollTop = 0;
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
