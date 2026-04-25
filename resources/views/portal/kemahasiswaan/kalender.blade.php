@extends('layouts.portal.kemahasiswaan')

@section('title', 'Kalender Kegiatan Kampus - Kemahasiswaan')
@section('page_title', 'Kalender Kegiatan Kampus')
@section('page_subtitle', 'Pantau jadwal kegiatan organisasi untuk koordinasi lintas UKM')
@section('page_class', 'kmh-page-kalender')

@php
    $weekdays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $kalenderKegiatan = $kalenderKegiatan ?? [];
    $kalenderSummary = $kalenderSummary ?? [
        'total' => 0,
        'bulan_ini' => 0,
        '7_hari' => 0,
    ];

    $selectedMonth = request('bulan', now()->format('Y-m'));
    if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
        $selectedMonth = now()->format('Y-m');
    }

    $viewMode = request('view', 'bulan') === 'list' ? 'list' : 'bulan';
    $selectedKategori = (string) request('kategori', 'semua');
    $searchQuery = trim((string) request('q', ''));

    $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
    $monthEnd = $monthStart->copy()->endOfMonth();
    $prevMonth = $monthStart->copy()->subMonth()->format('Y-m');
    $nextMonth = $monthStart->copy()->addMonth()->format('Y-m');

    $calendarStart = $monthStart->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
    $calendarEnd = $monthEnd->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);

    $inferKategori = function (array $item): string {
        $judul = \Illuminate\Support\Str::lower((string) ($item['judul'] ?? ''));
        $organisasi = \Illuminate\Support\Str::lower((string) ($item['organisasi'] ?? ''));

        if (\Illuminate\Support\Str::contains($judul, ['ujian', 'seminar', 'kuliah', 'akademik'])) {
            return 'akademik';
        }

        if (\Illuminate\Support\Str::contains($judul, ['libur', 'holiday'])) {
            return 'libur';
        }

        if (\Illuminate\Support\Str::contains($judul, ['masa tenang', 'pembatasan'])) {
            return 'masa_tenang';
        }

        if ($organisasi === '-' || $organisasi === '' || \Illuminate\Support\Str::contains($judul, ['kampus', 'universitas'])) {
            return 'event_besar';
        }

        return 'organisasi';
    };

    $filteredKegiatan = collect($kalenderKegiatan)
        ->map(function (array $item) use ($inferKategori) {
            $rawDate = $item['tanggal_raw'] ?? null;
            $parsedDate = null;

            if (!empty($rawDate)) {
                try {
                    $parsedDate = \Carbon\Carbon::parse((string) $rawDate);
                } catch (\Throwable) {
                    $parsedDate = null;
                }
            }

            $item['kategori'] = $item['kategori'] ?? $inferKategori($item);
            $item['tanggal_obj'] = $parsedDate;
            $item['tanggal_key'] = $parsedDate ? $parsedDate->toDateString() : null;

            return $item;
        })
        ->filter(function (array $item) use ($selectedKategori, $searchQuery, $monthStart) {
            if ($selectedKategori !== 'semua' && (string) ($item['kategori'] ?? '') !== $selectedKategori) {
                return false;
            }

            if ($searchQuery !== '') {
                $blob = \Illuminate\Support\Str::lower(implode(' ', [
                    (string) ($item['judul'] ?? ''),
                    (string) ($item['organisasi'] ?? ''),
                    (string) ($item['lokasi'] ?? ''),
                ]));

                if (!\Illuminate\Support\Str::contains($blob, \Illuminate\Support\Str::lower($searchQuery))) {
                    return false;
                }
            }

            if (($item['tanggal_obj'] ?? null) instanceof \Carbon\Carbon && $monthStart) {
                return $item['tanggal_obj']->year === $monthStart->year
                    && $item['tanggal_obj']->month === $monthStart->month;
            }

            return false;
        })
        ->sortBy('tanggal_obj')
        ->values();

    $eventsByDate = $filteredKegiatan
        ->groupBy('tanggal_key')
        ->map(fn ($items) => $items->values()->all())
        ->all();

    $calendarDays = collect();
    for ($cursor = $calendarStart->copy(); $cursor->lte($calendarEnd); $cursor->addDay()) {
        $dateKey = $cursor->toDateString();
        $calendarDays->push([
            'date' => $cursor->copy(),
            'date_key' => $dateKey,
            'in_month' => $cursor->month === $monthStart->month,
            'events' => $eventsByDate[$dateKey] ?? [],
        ]);
    }

    $buildUrl = function (array $overrides = []) use ($selectedMonth, $selectedKategori, $searchQuery, $viewMode) {
        $params = [
            'bulan' => $selectedMonth,
            'kategori' => $selectedKategori,
            'q' => $searchQuery,
            'view' => $viewMode,
        ];

        foreach ($overrides as $key => $value) {
            $params[$key] = $value;
        }

        $params = array_filter($params, fn ($value) => $value !== null && $value !== '' && $value !== 'semua');

        return route('portal.kemahasiswaan.kalender', $params);
    };

    $kategoriLabelMap = [
        'akademik' => 'Kegiatan Akademik',
        'organisasi' => 'Kegiatan Organisasi',
        'masa_tenang' => 'Masa Tidak Boleh Berorganisasi',
        'libur' => 'Libur Akademik',
        'event_besar' => 'Event Kampus Besar',
    ];
