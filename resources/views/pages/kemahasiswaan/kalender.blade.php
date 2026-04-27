@extends('layouts.portal.kemahasiswaan')

@section('title', 'Kalender Kegiatan Kampus - Kemahasiswaan')
@section('page_title', 'Kalender Kegiatan Kampus')
@section('page_subtitle', 'Pantau jadwal kegiatan organisasi untuk koordinasi lintas UKM')
@section('page_class', 'kmh-page-kalender')

@php
    $ui = $ui ?? [];
    $weekdays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $kalenderKegiatan = $kalenderKegiatan ?? [];
    $kalenderSummary = $kalenderSummary ?? [
        'total' => 0,
        'bulan_ini' => 0,
        '7_hari' => 0,
    ];
    $organizations = $organizations ?? [];

    $selectedMonth = request('bulan', now()->format('Y-m'));
    if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
        $selectedMonth = now()->format('Y-m');
    }

    $viewMode = request('view', 'bulan') === 'list' ? 'list' : 'bulan';
    $selectedKategori = (string) request('kategori', '');
    $selectedKategori = [
        'akademik' => 'acad',
        'organisasi' => 'org',
        'masa_tenang' => 'restricted',
        'libur' => 'holiday',
        'event_besar' => 'campus',
    ][$selectedKategori] ?? $selectedKategori;
    $searchQuery = trim((string) request('q', ''));

    $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
    $monthEnd = $monthStart->copy()->endOfMonth();
    $prevMonth = $monthStart->copy()->subMonth()->format('Y-m');
    $nextMonth = $monthStart->copy()->addMonth()->format('Y-m');

    $calendarStart = $monthStart->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
    $calendarEnd = $monthEnd->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);

    $parseScheduleDate = function ($value): ?\Carbon\Carbon {
        if (empty($value)) {
            return null;
        }

        try {
            return \Carbon\Carbon::parse((string) $value);
        } catch (\Throwable) {
            return null;
        }
    };

    $inferKategori = function (array $item): string {
        $judul = \Illuminate\Support\Str::lower((string) ($item['judul'] ?? ''));
        $organisasi = \Illuminate\Support\Str::lower((string) ($item['organisasi'] ?? ''));

        if (\Illuminate\Support\Str::contains($judul, ['ujian', 'seminar', 'kuliah', 'akademik'])) {
            return 'acad';
        }

        if (\Illuminate\Support\Str::contains($judul, ['libur', 'holiday'])) {
            return 'holiday';
        }

        if (\Illuminate\Support\Str::contains($judul, ['masa tenang', 'pembatasan'])) {
            return 'restricted';
        }

        if ($organisasi === '' || \Illuminate\Support\Str::contains($judul, ['kampus', 'universitas'])) {
            return 'campus';
        }

        return 'org';
    };

    $filteredKegiatan = collect($kalenderKegiatan)
        ->map(function (array $item) use ($inferKategori, $parseScheduleDate) {
            $startRaw = $item['tanggal_raw'] ?? null;
            $endRaw = $item['tanggal_selesai_raw'] ?? $startRaw;
            $startDate = $parseScheduleDate($startRaw);
            $endDate = $parseScheduleDate($endRaw) ?? $startDate;

            $kategori = trim((string) ($item['kategori'] ?? ''));
            if ($kategori === '') {
                $kategori = $inferKategori($item);
            }

            $item['kategori'] = $kategori !== '' ? $kategori : 'org';
            $item['tanggal_mulai_obj'] = $startDate;
            $item['tanggal_selesai_obj'] = $endDate;
            $item['tanggal_obj'] = $startDate;
            $item['tanggal_key'] = $startDate ? $startDate->toDateString() : null;
            $item['tanggal_range_label'] = $startDate
                ? (($endDate && !$startDate->isSameDay($endDate))
                    ? $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y')
                    : $startDate->translatedFormat('d M Y'))
                : '-';

            return $item;
        })
        ->filter(function (array $item) use ($selectedKategori, $searchQuery, $monthStart, $monthEnd) {
            if ($selectedKategori !== '' && (string) ($item['kategori'] ?? '') !== $selectedKategori) {
                return false;
            }

            if ($searchQuery !== '') {
                $blob = \Illuminate\Support\Str::lower(implode(' ', [
                    (string) ($item['judul'] ?? ''),
                    (string) ($item['organisasi'] ?? ''),
                    (string) ($item['lokasi'] ?? ''),
                    (string) ($item['deskripsi'] ?? ''),
                ]));

                if (!\Illuminate\Support\Str::contains($blob, \Illuminate\Support\Str::lower($searchQuery))) {
                    return false;
                }
            }

            $startDate = $item['tanggal_mulai_obj'] ?? null;
            $endDate = $item['tanggal_selesai_obj'] ?? null;

            if (!$startDate instanceof \Carbon\Carbon) {
                return false;
            }

            $endDate = $endDate instanceof \Carbon\Carbon ? $endDate : $startDate;

            return $startDate->lte($monthEnd) && $endDate->gte($monthStart);
        })
        ->sortBy('tanggal_mulai_obj')
        ->values();

    $eventsByDate = [];
    foreach ($filteredKegiatan as $item) {
        $startDate = $item['tanggal_mulai_obj'] ?? null;
        $endDate = $item['tanggal_selesai_obj'] ?? null;

        if (!$startDate instanceof \Carbon\Carbon) {
            continue;
        }

        $endDate = $endDate instanceof \Carbon\Carbon ? $endDate->copy() : $startDate->copy();
        $cursor = $startDate->copy();

        if ($cursor->lt($calendarStart)) {
            $cursor = $calendarStart->copy();
        }

        if ($endDate->gt($calendarEnd)) {
            $endDate = $calendarEnd->copy();
        }

        while ($cursor->lte($endDate)) {
            $dateKey = $cursor->toDateString();
            $eventsByDate[$dateKey][] = $item;
            $cursor->addDay();
        }
    }

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
        'acad' => $ui['category_akademik'] ?? '',
        'org' => $ui['category_organisasi'] ?? '',
        'restricted' => $ui['category_masa_tenang'] ?? '',
        'holiday' => $ui['category_libur'] ?? '',
        'campus' => $ui['category_event_besar'] ?? '',
    ];
