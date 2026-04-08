<div id="overlay-admin" class="overlay hidden"></div>
<aside id="burger-admin" class="burger-menu admin-burger" aria-hidden="true">
    <div class="burger-header">
        <div class="burger-title">Menu Admin</div>
        <button class="burger-close" aria-label="Close menu"><i class="bi bi-x-lg"></i></button>
    </div>
    <nav class="burger-nav">
        <ul>
            <li><a href="{{ route('home') }}" data-close>Landing Page</a></li>
            <li><a href="{{ route('portal.admin.dashboard') }}" data-close>Dashboard</a></li>
            <li><a href="#" data-close>Manajemen Organisasi</a></li>
            <li><a href="#" data-close>Manajemen Event</a></li>
            <li><a href="#" data-close>Manajemen Pengguna</a></li>
            <li class="burger-divider"></li>
            <li><a href="{{ route('portal.login') }}" id="logout-btn" data-close>Keluar</a></li>
        </ul>
    </nav>
</aside>
