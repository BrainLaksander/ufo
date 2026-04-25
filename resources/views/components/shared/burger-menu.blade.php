<div id="pengurus-overlay" class="pengurus-overlay hidden"></div>

<aside id="pengurus-burger" class="pengurus-burger" aria-hidden="true">
  <div class="burger-header">
    <div class="burger-title">Menu</div>
    <button id="burger-close" class="burger-close" aria-label="Close menu"><i class="bi bi-x-lg"></i></button>
  </div>
  <nav class="burger-nav">
    <ul>
      <li><a href="{{ route('portal.pengurus.dashboard') }}" data-close>Dashboard Organisasi</a></li>
      <li><a href="{{ route('profil.show') }}" data-close>Profil Organisasi</a></li>
      <li><a href="#" data-close>Event Organisasi</a></li>
      <li><a href="#" data-close>Pengumuman</a></li>
      <li><a href="#" data-close>Pengajuan & Laporan</a></li>
      <li><a href="#" data-close>Pendaftaran Organisasi</a></li>
      <li><a href="{{ route('mahasiswa.lost-found') }}" data-close>Lost & Found</a></li>
      <li><a href="#" data-close>Chat & Konsultasi</a></li>
      <li><a href="#" id="pengurus-logout" data-close>Keluar</a></li>
    </ul>
  </nav>
</aside>
<div id="drawer-overlay" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-none" style="z-index: 1040;" aria-hidden="true"></div>
<aside id="drawer" class="position-fixed top-0 start-0 vh-100 bg-warning" style="width: 280px; transform: translateX(-100%); transition: transform 0.3s ease; z-index: 1050;" aria-hidden="true">
  <div class="d-flex flex-column h-100">
    <div class="d-flex align-items-center justify-content-between p-3 border-bottom border-dark-subtle">
      <h3 class="fw-bold fs-5 mb-0">Menu</h3>
      <button id="close-drawer" class="btn btn-sm btn-outline-dark"><i class="bi bi-x-lg"></i></button>
    </div>
    <nav class="flex-grow-1 py-3">
      <ul class="list-unstyled mb-0">
        <li><a href="{{ route('mahasiswa.organisasi.index') }}" class="drawer-item d-block px-3 py-2 text-dark text-decoration-none" data-item="organisasi">Organisasi</a></li>
        <li><a href="{{ route('mahasiswa.event') }}" class="drawer-item d-block px-3 py-2 text-dark text-decoration-none" data-item="event">Event</a></li>
        <li><a href="{{ route('mahasiswa.lost-found') }}" class="drawer-item d-block px-3 py-2 text-dark text-decoration-none" data-item="lost-found" data-close>Lost & Found</a></li>
        <li><a href="{{ route('mahasiswa.pengumuman') }}" class="drawer-item d-block px-3 py-2 text-dark text-decoration-none" data-item="pengumuman">Pengumuman</a></li>
        <li><a href="{{ route('mahasiswa.tentang') }}" class="drawer-item d-block px-3 py-2 text-dark text-decoration-none" data-item="tentang">Tentang UFO</a></li>
      </ul>
    </nav>
    <div class="p-3 border-top border-dark-subtle small text-muted">
      <small>UNKLAB Forum Organization<br>Versi 1.0</small>
    </div>
  </div>
</aside>