@endphp

@section('content')
<div class="kmh-page kmh-kalender-page">
    <section class="kmh-card kmh-calendar-control-card">
        <div class="kmh-card-body">
            <div class="kmh-calendar-top-head">
                <div>
                    <h2>{{ $ui['calendar_title'] ?? '' }}</h2>
                    <p>{{ $ui['calendar_subtitle'] ?? '' }}</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="kmh-calendar-add-btn" data-bs-toggle="modal" data-bs-target="#kmh-calendar-manual-modal">
                        <i class="bi bi-plus-lg" aria-hidden="true"></i>
                        <span>{{ $ui['add_activity'] ?? '' }}</span>
                    </button>
                </div>
            </div>

            <form method="GET" action="{{ route('portal.kemahasiswaan.kalender') }}" class="kmh-calendar-filter-grid">
                <input type="hidden" name="bulan" value="{{ $selectedMonth }}">
                <input type="hidden" name="view" value="{{ $viewMode }}">

                <div>
                    <label class="kmh-calendar-label" for="kmh-kalender-kategori">{{ $ui['filter_category'] ?? '' }}</label>
                    <select id="kmh-kalender-kategori" name="kategori" class="kmh-calendar-select" onchange="this.form.submit()">
                        <option value="" @selected($selectedKategori === '')>{{ $ui['all_activities'] ?? '' }}</option>
                        <option value="acad" @selected($selectedKategori === 'acad')>{{ $ui['category_akademik'] ?? '' }}</option>
                        <option value="org" @selected($selectedKategori === 'org')>{{ $ui['category_organisasi'] ?? '' }}</option>
                        <option value="restricted" @selected($selectedKategori === 'restricted')>{{ $ui['category_masa_tenang'] ?? '' }}</option>
                        <option value="holiday" @selected($selectedKategori === 'holiday')>{{ $ui['category_libur'] ?? '' }}</option>
                        <option value="campus" @selected($selectedKategori === 'campus')>{{ $ui['category_event_besar'] ?? '' }}</option>
                    </select>
                </div>

                <div>
                    <label class="kmh-calendar-label" for="kmh-kalender-search">{{ $ui['search_label'] ?? '' }}</label>
                    <label class="kmh-calendar-search-wrap" for="kmh-kalender-search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input
                            id="kmh-kalender-search"
                            type="search"
                            name="q"
                            value="{{ $searchQuery }}"
                            placeholder="{{ $ui['search_placeholder'] ?? '' }}"
                        >
                    </label>
                </div>
            </form>

            <div class="kmh-calendar-legend">
                <span class="kmh-calendar-label">{{ $ui['legend_label'] ?? '' }}</span>
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
                        <span>{{ $ui['month_view'] ?? '' }}</span>
                    </a>
                    <a href="{{ $buildUrl(['view' => 'list']) }}" class="kmh-calendar-view-btn {{ $viewMode === 'list' ? 'is-active' : '' }}" role="tab" aria-selected="{{ $viewMode === 'list' ? 'true' : 'false' }}">
                        <i class="bi bi-list-ul"></i>
                        <span>{{ $ui['list_view'] ?? '' }}</span>
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
                            @php
                                $dayCategories = collect($day['events'])
                                    ->pluck('kategori')
                                    ->filter()
                                    ->map(fn ($value) => (string) $value)
                                    ->unique()
                                    ->values();

                                $dayTone = (string) ($dayCategories->first() ?? '');
                                $colorMap = [
                                    'acad' => '#1e40af',
                                    'org' => '#047857',
                                    'restricted' => '#b45309',
                                    'holiday' => '#be123c',
                                    'campus' => '#5b21b6',
                                ];

                                $dateStyle = null;
                                if ($dayCategories->count() > 1) {
                                    $palette = $dayCategories
                                        ->map(fn (string $key) => $colorMap[$key] ?? null)
                                        ->filter()
                                        ->values();

                                    if ($palette->isNotEmpty()) {
                                        $segments = [];
                                        $count = $palette->count();
                                        foreach ($palette as $index => $hex) {
                                            $start = (int) floor(($index / $count) * 100);
                                            $end = (int) floor((($index + 1) / $count) * 100);
                                            $segments[] = $hex . ' ' . $start . '% ' . $end . '%';
                                        }

                                        $dateStyle = 'background: conic-gradient(' . implode(', ', $segments) . '); color: #ffffff;';
                                    }
                                }
                            @endphp
                            <article class="kmh-calendar-day {{ $day['in_month'] ? '' : 'is-outside' }} {{ $dayTone !== '' ? 'is-' . $dayTone : '' }}" role="cell">
                                <span class="kmh-calendar-date" @if($dateStyle) style="{{ $dateStyle }}" @endif>{{ $day['date']->day }}</span>

                                <div class="kmh-calendar-events">
                                    @forelse(collect($day['events'])->take(2) as $event)
                                        <span class="kmh-calendar-pill is-{{ $event['kategori'] ?? '' }}" title="{{ $event['judul'] ?? '' }}">
                                            {{ \Illuminate\Support\Str::limit((string) ($event['judul'] ?? ''), 16) }}
                                        </span>
                                    @empty
                                        <span class="kmh-calendar-empty">&nbsp;</span>
                                    @endforelse

                                    @if(count($day['events']) > 2)
                                        <span class="kmh-calendar-more">+{{ count($day['events']) - 2 }} {{ $ui['more_suffix'] ?? '' }}</span>
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
                                <th>{{ $ui['table_col_title'] ?? '' }}</th>
                                <th>{{ $ui['table_col_org'] ?? '' }}</th>
                                <th>{{ $ui['table_col_date'] ?? '' }}</th>
                                <th>{{ $ui['table_col_location'] ?? '' }}</th>
                                <th>{{ $ui['table_col_category'] ?? '' }}</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($filteredKegiatan as $item)
                                <tr>
                                    <td>{{ $item['judul'] ?? '' }}</td>
                                    <td>{{ $item['organisasi'] ?? '' }}</td>
                                    <td>
                                        <div>{{ $item['tanggal_range_label'] ?? ($item['tanggal'] ?? '') }}</div>
                                    </td>
                                    <td>{{ $item['lokasi'] ?? '' }}</td>
                                    <td>
                                        <span class="kmh-calendar-pill is-{{ $item['kategori'] ?? '' }}">
                                            {{ $kategoriLabelMap[$item['kategori'] ?? ''] ?? '' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if(!empty($item['id']) && ($item['can_delete'] ?? false))
                                            <form method="POST" action="{{ route('portal.kemahasiswaan.jadwal.destroy', ['id' => $item['id']]) }}" onsubmit="return confirm('Hapus kegiatan ini dari kalender?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="kmh-empty-row">{{ $ui['empty_state'] ?? '' }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
</div>

<div class="modal fade" id="kmh-calendar-manual-modal" tabindex="-1" aria-labelledby="kmh-calendar-manual-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable kmh-calendar-modal-dialog">
        <div class="modal-content kmh-calendar-modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="kmh-calendar-manual-modal-title">{{ $ui['modal_title'] ?? '' }}</h5>
                    <p class="modal-subtitle mb-0">{{ $ui['modal_subtitle'] ?? '' }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                @unless(count($organizations) > 0)
                    <div class="alert alert-warning" role="alert">
                        {{ $ui['schedule_form_warning'] ?? '' }}
                    </div>
                @endunless

                <form method="POST" action="{{ route('portal.kemahasiswaan.jadwal.store') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label" for="kmh-calendar-title">{{ $ui['field_title'] ?? '' }}</label>
                        <input
                            type="text"
                            id="kmh-calendar-title"
                            name="judul"
                            class="form-control"
                            placeholder="{{ $ui['field_title_placeholder'] ?? '' }}"
                            required
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="kmh-calendar-start">{{ $ui['field_start_date'] ?? '' }}</label>
                        <input type="date" id="kmh-calendar-start" name="tanggal_mulai" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="kmh-calendar-end">{{ $ui['field_end_date'] ?? '' }}</label>
                        <input type="date" id="kmh-calendar-end" name="tanggal_selesai" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="kmh-calendar-category">{{ $ui['field_category'] ?? '' }}</label>
                        <select id="kmh-calendar-category" name="kategori" class="form-select" required>
                            <option value="acad">{{ $ui['category_akademik'] ?? '' }}</option>
                            <option value="org" selected>{{ $ui['category_organisasi'] ?? '' }}</option>
                            <option value="restricted">{{ $ui['category_masa_tenang'] ?? '' }}</option>
                            <option value="holiday">{{ $ui['category_libur'] ?? '' }}</option>
                            <option value="campus">{{ $ui['category_event_besar'] ?? '' }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="kmh-calendar-organization">{{ $ui['field_organization'] ?? '' }}</label>
                        <select id="kmh-calendar-organization" name="organization_id" class="form-select" required @disabled(count($organizations) === 0)>
                            <option value="">{{ $ui['schedule_org_placeholder'] ?? '' }}</option>
                            @foreach($organizations as $organization)
                                <option value="{{ $organization['id'] }}">{{ $organization['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="kmh-calendar-location">{{ $ui['field_location'] ?? '' }}</label>
                        <input
                            type="text"
                            id="kmh-calendar-location"
                            name="lokasi"
                            class="form-control"
                            placeholder="{{ $ui['field_location_placeholder'] ?? '' }}"
                            required
                        >
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="kmh-calendar-description">{{ $ui['field_description'] ?? '' }}</label>
                        <textarea
                            id="kmh-calendar-description"
                            name="deskripsi"
                            class="form-control"
                            rows="4"
                            placeholder="{{ $ui['field_description_placeholder'] ?? '' }}"
                        ></textarea>
                    </div>
                    <div class="col-12 d-flex flex-wrap justify-content-end gap-2 pt-1">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ $ui['cancel_button'] ?? '' }}</button>
                        <button type="submit" class="btn btn-primary" @disabled(count($organizations) === 0)>{{ $ui['save_button'] ?? '' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.kmh-kalender-page .kmh-calendar-day.is-acad .kmh-calendar-date {
    color: #1e40af;
    background: #dbeafe;
}

.kmh-kalender-page .kmh-calendar-day.is-org .kmh-calendar-date {
    color: #047857;
    background: #d1fae5;
}

.kmh-kalender-page .kmh-calendar-day.is-restricted .kmh-calendar-date {
    color: #b45309;
    background: #fef3c7;
}

.kmh-kalender-page .kmh-calendar-day.is-holiday .kmh-calendar-date {
    color: #be123c;
    background: #ffe4e6;
}

.kmh-kalender-page .kmh-calendar-day.is-campus .kmh-calendar-date {
    color: #5b21b6;
    background: #ede9fe;
}

.kmh-kalender-page .kmh-calendar-day .kmh-calendar-date {
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 1.9rem;
    min-height: 1.9rem;
    font-weight: 700;
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
}
</style>
@endpush
