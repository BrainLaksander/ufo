@extends('layouts.portal.kemahasiswaan')

@section('title', 'Manajemen Pengumuman - Kemahasiswaan')
@section('page_title', 'Manajemen Pengumuman & Distribusi Email')
@section('page_subtitle', 'Kelola publikasi pengumuman dan alur review distribusi email organisasi')
@section('page_class', 'kmh-page-pengumuman')

@php
    $ui = $ui ?? [];
    $workflowPengumuman = $workflowPengumuman ?? [];
    $emailReviewQueue = $emailReviewQueue ?? [];
    $scheduledEmailQueue = $scheduledEmailQueue ?? [];
    $sentEmailQueue = $sentEmailQueue ?? [];
    $ukmAccounts = $ukmAccounts ?? [];

    $publishedCount = collect($workflowPengumuman)->where('status_code', 'published')->count();
    $scheduledCount = collect($workflowPengumuman)->where('status_code', 'scheduled')->count();
    $draftCount = collect($workflowPengumuman)->where('status_code', 'draft')->count();
    $defaultStudentRecipient = config('mail.announcement_default_student_recipient', 'student252@student.unklab.ac.id');
    $hasAccounts = !empty($ukmAccounts);
    $defaultAnnouncementAccountId = $ukmAccounts[0]['id'] ?? null;
    $minPublishAt = now()->format('Y-m-d\TH:i');
    $hasCreateAnnouncementErrors = $errors->has('judul')
        || $errors->has('kategori')
        || $errors->has('target')
        || $errors->has('manual_recipients')
        || $errors->has('konten')
        || $errors->has('publish_at')
        || $errors->has('submit_action');

    $announcementEditPayload = collect($workflowPengumuman)
        ->mapWithKeys(function (array $item) {
            $publishAt = !empty($item['publish_at'])
                ? \Carbon\Carbon::parse($item['publish_at'])->format('Y-m-d\TH:i')
                : '';

            return [
                (int) ($item['id'] ?? 0) => [
                    'id' => (int) ($item['id'] ?? 0),
                    'judul' => (string) ($item['judul'] ?? ''),
                    'kategori' => (string) ($item['kategori'] ?? ''),
                    'target_mode' => (string) ($item['target_mode'] ?? ''),
                    'recipient_mode' => (string) ($item['recipient_mode'] ?? ''),
                    'recipient_emails' => (string) ($item['recipient_emails'] ?? ''),
                    'konten' => (string) ($item['konten'] ?? ''),
                    'publish_at' => $publishAt,
                    'status_code' => (string) ($item['status_code'] ?? ''),
                    'email_delivery_status' => (string) ($item['email_delivery_status'] ?? ''),
                ],
            ];
        })
        ->all();

    $announcementCategoryOptions = [
        'Informasi Akademik',
        'Pengumuman Penting',
        'Reminder',
        'Ucapan',
    ];

    $announcementTargetOptions = [
        'all_students' => 'Semua Mahasiswa',
        'manual' => 'Manual',
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
                            <option value="draft">Draft</option>
                            <option value="published">Terpublikasi</option>
                            <option value="scheduled">Terjadwal</option>
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
                        <input type="hidden" id="kmh-pengumuman-mode" value="create">
                        @unless($hasAccounts)
                            <div class="alert alert-warning" role="alert">
                                {{ $ui['account_missing_warning'] ?? '' }}
                            </div>
                        @endunless

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">{{ $ui['field_title'] ?? 'Judul Pengumuman' }} <span class="text-danger">*</span></label>
                                <input
                                    id="kmh-pengumuman-judul"
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
                                <select id="kmh-pengumuman-kategori" name="kategori" class="form-select" required>
                                    <option value="">{{ $ui['field_category_placeholder'] ?? 'Pilih Kategori' }}</option>
                                    @foreach($announcementCategoryOptions as $category)
                                        <option value="{{ $category }}" @selected(old('kategori') === $category)>{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">{{ $ui['field_content'] ?? 'Konten Pengumuman' }} <span class="text-danger">*</span></label>
                                <textarea
                                    id="kmh-pengumuman-konten"
                                    name="konten"
                                    class="form-control"
                                    rows="5"
                                    maxlength="10000"
                                    placeholder="{{ $ui['field_content_placeholder'] ?? 'Tulis konten pengumuman di sini...' }}"
                                    required
                                >{{ old('konten') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ $ui['field_target'] ?? 'Target Distribusi Email' }} <span class="text-danger">*</span></label>
                                <select id="kmh-pengumuman-target" name="target" class="form-select" required>
                                    <option value="">{{ $ui['field_target_placeholder'] ?? 'Pilih target pengiriman' }}</option>
                                    @foreach($announcementTargetOptions as $targetValue => $targetLabel)
                                        <option value="{{ $targetValue }}" @selected(old('target', 'all_students') === $targetValue)>{{ $targetLabel }}</option>
                                    @endforeach
                                </select>
                                <small id="kmh-pengumuman-target-note" class="form-text text-muted d-block mt-2">
                                    {{ $ui['default_student_recipient_label'] ?? 'Default semua mahasiswa: ' . $defaultStudentRecipient }}
                                </small>
                            </div>

                            <div class="col-md-6 d-none" id="kmh-pengumuman-manual-recipients-wrap">
                                <label class="form-label">{{ $ui['field_manual_recipients'] ?? 'Email Manual' }} <span class="text-danger">*</span></label>
                                <textarea
                                    id="kmh-pengumuman-manual-recipients"
                                    name="manual_recipients"
                                    class="form-control"
                                    rows="4"
                                    maxlength="4000"
                                    placeholder="{{ $ui['field_manual_recipients_placeholder'] ?? 'Pisahkan email dengan koma atau baris baru' }}"
                                >{{ old('manual_recipients') }}</textarea>
                                <small class="form-text text-muted d-block mt-2">Pisahkan beberapa alamat dengan koma atau baris baru.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ $ui['field_publish_date'] ?? 'Jadwal Publish' }}</label>
                                <input
                                    id="kmh-pengumuman-publish-at"
                                    type="datetime-local"
                                    name="publish_at"
                                    class="form-control"
                                    value="{{ old('publish_at') }}"
                                    min="{{ $minPublishAt }}"
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
                        <button id="kmh-pengumuman-submit-primary" type="submit" name="submit_action" value="publish_now" class="btn btn-primary kmh-form-action-btn" @disabled(!$hasAccounts)>{{ $ui['publish_now_button'] ?? 'Publikasikan Sekarang' }}</button>
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
            <h2>{{ $ui['queue_title'] ?? 'Antrian Email Terjadwal' }}</h2>
            <small>{{ count($scheduledEmailQueue) }} {{ $ui['queue_count_suffix'] ?? 'item menunggu kirim' }}</small>
        </header>
        <div class="kmh-card-body pt-0">
            <div class="table-responsive">
                <table class="table kmh-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Target</th>
                            <th>Jadwal Publish</th>
                            <th>Status Kirim</th>
                            <th>Kesalahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scheduledEmailQueue as $item)
                            <tr>
                                <td>{{ $item['judul'] ?? '-' }}</td>
                                <td>{{ $item['target'] ?? '-' }}</td>
                                <td>{{ !empty($item['publish_at']) ? \Carbon\Carbon::parse($item['publish_at'])->format('d M Y H:i') : '-' }}</td>
                                <td><span class="kmh-status-pill {{ $statusClass($item['status'] ?? 'Draft') }}">{{ $item['email_delivery_label'] ?? 'Menunggu Kirim' }}</span></td>
                                <td>{{ $item['email_delivery_error'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="kmh-empty-row">{{ $ui['queue_empty'] ?? 'Tidak ada antrian email terjadwal.' }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="kmh-card">
        <header class="kmh-card-head">
            <h2>{{ $ui['sent_title'] ?? 'Email Terkirim' }}</h2>
            <small>{{ count($sentEmailQueue) }} {{ $ui['sent_count_suffix'] ?? 'item sudah terkirim' }}</small>
        </header>
        <div class="kmh-card-body pt-0">
            <div class="table-responsive">
                <table class="table kmh-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Target</th>
                            <th>Waktu Kirim</th>
                            <th>Status Kirim</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sentEmailQueue as $item)
                            <tr>
                                <td>{{ $item['judul'] ?? '-' }}</td>
                                <td>{{ $item['target'] ?? '-' }}</td>
                                <td>{{ !empty($item['email_dispatched_at']) ? \Carbon\Carbon::parse($item['email_dispatched_at'])->format('d M Y H:i') : '-' }}</td>
                                <td><span class="kmh-status-pill {{ $statusClass($item['status'] ?? 'Draft') }}">{{ $item['email_delivery_label'] ?? 'Terkirim' }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="kmh-empty-row">{{ $ui['sent_empty'] ?? 'Belum ada email yang terkirim.' }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

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
                            @php
                                $itemId = (int) ($item['id'] ?? 0);
                                $itemJudul = (string) ($item['judul'] ?? '');
                                $itemRingkasan = (string) ($item['ringkasan'] ?? '');
                                $itemUkmName = (string) ($item['ukm_account_name'] ?? '-');
                                $itemOrganisasi = (string) ($item['organisasi'] ?? '-');
                                $itemStatus = (string) ($item['status'] ?? '-');
                                $itemEmailReviewStatus = (string) ($item['email_review_status'] ?? '-');
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $itemJudul }}</div>
                                    <small class="text-muted">{{ $itemRingkasan }}</small>
                                </td>
                                <td>{{ $itemUkmName }}</td>
                                <td>{{ $itemOrganisasi }}</td>
                                <td>
                                    <span class="kmh-status-pill {{ $statusClass($itemStatus) }}">{{ $itemStatus }}</span>
                                </td>
                                <td>
                                    <span class="kmh-status-pill {{ $reviewClass($itemEmailReviewStatus) }}">{{ $itemEmailReviewStatus }}</span>
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('portal.kemahasiswaan.pengumuman.email-review', ['id' => $itemId]) }}" class="kmh-inline-review">
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
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workflowPengumuman as $item)
                            @php
                                $itemId = (int) ($item['id'] ?? 0);
                                $itemJudul = (string) ($item['judul'] ?? '-');
                                $itemKategori = (string) ($item['kategori'] ?? '-');
                                $itemTarget = (string) ($item['target'] ?? '-');
                                $itemOrganisasi = (string) ($item['organisasi'] ?? '-');
                                $itemStatus = (string) ($item['status'] ?? '-');
                                $itemEmailReviewStatus = (string) ($item['email_review_status'] ?? '-');
                                $itemStatusCode = (string) ($item['status_code'] ?? '');
                                $rowSearch = \Illuminate\Support\Str::lower(implode(' ', [
                                    $itemJudul,
                                    $itemKategori,
                                    $itemTarget,
                                    $itemOrganisasi,
                                    (string) ($item['ringkasan'] ?? ''),
                                ]));
                                $canEditSchedule = $itemStatusCode === 'scheduled';
                                $canDelete = in_array($itemStatusCode, ['draft', 'scheduled'], true);
                            @endphp
                            <tr
                                data-pengumuman-row
                                data-pengumuman-search="{{ $rowSearch }}"
                                data-pengumuman-status="{{ $itemStatusCode }}"
                            >
                                <td>{{ $itemJudul }}</td>
                                <td>{{ $itemKategori }}</td>
                                <td>{{ $itemTarget }}</td>
                                <td>{{ $itemOrganisasi }}</td>
                                <td>
                                    @if(!empty($item['publish_at']))
                                        {{ \Carbon\Carbon::parse($item['publish_at'])->format('d M Y') }}
                                    @else
                                        
                                    @endif
                                </td>
                                <td>
                                    <span class="kmh-status-pill {{ $statusClass($itemStatus) }}">{{ $itemStatus }}</span>
                                </td>
                                <td>
                                    <div>
                                        <span class="kmh-status-pill {{ $reviewClass($itemEmailReviewStatus) }}">{{ $itemEmailReviewStatus }}</span>
                                    </div>
                                    @if(!empty($item['email_review_note']))
                                        <small class="text-muted d-block mt-1">{{ $item['email_review_note'] }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        @if($canEditSchedule)
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-pengumuman-edit
                                                data-pengumuman-id="{{ $itemId }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#kmh-pengumuman-modal"
                                            >
                                                Edit Jadwal
                                            </button>
                                        @endif

                                        @if($canDelete)
                                            <form method="POST" action="{{ route('portal.kemahasiswaan.pengumuman.destroy', ['id' => $itemId]) }}" onsubmit="return confirm('Hapus pengumuman ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="kmh-empty-row">{{ $ui['list_empty'] ?? '' }}</td>
                            </tr>
                        @endforelse

                        @if(count($workflowPengumuman) > 0)
                            <tr class="kmh-filter-empty-row d-none" data-pengumuman-empty>
                                <td colspan="8" class="kmh-empty-row">{{ $ui['list_filter_empty'] ?? '' }}</td>
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
    var editPayload = @json($announcementEditPayload);
    var searchInput = document.getElementById('kmh-pengumuman-search');
    var statusSelect = document.getElementById('kmh-pengumuman-status');
    var rows = Array.prototype.slice.call(document.querySelectorAll('[data-pengumuman-row]'));
    var emptyRow = document.querySelector('[data-pengumuman-empty]');
    var modalElement = document.getElementById('kmh-pengumuman-modal');
    var modalTitle = document.getElementById('kmh-pengumuman-modal-label');
    var modeInput = document.getElementById('kmh-pengumuman-mode');
    var form = modalElement ? modalElement.querySelector('form') : null;
    var editButtons = Array.prototype.slice.call(document.querySelectorAll('[data-pengumuman-edit]'));
    var createButton = document.querySelector('.kmh-pengumuman-cta');
    var fieldJudul = document.getElementById('kmh-pengumuman-judul');
    var fieldKategori = document.getElementById('kmh-pengumuman-kategori');
    var fieldTarget = document.getElementById('kmh-pengumuman-target');
    var fieldManualRecipientsWrap = document.getElementById('kmh-pengumuman-manual-recipients-wrap');
    var fieldManualRecipients = document.getElementById('kmh-pengumuman-manual-recipients');
    var fieldTargetNote = document.getElementById('kmh-pengumuman-target-note');
    var fieldKonten = document.getElementById('kmh-pengumuman-konten');
    var fieldPublishAt = document.getElementById('kmh-pengumuman-publish-at');
    var primarySubmitButton = document.getElementById('kmh-pengumuman-submit-primary');

    var storeAction = form ? form.getAttribute('action') : '';
    var updateActionTemplate = '{{ route('portal.kemahasiswaan.pengumuman.update', ['id' => '__ID__']) }}';

    if (!searchInput || !statusSelect) {
        return;
    }

    function updatePrimaryActionLabel() {
        if (!primarySubmitButton || !fieldPublishAt) {
            return;
        }

        var hasScheduleValue = String(fieldPublishAt.value || '').trim() !== '';
        primarySubmitButton.textContent = hasScheduleValue
            ? 'Simpan di Jadwal Publikasi'
            : 'Publikasikan Sekarang';
    }

    function syncTargetRecipientFields() {
        if (!fieldTarget || !fieldManualRecipientsWrap || !fieldManualRecipients) {
            return;
        }

        var isManual = String(fieldTarget.value || '') === 'manual';
        fieldManualRecipientsWrap.classList.toggle('d-none', !isManual);
        fieldManualRecipients.required = isManual;
        if (fieldTargetNote) {
            fieldTargetNote.classList.toggle('d-none', isManual);
        }
        if (!isManual) {
            fieldManualRecipients.setCustomValidity('');
        }
    }

    function validatePublishDateNotPast() {
        if (!fieldPublishAt) {
            return;
        }

        var value = String(fieldPublishAt.value || '').trim();
        if (value === '') {
            fieldPublishAt.setCustomValidity('');
            return;
        }

        var selected = new Date(value);
        var now = new Date();
        now.setSeconds(0, 0);
        if (selected.getTime() < now.getTime()) {
            fieldPublishAt.setCustomValidity('Jadwal publish tidak boleh menggunakan tanggal atau waktu yang sudah lewat.');
        } else {
            fieldPublishAt.setCustomValidity('');
        }
    }

    function resetToCreateMode() {
        if (!form) {
            return;
        }

        form.setAttribute('action', storeAction);
        form.reset();
        if (modeInput) {
            modeInput.value = 'create';
        }

        if (modalTitle) {
            modalTitle.textContent = 'Buat Pengumuman Baru';
        }

        if (fieldTarget) {
            fieldTarget.value = 'all_students';
        }
        if (fieldManualRecipients) {
            fieldManualRecipients.value = '';
        }

        syncTargetRecipientFields();
        updatePrimaryActionLabel();
    }

    function setEditMode(id) {
        if (!form) {
            return;
        }

        var payload = editPayload[String(id)] || null;
        if (!payload) {
            return;
        }

        form.setAttribute('action', updateActionTemplate.replace('__ID__', String(payload.id)));
        if (modeInput) {
            modeInput.value = 'edit';
        }

        if (modalTitle) {
            modalTitle.textContent = 'Edit Jadwal Pengumuman';
        }

        if (fieldJudul) fieldJudul.value = payload.judul || '';
        if (fieldKategori) fieldKategori.value = payload.kategori || '';
        if (fieldTarget) fieldTarget.value = payload.target_mode || payload.recipient_mode || 'all_students';
        if (fieldManualRecipients) fieldManualRecipients.value = payload.recipient_emails || '';
        if (fieldKonten) fieldKonten.value = payload.konten || '';
        if (fieldPublishAt) fieldPublishAt.value = payload.publish_at || '';

        syncTargetRecipientFields();
        validatePublishDateNotPast();
        updatePrimaryActionLabel();
    }

    function applyFilter() {
        var query = (searchInput.value || '').toLowerCase().trim();
        var status = (statusSelect.value || 'draft').toLowerCase();
        var visibleCount = 0;

        rows.forEach(function (row) {
            var rowSearch = (row.getAttribute('data-pengumuman-search') || '').toLowerCase();
            var rowStatus = (row.getAttribute('data-pengumuman-status') || '').toLowerCase();
            var textMatch = query === '' || rowSearch.indexOf(query) !== -1;
            var statusMatch = rowStatus === status;
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
    if (fieldTarget) {
        fieldTarget.addEventListener('change', function () {
            syncTargetRecipientFields();
        });
    }
    if (fieldPublishAt) {
        fieldPublishAt.addEventListener('input', function () {
            validatePublishDateNotPast();
            updatePrimaryActionLabel();
        });
        fieldPublishAt.addEventListener('change', function () {
            validatePublishDateNotPast();
            updatePrimaryActionLabel();
        });
    }

    if (form) {
        form.addEventListener('submit', function (event) {
            syncTargetRecipientFields();
            validatePublishDateNotPast();

            if (fieldTarget && String(fieldTarget.value || '') === 'manual' && fieldManualRecipients && String(fieldManualRecipients.value || '').trim() === '') {
                event.preventDefault();
                fieldManualRecipients.setCustomValidity('Silakan isi minimal satu alamat email manual.');
                fieldManualRecipients.reportValidity();
                return;
            }

            if (fieldManualRecipients) {
                fieldManualRecipients.setCustomValidity('');
            }

            if (fieldPublishAt && !fieldPublishAt.checkValidity()) {
                event.preventDefault();
                fieldPublishAt.reportValidity();
            }
        });
    }

    if (createButton) {
        createButton.addEventListener('click', function () {
            resetToCreateMode();
        });
    }

    editButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var id = button.getAttribute('data-pengumuman-id');
            if (!id) {
                return;
            }

            setEditMode(id);
        });
    });

    applyFilter();
    syncTargetRecipientFields();
    updatePrimaryActionLabel();

    @if($hasCreateAnnouncementErrors)
        if (modalElement && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalElement).show();
        }
    @endif
});
</script>
@endpush
