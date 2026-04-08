<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - UFO Organisasi Kampus</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}">

    {{-- Custom Styles --}}
    <style>
        :root {
            --primary: #6A1B9A;
            --secondary: #FFC107;
            --success: #198754;
            --danger: #DC3545;
            --warning: #FFC107;
            --info: #0DCAF0;
            --light: #F8F9FA;
            --dark: #212529;
            --muted: #6C757D;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--light);
            color: var(--dark);
        }

        /* ========== HEADER/NAVBAR ========== */
        .navbar-dashboard {
            background-color: var(--primary);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1020;
            height: 70px;
            padding: 0 1.5rem;
        }

        .navbar-dashboard .navbar-brand {
            display: flex !important;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.3rem;
            color: white !important;
            text-decoration: none;
        }

        .navbar-dashboard .navbar-brand i {
            font-size: 1.5rem;
        }

        .navbar-dashboard .nav-item {
            margin-right: 1.5rem;
        }

        .navbar-dashboard .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .navbar-dashboard .nav-link:hover {
            color: white !important;
        }

        .navbar-dashboard .nav-link i {
            margin-right: 0.5rem;
        }

        /* ========== SIDEBAR ========== */
        .sidebar {
            position: fixed;
            left: 0;
            top: 70px;
            bottom: 0;
            width: 280px;
            background: white;
            border-right: 1px solid #e9ecef;
            overflow-y: auto;
            padding: 1.5rem 0;
            z-index: 1010;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 1.5rem;
            color: var(--muted);
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .sidebar-menu .menu-item:hover {
            color: var(--primary);
            background-color: rgba(106, 27, 154, 0.05);
        }

        .sidebar-menu .menu-item.active {
            color: white;
            background-color: var(--primary);
            border-left: 3px solid var(--secondary);
            padding-left: calc(1.5rem - 3px);
        }

        .sidebar-menu .menu-item i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        .sidebar-divider {
            margin: 1rem 0;
            border-top: 1px solid #e9ecef;
        }

        /* ========== MAIN CONTENT ========== */
        .main-wrapper {
            margin-left: 280px;
            margin-top: 70px;
            min-height: calc(100vh - 70px);
            padding: 2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
                width: 100%;
                z-index: 1015;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
                padding: 1rem;
            }

            .navbar-dashboard {
                padding: 0 1rem;
            }
        }

        /* ========== CARDS ========== */
        .card-stat {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }

        .card-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--dark);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* ========== BADGES ========== */
        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* ========== BUTTONS ========== */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: #5b1873;
            border-color: #5b1873;
        }

        .btn-secondary {
            background-color: var(--secondary);
            border-color: var(--secondary);
            color: var(--dark);
        }

        .btn-secondary:hover {
            background-color: #e0a800;
            border-color: #e0a800;
        }

        .btn-soft-primary {
            background-color: rgba(106, 27, 154, 0.1);
            color: var(--primary);
            border: 1px solid rgba(106, 27, 154, 0.2);
        }

        .btn-soft-primary:hover {
            background-color: rgba(106, 27, 154, 0.2);
            color: var(--primary);
            border-color: rgba(106, 27, 154, 0.3);
        }

        /* ========== UTILITY ========== */
        .text-muted {
            color: var(--muted) !important;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1.5rem;
        }

        .breadcrumb-custom {
            background-color: transparent;
            padding: 0;
            margin-bottom: 2rem;
        }

        .breadcrumb-custom .breadcrumb-item.active {
            color: var(--muted);
        }

        .breadcrumb-custom .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
        }

        .breadcrumb-custom .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        /* ========== NOTIFICATIONS ========== */
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--danger);
            color: white;
            font-size: 0.7rem;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        @media (max-width: 576px) {
            .navbar-dashboard {
                padding: 0 0.75rem;
            }

            .navbar-dashboard .navbar-brand {
                font-size: 1.1rem;
            }

            .main-wrapper {
                padding: 1rem 0.75rem;
            }
        }
    </style>

    @yield('extra-css')
