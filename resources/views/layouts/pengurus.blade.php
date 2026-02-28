<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Pengurus') - UFO Organisasi</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #3B82F6;
            --secondary-color: #663399;
            --dark-color: #1a1a2e;
            --light-bg: #f8f9fa;
            --border-color: #dee2e6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--light-bg);
            overflow-x: hidden;
        }

        /* Header Styles */
        .navbar-pengurus {
            background-color: #6A1B9A;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1040;
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .navbar-pengurus .navbar-brand {
            font-weight: 700;
            color: white !important;
            margin-right: auto;
            font-size: 1.3rem;
        }

        .navbar-pengurus .navbar-brand i {
            margin-right: 10px;
        }

        .navbar-pengurus .navbar-center {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-right: auto;
            margin-left: 20px;
        }

        .navbar-pengurus .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar-pengurus .nav-link {
            color: white !important;
            cursor: pointer;
            transition: color 0.3s;
            position: relative;
        }

        .navbar-pengurus .nav-link:hover {
            color: #FFD54F !important;
        }

        .navbar-pengurus .nav-link i {
            font-size: 1.2rem;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 288px;
            background: #FFC107;
            color: black;
            position: fixed;
            left: 0;
            top: 70px;
            bottom: 0;
            z-index: 1030;
            overflow-y: auto;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .sidebar-header {
            padding: 24px 16px;
            border-bottom: none;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .sidebar-header .header-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #6A1B9A;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            flex-shrink: 0;
        }

        .sidebar-header .header-text h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: black;
        }

        .sidebar-header .header-text p {
            margin: 4px 0 0 0;
            font-size: 12px;
            color: rgba(0, 0, 0, 0.6);
        }

        .sidebar-nav {
            padding: 0 16px;
            list-style: none;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .sidebar-nav li {
            margin: 0;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: black;
            text-decoration: none;
            transition: all 0.2s;
            border-radius: 12px;
            margin: 0;
            font-size: 15px;
            font-weight: 500;
            background: #FFD54F;
        }

        .sidebar-nav a:hover {
            background: #FFE082;
            color: black;
        }

        .sidebar-nav a.active {
            background: #6A1B9A;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(106, 27, 154, 0.3);
        }

        .sidebar-nav a i {
            width: 20px;
            height: 20px;
            text-align: center;
            font-size: 18px;
        }

        .sidebar-divider {
            height: 1px;
            background: rgba(0, 0, 0, 0.1);
            margin: 8px 0;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            margin-top: auto;
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: black;
            text-decoration: none;
            font-size: 15px;
            padding: 12px 16px;
            border-radius: 12px;
            transition: all 0.2s;
            background: #FFD54F;
            font-weight: 500;
        }

        .sidebar-footer a:hover {
            background: #FFE082;
            color: black;
        }

        /* Notification Panel */
        .notification-panel {
            width: 380px;
            background-color: white;
            position: fixed;
            right: 0;
            top: 70px;
            bottom: 0;
            z-index: 1030;
            overflow-y: auto;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            box-shadow: -2px 0 8px rgba(0, 0, 0, 0.1);
            border-left: 1px solid var(--border-color);
        }

        .notification-panel.show {
            transform: translateX(0);
        }

        .notification-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h5 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .notification-header .btn-close {
            padding: 0;
        }

        .notification-list {
            padding: 0;
            list-style: none;
            margin: 0;
        }

        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .notification-item:hover {
            background-color: var(--light-bg);
        }

        .notification-item.unread {
            background-color: rgba(59, 130, 246, 0.05);
            border-left: 3px solid var(--primary-color);
        }

        .notification-item-title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 5px;
            color: #333;
        }

        .notification-item-text {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 8px;
        }

        .notification-item-time {
            font-size: 0.75rem;
            color: #999;
        }

        /* Main Content Area */
        .main-content {
            margin-top: 70px;
            margin-left: 0;
            transition: margin-left 0.3s;
            padding: 30px 20px;
            min-height: calc(100vh - 70px);
        }

        @media (min-width: 768px) {
            .main-content {
                padding: 30px;
            }
        }

        /* Backdrop */
        .sidebar-backdrop {
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1020;
            display: none;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .page-header p {
            color: #666;
            margin: 0;
        }

        /* Cards */
        .card-dashboard {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
        }

        .card-dashboard:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .card-stat {
            padding: 20px;
            text-align: center;
            border-radius: 12px;
        }

        .card-stat-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .card-stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .card-stat-label {
            font-size: 0.9rem;
            color: #666;
        }

        /* Buttons */
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            background-color: #2563eb;
            border-color: #2563eb;
            color: white;
        }

        /* Floating Chatbot Button */
        .floating-chatbot {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            transition: all 0.3s;
            z-index: 1010;
            color: white;
            border: none;
            padding: 0;
        }

        .floating-chatbot:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6);
        }

        .floating-chatbot i {
            font-size: 1.5rem;
        }

        /* Badge */
        .badge-custom {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-primary {
            background-color: rgba(59, 130, 246, 0.2);
            color: var(--primary-color);
        }

        .badge-success {
            background-color: rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }

        .badge-warning {
            background-color: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-pengurus .navbar-brand {
                font-size: 1rem;
            }

            .navbar-pengurus .navbar-center {
                display: none;
            }

            .notification-panel {
                width: 100%;
            }

            .sidebar {
                width: 100%;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .floating-chatbot {
                bottom: 20px;
                right: 20px;
                width: 50px;
                height: 50px;
            }
        }

        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar,
        .notification-panel::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .notification-panel::-webkit-scrollbar-track {
            background: transparent;
        }

        .notification-panel::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 3px;
        }

        .notification-panel::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.2);
        }

        /* Print */
        @media print {
            .navbar-pengurus,
            .sidebar,
            .notification-panel,
            .floating-chatbot,
            .sidebar-backdrop {
                display: none;
            }

            .main-content {
                margin-top: 0;
                padding: 0;
            }
        }
        }

        .user-info-name {
            font-weight: 600;
        }

        .user-info-role {
            opacity: 0.9;
            font-size: 11px;
        }

        .notification-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .notification-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        /* SIDEBAR */
        .pengurus-sidebar {
            position: fixed;
            left: 0;
            top: 70px;
            bottom: 0;
            width: 280px;
            background: linear-gradient(135deg, var(--primary) 0%, #5a2d7f 100%);
            color: white;
            overflow-y: auto;
            padding: 20px 0;
            z-index: 999;
            transition: transform 0.3s;
        }

        .pengurus-sidebar.hidden {
            transform: translateX(-100%);
        }

        .sidebar-nav {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .sidebar-nav a {
            display: block;
            padding: 14px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 4px solid transparent;
            font-size: 14px;
            font-weight: 500;
        }

        .sidebar-nav a:hover {
            background: rgba(255,255,255,0.1);
            border-left-color: var(--accent);
        }

        .sidebar-nav a.active {
            background: rgba(255,255,255,0.15);
            border-left-color: var(--accent);
            color: var(--accent);
        }

        .sidebar-nav-icon {
            margin-right: 10px;
            font-size: 18px;
        }

        /* MAIN CONTENT */
        .pengurus-main {
            min-height: calc(100vh - 70px);
            padding: 30px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            margin: 0;
        }

        /* CARDS */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 2px solid #f0f0f0;
            transition: all 0.3s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(102,51,153,0.1);
            border-color: var(--accent);
        }

        .stat-card-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .stat-card-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .stat-card-label {
            font-size: 13px;
            color: var(--text-secondary);
        }

        /* BUTTONS */
        .btn-primary-org {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-org:hover {
            background: #552988;
            color: white;
            text-decoration: none;
        }

        .btn-secondary-org {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-secondary-org:hover {
            background: var(--primary);
            color: white;
            text-decoration: none;
        }

        .btn-danger-org {
            background: var(--danger);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s;
        }

        .btn-danger-org:hover {
            background: #bb0000;
        }

        .btn-success-org {
            background: var(--success);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s;
        }

        .btn-success-org:hover {
            background: #009900;
        }

        /* TABLE */
        .table-org {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 2px solid #f0f0f0;
        }

        .table-org thead {
            background: var(--primary);
            color: white;
        }

        .table-org th {
            border: none;
            padding: 15px;
            font-weight: 600;
            font-size: 13px;
        }

        .table-org td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-org tbody tr:hover {
            background: #f9f9f9;
        }

        /* BADGE */
        .badge-org {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-org.success {
            background: rgba(0,170,0,0.15);
            color: var(--success);
        }

        .badge-org.warning {
            background: rgba(255,165,0,0.15);
            color: #FFA500;
        }

        .badge-org.danger {
            background: rgba(204,0,0,0.15);
            color: var(--danger);
        }

        .badge-org.info {
            background: rgba(102,51,153,0.15);
            color: var(--primary);
        }

        /* FORM */
        .form-org {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 2px solid #f0f0f0;
        }

        .form-org .form-group {
            margin-bottom: 20px;
        }

        .form-org label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary);
            font-size: 14px;
        }

        .form-org input,
        .form-org textarea,
        .form-org select {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            transition: border 0.2s;
        }

        .form-org input:focus,
        .form-org textarea:focus,
        .form-org select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(102,51,153,0.1);
        }

        .form-org textarea {
            min-height: 150px;
            resize: vertical;
        }

}
    </style>

    @yield('extra-css')
