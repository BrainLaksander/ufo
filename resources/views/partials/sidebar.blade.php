<aside id="ufo-sidebar" class="sidebar" aria-hidden="true">
    <div class="sidebar-inner">
        @php
            $isKemahasiswaan = auth()->check() && auth()->user()->role === 'kemahasiswaan';
            $isPengurusUkm = auth()->check() && auth()->user()->role === 'pengurus_ukm';
            $isInternalRole = $isKemahasiswaan || $isPengurusUkm;
            $sidebarTitle = $isPengurusUkm ? 'Menu Pengurus' : 'Menu UFO';
            $sidebarSubtitle = '';

            if ($isInternalRole && auth()->check()) {
                $roleLabel = $isKemahasiswaan ? 'Kemahasiswaan' : 'Pengurus UKM';
                $sidebarSubtitle = auth()->user()->name . ' • ' . $roleLabel;
            }
        @endphp

        <div class="sidebar-top">
            <div class="sidebar-brand">
                    <div class="logo">
                        <img src="/logoufo.png" alt="Logo UFO">
                    </div>
                    <div class="sidebar-brand-text">
                        <div class="title">{{ $sidebarTitle }}</div>
                        @if($sidebarSubtitle !== '')
                            <div class="sidebar-subtitle">{{ $sidebarSubtitle }}</div>
                        @endif
                    </div>
                </div>
                <button type="button" class="sidebar-close" aria-label="Tutup menu">&times;</button>
        </div>

        @php
            $fallbackMenuIcons = [
                'Dashboard' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3" y="3" width="8" height="8" fill="none" stroke="currentColor" stroke-width="2"/><rect x="13" y="3" width="8" height="8" fill="none" stroke="currentColor" stroke-width="2"/><rect x="3" y="13" width="8" height="8" fill="none" stroke="currentColor" stroke-width="2"/><rect x="13" y="13" width="8" height="8" fill="none" stroke="currentColor" stroke-width="2"/></svg>',
                'Dashboard Organisasi' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3" y="3" width="8" height="8" fill="none" stroke="currentColor" stroke-width="2"/><rect x="13" y="3" width="8" height="8" fill="none" stroke="currentColor" stroke-width="2"/><rect x="3" y="13" width="8" height="8" fill="none" stroke="currentColor" stroke-width="2"/><rect x="13" y="13" width="8" height="8" fill="none" stroke="currentColor" stroke-width="2"/></svg>',
                'Profil Organisasi' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="7" r="4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'Event Organisasi' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3" y="5" width="18" height="16" rx="3" ry="3" fill="none" stroke="currentColor" stroke-width="2"/><path d="M16 3v4M8 3v4M3 11h18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'Manajemen Organisasi' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M16 11a2 2 0 1 0 0-4 2 2 0 0 0 0 4ZM12 7a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'Manajemen Pengajuan Kegiatan & Laporan' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M9 12h6M9 16h6M9 8h6M5 20h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'Pengajuan & Laporan Kegiatan' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M9 12h6M9 16h6M9 8h6M5 20h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'Manajemen Pengumuman & Distribusi Email' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z M22 6l-10 7L2 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'Pengumuman' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 7h9l6-3v16l-6-3H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2Z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 16v3a2 2 0 0 0 2 2h1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'Kontak Pengurus UKM' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'Kontak Organisasi' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'Kalender Kegiatan Kampus' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3" y="4" width="18" height="18" rx="2" ry="2" fill="none" stroke="currentColor" stroke-width="2"/><line x1="16" y1="2" x2="16" y2="6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="8" y1="2" x2="8" y2="6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="3" y1="10" x2="21" y2="10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
                'Notifikasi Sistem' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.73 21a2 2 0 0 1-3.46 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'Organisasi' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 9.5A3.5 3.5 0 1 1 7.5 6 3.5 3.5 0 0 1 4 9.5Zm0 0v2.5"/><path d="M20 10.5A3.5 3.5 0 1 1 16.5 7 3.5 3.5 0 0 1 20 10.5Zm0 0v1.5"/><path d="M8 20v-2a4 4 0 0 1 8 0v2"/></svg>',
                'Event Kampus' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="3" y="5" width="18" height="16" rx="3" ry="3"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>',
                'Lost & Found' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><path d="m20 20-3.5-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
                'Tentang UFO' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9"/><path d="M12 10v6"/><circle cx="12" cy="7.5" r="1"/></svg>',
                'Login' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 12a8 8 0 1 0 16 0 8 8 0 0 0-16 0Z"/><path d="M12 7v5l3 2"/></svg>',
            ];

            $resolveMenuIcon = function ($title) use ($fallbackMenuIcons) {
                return $fallbackMenuIcons[$title] ?? '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 12h16"/><path d="M4 6h16"/><path d="M4 18h16"/></svg>';
            };

            $isActiveMenu = function (array $routePatterns = [], array $pathPatterns = []) {
                foreach ($routePatterns as $pattern) {
                    if (request()->routeIs($pattern)) {
                        return true;
                    }
                }

                foreach ($pathPatterns as $pattern) {
                    if (request()->is($pattern)) {
                        return true;
                    }
                }

                return false;
            };

            $menuList = collect();
            if (isset($menuItems) && is_iterable($menuItems)) {
                $menuList = collect($menuItems)->map(function ($item) use ($resolveMenuIcon) {
                    $title = $item->title ?? '';
                    $url = $item->url ?? '#';

                    if ($title === 'Dashboard') {
                        $url = route('kemahasiswaan.dashboard');
                    } elseif ($title === 'Manajemen Organisasi') {
                        $url = route('kemahasiswaan.organizations.index');
                    } elseif ($title === 'Organisasi') {
                        $url = url('/');
                    } elseif ($title === 'Event Kampus') {
                        $url = route('events.index');
                    } elseif ($title === 'Pengumuman') {
                        $url = route('pengumuman.index');
                    } elseif ($title === 'Lost & Found') {
                        $url = route('lost-found.index');
                    } elseif ($title === 'Tentang UFO' || $title === 'Tentang') {
                        $url = route('tentang.index');
                    }

                    return (object) [
                        'title' => $title,
                        'url' => $url,
                        'active' => (bool) ($item->active ?? false),
                        'icon_svg' => $resolveMenuIcon($title),
                    ];
                });
            }

            // Show different menu for authenticated Kemahasiswaan users
            if ($menuList->isEmpty()) {
                if ($isKemahasiswaan) {
                    $menuList = collect([
                        (object) ['title' => 'Dashboard', 'url' => route('kemahasiswaan.dashboard'), 'active' => $isActiveMenu(['kemahasiswaan.dashboard'], ['kemahasiswaan']), 'icon_svg' => $resolveMenuIcon('Dashboard')],
                        (object) ['title' => 'Manajemen Organisasi', 'url' => route('kemahasiswaan.organizations.index'), 'active' => $isActiveMenu(['kemahasiswaan.organizations.*'], ['kemahasiswaan/organisasi*']), 'icon_svg' => $resolveMenuIcon('Manajemen Organisasi')],
                        (object) ['title' => 'Manajemen Pengajuan Kegiatan & Laporan', 'url' => route('kemahasiswaan.submissions.index'), 'active' => $isActiveMenu(['kemahasiswaan.submissions.*'], ['kemahasiswaan/pengajuan-kegiatan-laporan*']), 'icon_svg' => $resolveMenuIcon('Manajemen Pengajuan Kegiatan & Laporan')],
                        (object) ['title' => 'Manajemen Pengumuman & Distribusi Email', 'url' => route('kemahasiswaan.announcements.index'), 'active' => $isActiveMenu(['kemahasiswaan.announcements.*'], ['kemahasiswaan/pengumuman*']), 'icon_svg' => $resolveMenuIcon('Manajemen Pengumuman & Distribusi Email')],
                        (object) ['title' => 'Kontak Pengurus UKM', 'url' => route('kemahasiswaan.contacts'), 'active' => $isActiveMenu(['kemahasiswaan.contacts'], ['kemahasiswaan/kontak*']), 'icon_svg' => $resolveMenuIcon('Kontak Pengurus UKM')],
                        (object) ['title' => 'Kalender Kegiatan Kampus', 'url' => route('kemahasiswaan.calendar'), 'active' => $isActiveMenu(['kemahasiswaan.calendar'], ['kemahasiswaan/kalender*']), 'icon_svg' => $resolveMenuIcon('Kalender Kegiatan Kampus')],
                        (object) ['title' => 'Notifikasi Sistem', 'url' => route('kemahasiswaan.notifications.index'), 'active' => $isActiveMenu(['kemahasiswaan.notifications.*'], ['kemahasiswaan/notifikasi*']), 'icon_svg' => $resolveMenuIcon('Notifikasi Sistem')],
                    ]);
                } elseif ($isPengurusUkm) {
                    $org = auth()->user()->organization;
                    $isBemUniversitas = $org && (stripos($org->name, 'BEM UNKLAB') !== false || ($org->kategori === 'BEM' && $org->level === 'Universitas'));
                    
                    $menus = [
                        (object) ['title' => 'Dashboard Organisasi', 'url' => route('pengurus-ukm.dashboard'), 'active' => $isActiveMenu(['pengurus-ukm.dashboard'], ['pengurus-ukm']), 'icon_svg' => $resolveMenuIcon('Dashboard Organisasi')],
                        (object) ['title' => 'Profil Organisasi', 'url' => route('pengurus-ukm.profile'), 'active' => $isActiveMenu(['pengurus-ukm.profile*'], ['pengurus-ukm/profil*']), 'icon_svg' => $resolveMenuIcon('Profil Organisasi')],
                        (object) ['title' => 'Event Organisasi', 'url' => route('pengurus-ukm.events.index'), 'active' => $isActiveMenu(['pengurus-ukm.events.*'], ['pengurus-ukm/events*']), 'icon_svg' => $resolveMenuIcon('Event Organisasi')],
                        (object) ['title' => 'Pengumuman', 'url' => route('pengurus-ukm.announcements.index'), 'active' => $isActiveMenu(['pengurus-ukm.announcements.*'], ['pengurus-ukm/pengumuman*']), 'icon_svg' => $resolveMenuIcon('Pengumuman')],
                        (object) ['title' => 'Pengajuan & Laporan Kegiatan', 'url' => route('pengurus-ukm.submissions.index'), 'active' => $isActiveMenu(['pengurus-ukm.submissions.*'], ['pengurus-ukm/pengajuan-laporan*']), 'icon_svg' => $resolveMenuIcon('Pengajuan & Laporan Kegiatan')],
                    ];
                    
                    if ($isBemUniversitas) {
                        $menus[] = (object) ['title' => 'Lost & Found', 'url' => route('pengurus-ukm.lost-found.index'), 'active' => $isActiveMenu(['pengurus-ukm.lost-found.*'], ['pengurus-ukm/lost-found*']), 'icon_svg' => $resolveMenuIcon('Lost & Found')];
                    }
                    
                    $menus[] = (object) ['title' => 'Kontak Organisasi', 'url' => route('pengurus-ukm.contacts'), 'active' => $isActiveMenu(['pengurus-ukm.contacts'], ['pengurus-ukm/kontak*']), 'icon_svg' => $resolveMenuIcon('Kontak Organisasi')];
                    $menuList = collect($menus);
                } elseif (auth()->check()) {
                    $menuList = collect([
                        (object) ['title' => 'Organisasi', 'url' => url('/'), 'active' => $isActiveMenu([], ['/', '']), 'icon_svg' => $resolveMenuIcon('Organisasi')],
                        (object) ['title' => 'Event Kampus', 'url' => route('events.index'), 'active' => $isActiveMenu([], ['events*']), 'icon_svg' => $resolveMenuIcon('Event Kampus')],
                        (object) ['title' => 'Pengumuman', 'url' => route('pengumuman.index'), 'active' => $isActiveMenu([], ['pengumuman*']), 'icon_svg' => $resolveMenuIcon('Pengumuman')],
                        (object) ['title' => 'Lost & Found', 'url' => route('lost-found.index'), 'active' => $isActiveMenu([], ['lost-found*']), 'icon_svg' => $resolveMenuIcon('Lost & Found')],
                        (object) ['title' => 'Tentang UFO', 'url' => route('tentang.index'), 'active' => $isActiveMenu([], ['tentang*']), 'icon_svg' => $resolveMenuIcon('Tentang UFO')],
                    ]);
                } else {
                    $menuList = collect([
                        (object) ['title' => 'Organisasi', 'url' => url('/'), 'active' => $isActiveMenu([], ['/', '']), 'icon_svg' => $resolveMenuIcon('Organisasi')],
                        (object) ['title' => 'Event Kampus', 'url' => route('events.index'), 'active' => $isActiveMenu([], ['events*']), 'icon_svg' => $resolveMenuIcon('Event Kampus')],
                        (object) ['title' => 'Kalender Kegiatan Kampus', 'url' => route('calendar.index'), 'active' => $isActiveMenu(['calendar.index'], ['kalender*']), 'icon_svg' => $resolveMenuIcon('Kalender Kegiatan Kampus')],
                        (object) ['title' => 'Pengumuman', 'url' => route('pengumuman.index'), 'active' => $isActiveMenu([], ['pengumuman*']), 'icon_svg' => $resolveMenuIcon('Pengumuman')],
                        (object) ['title' => 'Lost & Found', 'url' => route('lost-found.index'), 'active' => $isActiveMenu([], ['lost-found*']), 'icon_svg' => $resolveMenuIcon('Lost & Found')],
                        (object) ['title' => 'Tentang UFO', 'url' => route('tentang.index'), 'active' => $isActiveMenu([], ['tentang*']), 'icon_svg' => $resolveMenuIcon('Tentang UFO')],
                    ]);
                }
            }
        @endphp

        <nav class="sidebar-nav" aria-label="Main menu">
            <ul>
                @foreach($menuList as $item)
                    <li class="{{ $item->active ? 'active' : '' }}">
                        <a href="{{ $item->url }}" @if($item->active) aria-current="page" @endif>
                            <span class="menu-icon" aria-hidden="true">{!! $item->icon_svg !!}</span>
                            <span class="menu-label">{{ $item->title }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        @auth
            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                    @csrf
                    <button type="submit" class="logout-btn {{ $isPengurusUkm ? 'danger' : '' }}" aria-label="Logout">
                        <span class="menu-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <polyline points="16 17 21 12 16 7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <line x1="21" y1="12" x2="9" y2="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="menu-label">Keluar</span>
                    </button>
                </form>
            </div>
        @else
            <div class="sidebar-footer">
                <a href="{{ route('login') }}" class="login-btn {{ request()->routeIs('login') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 18px; color: #111827; text-decoration: none; border-radius: 12px; font-weight: 600; transition: background 0.2s ease;">
                    <span class="menu-icon" aria-hidden="true" style="color: #4c3563; display: flex; align-items: center;">{!! $resolveMenuIcon('Login') !!}</span>
                    <span class="menu-label">Login</span>
                </a>
            </div>
        @endauth
    </div>
</aside>
<div class="sidebar-backdrop" data-sidebar-backdrop></div>