</head>
<body>
    {{-- TOP NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dashboard d-flex align-items-center">
        <div class="container-fluid d-flex align-items-center">
            {{-- Brand --}}
            <a class="navbar-brand me-auto" href="{{ route('dashboard') }}">
                <i class="bi bi-building"></i>
                <span class="d-none d-sm-inline">UFO</span>
            </a>

            {{-- Mobile Menu Toggle --}}
            <button class="btn btn-link text-white d-lg-none me-2" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>

            {{-- Navbar Menu --}}
            <div class="d-flex align-items-center gap-2">
                {{-- Notifications --}}
                <div class="nav-item dropdown position-relative">
                    <button class="nav-link btn btn-link text-white position-relative" type="button"
                        id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li><a class="dropdown-item" href="#">Proposal baru menunggu review</a></li>
                        <li><a class="dropdown-item" href="#">Pengumuman telah dipublikasikan</a></li>
                        <li><a class="dropdown-item" href="#">Barang ditemukan</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Lihat semua notifikasi</a></li>
                    </ul>
                </div>

                {{-- User Profile --}}
                <div class="nav-item dropdown">
                    <button class="nav-link btn btn-link text-white d-flex align-items-center" type="button"
                        id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ auth()->user()->getAvatarUrl() }}" alt="Avatar" 
                            class="rounded-circle me-2" width="32" height="32">
                        <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><span class="dropdown-header">{{ auth()->user()->name }}</span></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Pengaturan</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('home') }}" class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Landing Page</span>
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- Admin & Pengurus Menu --}}
            @if(auth()->user()->hasAnyRole(['admin', 'pengurus']))
                <li>
                    <a href="{{ route('profil.show') }}"
                        class="menu-item {{ request()->routeIs('profil.*') ? 'active' : '' }}">
                        <i class="bi bi-info-circle"></i>
                        <span>Profil Organisasi</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('events.index') }}"
                        class="menu-item {{ request()->routeIs('events.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event"></i>
                        <span>Event</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('announcements.index') }}"
                        class="menu-item {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                        <i class="bi bi-megaphone"></i>
                        <span>Pengumuman</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('proposals.index') }}"
                        class="menu-item {{ request()->routeIs('proposals.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Pengajuan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('lostfound.index') }}"
                        class="menu-item {{ request()->routeIs('lostfound.*') ? 'active' : '' }}">
                        <i class="bi bi-search"></i>
                        <span>Lost & Found</span>
                    </a>
                </li>

                <li class="sidebar-divider"></li>
            @endif

            {{-- Admin Only Menu --}}
            @if(auth()->user()->isAdmin())
                <li>
                    <a href="{{ route('messages.index') }}"
                        class="menu-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                        <i class="bi bi-chat-left-text"></i>
                        <span>Pesan</span>
                    </a>
                </li>

                <li class="sidebar-divider"></li>
            @endif

            {{-- Logout (jika diinginkan juga di sidebar) --}}
            <li>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="menu-item w-100 text-start">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    {{-- SIDEBAR BACKDROP (Mobile) --}}
    <div class="sidebar-backdrop d-lg-none" id="sidebarBackdrop"></div>

    {{-- MAIN CONTENT --}}
    <div class="main-wrapper">
        @if(request()->breadcrumbs)
            <nav aria-label="breadcrumb" class="breadcrumb-custom">
                <ol class="breadcrumb">
                    {{-- Breadcrumb items --}}
                </ol>
            </nav>
        @endif

        @yield('content')
    </div>

    {{-- Bootstrap JS Bundle --}}
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    {{-- Mobile Sidebar Toggle --}}
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                backdrop.classList.toggle('show');
            });

            backdrop.addEventListener('click', function() {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
            });

            // Close sidebar when clicking menu items
            sidebar.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                });
            });
        }
    </script>

    @yield('extra-js')
</body>
</html>