@endphp

@section('content')
<div class="kmh-page kmh-kalender-page">
    <section class="kmh-card kmh-calendar-control-card">
        <div class="kmh-card-body">
            <div class="kmh-calendar-top-head">
                <div>
                    <h2>Kalender Kegiatan Kampus</h2>
                    <p>Kelola dan pantau semua kegiatan akademik &amp; non-akademik</p>
                </div>
                <a href="{{ route('portal.kemahasiswaan.pengajuan') }}#bagian-jadwal" class="kmh-calendar-add-btn">
                    <i class="bi bi-plus-lg" aria-hidden="true"></i>
                    <span>Tambah Kegiatan</span>
                </a>
            </div>

            <form method="GET" action="{{ route('portal.kemahasiswaan.kalender') }}" class="kmh-calendar-filter-grid">
                <input type="hidden" name="bulan" value="{{ $selectedMonth }}">
                <input type="hidden" name="view" value="{{ $viewMode }}">

                <div>
                    <label class="kmh-calendar-label" for="kmh-kalender-kategori">Filter Kategori</label>
                    <select id="kmh-kalender-kategori" name="kategori" class="kmh-calendar-select" onchange="this.form.submit()">
                        <option value="semua" @selected($selectedKategori === 'semua')>Semua Kegiatan</option>
                        <option value="akademik" @selected($selectedKategori === 'akademik')>Kegiatan Akademik</option>
                        <option value="organisasi" @selected($selectedKategori === 'organisasi')>Kegiatan Organisasi</option>
                        <option value="masa_tenang" @selected($selectedKategori === 'masa_tenang')>Masa Tidak Boleh Berorganisasi</option>
                        <option value="libur" @selected($selectedKategori === 'libur')>Libur Akademik</option>
                        <option value="event_besar" @selected($selectedKategori === 'event_besar')>Event Kampus Besar</option>
                    </select>
                </div>

                <div>
                    <label class="kmh-calendar-label" for="kmh-kalender-search">Cari Kegiatan</label>
                    <label class="kmh-calendar-search-wrap" for="kmh-kalender-search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input
                            id="kmh-kalender-search"
                            type="search"
                            name="q"
                            value="{{ $searchQuery }}"
                            placeholder="Cari nama kegiatan..."
                        >
                    </label>
                </div>
            </form>

            <div class="kmh-calendar-legend">
                <span class="kmh-calendar-label">Legend:</span>
                @foreach($kategoriLabelMap as $key => $label)
                    <span class="kmh-calendar-legend-item">
                        <span class="kmh-calendar-dot is-{{ $key }}"></span>
                        <span>{{ $label }}</span>
                    </span>
                @endforeach
            </div>
        </div>
    </section>

    <section class="kmh-card kmh-calendar-grid-card">
        <div class="kmh-card-body">
            <div class="kmh-calendar-month-head">
                <a href="{{ $buildUrl(['bulan' => $prevMonth]) }}" class="kmh-calendar-nav-btn" aria-label="Bulan sebelumnya">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <h3>{{ $monthStart->translatedFormat('F Y') }}</h3>
                <a href="{{ $buildUrl(['bulan' => $nextMonth]) }}" class="kmh-calendar-nav-btn" aria-label="Bulan berikutnya">
                    <i class="bi bi-chevron-right"></i>
                </a>

                <div class="kmh-calendar-view-toggle" role="tablist" aria-label="Pilih mode tampilan">
                    <a href="{{ $buildUrl(['view' => 'bulan']) }}" class="kmh-calendar-view-btn {{ $viewMode === 'bulan' ? 'is-active' : '' }}" role="tab" aria-selected="{{ $viewMode === 'bulan' ? 'true' : 'false' }}">
                        <i class="bi bi-calendar3"></i>
                        <span>Bulan</span>
                    </a>
                    <a href="{{ $buildUrl(['view' => 'list']) }}" class="kmh-calendar-view-btn {{ $viewMode === 'list' ? 'is-active' : '' }}" role="tab" aria-selected="{{ $viewMode === 'list' ? 'true' : 'false' }}">
                        <i class="bi bi-list-ul"></i>
                        <span>List</span>
                    </a>
                </div>
            </div>

            @if($viewMode === 'bulan')
                <div class="kmh-calendar-month-grid" role="table" aria-label="Kalender bulanan kegiatan">
                    <div class="kmh-calendar-week-header" role="rowgroup">
                        @foreach($weekdays as $weekday)
                            <div class="kmh-calendar-week-cell" role="columnheader">{{ $weekday }}</div>
                        @endforeach
                    </div>

                    <div class="kmh-calendar-days" role="rowgroup">
                        @foreach($calendarDays as $day)
                            <article class="kmh-calendar-day {{ $day['in_month'] ? '' : 'is-outside' }}" role="cell">
                                <span class="kmh-calendar-date">{{ $day['date']->day }}</span>

                                <div class="kmh-calendar-events">
                                    @forelse(collect($day['events'])->take(2) as $event)
                                        <span class="kmh-calendar-pill is-{{ $event['kategori'] ?? 'organisasi' }}" title="{{ $event['judul'] ?? '-' }}">
                                            {{ \Illuminate\Support\Str::limit((string) ($event['judul'] ?? '-'), 16) }}
                                        </span>
                                    @empty
                                        <span class="kmh-calendar-empty">&nbsp;</span>
                                    @endforelse

                                    @if(count($day['events']) > 2)
                                        <span class="kmh-calendar-more">+{{ count($day['events']) - 2 }} lainnya</span>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table kmh-table">
                        <thead>
                            <tr>
                                <th>Judul Kegiatan</th>
                                <th>Organisasi</th>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($filteredKegiatan as $item)
                                <tr>
                                    <td>{{ $item['judul'] ?? '-' }}</td>
                                    <td>{{ $item['organisasi'] ?? '-' }}</td>
                                    <td>{{ $item['tanggal'] ?? '-' }}</td>
                                    <td>{{ $item['lokasi'] ?? '-' }}</td>
                                    <td>
                                        <span class="kmh-calendar-pill is-{{ $item['kategori'] ?? 'organisasi' }}">
                                            {{ $kategoriLabelMap[$item['kategori'] ?? 'organisasi'] ?? 'Kegiatan Organisasi' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="kmh-empty-row">Tidak ada agenda untuk filter bulan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
