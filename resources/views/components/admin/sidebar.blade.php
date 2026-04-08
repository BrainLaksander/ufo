<div id="sidebar-overlay" class="sidebar-overlay hidden"></div>
<aside id="sidebar" class="sidebar admin-sidebar" aria-hidden="true">
    <div class="sidebar-header">
        <h3>Menu Admin</h3>
        <button id="sidebar-close" class="sidebar-close" aria-label="Close sidebar"><i class="bi bi-x-lg"></i></button>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li><a href="{{ route('home') }}" class="nav-link" data-close>Landing Page</a></li>
            <li><a href="{{ route('portal.admin.dashboard') }}" class="nav-link" data-close>Dashboard</a></li>
            <li><a href="#" class="nav-link" data-close>Manajemen Organisasi</a></li>
            <li><a href="#" class="nav-link" data-close>Manajemen Event</a></li>
            <li><a href="#" class="nav-link" data-close>Manajemen Pengguna</a></li>
            <li class="sidebar-divider"></li>
            <li><a href="{{ route('portal.login') }}" class="nav-link" data-close>Keluar</a></li>
        </ul>
    </nav>
</aside>
