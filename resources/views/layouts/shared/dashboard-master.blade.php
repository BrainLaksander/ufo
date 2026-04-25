<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - UFO Organisasi Kampus</title>
    <link rel="icon" type="image/png" href="{{ asset('logoufo.png') }}">
    <link rel="shortcut icon" href="{{ asset('logoufo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logoufo.png') }}">

    {{-- Bootstrap 5 CSS --}}
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/views/dashboard-master.css') }}">

    @yield('extra-css')
</head>
<body>
    {{-- TOP NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dashboard d-flex align-items-center">
        <div class="container-fluid d-flex align-items-center">
            {{-- Brand --}}
            <a class="navbar-brand me-auto" href="{{ route('dashboard') }}">
                <img src="{{ asset('logoufo.png') }}" alt="Logo UFO" class="navbar-brand-logo" width="28" height="28">
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

            {{-- Menu Pengurus --}}
            @if(auth()->user()->isPengurus())
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

            {{-- Menu Kemahasiswaan --}}
            @if(!auth()->user()->isPengurus() && !auth()->user()->isMahasiswa())
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
