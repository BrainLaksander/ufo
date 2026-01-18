<div id="pengurus-overlay" class="pengurus-overlay hidden"></div>

<aside id="pengurus-burger" class="pengurus-burger" aria-hidden="true">
  <div class="burger-header">
    <div class="burger-title">Menu</div>
    <button id="burger-close" class="burger-close" aria-label="Close menu">✕</button>
  </div>
  <nav class="burger-nav">
    <ul>
      <li><a href="/pengurus/dashboard" data-close>Dashboard Organisasi</a></li>
      <li><a href="/pengurus/profil" data-close>Profil Organisasi</a></li>
      <li><a href="#" data-close>Event Organisasi</a></li>
      <li><a href="#" data-close>Pengumuman</a></li>
      <li><a href="#" data-close>Pengajuan & Laporan</a></li>
      <li><a href="#" data-close>Pendaftaran Organisasi</a></li>
      <li><a href="#" data-close>Lost & Found</a></li>
      <li><a href="#" data-close>Chat & Konsultasi</a></li>
      <li><a href="#" id="pengurus-logout" data-close>Keluar</a></li>
    </ul>
  </nav>
</aside>
<div id="drawer-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden transition-opacity" aria-hidden="true"></div>
<aside id="drawer" class="fixed top-0 left-0 h-screen w-64 bg-yellow-400 z-50 transform -translate-x-full transition-transform duration-300" aria-hidden="true">
  <div class="flex flex-col h-full">
    <div class="flex items-center justify-between p-4 border-b border-yellow-500">
      <h3 class="font-bold text-lg text-gray-800">Menu</h3>
      <button id="close-drawer" class="text-2xl text-gray-800 hover:bg-yellow-300 p-1 rounded">×</button>
    </div>
    <nav class="flex-1 py-4">
      <ul class="space-y-0">
        <li><a href="{{ route('mahasiswa.organisasi') }}" class="drawer-item block px-4 py-3 text-gray-800 hover:bg-yellow-300 font-medium" data-item="organisasi">Organisasi</a></li>
        <li><a href="{{ route('mahasiswa.event') }}" class="drawer-item block px-4 py-3 text-gray-800 hover:bg-yellow-300" data-item="event">Event</a></li>
        <li><a href="{{ route('mahasiswa.lost-found') }}" class="drawer-item block px-4 py-3 text-gray-800 hover:bg-yellow-300" data-item="lost-found">Lost & Found</a></li>
        <li><a href="{{ route('mahasiswa.pengumuman') }}" class="drawer-item block px-4 py-3 text-gray-800 hover:bg-yellow-300" data-item="pengumuman">Pengumuman</a></li>
        <li><a href="{{ route('mahasiswa.tentang') }}" class="drawer-item block px-4 py-3 text-gray-800 hover:bg-yellow-300" data-item="tentang">Tentang UFO</a></li>
      </ul>
    </nav>
    <div class="p-4 border-t border-yellow-500 text-xs text-gray-700">
      <small>UNKLAB Forum Organization<br>Versi 1.0</small>
    </div>
  </div>
</aside>
