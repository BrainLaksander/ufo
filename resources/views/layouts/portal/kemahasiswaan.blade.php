<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Kemahasiswaan - UFO')</title>
    <link rel="icon" type="image/png" href="{{ asset('logoufo.png') }}">
    <link rel="shortcut icon" href="{{ asset('logoufo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logoufo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    {{-- Load Bootstrap and local assets directly from public files --}}
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/kemahasiswaan.css') }}">
    @stack('styles')
</head>
@php
    $pageClass = trim($__env->yieldContent('page_class', ''));
@endphp
<body class="kemahasiswaan-layout{{ $pageClass !== '' ? ' ' . $pageClass : '' }}">
    @include('components.kemahasiswaan.header')

    <div class="role-layout role-layout-kemahasiswaan">
        <!-- Sidebar -->
        @include('components.kemahasiswaan.sidebar')

        <!-- Main content -->
        <main class="main-content role-main-content kmh-shell-main">
            @yield('content')
        </main>
    </div>

    <div id="sidebar-overlay" class="sidebar-overlay hidden" aria-hidden="true"></div>

    <div class="modal fade" id="kmhActionModal" tabindex="-1" aria-labelledby="kmhActionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="kmhActionModalLabel">Aksi</h5>
                        <small class="text-muted">Ringkasan aksi yang dipilih.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" id="kmhActionModalBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Legacy scripts (kept for non-SPA behavior) --}}
    <script src="/js/core/utils.js"></script>
    <script src="/js/core/navigation.js"></script>

    {{-- Load Chart.js + Bootstrap JS from local public assets --}}
    <script src="{{ asset('vendor/chart.js/chart.umd.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/kemahasiswaan/dashboard.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalEl = document.getElementById('kmhActionModal');
        var titleEl = document.getElementById('kmhActionModalLabel');
        var bodyEl = document.getElementById('kmhActionModalBody');

        if (!modalEl || !titleEl || !bodyEl) {
            return;
        }

        modalEl.addEventListener('show.bs.modal', function (event) {
            var trigger = event.relatedTarget;
            var title = trigger && trigger.getAttribute('data-modal-title') ? trigger.getAttribute('data-modal-title') : (trigger ? trigger.textContent.trim() : 'Aksi');
            var message = trigger && trigger.getAttribute('data-modal-message') ? trigger.getAttribute('data-modal-message') : 'Aksi ini dibuka sebagai modal.';

            titleEl.textContent = title || 'Aksi';
            bodyEl.textContent = message || 'Aksi ini dibuka sebagai modal.';
        });

        var body = document.body;
        var sidebar = document.getElementById('sidebar');
        var overlay = document.getElementById('sidebar-overlay');
        var toggleButtons = Array.prototype.slice.call(document.querySelectorAll('[data-kmh-sidebar-toggle]'));
        var desktopCollapsedKey = 'kmh-sidebar-collapsed';
        var mobileQuery = window.matchMedia('(max-width: 1024px)');

        if (!sidebar || toggleButtons.length === 0) {
            return;
        }

        function isMobile() {
            return mobileQuery.matches;
        }

        function getStoredDesktopCollapsed() {
            try {
                return window.localStorage.getItem(desktopCollapsedKey) === '1';
            } catch (error) {
                return false;
            }
        }

        function setStoredDesktopCollapsed(value) {
            try {
                window.localStorage.setItem(desktopCollapsedKey, value ? '1' : '0');
            } catch (error) {
                // ignore storage errors
            }
        }

        function setButtonState(expanded) {
            toggleButtons.forEach(function (button) {
                button.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                button.setAttribute('aria-label', expanded ? 'Minimize sidebar' : 'Buka sidebar');
            });
        }

        function setSidebarState(expanded, persistDesktopState) {
            if (isMobile()) {
                sidebar.setAttribute('aria-hidden', expanded ? 'false' : 'true');
                sidebar.classList.toggle('is-open', expanded);

                if (overlay) {
                    overlay.classList.toggle('hidden', !expanded);
                    overlay.setAttribute('aria-hidden', expanded ? 'false' : 'true');
                }
            } else {
                body.classList.toggle('kmh-sidebar-collapsed', !expanded);
                sidebar.setAttribute('aria-hidden', 'false');
                sidebar.classList.remove('is-open');

                if (overlay) {
                    overlay.classList.add('hidden');
                    overlay.setAttribute('aria-hidden', 'true');
                }

                if (persistDesktopState) {
                    setStoredDesktopCollapsed(!expanded);
                }
            }

            setButtonState(expanded);
        }

        var initialExpanded = true;
        if (!isMobile()) {
            initialExpanded = !getStoredDesktopCollapsed();
        }

        setSidebarState(initialExpanded, false);

        toggleButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var expanded;

                if (isMobile()) {
                    expanded = sidebar.getAttribute('aria-hidden') === 'true';
                } else {
                    expanded = !body.classList.contains('kmh-sidebar-collapsed');
                }

                setSidebarState(!expanded, true);
            });
        });

        if (overlay) {
            overlay.addEventListener('click', function () {
                setSidebarState(false, false);
            });
        }

        mobileQuery.addEventListener('change', function () {
            if (isMobile()) {
                body.classList.remove('kmh-sidebar-collapsed');
                setSidebarState(false, false);
            } else {
                setSidebarState(!getStoredDesktopCollapsed(), false);
            }
        });
    });
    </script>
    @stack('scripts')
</body>
</html>
