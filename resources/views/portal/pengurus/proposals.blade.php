@extends('layouts.portal.pengurus')

@section('title', 'Pengajuan & Laporan')

@php
    $pengajuanCount = count($workflowPengajuan ?? []);
    $laporanCount = count($workflowLaporan ?? []);

    $statusToPill = function (?string $status): string {
        $value = strtolower((string) $status);

        if (str_contains($value, 'revisi')) {
            return 'revision';
        }

        if (str_contains($value, 'tolak')) {
            return 'rejected';
        }

        if (str_contains($value, 'diajukan') || str_contains($value, 'review')) {
            return 'pending';
        }

        if (str_contains($value, 'setuju')) {
            return 'approved';
        }

        return 'draft';
    };

    $statusToIcon = function (?string $status) use ($statusToPill): string {
        return match ($statusToPill($status)) {
            'approved' => 'bi-check-circle',
            'pending' => 'bi-clock',
            'rejected' => 'bi-x-circle',
            'revision' => 'bi-exclamation-circle',
            default => 'bi-dot',
        };
    };

    $formatDate = function (?string $date): string {
        if (!$date) {
            return '-';
        }

        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Throwable) {
            return (string) $date;
        }
    };

    $formatCurrency = function ($amount): string {
        if ($amount === null || $amount === '') {
            return '-';
        }

        if (is_numeric($amount)) {
            return 'Rp ' . number_format((float) $amount, 0, ',', '.');
        }

        $stringAmount = trim((string) $amount);
        if ($stringAmount === '') {
            return '-';
        }

        return str_starts_with(strtolower($stringAmount), 'rp') ? $stringAmount : 'Rp ' . $stringAmount;
    };
@endphp

