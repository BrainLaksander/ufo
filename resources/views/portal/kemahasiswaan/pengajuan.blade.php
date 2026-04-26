@extends('layouts.portal.kemahasiswaan')

@section('title', 'Manajemen Pengajuan & Laporan - Kemahasiswaan')
@section('page_title', 'Manajemen Pengajuan Kegiatan & Laporan')
@section('page_subtitle', 'Review pengajuan, laporan LPJ, dan penjadwalan kegiatan organisasi')
@section('page_class', 'kmh-page-pengajuan')

@php
    $ui = $ui ?? [];
    $workflowPengajuan = $workflowPengajuan ?? [];
    $workflowLaporan = $workflowLaporan ?? [];
    $jadwalKegiatan = $jadwalKegiatan ?? [];
    $organizations = $organizations ?? [];

    $pendingStatuses = ['Diajukan', 'Sedang Direview', 'Revisi'];
    $pendingPengajuan = collect($workflowPengajuan)->whereIn('status', $pendingStatuses)->count();
    $pendingLaporan = collect($workflowLaporan)->whereIn('status', $pendingStatuses)->count();

    $statusClass = function (string $status): string {
        return match ($status) {
            'Disetujui' => 'is-success',
            'Diajukan', 'Sedang Direview', 'Revisi' => 'is-warning',
            'Ditolak' => 'is-danger',
            'Draft' => 'is-muted',
            default => 'is-info',
        };
    };

    $hasOrganizations = !empty($organizations);
@endphp

