@extends('layouts.portal.kemahasiswaan')

@section('title', 'Manajemen Pengumuman - Kemahasiswaan')
@section('page_title', 'Manajemen Pengumuman & Distribusi Email')
@section('page_subtitle', 'Kelola publikasi pengumuman dan alur review distribusi email organisasi')
@section('page_class', 'kmh-page-pengumuman')

@php
    $ui = $ui ?? [];
    $workflowPengumuman = $workflowPengumuman ?? [];
    $emailReviewQueue = $emailReviewQueue ?? [];
    $ukmAccounts = $ukmAccounts ?? [];

    $publishedCount = collect($workflowPengumuman)->where('status_code', 'published')->count();
    $scheduledCount = collect($workflowPengumuman)->where('status_code', 'scheduled')->count();
    $draftCount = collect($workflowPengumuman)->where('status_code', 'draft')->count();
    $hasAccounts = !empty($ukmAccounts);
    $defaultAnnouncementAccountId = $ukmAccounts[0]['id'] ?? null;
    $hasCreateAnnouncementErrors = $errors->has('judul')
        || $errors->has('kategori')
        || $errors->has('target')
        || $errors->has('konten')
        || $errors->has('publish_at')
        || $errors->has('submit_action');

    $announcementCategoryOptions = [
        'Informasi Akademik',
        'Pengumuman Penting',
        'Reminder',
        'Ucapan',
    ];

    $announcementTargetOptions = [
        'Semua Organisasi',
        'Organisasi Tertentu',
        'Semua Mahasiswa',
        'Mahasiswa Tertentu',
    ];

    $statusClass = function (string $status): string {
        return match ($status) {
            'Terpublikasi' => 'is-success',
            'Terjadwal' => 'is-info',
            'Draft' => 'is-muted',
            'Arsip' => 'is-warning',
            default => 'is-muted',
        };
    };

    $reviewClass = function (string $status): string {
        return match ($status) {
            'Disetujui' => 'is-success',
            'Menunggu Review', 'Perlu Revisi' => 'is-warning',
            'Ditolak' => 'is-danger',
            default => 'is-muted',
        };
    };
@endphp