@push('styles')
<style>
    .ufo-proposal-page {
        display: grid;
        gap: 0.92rem;
    }

    .ufo-proposal-hero,
    .ufo-proposal-board {
        border-radius: 12px;
        border: 1px solid #d9dde3;
        background: #f4f5f7;
        box-shadow: 0 1px 2px rgba(24, 39, 75, 0.06);
    }

    .ufo-proposal-hero {
        padding: 1.05rem 1.15rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .ufo-proposal-hero-icon {
        width: 46px;
        height: 46px;
        border-radius: 8px;
        background: #ff5f00;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .ufo-proposal-hero h1 {
        margin: 0;
        font-size: 1.95rem;
        line-height: 1.1;
        color: #3f2469;
        font-weight: 600;
    }

    .ufo-proposal-hero p {
        margin: 0.22rem 0 0;
        color: #5f6b7a;
        font-size: 0.89rem;
    }

    .ufo-proposal-board {
        overflow: hidden;
    }

    .ufo-proposal-tabs {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        border-bottom: 1px solid #d9dde3;
        margin: 0;
        padding: 0;
        background: #f7f7f8;
    }

    .ufo-proposal-tabs .nav-item {
        margin: 0;
        display: flex;
    }

    .ufo-proposal-tabs .nav-link {
        width: 100%;
        border: 0;
        border-bottom: 2px solid transparent;
        border-radius: 0;
        margin: 0;
        background: transparent;
        color: #4e5c6f;
        padding: 0.86rem 1rem;
        font-size: 0.99rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.38rem;
    }

    .ufo-proposal-tabs .nav-link.active {
        color: #3d2167;
        background: #f7f7f8;
        border-bottom-color: #3d2167;
    }

    .ufo-proposal-count {
        min-width: 18px;
        height: 18px;
        border-radius: 999px;
        font-size: 0.71rem;
        font-weight: 700;
        color: #8e6a00;
        background: #f8e8a8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        padding: 0 0.28rem;
    }

    .ufo-proposal-pane {
        padding: 1rem;
    }

    .ufo-proposal-pane-head {
        margin-bottom: 0.92rem;
    }

    .ufo-proposal-btn {
        border: 0;
        border-radius: 6px;
        background: #4b2a72;
        color: #fff;
        font-size: 0.91rem;
        font-weight: 500;
        padding: 0.52rem 0.82rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .ufo-proposal-btn:hover {
        background: #3f2360;
    }

    .ufo-proposal-list {
        display: grid;
        gap: 0.72rem;
    }

    .ufo-proposal-card {
        border: 1px solid #e0e4ea;
        border-radius: 8px;
        background: #f5f6f8;
        padding: 0.78rem 0.72rem;
    }

    .ufo-proposal-card-shell {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 0.7rem;
    }

    .ufo-proposal-card-head {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.48rem;
        margin-bottom: 0.55rem;
    }

    .ufo-proposal-card-title {
        margin: 0;
        color: #38245c;
        font-size: 1.08rem;
        font-weight: 500;
    }

    .ufo-proposal-status {
        border-radius: 999px;
        border: 1px solid transparent;
        padding: 0.18rem 0.55rem;
        font-size: 0.77rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.26rem;
        line-height: 1;
    }

    .ufo-proposal-status.pending {
        color: #8a6100;
        background: #fff1c2;
        border-color: #eed27f;
    }

    .ufo-proposal-status.approved {
        color: #0f7d3f;
        background: #ccf3da;
        border-color: #94dfb3;
    }

    .ufo-proposal-status.rejected,
    .ufo-proposal-status.revision {
        color: #ac2e2e;
        background: #ffd8d8;
        border-color: #f2b0b0;
    }

    .ufo-proposal-status.draft {
        color: #2c5fa3;
        background: #e5efff;
        border-color: #b5cef5;
    }

    .ufo-proposal-meta-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.5rem;
    }

    .ufo-proposal-meta {
        margin: 0;
        font-size: 0.91rem;
        color: #5a6777;
    }

    .ufo-proposal-feedback {
        margin-top: 0.58rem;
        border: 1px solid #bbd4ff;
        background: #e4efff;
        color: #2456a3;
        border-radius: 6px;
        padding: 0.5rem 0.66rem;
        font-size: 0.88rem;
    }

    .ufo-proposal-card-actions {
        display: inline-flex;
        gap: 0.56rem;
        align-items: flex-start;
        padding-top: 0.35rem;
    }

    .ufo-proposal-icon-action {
        width: 28px;
        height: 28px;
        border: 0;
        padding: 0;
        background: transparent;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        text-decoration: none;
    }

    .ufo-proposal-icon-action.view {
        color: #2a64df;
    }

    .ufo-proposal-icon-action.download {
        color: #159b43;
    }

    .ufo-proposal-icon-action.disabled {
        color: #9ba7b7;
        cursor: not-allowed;
    }

    .ufo-proposal-empty {
        margin: 0;
        color: #687686;
        font-size: 0.9rem;
        padding: 0.4rem 0;
    }

    @media (max-width: 992px) {
        .ufo-proposal-meta-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 768px) {
        .ufo-proposal-hero h1 {
            font-size: 1.45rem;
        }

        .ufo-proposal-card-shell {
            grid-template-columns: 1fr;
        }

        .ufo-proposal-card-actions {
            justify-content: flex-end;
            padding-top: 0;
        }

        .ufo-proposal-meta-grid {
            grid-template-columns: 1fr;
            gap: 0.28rem;
        }
    }

    /* Modal Tab Styling */
    #proposalReportModal .nav-tabs {
        border-bottom: 2px solid #e9ecef;
    }

    #proposalReportModal .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 3px solid transparent;
        font-weight: 500;
        transition: all 0.3s ease;
        padding-bottom: 12px;
    }

    #proposalReportModal .nav-tabs .nav-link:hover {
        color: #495057;
        border-bottom-color: #dee2e6;
    }

    #proposalReportModal .nav-tabs .nav-link.active {
        color: #312e81;
        border-bottom-color: #312e81;
        background-color: transparent;
    }

    #proposalReportModal .tab-content {
        padding-top: 0;
    }

    #proposalReportModal .modal-body {
        padding-top: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="ufo-proposal-page">
    <section class="ufo-proposal-hero">
        <span class="ufo-proposal-hero-icon"><i class="bi bi-file-earmark-text"></i></span>
        <div>
            <h1>Pengajuan &amp; Laporan</h1>
            <p>Kelola proposal dan laporan kegiatan</p>
        </div>
    </section>

        @if(session('success'))
            <div class="alert alert-success mt-3 mb-0" role="alert">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mt-3 mb-0" role="alert">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mt-3 mb-0" role="alert">{{ $errors->first() }}</div>
        @endif

        @if(!($hasPengurusContext ?? false))
            <div class="alert alert-warning mt-3 mb-0" role="alert">
                Akun pengurus belum terhubung ke organisasi. Form pengajuan dan laporan dinonaktifkan.
            </div>
        @endif

    <section class="ufo-proposal-board">
        <ul class="nav ufo-proposal-tabs" id="proposalTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pengajuan-tab" data-bs-toggle="pill" data-bs-target="#pengajuan-pane" type="button" role="tab" aria-controls="pengajuan-pane" aria-selected="true">
                    <i class="bi bi-file-earmark-text"></i> Proposal Kegiatan <span class="ufo-proposal-count">{{ $pengajuanCount }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="laporan-tab" data-bs-toggle="pill" data-bs-target="#laporan-pane" type="button" role="tab" aria-controls="laporan-pane" aria-selected="false">
                    <i class="bi bi-upload"></i> Laporan Kegiatan <span class="ufo-proposal-count">{{ $laporanCount }}</span>
                </button>
            </li>
        </ul>

        <div class="tab-content" id="proposalTabsContent">
            <div class="tab-pane fade show active" id="pengajuan-pane" role="tabpanel" aria-labelledby="pengajuan-tab" tabindex="0">
                <div class="ufo-proposal-pane">
                    <div class="ufo-proposal-pane-head">
                        <button class="ufo-proposal-btn" type="button" data-bs-toggle="modal" data-bs-target="#proposalReportModal" data-bs-tab="pengajuan">
                            <i class="bi bi-plus-lg"></i>
                            Ajukan Proposal Baru
                        </button>
                    </div>

                    <div class="ufo-proposal-list">
                    @forelse(($workflowPengajuan ?? []) as $item)
                        @php
                            $pill = $statusToPill($item['status'] ?? '');
                            $status = (string) ($item['status'] ?? '');
                            $submittedDate = $formatDate($item['tanggal_kegiatan'] ?? null);
                            $budgetLabel = $formatCurrency($item['budget'] ?? $item['anggaran'] ?? null);
                            $fileName = (string) ($item['file_name'] ?? $item['lampiran'] ?? '-');
                            $downloadUrl = (string) ($item['download_url'] ?? $item['file_url'] ?? $item['file'] ?? '');
                        @endphp

                        <article class="ufo-proposal-card">
                            <div class="ufo-proposal-card-shell">
                                <div>
                                    <div class="ufo-proposal-card-head">
                                        <h3 class="ufo-proposal-card-title">{{ $item['judul'] }}</h3>
                                        <span class="ufo-proposal-status {{ $pill }}"><i class="bi {{ $statusToIcon($status) }}"></i> {{ $status }}</span>
                                    </div>

                                    <div class="ufo-proposal-meta-grid">
                                        <p class="ufo-proposal-meta">Diajukan: {{ $submittedDate }}</p>
                                        <p class="ufo-proposal-meta">Budget: {{ $budgetLabel }}</p>
                                        <p class="ufo-proposal-meta">File: {{ $fileName !== '' ? $fileName : '-' }}</p>
                                    </div>

                                    @if(!empty($item['catatan_departemen']))
                                        <p class="ufo-proposal-feedback">Feedback: {{ $item['catatan_departemen'] }}</p>
                                    @endif
                                </div>

                                <div class="ufo-proposal-card-actions">
                                    <button class="ufo-proposal-icon-action view" type="button" data-bs-toggle="modal" data-bs-target="#proposalDetailModal" data-proposal-detail='@json($item)' title="Lihat detail">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    @if($downloadUrl !== '')
                                        <a class="ufo-proposal-icon-action download" href="{{ $downloadUrl }}" target="_blank" rel="noopener" title="Unduh berkas">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    @else
                                        <span class="ufo-proposal-icon-action disabled" title="Berkas tidak tersedia">
                                            <i class="bi bi-download"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="ufo-proposal-empty">Belum ada data proposal kegiatan.</p>
                    @endforelse
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="laporan-pane" role="tabpanel" aria-labelledby="laporan-tab" tabindex="0">
                <div class="ufo-proposal-pane">
                    <div class="ufo-proposal-pane-head">
                        <button class="ufo-proposal-btn" type="button" data-bs-toggle="modal" data-bs-target="#proposalReportModal" data-bs-tab="laporan">
                            <i class="bi bi-plus-lg"></i>
                            Upload Laporan Baru
                        </button>
                    </div>

                    <div class="ufo-proposal-list">
                    @forelse(($workflowLaporan ?? []) as $item)
                        @php
                            $pill = $statusToPill($item['status'] ?? '');
                            $status = (string) ($item['status'] ?? '');
                            $eventDate = $formatDate($item['tanggal_event'] ?? $item['tanggal_kegiatan'] ?? null);
                            $submitDate = $formatDate($item['tanggal_laporan'] ?? null);
                            $realisasiBudget = $formatCurrency($item['realisasi_budget'] ?? $item['budget_realisasi'] ?? null);
                            $downloadUrl = (string) ($item['download_url'] ?? $item['file_url'] ?? $item['file'] ?? '');
                        @endphp

                        <article class="ufo-proposal-card">
                            <div class="ufo-proposal-card-shell">
                                <div>
                                    <div class="ufo-proposal-card-head">
                                        <h3 class="ufo-proposal-card-title">{{ $item['judul'] }}</h3>
                                        <span class="ufo-proposal-status {{ $pill }}"><i class="bi {{ $statusToIcon($status) }}"></i> {{ $status }}</span>
                                    </div>

                                    <div class="ufo-proposal-meta-grid">
                                        <p class="ufo-proposal-meta">Tanggal Event: {{ $eventDate }}</p>
                                        <p class="ufo-proposal-meta">Disubmit: {{ $submitDate }}</p>
                                        <p class="ufo-proposal-meta">Realisasi Budget: {{ $realisasiBudget }}</p>
                                    </div>

                                    @if(!empty($item['catatan_departemen']))
                                        <p class="ufo-proposal-feedback">Feedback: {{ $item['catatan_departemen'] }}</p>
                                    @endif
                                </div>

                                <div class="ufo-proposal-card-actions">
                                    <button class="ufo-proposal-icon-action view" type="button" data-bs-toggle="modal" data-bs-target="#reportDetailModal" data-report-detail='@json($item)' title="Lihat detail">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    @if($downloadUrl !== '')
                                        <a class="ufo-proposal-icon-action download" href="{{ $downloadUrl }}" target="_blank" rel="noopener" title="Unduh berkas">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    @else
                                        <span class="ufo-proposal-icon-action disabled" title="Berkas tidak tersedia">
                                            <i class="bi bi-download"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="ufo-proposal-empty">Belum ada data laporan kegiatan.</p>
                    @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Combined Proposal & Report Modal with Tabs -->
    <div class="modal fade" id="proposalReportModal" tabindex="-1" aria-labelledby="proposalReportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <div>
                        <h5 class="modal-title" id="proposalReportModalLabel">Ajukan Proposal Baru</h5>
                        <small class="text-muted">Kirim izin kegiatan ke Kemahasiswaan.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs ps-3 pe-3 pt-2" id="proposalReportTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pengajuan-form-tab" data-bs-toggle="tab" data-bs-target="#pengajuan-form-pane" type="button" role="tab" aria-controls="pengajuan-form-pane" aria-selected="true">
                            Ajukan Proposal
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="laporan-form-tab" data-bs-toggle="tab" data-bs-target="#laporan-form-pane" type="button" role="tab" aria-controls="laporan-form-pane" aria-selected="false">
                            Upload Laporan
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="proposalReportTabContent">
                    <!-- Pengajuan Tab -->
                    <div class="tab-pane fade show active" id="pengajuan-form-pane" role="tabpanel" aria-labelledby="pengajuan-form-tab">
                        <form method="POST" action="{{ route('portal.pengurus.proposals.store') }}" id="proposalForm">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Judul Kegiatan <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" maxlength="180" placeholder="Contoh: Workshop Web Development 2024" required @disabled(!($hasPengurusContext ?? false))>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                                        <select name="type" class="form-select" required @disabled(!($hasPengurusContext ?? false))>
                                            <option value="proposal">Proposal</option>
                                            <option value="budget">Budget</option>
                                            <option value="activity_plan">Activity Plan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Penanggung Jawab</label>
                                        <input type="text" class="form-control" value="{{ $pengurusOrganizationName ?? '' }}" disabled>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Deskripsi Kegiatan <span class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control" rows="5" maxlength="3000" placeholder="Jelaskan tujuan, lokasi, dan manfaat kegiatan" required @disabled(!($hasPengurusContext ?? false))>{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <div class="alert alert-info mt-3 mb-0" role="alert">
                                    Pastikan informasi lengkap sebelum mengajukan agar proses review lebih cepat.
                                </div>
                            </div>
                            <div class="modal-footer border-top-0 pt-0 mt-3">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="ufo-kboard-btn primary" @disabled(!($hasPengurusContext ?? false))>Submit</button>
                            </div>
                        </form>
                    </div>

                    <!-- Laporan Tab -->
                    <div class="tab-pane fade" id="laporan-form-pane" role="tabpanel" aria-labelledby="laporan-form-tab">
                        <form method="POST" action="{{ route('portal.pengurus.reports.store') }}" id="reportForm">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Judul Laporan <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" maxlength="180" placeholder="Contoh: LPJ Workshop Web Development" required @disabled(!($hasPengurusContext ?? false))>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tipe Laporan <span class="text-danger">*</span></label>
                                        <select name="report_type" class="form-select" required @disabled(!($hasPengurusContext ?? false))>
                                            <option value="activity">Activity</option>
                                            <option value="financial">Financial</option>
                                            <option value="semester">Semester</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Jumlah Peserta <span class="text-danger">*</span></label>
                                        <input type="number" min="0" name="participants" class="form-control" value="0" required @disabled(!($hasPengurusContext ?? false))>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Event Terkait (Opsional)</label>
                                        <select name="event_id" class="form-select" @disabled(!($hasPengurusContext ?? false))>
                                            <option value="">Tanpa event</option>
                                            @foreach(($eventOptions ?? []) as $event)
                                                <option value="{{ $event['id'] }}">{{ $event['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Ringkasan Laporan <span class="text-danger">*</span></label>
                                        <textarea name="content" class="form-control" rows="5" maxlength="5000" placeholder="Tuliskan ringkasan hasil kegiatan" required @disabled(!($hasPengurusContext ?? false))>{{ old('content') }}</textarea>
                                    </div>
                                </div>

                                <div class="alert alert-warning mt-3 mb-0" role="alert">
                                    Laporan dikirim maksimal 7 hari setelah kegiatan selesai.
                                </div>
                            </div>
                            <div class="modal-footer border-top-0 pt-0 mt-3">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="ufo-kboard-btn gold" @disabled(!($hasPengurusContext ?? false))>Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="proposalDetailModal" tabindex="-1" aria-labelledby="proposalDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="proposalDetailModalLabel">Detail Pengajuan</h5>
                        <small class="text-muted">Ringkasan proposal yang dipilih.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" id="proposalDetailModalBody"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reportDetailModal" tabindex="-1" aria-labelledby="reportDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="reportDetailModalLabel">Detail Laporan</h5>
                        <small class="text-muted">Ringkasan laporan yang dipilih.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" id="reportDetailModalBody"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var proposalModal = document.getElementById('proposalDetailModal');
    var reportModal = document.getElementById('reportDetailModal');
    var proposalReportModal = document.getElementById('proposalReportModal');

    function renderDetail(targetBody, data, isReport) {
        if (!targetBody) {
            return;
        }

        var title = data.judul || data.title || '-';
        var org = data.organisasi || data.organization || '-';
        var date = data.tanggal_kegiatan || data.tanggal_laporan || data.date || '-';
        var status = data.status || '-';
        var note = data.catatan_departemen || data.department_review_note || '-';
        var extra = isReport ? (data.tipe_laporan || data.report_type || '-') : (data.type || '-');

        targetBody.innerHTML = ''
            + '<dl class="row mb-0">'
            + '<dt class="col-sm-4">Judul</dt><dd class="col-sm-8">' + title + '</dd>'
            + '<dt class="col-sm-4">Organisasi</dt><dd class="col-sm-8">' + org + '</dd>'
            + '<dt class="col-sm-4">Tanggal</dt><dd class="col-sm-8">' + date + '</dd>'
            + '<dt class="col-sm-4">Status</dt><dd class="col-sm-8">' + status + '</dd>'
            + '<dt class="col-sm-4">Keterangan</dt><dd class="col-sm-8">' + (note || '-') + '</dd>'
            + '<dt class="col-sm-4">' + (isReport ? 'Tipe Laporan' : 'Jenis Proposal') + '</dt><dd class="col-sm-8">' + extra + '</dd>'
            + '</dl>';
    }

    if (proposalModal) {
        proposalModal.addEventListener('show.bs.modal', function (event) {
            var trigger = event.relatedTarget;
            var data = {};

            try {
                data = trigger && trigger.getAttribute('data-proposal-detail') ? JSON.parse(trigger.getAttribute('data-proposal-detail')) : {};
            } catch (error) {
                data = {};
            }

            renderDetail(document.getElementById('proposalDetailModalBody'), data, false);
        });
    }

    if (reportModal) {
        reportModal.addEventListener('show.bs.modal', function (event) {
            var trigger = event.relatedTarget;
            var data = {};

            try {
                data = trigger && trigger.getAttribute('data-report-detail') ? JSON.parse(trigger.getAttribute('data-report-detail')) : {};
            } catch (error) {
                data = {};
            }

            renderDetail(document.getElementById('reportDetailModalBody'), data, true);
        });
    }

    // Handle Proposal/Report Modal Tab Switching
    if (proposalReportModal) {
        proposalReportModal.addEventListener('show.bs.modal', function (event) {
            var trigger = event.relatedTarget;
            var tab = trigger ? trigger.getAttribute('data-bs-tab') : 'pengajuan';

            // Reset forms when modal opens
            var proposalForm = document.getElementById('proposalForm');
            var reportForm = document.getElementById('reportForm');
            if (proposalForm) proposalForm.reset();
            if (reportForm) reportForm.reset();

            // Switch to appropriate tab
            if (tab === 'laporan') {
                var laporanTab = document.getElementById('laporan-form-tab');
                if (laporanTab) {
                    var tabInstance = new bootstrap.Tab(laporanTab);
                    tabInstance.show();
                    updateModalTitle('Upload Laporan Baru', 'Kirim LPJ kegiatan yang sudah selesai.');
                }
            } else {
                var pengajuanTab = document.getElementById('pengajuan-form-tab');
                if (pengajuanTab) {
                    var tabInstance = new bootstrap.Tab(pengajuanTab);
                    tabInstance.show();
                    updateModalTitle('Ajukan Proposal Baru', 'Kirim izin kegiatan ke Kemahasiswaan.');
                }
            }
        });

        // Update title when tabs are clicked
        var pengajuanFormTab = document.getElementById('pengajuan-form-tab');
        var laporanFormTab = document.getElementById('laporan-form-tab');

        if (pengajuanFormTab) {
            pengajuanFormTab.addEventListener('shown.bs.tab', function () {
                updateModalTitle('Ajukan Proposal Baru', 'Kirim izin kegiatan ke Kemahasiswaan.');
            });
        }

        if (laporanFormTab) {
            laporanFormTab.addEventListener('shown.bs.tab', function () {
                updateModalTitle('Upload Laporan Baru', 'Kirim LPJ kegiatan yang sudah selesai.');
            });
        }

        function updateModalTitle(title, subtitle) {
            var modalTitle = document.getElementById('proposalReportModalLabel');
            var modalSubtitle = document.querySelector('#proposalReportModal .modal-header small');
            if (modalTitle) modalTitle.textContent = title;
            if (modalSubtitle) modalSubtitle.textContent = subtitle;
        }
    }
});
</script>
@endpush
