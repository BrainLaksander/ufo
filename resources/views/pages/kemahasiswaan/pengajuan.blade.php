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
                                 data-review-id="{{ $item['id'] }}"
                                 data-review-judul="{{ $item['judul'] }}"
                                 data-review-organisasi="{{ $item['organisasi'] }}"
                                 data-review-tanggal="{{ \Carbon\Carbon::parse($item['tanggal_kegiatan'])->format('d M Y') }}"
                                 data-review-deskripsi="{{ $item['deskripsi'] ?? '' }}"
                                 data-review-file="{{ $item['file_path'] ?? '' }}"
                                 data-review-catatan="{{ $item['catatan_departemen'] ?? '' }}"
                             >
                                <td>{{ $item['judul'] }}</td>
                                <td>{{ $item['organisasi'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($item['tanggal_kegiatan'])->format('d M Y') }}</td>
                                <td>
                                    <span class="kmh-status-pill {{ $statusClass($item['status']) }}">{{ $item['status'] }}</span>
                                </td>
                                <td>{{ $item['catatan_departemen'] ?? '' }}</td>
                                 <td class="text-center">
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-primary kmh-review-btn" 
                                        data-review-trigger="pengajuan"
                                    >
                                        <i class="bi bi-search"></i>
                                        <span>Review</span>
                                    </button>
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

    <section id="bagian-laporan" class="kmh-card d-none">
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
                                 data-review-id="{{ $item['id'] }}"
                                 data-review-judul="{{ $item['judul'] }}"
                                 data-review-organisasi="{{ $item['organisasi'] }}"
                                 data-review-tanggal="{{ \Carbon\Carbon::parse($item['tanggal_laporan'])->format('d M Y') }}"
                                 data-review-deskripsi="{{ $item['konten'] ?? '' }}"
                                 data-review-file="{{ $item['attachment'] ?? '' }}"
                                 data-review-catatan="{{ $item['catatan_departemen'] ?? '' }}"
                             >
                                <td>{{ $item['judul'] }}</td>
                                <td>{{ $item['organisasi'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($item['tanggal_laporan'])->format('d M Y') }}</td>
                                <td>
                                    <span class="kmh-status-pill {{ $statusClass($item['status']) }}">{{ $item['status'] }}</span>
                                </td>
                                <td>{{ $item['catatan_departemen'] ?? '' }}</td>
                                 <td class="text-center">
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-primary kmh-review-btn" 
                                        data-review-trigger="laporan"
                                    >
                                        <i class="bi bi-search"></i>
                                        <span>Review</span>
                                    </button>
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

</div>

<!-- Modal Review -->
<div class="modal fade kmh-org-modal" id="kmh-review-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold">Detail & Review <span id="kmh-review-modal-type"></span></h5>
                    <small class="text-muted" id="kmh-review-modal-subtitle"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <div class="p-3 bg-light rounded-3 mb-4">
                        <h6 class="fw-bold text-uppercase small text-muted mb-2">Informasi Berkas</h6>
                        <h5 id="kmh-review-detail-title" class="fw-bold mb-3"></h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-2 text-primary">
                                        <i class="bi bi-building fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Organisasi</small>
                                        <span id="kmh-review-detail-org" class="fw-medium"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-2 text-primary">
                                        <i class="bi bi-calendar-event fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Tanggal Diajukan</small>
                                        <span id="kmh-review-detail-date" class="fw-medium"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-2"><i class="bi bi-text-paragraph me-1"></i> Deskripsi / Konten</h6>
                    <div id="kmh-review-detail-desc" class="p-3 border rounded-3 mb-4 bg-white" style="white-space: pre-wrap; min-height: 120px; font-size: 0.95rem; line-height: 1.6;"></div>

                    <div id="kmh-review-detail-file-container" class="mb-2">
                        <h6 class="fw-bold mb-2"><i class="bi bi-paperclip me-1"></i> Lampiran File</h6>
                        <div class="p-3 border rounded-3 bg-white d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-pdf fs-3 text-danger me-3"></i>
                                <div>
                                    <span class="d-block fw-medium">Dokumen Pendukung</span>
                                    <small class="text-muted">Klik tombol di samping untuk mengunduh berkas</small>
                                </div>
                            </div>
                            <a id="kmh-review-detail-file-link" href="#" target="_blank" class="btn btn-sm btn-primary px-3">
                                <i class="bi bi-download me-1"></i>
                                Unduh
                            </a>
                        </div>
                        <div id="kmh-review-detail-no-file" class="p-3 border border-dashed rounded-3 text-center text-muted italic bg-light">
                            Tidak ada lampiran file untuk berkas ini
                        </div>
                    </div>
                </div>

                <div class="kmh-review-section p-4 bg-primary bg-opacity-10 rounded-4 border border-primary border-opacity-25">
                    <form id="kmh-review-modal-form" method="POST" action="">
                        @csrf
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <i class="bi bi-pencil-square"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-primary">Tindakan Review & Keputusan</h6>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium small">Keputusan Final</label>
                                <select name="decision" class="form-select border-primary border-opacity-25" required>
                                    <option value="disetujui">Setujui (Approve)</option>
                                    <option value="ditolak">Tolak (Disapprove)</option>
                                    <option value="revisi">Minta Revisi</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-medium small">Komentar / Catatan Review</label>
                                <textarea name="catatan" class="form-control border-primary border-opacity-25" rows="3" placeholder="Berikan alasan penolakan, instruksi revisi yang jelas, atau catatan khusus persetujuan..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="kmh-review-modal-form" class="btn btn-primary px-5 fw-bold">
                    <i class="bi bi-check2-circle me-1"></i>
                    Simpan Keputusan
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('kmh-review-search');
    var statusSelect = document.getElementById('kmh-review-status');
    var rows = Array.prototype.slice.call(document.querySelectorAll('[data-review-row]'));
    
    var reviewModalEl = document.getElementById('kmh-review-modal');
    var reviewForm = document.getElementById('kmh-review-modal-form');
    var reviewButtons = document.querySelectorAll('.kmh-review-btn');

    function applyFilter() {
        if (!searchInput || !statusSelect || rows.length === 0) return;

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

    if (reviewModalEl && reviewForm) {
        var reviewModal = new bootstrap.Modal(reviewModalEl);

        reviewButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var row = button.closest('[data-review-row]');
                if (!row) return;

                var type = button.getAttribute('data-review-trigger');
                var id = row.getAttribute('data-review-id');
                var judul = row.getAttribute('data-review-judul');
                var org = row.getAttribute('data-review-organisasi');
                var tanggal = row.getAttribute('data-review-tanggal');
                var deskripsi = row.getAttribute('data-review-deskripsi');
                var filePath = row.getAttribute('data-review-file');
                var catatan = row.getAttribute('data-review-catatan');

                document.getElementById('kmh-review-modal-type').textContent = type === 'pengajuan' ? 'Izin Kegiatan' : 'Laporan (LPJ)';
                document.getElementById('kmh-review-modal-subtitle').textContent = 'Review berkas dari ' + org;
                document.getElementById('kmh-review-detail-title').textContent = judul;
                document.getElementById('kmh-review-detail-desc').textContent = deskripsi || '-';
                document.getElementById('kmh-review-detail-org').textContent = org;
                document.getElementById('kmh-review-detail-date').textContent = tanggal;

                var fileLink = document.getElementById('kmh-review-detail-file-link');
                var noFile = document.getElementById('kmh-review-detail-no-file');

                if (filePath) {
                    fileLink.href = '/storage/' + filePath;
                    fileLink.parentElement.classList.remove('d-none');
                    noFile.classList.add('d-none');
                } else {
                    fileLink.parentElement.classList.add('d-none');
                    noFile.classList.remove('d-none');
                }

                reviewForm.querySelector('textarea[name="catatan"]').value = catatan || '';

                var actionUrl = type === 'pengajuan' 
                    ? '{{ route('portal.kemahasiswaan.pengajuan.review', ['id' => ':id']) }}'
                    : '{{ route('portal.kemahasiswaan.laporan.review', ['id' => ':id']) }}';
                reviewForm.setAttribute('action', actionUrl.replace(':id', id));

                reviewModal.show();
            });
        });
    }

    // Tab switching logic
    var tabLinks = document.querySelectorAll('.kmh-tab-link');
    var sections = {
        '#bagian-pengajuan': document.getElementById('bagian-pengajuan'),
        '#bagian-laporan': document.getElementById('bagian-laporan')
    };

    tabLinks.forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            var targetId = link.getAttribute('href');

            // Update active tab link
            tabLinks.forEach(function (l) { l.classList.remove('is-active'); });
            link.classList.add('is-active');

            // Toggle sections
            Object.keys(sections).forEach(function (id) {
                if (sections[id]) {
                    sections[id].classList.toggle('d-none', id !== targetId);
                }
            });
        });
    });

    if (searchInput) searchInput.addEventListener('input', applyFilter);
    if (statusSelect) statusSelect.addEventListener('change', applyFilter);
    
    applyFilter();
});
</script>
@endpush