@section('content')
<div class="kmh-page kmh-pengajuan-page">
    @if(session('success'))
        <div class="alert alert-success mb-0" role="alert">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-0" role="alert">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-0" role="alert">{{ $errors->first() }}</div>
    @endif

    <section class="kmh-stats-grid is-two">
        <article class="kmh-stat-card tone-primary">
            <div>
                <p>Total Pengajuan Kegiatan</p>
                <strong class="kmh-stat-value">{{ count($workflowPengajuan) }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-folder2-open"></i></span>
        </article>

        <article class="kmh-stat-card tone-warning">
            <div>
                <p>Pengajuan Menunggu Review</p>
                <strong class="kmh-stat-value">{{ $pendingPengajuan }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-hourglass-split"></i></span>
        </article>

        <article class="kmh-stat-card tone-info">
            <div>
                <p>Total Laporan LPJ</p>
                <strong class="kmh-stat-value">{{ count($workflowLaporan) }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-clipboard-data"></i></span>
        </article>

        <article class="kmh-stat-card tone-success">
            <div>
                <p>Laporan Menunggu Review</p>
                <strong class="kmh-stat-value">{{ $pendingLaporan }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-check2-square"></i></span>
        </article>
    </section>

    <section class="kmh-card kmh-tab-card">
        <div class="kmh-card-body pt-0">
            <div class="kmh-tab-links" role="navigation" aria-label="Navigasi bagian review">
                <a href="#bagian-pengajuan" class="kmh-tab-link is-active">Pengajuan Kegiatan</a>
                <a href="#bagian-laporan" class="kmh-tab-link">Laporan Kegiatan (LPJ)</a>
                <a href="#bagian-jadwal" class="kmh-tab-link">Jadwal Kegiatan</a>
            </div>
        </div>
    </section>

    <section class="kmh-card">
        <div class="kmh-card-body">
            <div class="kmh-toolbar">
                <div class="kmh-toolbar-group">
                    <label class="kmh-toolbar-field is-search mb-0" for="kmh-review-search">
                        <i class="bi bi-search kmh-toolbar-icon"></i>
                        <input
                            id="kmh-review-search"
                            type="search"
                            class="kmh-toolbar-input"
                            placeholder="{{ $ui['search_placeholder'] ?? '' }}"
                            aria-label="{{ $ui['search_aria'] ?? '' }}"
                        >
                    </label>

                    <label class="kmh-toolbar-field mb-0" for="kmh-review-status">
                        <i class="bi bi-funnel kmh-toolbar-icon"></i>
                        <select id="kmh-review-status" class="kmh-toolbar-select" aria-label="Filter status review">
                            <option value="semua">{{ $ui['all_statuses'] ?? '' }}</option>
                            <option value="diajukan">Diajukan</option>
                            <option value="sedang-direview">Sedang Direview</option>
                            <option value="revisi">Revisi</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                            <option value="draft">Draft</option>
                        </select>
                    </label>
                </div>

                <div class="kmh-toolbar-end">
                    <p class="kmh-toolbar-caption mb-0">{{ $ui['filter_scope_caption'] ?? '' }}</p>
                </div>
            </div>
        </div>
    </section>

    <section id="bagian-pengajuan" class="kmh-card">
        <header class="kmh-card-head">
            <h2>Review Izin Kegiatan</h2>
            <small>{{ count($workflowPengajuan) }} data pengajuan</small>
        </header>
        <div class="kmh-card-body pt-0">
            <div class="table-responsive">
                <table class="table kmh-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Organisasi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th class="text-center">Aksi Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workflowPengajuan as $item)
                            @php
                                $rowSearch = \Illuminate\Support\Str::lower(implode(' ', [
                                    (string) ($item['judul'] ?? ''),
                                    (string) ($item['organisasi'] ?? ''),
                                    (string) ($item['catatan_departemen'] ?? ''),
                                ]));
                            @endphp
                            <tr
                                data-review-row
                                data-review-table="pengajuan"
                                data-review-status="{{ \Illuminate\Support\Str::slug((string) ($item['status'] ?? ''), '-') }}"
                                data-review-search="{{ $rowSearch }}"
                            >
                                <td>{{ $item['judul'] }}</td>
                                <td>{{ $item['organisasi'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($item['tanggal_kegiatan'])->format('d M Y') }}</td>
                                <td>
                                    <span class="kmh-status-pill {{ $statusClass($item['status']) }}">{{ $item['status'] }}</span>
                                </td>
                                <td>{{ $item['catatan_departemen'] ?? '' }}</td>
                                <td class="text-center">
                                    @if(in_array($item['status'], $pendingStatuses, true))
                                        <form method="POST" action="{{ route('portal.kemahasiswaan.pengajuan.review', ['id' => $item['id']]) }}" class="kmh-inline-review">
                                            @csrf
                                            <select name="decision" class="form-select form-select-sm">
                                                <option value="disetujui">Setujui</option>
                                                <option value="ditolak">Tolak</option>
                                                <option value="revisi">Revisi</option>
                                            </select>
                                            <input type="text" name="catatan" class="form-control form-control-sm" placeholder="{{ $ui['review_note_placeholder'] ?? '' }}">
                                            <button type="submit" class="btn btn-sm btn-primary kmh-review-submit-btn">
                                                <i class="bi bi-check2-circle"></i>
                                                <span>{{ $ui['review_save_button'] ?? '' }}</span>
                                            </button>
                                        </form>
                                    @else
                                        <small class="text-muted">{{ $ui['no_followup_action'] ?? '' }}</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="kmh-empty-row">{{ $ui['table_pengajuan_empty'] ?? '' }}</td>
                            </tr>
                        @endforelse

                        @if(count($workflowPengajuan) > 0)
                            <tr class="kmh-filter-empty-row d-none" data-review-empty="pengajuan">
                                <td colspan="6" class="kmh-empty-row">{{ $ui['table_pengajuan_filter_empty'] ?? '' }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section id="bagian-laporan" class="kmh-card">
        <header class="kmh-card-head">
            <h2>Review Laporan Kegiatan (LPJ)</h2>
            <small>{{ count($workflowLaporan) }} data laporan</small>
        </header>
        <div class="kmh-card-body pt-0">
            <div class="table-responsive">
                <table class="table kmh-table">
                    <thead>
                        <tr>
                            <th>Judul Laporan</th>
                            <th>Organisasi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th class="text-center">Aksi Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workflowLaporan as $item)
                            @php
                                $rowSearch = \Illuminate\Support\Str::lower(implode(' ', [
                                    (string) ($item['judul'] ?? ''),
                                    (string) ($item['organisasi'] ?? ''),
                                    (string) ($item['catatan_departemen'] ?? ''),
                                ]));
                            @endphp
                            <tr
                                data-review-row
                                data-review-table="laporan"
                                data-review-status="{{ \Illuminate\Support\Str::slug((string) ($item['status'] ?? ''), '-') }}"
                                data-review-search="{{ $rowSearch }}"
                            >
                                <td>{{ $item['judul'] }}</td>
                                <td>{{ $item['organisasi'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($item['tanggal_laporan'])->format('d M Y') }}</td>
                                <td>
                                    <span class="kmh-status-pill {{ $statusClass($item['status']) }}">{{ $item['status'] }}</span>
                                </td>
                                <td>{{ $item['catatan_departemen'] ?? '' }}</td>
                                <td class="text-center">
                                    @if(in_array($item['status'], $pendingStatuses, true))
                                        <form method="POST" action="{{ route('portal.kemahasiswaan.laporan.review', ['id' => $item['id']]) }}" class="kmh-inline-review">
                                            @csrf
                                            <select name="decision" class="form-select form-select-sm">
                                                <option value="disetujui">Setujui</option>
                                                <option value="ditolak">Tolak</option>
                                                <option value="revisi">Revisi</option>
                                            </select>
                                            <input type="text" name="catatan" class="form-control form-control-sm" placeholder="{{ $ui['review_note_placeholder'] ?? '' }}">
                                            <button type="submit" class="btn btn-sm btn-primary kmh-review-submit-btn">
                                                <i class="bi bi-check2-circle"></i>
                                                <span>{{ $ui['review_save_button'] ?? '' }}</span>
                                            </button>
                                        </form>
                                    @else
                                        <small class="text-muted">{{ $ui['no_followup_action'] ?? '' }}</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="kmh-empty-row">{{ $ui['table_laporan_empty'] ?? '' }}</td>
                            </tr>
                        @endforelse

                        @if(count($workflowLaporan) > 0)
                            <tr class="kmh-filter-empty-row d-none" data-review-empty="laporan">
                                <td colspan="6" class="kmh-empty-row">{{ $ui['table_laporan_filter_empty'] ?? '' }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section id="bagian-jadwal" class="kmh-grid-2 is-4-6">
        <article class="kmh-card">
            <header class="kmh-card-head">
                <h2>Masukkan Jadwal Kegiatan</h2>
            </header>
            <div class="kmh-card-body">
                @unless($hasOrganizations)
                    <div class="alert alert-warning" role="alert">
                        {{ $ui['schedule_form_warning'] ?? '' }}
                    </div>
                @endunless

                <form method="POST" action="{{ route('portal.kemahasiswaan.jadwal.store') }}" class="row g-2">
                    @csrf
                    <div class="col-12">
                        <label class="form-label">Judul Kegiatan</label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="acad">{{ $ui['category_akademik'] ?? 'Kegiatan Akademik' }}</option>
                            <option value="org" selected>{{ $ui['category_organisasi'] ?? 'Kegiatan Organisasi' }}</option>
                            <option value="restricted">{{ $ui['category_masa_tenang'] ?? 'Masa Tidak Boleh Berorganisasi' }}</option>
                            <option value="holiday">{{ $ui['category_libur'] ?? 'Libur Akademik' }}</option>
                            <option value="campus">{{ $ui['category_event_besar'] ?? 'Event Kampus Besar' }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Organisasi</label>
                        <select name="organization_id" class="form-select" required @disabled(!$hasOrganizations)>
                            <option value="">{{ $ui['schedule_org_placeholder'] ?? '' }}</option>
                            @foreach($organizations as $organization)
                                <option value="{{ $organization['id'] }}">{{ $organization['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-12 d-grid mt-2">
                        <button type="submit" class="btn btn-primary kmh-form-action-btn" @disabled(!$hasOrganizations)>{{ $ui['schedule_save_button'] ?? '' }}</button>
                    </div>
                </form>
            </div>
        </article>

        <article class="kmh-card">
            <header class="kmh-card-head">
                <h2>Jadwal Kegiatan Aktif</h2>
                <small>{{ count($jadwalKegiatan) }} data jadwal</small>
            </header>
            <div class="kmh-card-body pt-0">
                <div class="table-responsive">
                    <table class="table kmh-table">
                        <thead>
                            <tr>
                                <th>Kegiatan</th>
                                <th>Organisasi</th>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalKegiatan as $jadwal)
                                <tr>
                                    <td>{{ $jadwal['judul'] }}</td>
                                    <td>{{ $jadwal['organisasi'] }}</td>
                                    <td>{{ $jadwal['tanggal_range_label'] ?? \Carbon\Carbon::parse($jadwal['tanggal'])->format('d M Y') }}</td>
                                    <td>{{ $jadwal['kategori'] ?? '' }}</td>
                                    <td>{{ $jadwal['lokasi'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="kmh-empty-row">{{ $ui['schedule_empty'] ?? '' }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </article>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('kmh-review-search');
    var statusSelect = document.getElementById('kmh-review-status');
    var rows = Array.prototype.slice.call(document.querySelectorAll('[data-review-row]'));

    if (!searchInput || !statusSelect || rows.length === 0) {
        return;
    }

    function applyFilter() {
        var query = (searchInput.value || '').toLowerCase().trim();
        var status = (statusSelect.value || 'semua').toLowerCase();
        var visibleByTable = {
            pengajuan: 0,
            laporan: 0,
        };

        rows.forEach(function (row) {
            var rowSearch = (row.getAttribute('data-review-search') || '').toLowerCase();
            var rowStatus = (row.getAttribute('data-review-status') || '').toLowerCase();
            var tableName = row.getAttribute('data-review-table') || '';

            var textMatch = query === '' || rowSearch.indexOf(query) !== -1;
            var statusMatch = status === 'semua' || rowStatus === status;
            var showRow = textMatch && statusMatch;

            row.classList.toggle('d-none', !showRow);

            if (showRow && Object.prototype.hasOwnProperty.call(visibleByTable, tableName)) {
                visibleByTable[tableName] += 1;
            }
        });

        ['pengajuan', 'laporan'].forEach(function (tableName) {
            var emptyRow = document.querySelector('[data-review-empty="' + tableName + '"]');
            if (emptyRow) {
                emptyRow.classList.toggle('d-none', (visibleByTable[tableName] || 0) !== 0);
            }
        });
    }

    searchInput.addEventListener('input', applyFilter);
    statusSelect.addEventListener('change', applyFilter);
    applyFilter();
});
</script>
@endpush
