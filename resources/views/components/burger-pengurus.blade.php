<div id="overlay-pengurus" class="overlay hidden"></div>
<aside id="burger-pengurus" class="burger-menu pengurus-burger" aria-hidden="true">
    <div class="burger-header">
        <div class="burger-title">Menu Pengurus</div>
        <button class="burger-close" aria-label="Close menu"><i class="bi bi-x-lg"></i></button>
    </div>
    <nav class="burger-nav">
        <ul>
            <li><a href="{{ route('home') }}" data-close>Landing Page</a></li>
            <li><a href="{{ route('portal.pengurus.dashboard') }}" data-close>Dashboard</a></li>
            <li><a href="{{ route('profil.show') }}" data-close>Profil Organisasi</a></li>
            <li><a href="#" data-close>Event Organisasi</a></li>
            <li><a href="#" data-close>Pengumuman</a></li>
            <li><a href="#" data-close>Pengajuan & Laporan</a></li>
            <li><a href="#" data-close>Pendaftaran Organisasi</a></li>
            <li><a href="{{ route('mahasiswa.lost-found') }}" data-close>Lost & Found</a></li>
            <li><a href="#" data-close>Chat & Konsultasi</a></li>
            <li class="burger-divider"></li>
            <li><a href="{{ route('portal.login') }}" id="logout-btn" data-close>Keluar</a></li>
        </ul>
    </nav>
</aside>
