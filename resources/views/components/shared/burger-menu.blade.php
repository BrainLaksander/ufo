{{--
  Komponen Burger Menu generik untuk drawer navigasi

  Props:
  - $variant: mahasiswa, kemahasiswaan, pengurus (default: mahasiswa)
  - $title: Judul menu (optional)
  - $items: Array item menu (optional)
  - $overlayId: ID overlay (optional)
  - $menuId: ID drawer (optional)
  - $footer: Teks footer drawer (optional)

  Format item:
  [
    'label' => '...',
    'href' => '...',
    'icon' => 'bi bi-...',
    'class' => '...',
    'divider' => false,
    'close' => true,
  ]
--}}

@php
  $variant = $variant ?? 'mahasiswa';

  $defaults = [
    'mahasiswa' => [
      'overlayId' => 'burger-overlay',
      'menuId' => 'burger-menu',
      'title' => 'Menu UFO',
      'footer' => 'UNKLAB Forum Organization<br>Versi 1.0',
      'items' => [
        ['label' => 'Landing Page', 'href' => route('home'), 'icon' => 'bi bi-house-door-fill', 'class' => 'burger-link'],
        ['label' => 'Organisasi', 'href' => route('mahasiswa.organisasi.index'), 'icon' => 'bi bi-people-fill', 'class' => 'burger-link organisasi-link'],
        ['label' => 'Event', 'href' => route('mahasiswa.event'), 'icon' => 'bi bi-calendar-event-fill', 'class' => 'burger-link'],
        ['label' => 'Kalendar Kegiatan', 'href' => route('mahasiswa.kalendar'), 'icon' => 'bi bi-calendar3', 'class' => 'burger-link'],
        ['label' => 'Lost & Found', 'href' => route('mahasiswa.lost-found'), 'icon' => 'bi bi-search', 'class' => 'burger-link'],
        ['label' => 'Pengumuman', 'href' => route('mahasiswa.pengumuman'), 'icon' => 'bi bi-megaphone-fill', 'class' => 'burger-link'],
        ['label' => 'Tentang UFO', 'href' => route('mahasiswa.tentang'), 'icon' => 'bi bi-info-circle-fill', 'class' => 'burger-link'],
        ['label' => 'Portal Internal', 'href' => route('portal.login'), 'icon' => 'bi bi-box-arrow-in-right', 'class' => 'burger-link'],
      ],
    ],
    'kemahasiswaan' => [
      'overlayId' => 'overlay-kemahasiswaan',
      'menuId' => 'burger-kemahasiswaan',
      'title' => 'Menu Kemahasiswaan',
      'footer' => null,
      'items' => [
        ['label' => 'Landing Page', 'href' => route('home'), 'close' => true],
        ['label' => 'Dashboard', 'href' => route('portal.kemahasiswaan.dashboard'), 'close' => true],
        ['label' => 'Manajemen Organisasi', 'href' => route('portal.kemahasiswaan.organisasi'), 'close' => true],
        ['label' => 'Pendaftaran Organisasi', 'href' => route('portal.kemahasiswaan.organisasi'), 'close' => true],
        ['label' => 'Event Organisasi', 'href' => route('portal.kemahasiswaan.pengajuan'), 'close' => true],
        ['divider' => true],
        ['label' => 'Keluar', 'href' => route('portal.login'), 'close' => true],
      ],
    ],
    'pengurus' => [
      'overlayId' => 'pengurus-overlay',
      'menuId' => 'pengurus-burger',
      'title' => 'Menu',
      'footer' => null,
      'items' => [
        ['label' => 'Dashboard Organisasi', 'href' => route('portal.pengurus.dashboard'), 'close' => true],
        ['label' => 'Profil Organisasi', 'href' => route('profil.show'), 'close' => true],
        ['label' => 'Event Organisasi', 'href' => route('portal.pengurus.events'), 'close' => true],
        ['label' => 'Pengumuman', 'href' => route('portal.pengurus.announcements'), 'close' => true],
        ['label' => 'Pengajuan & Laporan', 'href' => route('portal.pengurus.proposals'), 'close' => true],
        ['label' => 'Pendaftaran Organisasi', 'href' => route('portal.pengurus.kontak'), 'close' => true],
        ['label' => 'Lost & Found', 'href' => route('mahasiswa.lost-found'), 'close' => true],
        ['label' => 'Chat & Konsultasi', 'href' => route('portal.pengurus.kontak'), 'close' => true],
        ['label' => 'Keluar', 'href' => route('portal.login'), 'close' => true],
      ],
    ],
  ];

  $config = $defaults[$variant] ?? $defaults['mahasiswa'];
  $overlayId = $overlayId ?? $config['overlayId'];
  $menuId = $menuId ?? $config['menuId'];
  $title = $title ?? $config['title'];
  $footer = array_key_exists('footer', get_defined_vars()) ? $footer : $config['footer'];
  $items = $items ?? $config['items'];
@endphp

<div id="{{ $overlayId }}" class="overlay hidden"></div>
<aside id="{{ $menuId }}" class="burger-menu {{ $variant }}-burger" aria-hidden="true">
  <div class="burger-header">
    <div class="burger-header-content">
      <div class="burger-title">{{ $title }}</div>
    </div>
    <button class="burger-close" aria-label="Close menu"><i class="bi bi-x-lg"></i></button>
  </div>

  <nav class="burger-nav">
    <ul>
      @foreach ($items as $item)
        @if(!empty($item['divider']))
          <li class="burger-divider"></li>
          @continue
        @endif

        <li>
          <a href="{{ $item['href'] ?? '#' }}"
             class="{{ $item['class'] ?? '' }}"
             @if(($item['close'] ?? true)) data-close @endif>
            @if(!empty($item['icon']))
              <span class="burger-link-icon"><i class="{{ $item['icon'] }}"></i></span>
            @endif
            <span class="burger-link-text">{{ $item['label'] ?? '' }}</span>
          </a>
        </li>
      @endforeach
    </ul>
  </nav>

  @if($footer)
    <div class="p-3 border-top border-dark-subtle small text-muted">
      {!! $footer !!}
    </div>
  @endif
</aside>
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
        <li><a href="{{ route('mahasiswa.kalendar') }}" class="drawer-item d-block px-3 py-2 text-dark text-decoration-none" data-item="kalendar-kegiatan">Kalendar Kegiatan</a></li>
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