@section('content')
<div class="kmh-page kmh-pengumuman-page">
    @if(session('success'))
        <div class="alert alert-success mb-0" role="alert">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-0" role="alert">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-0" role="alert">{{ $errors->first() }}</div>
    @endif

    <section class="kmh-stats-grid">
        <article class="kmh-stat-card tone-primary">
            <div>
                <p>{{ $ui['total_label'] ?? '' }}</p>
                <strong class="kmh-stat-value">{{ count($workflowPengumuman) }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-megaphone-fill"></i></span>
        </article>

        <article class="kmh-stat-card tone-success">
            <div>
                <p>{{ $ui['published_label'] ?? '' }}</p>
                <strong class="kmh-stat-value">{{ $publishedCount }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-check-circle-fill"></i></span>
        </article>

        <article class="kmh-stat-card tone-info">
            <div>
                <p>{{ $ui['scheduled_label'] ?? '' }}</p>
                <strong class="kmh-stat-value">{{ $scheduledCount }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-calendar2-check-fill"></i></span>
        </article>

        <article class="kmh-stat-card tone-muted">
            <div>
                <p>{{ $ui['draft_label'] ?? '' }}</p>
                <strong class="kmh-stat-value">{{ $draftCount }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-pencil-square"></i></span>
        </article>
    </section>

    <section class="kmh-card">
        <div class="kmh-card-body">
            <div class="kmh-toolbar">
                <div class="kmh-toolbar-group">
                    <label class="kmh-toolbar-field is-search mb-0" for="kmh-pengumuman-search">
                        <i class="bi bi-search kmh-toolbar-icon"></i>
                        <input
                            id="kmh-pengumuman-search"
                            type="search"
                            class="kmh-toolbar-input"
                            placeholder="{{ $ui['search_placeholder'] ?? '' }}"
                            aria-label="{{ $ui['search_aria'] ?? '' }}"
                        >
                    </label>

                    <label class="kmh-toolbar-field mb-0" for="kmh-pengumuman-status">
                        <i class="bi bi-funnel kmh-toolbar-icon"></i>
                        <select id="kmh-pengumuman-status" class="kmh-toolbar-select" aria-label="Filter status pengumuman">
                            <option value="semua">{{ $ui['all_statuses'] ?? '' }}</option>
                            <option value="published">Terpublikasi</option>
                            <option value="scheduled">Terjadwal</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Arsip</option>
                        </select>
                    </label>
                </div>

                <div class="kmh-toolbar-end">
                    <button type="button" class="kmh-quick-btn is-primary kmh-pengumuman-cta" data-bs-toggle="modal" data-bs-target="#kmh-pengumuman-modal">
                        <i class="bi bi-plus-circle"></i>
                        <span>{{ $ui['create_new_button'] ?? '' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="kmh-pengumuman-modal" tabindex="-1" aria-labelledby="kmh-pengumuman-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="kmh-pengumuman-modal-label">{{ $ui['modal_title'] ?? ($ui['create_new_title'] ?? 'Buat Pengumuman Baru') }}</h5>
                        <small class="text-muted">{{ $ui['modal_subtitle'] ?? ($ui['create_new_subtitle'] ?? '') }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form method="POST" action="{{ route('portal.kemahasiswaan.pengumuman.store') }}">
                    @csrf
                    @if(!empty($defaultAnnouncementAccountId))
                        <input type="hidden" name="ukm_account_id" value="{{ $defaultAnnouncementAccountId }}">
                    @endif

                    <div class="modal-body">
                        @unless($hasAccounts)
                            <div class="alert alert-warning" role="alert">
                                {{ $ui['account_missing_warning'] ?? '' }}
                            </div>
                        @endunless

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">{{ $ui['field_title'] ?? 'Judul Pengumuman' }} <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    name="judul"
                                    class="form-control"
                                    maxlength="140"
                                    value="{{ old('judul') }}"
                                    placeholder="Masukkan judul pengumuman..."
                                    required
                                >
                            </div>

                            <div class="col-12">
                                <label class="form-label">{{ $ui['field_category'] ?? 'Kategori' }} <span class="text-danger">*</span></label>
                                <select name="kategori" class="form-select" required>
                                    <option value="">{{ $ui['field_category_placeholder'] ?? 'Pilih Kategori' }}</option>
                                    @foreach($announcementCategoryOptions as $category)
                                        <option value="{{ $category }}" @selected(old('kategori') === $category)>{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">{{ $ui['field_content'] ?? 'Konten Pengumuman' }} <span class="text-danger">*</span></label>
                                <textarea
                                    name="konten"
                                    class="form-control"
                                    rows="5"
                                    maxlength="10000"
                                    placeholder="{{ $ui['field_content_placeholder'] ?? 'Tulis konten pengumuman di sini...' }}"
                                    required
                                >{{ old('konten') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ $ui['field_target'] ?? 'Target Distribusi' }} <span class="text-danger">*</span></label>
                                <select name="target" class="form-select" required>
                                    <option value="">{{ $ui['field_target_placeholder'] ?? 'Pilih target distribusi' }}</option>
                                    @foreach($announcementTargetOptions as $target)
                                        <option value="{{ $target }}" @selected(old('target') === $target)>{{ $target }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ $ui['field_publish_date'] ?? 'Jadwal Publish (Opsional)' }}</label>
                                <input
                                    type="datetime-local"
                                    name="publish_at"
                                    class="form-control"
                                    value="{{ old('publish_at') }}"
                                    placeholder="{{ $ui['field_publish_placeholder'] ?? 'dd/mm/yyyy --:--' }}"
                                >
                            </div>
                        </div>

                        <div class="alert alert-warning mt-3 mb-0" role="alert">
                            <strong>{{ $ui['distribution_info_title'] ?? '' }}</strong> {{ $ui['distribution_info_body'] ?? '' }}
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ $ui['cancel_button'] ?? 'Batal' }}</button>
                        <button type="submit" name="submit_action" value="draft" class="btn btn-secondary" @disabled(!$hasAccounts)>{{ $ui['save_draft_button'] ?? 'Simpan sebagai Draft' }}</button>
                        <button type="submit" name="submit_action" value="publish_now" class="btn btn-primary kmh-form-action-btn" @disabled(!$hasAccounts)>{{ $ui['publish_now_button'] ?? 'Publikasikan Sekarang' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="kmh-info-banner">
        <strong>{{ $ui['distribution_info_title'] ?? '' }}</strong>
        {{ $ui['distribution_info_body'] ?? '' }}
    </div>

    <section class="kmh-card">
        <header class="kmh-card-head">
            <h2>{{ $ui['review_email_title'] ?? '' }}</h2>
            <small>{{ count($emailReviewQueue) }} {{ $ui['review_email_count_suffix'] ?? '' }}</small>
        </header>
        <div class="kmh-card-body pt-0">
            <div class="table-responsive">
                <table class="table kmh-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Pemohon</th>
                            <th>Organisasi</th>
                            <th>Status Publikasi</th>
                            <th>Status Review Email</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emailReviewQueue as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $item['judul'] }}</div>
                                    <small class="text-muted">{{ $item['ringkasan'] }}</small>
                                </td>
                                <td>{{ $item['ukm_account_name'] }}</td>
                                <td>{{ $item['organisasi'] }}</td>
                                <td>
                                    <span class="kmh-status-pill {{ $statusClass($item['status']) }}">{{ $item['status'] }}</span>
                                </td>
                                <td>
                                    <span class="kmh-status-pill {{ $reviewClass($item['email_review_status']) }}">{{ $item['email_review_status'] }}</span>
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('portal.kemahasiswaan.pengumuman.email-review', ['id' => $item['id']]) }}" class="kmh-inline-review">
                                        @csrf
                                        <select name="decision" class="form-select form-select-sm">
                                            <option value="setujui">Setujui</option>
                                            <option value="tolak">Tolak</option>
                                            <option value="revisi">Revisi</option>
                                        </select>
                                        <input type="text" name="catatan" class="form-control form-control-sm" placeholder="{{ $ui['note_placeholder'] ?? '' }}">
                                        <button type="submit" class="btn btn-sm btn-primary kmh-form-action-btn">{{ $ui['save_button'] ?? '' }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="kmh-empty-row">{{ $ui['review_queue_empty'] ?? '' }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="kmh-card">
        <header class="kmh-card-head">
            <h2>{{ $ui['list_title'] ?? '' }}</h2>
            <small>{{ count($workflowPengumuman) }} {{ $ui['list_count_suffix'] ?? '' }}</small>
        </header>
        <div class="kmh-card-body pt-0">
            <div class="table-responsive">
                <table class="table kmh-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Target</th>
                            <th>Pemohon</th>
                            <th>Publish</th>
                            <th>Status</th>
                            <th>Review Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workflowPengumuman as $item)
                            @php
                                $rowSearch = \Illuminate\Support\Str::lower(implode(' ', [
                                    (string) ($item['judul'] ?? ''),
                                    (string) ($item['kategori'] ?? ''),
                                    (string) ($item['target'] ?? ''),
                                    (string) ($item['organisasi'] ?? ''),
                                    (string) ($item['ringkasan'] ?? ''),
                                ]));
                            @endphp
                            <tr
                                data-pengumuman-row
                                data-pengumuman-search="{{ $rowSearch }}"
                                data-pengumuman-status="{{ $item['status_code'] }}"
                            >
                                <td>{{ $item['judul'] }}</td>
                                <td>{{ $item['kategori'] }}</td>
                                <td>{{ $item['target'] }}</td>
                                <td>{{ $item['organisasi'] }}</td>
                                <td>
                                    @if(!empty($item['publish_at']))
                                        {{ \Carbon\Carbon::parse($item['publish_at'])->format('d M Y') }}
                                    @else
                                        
                                    @endif
                                </td>
                                <td>
                                    <span class="kmh-status-pill {{ $statusClass($item['status']) }}">{{ $item['status'] }}</span>
                                </td>
                                <td>
                                    <div>
                                        <span class="kmh-status-pill {{ $reviewClass($item['email_review_status']) }}">{{ $item['email_review_status'] }}</span>
                                    </div>
                                    @if(!empty($item['email_review_note']))
                                        <small class="text-muted d-block mt-1">{{ $item['email_review_note'] }}</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="kmh-empty-row">{{ $ui['list_empty'] ?? '' }}</td>
                            </tr>
                        @endforelse

                        @if(count($workflowPengumuman) > 0)
                            <tr class="kmh-filter-empty-row d-none" data-pengumuman-empty>
                                <td colspan="7" class="kmh-empty-row">{{ $ui['list_filter_empty'] ?? '' }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('kmh-pengumuman-search');
    var statusSelect = document.getElementById('kmh-pengumuman-status');
    var rows = Array.prototype.slice.call(document.querySelectorAll('[data-pengumuman-row]'));
    var emptyRow = document.querySelector('[data-pengumuman-empty]');

    if (!searchInput || !statusSelect || rows.length === 0) {
        return;
    }

    function applyFilter() {
        var query = (searchInput.value || '').toLowerCase().trim();
        var status = (statusSelect.value || 'semua').toLowerCase();
        var visibleCount = 0;

        rows.forEach(function (row) {
            var rowSearch = (row.getAttribute('data-pengumuman-search') || '').toLowerCase();
            var rowStatus = (row.getAttribute('data-pengumuman-status') || '').toLowerCase();
            var textMatch = query === '' || rowSearch.indexOf(query) !== -1;
            var statusMatch = status === 'semua' || rowStatus === status;
            var showRow = textMatch && statusMatch;

            row.classList.toggle('d-none', !showRow);
            if (showRow) {
                visibleCount += 1;
            }
        });

        if (emptyRow) {
            emptyRow.classList.toggle('d-none', visibleCount !== 0);
        }
    }

    searchInput.addEventListener('input', applyFilter);
    statusSelect.addEventListener('change', applyFilter);
    applyFilter();

    @if($hasCreateAnnouncementErrors)
        var modalElement = document.getElementById('kmh-pengumuman-modal');
        if (modalElement && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalElement).show();
        }
    @endif
});
</script>
@endpush
