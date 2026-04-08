<div id="overlay-mahasiswa" class="overlay hidden"></div>
<aside id="burger-mahasiswa" class="burger-menu" aria-hidden="true">
    <div class="burger-header">
        <div class="burger-title">Menu</div>
        <button class="burger-close" aria-label="Close menu"><i class="bi bi-x-lg"></i></button>
    </div>
    <nav class="burger-nav">
        <ul>
            <li><a href="{{ route('home') }}" data-close>Dashboard</a></li>
            <li><a href="{{ route('mahasiswa.organisasi.index') }}" data-close>Organisasi</a></li>
            <li><a href="{{ route('mahasiswa.event') }}" data-close>Event</a></li>
            <li><a href="{{ route('mahasiswa.lost-found') }}" data-close>Lost & Found</a></li>
            <li><a href="#" data-close>Pengumuman</a></li>
            <li class="burger-divider"></li>
            <li><a href="{{ route('portal.login') }}" data-close>Portal Internal</a></li>
        </ul>
    </nav>
</aside>