</head>
<body>
    <!-- Header Navigation -->
    <nav class="navbar-pengurus">
        <div class="navbar-brand">
            <i class="fas fa-graduation-cap"></i>
            UFO Pengurus
        </div>

        <div class="navbar-center">
            <span style="color: #666; font-weight: 600;">Portal Manajemen Organisasi</span>
        </div>

        <div class="navbar-right">
            <!-- Notification Button -->
            <a class="nav-link" href="#" id="notificationBtn" title="Notifikasi">
                <i class="fas fa-bell"></i>
                <span class="badge bg-danger position-absolute" style="font-size: 0.65rem; padding: 2px 5px; top: -5px; right: -5px;">3</span>
            </a>

            <!-- User Menu -->
            <div class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name=Ahmad+Rifki&background=3B82F6&color=fff&size=32" alt="User" class="rounded-circle" width="32" height="32">
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>

            <!-- Burger Menu Button -->
            <a class="nav-link" href="#" id="burgerBtn" title="Menu">
                <i class="fas fa-bars"></i>
            </a>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h5>UFO Organisasi</h5>
            <p>Pengurus</p>
        </div>

        <ul class="sidebar-nav">
            <li><a href="{{ route('portal.pengurus.dashboard') }}" class="@if(request()->routeIs('portal.pengurus.dashboard')) active @endif">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a></li>

            <li><a href="{{ route('portal.pengurus.members') }}" class="@if(request()->routeIs('portal.pengurus.members')) active @endif">
                <i class="fas fa-building"></i>
                <span>Profil Organisasi</span>
            </a></li>

            <li><a href="{{ route('portal.pengurus.events') }}" class="@if(request()->routeIs('portal.pengurus.events')) active @endif">
                <i class="fas fa-calendar-alt"></i>
                <span>Event</span>
            </a></li>

            <li><a href="{{ route('portal.pengurus.announcements') }}" class="@if(request()->routeIs('portal.pengurus.announcements')) active @endif">
                <i class="fas fa-bullhorn"></i>
                <span>Pengumuman</span>
            </a></li>

            <li><a href="{{ route('portal.pengurus.proposals') }}" class="@if(request()->routeIs('portal.pengurus.proposals')) active @endif">
                <i class="fas fa-file-alt"></i>
                <span>Pengajuan Laporan</span>
            </a></li>

            <li><a href="{{ route('portal.pengurus.applications') }}" class="@if(request()->routeIs('portal.pengurus.applications')) active @endif">
                <i class="fas fa-user-check"></i>
                <span>Pendaftaran</span>
            </a></li>

            <li><a href="{{ route('portal.pengurus.lostandfound') }}" class="@if(request()->routeIs('portal.pengurus.lostandfound')) active @endif">
                <i class="fas fa-search"></i>
                <span>Lost & Found</span>
            </a></li>

            <li><a href="#" class="">
                <i class="fas fa-comments"></i>
                <span>Chat</span>
            </a></li>
        </ul>

        <div class="sidebar-footer">
            <a href="#" onclick="event.preventDefault();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Notification Panel -->
    <div class="notification-panel" id="notificationPanel">
        <div class="notification-header">
            <h5>Notifikasi</h5>
            <button class="btn-close" id="closeNotificationBtn"></button>
        </div>

        <ul class="notification-list">
            <li class="notification-item unread">
                <div class="notification-item-title">Event Baru Dibuat</div>
                <div class="notification-item-text">Workshop Python for Beginners telah dibuat dan menunggu persetujuan.</div>
                <div class="notification-item-time">Baru saja</div>
            </li>

            <li class="notification-item unread">
                <div class="notification-item-title">Pendaftaran Diterima</div>
                <div class="notification-item-text">Ahmad Rifki telah diterima sebagai anggota baru UFO Organisasi.</div>
                <div class="notification-item-time">2 jam yang lalu</div>
            </li>

            <li class="notification-item unread">
                <div class="notification-item-title">Laporan Baru</div>
                <div class="notification-item-text">Ada 3 laporan lost & found baru yang menunggu moderasi.</div>
                <div class="notification-item-time">5 jam yang lalu</div>
            </li>

            <li class="notification-item">
                <div class="notification-item-title">Pengumuman Dipublikasikan</div>
                <div class="notification-item-text">Pengumuman "Jadwal Rapat Rutin" telah dipublikasikan untuk semua anggota.</div>
                <div class="notification-item-time">1 hari yang lalu</div>
            </li>

            <li class="notification-item">
                <div class="notification-item-title">Meeting dengan Kemahasiswaan</div>
                <div class="notification-item-text">Reminder: Ada meeting dengan pihak kemahasiswaan hari Jum'at pukul 15:00.</div>
                <div class="notification-item-time">2 hari yang lalu</div>
            </li>
        </ul>
    </div>

    <!-- Sidebar Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Floating Chatbot Button -->
    <button class="floating-chatbot" id="chatbotBtn" title="UFO Bot">
        <i class="fas fa-robot"></i>
    </button>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        const burgerBtn = document.getElementById('burgerBtn');
        const notificationBtn = document.getElementById('notificationBtn');
        const closeNotificationBtn = document.getElementById('closeNotificationBtn');
        const sidebar = document.getElementById('sidebar');
        const notificationPanel = document.getElementById('notificationPanel');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');
        const chatbotBtn = document.getElementById('chatbotBtn');

        // Handle Burger Button (Toggle Sidebar)
        burgerBtn.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar.classList.toggle('show');
            sidebarBackdrop.classList.toggle('show');
        });

        // Handle Notification Button (Toggle Notification Panel)
        notificationBtn.addEventListener('click', function(e) {
            e.preventDefault();
            notificationPanel.classList.toggle('show');
        });

        // Close Notification Button
        closeNotificationBtn.addEventListener('click', function(e) {
            e.preventDefault();
            notificationPanel.classList.remove('show');
        });

        // Close sidebar when clicking backdrop
        sidebarBackdrop.addEventListener('click', function() {
            sidebar.classList.remove('show');
            sidebarBackdrop.classList.remove('show');
        });

        // Close sidebar when clicking a link
        document.querySelectorAll('.sidebar-nav a').forEach(link => {
            link.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarBackdrop.classList.remove('show');
            });
        });

        // Chatbot Button Click
        chatbotBtn.addEventListener('click', function() {
            alert('UFO Bot: Halo! Ada yang bisa saya bantu?');
        });

        // Initialize Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Close notification panel when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('#notificationBtn') && 
                !event.target.closest('.notification-panel') &&
                !event.target.closest('#closeNotificationBtn')) {
                notificationPanel.classList.remove('show');
            }
        });
    </script>

    @yield('extra-js')
</body>
</html>
