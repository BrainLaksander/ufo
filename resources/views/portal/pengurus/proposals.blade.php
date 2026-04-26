@extends('layouts.portal.pengurus')

@section('title', 'Pengajuan & Laporan')

@php
    $pengajuanCount = count($workflowPengajuan ?? []);
    $laporanCount = count($workflowLaporan ?? []);
    $jadwalCount = count($jadwalKegiatan ?? []);

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
@endphp

@push('styles')
<style>
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
<div class="ufo-kboard-page">
    <section class="ufo-kboard-section">
        <div class="ufo-kboard-item-head">
            <div>
                <h1 class="ufo-kboard-heading">Pengajuan &amp; Laporan Kegiatan</h1>
                <p class="ufo-kboard-lead">Ajukan izin kegiatan dan kirim laporan ke Kemahasiswaan.</p>
            </div>

            <div class="ufo-kboard-item-actions mt-0">
                <button class="ufo-kboard-btn primary" type="button" data-bs-toggle="modal" data-bs-target="#proposalReportModal" data-bs-tab="pengajuan">
                    <i class="bi bi-plus-lg"></i>
                    Ajukan Proposal Baru
                </button>
                <button class="ufo-kboard-btn gold" type="button" data-bs-toggle="modal" data-bs-target="#proposalReportModal" data-bs-tab="laporan">
                    <i class="bi bi-file-earmark-arrow-up"></i>
                    Upload Laporan Baru
                </button>
            </div>
        </div>

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

        <div class="ufo-kboard-row three mt-3">
            <article class="ufo-kboard-stat blue">
                <h3>{{ $pengajuanCount }}</h3>
                <p>Total Pengajuan</p>
            </article>
            <article class="ufo-kboard-stat green">
                <h3>{{ $laporanCount }}</h3>
                <p>Total Laporan</p>
            </article>
            <article class="ufo-kboard-stat purple">
                <h3>{{ $jadwalCount }}</h3>
                <p>Jadwal Terkait</p>
            </article>
        </div>
    </section>

    <section class="ufo-kboard-section">
        <ul class="nav nav-pills mb-3" id="proposalTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pengajuan-tab" data-bs-toggle="pill" data-bs-target="#pengajuan-pane" type="button" role="tab" aria-controls="pengajuan-pane" aria-selected="true">
                    Pengajuan Kegiatan ({{ $pengajuanCount }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="laporan-tab" data-bs-toggle="pill" data-bs-target="#laporan-pane" type="button" role="tab" aria-controls="laporan-pane" aria-selected="false">
                    Laporan Kegiatan ({{ $laporanCount }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="proposalTabsContent">
            <div class="tab-pane fade show active" id="pengajuan-pane" role="tabpanel" aria-labelledby="pengajuan-tab" tabindex="0">
                <div class="ufo-kboard-list">
                    @forelse(($workflowPengajuan ?? []) as $item)
                        @php
                            $pill = $statusToPill($item['status'] ?? '');
                            $status = (string) ($item['status'] ?? '');
                        @endphp

                        <article class="ufo-kboard-item">
                            <div class="ufo-kboard-item-head">
                                <div>
                                    <h3 class="ufo-kboard-item-title">{{ $item['judul'] }}</h3>
                                    <p class="ufo-kboard-item-meta">{{ $item['organisasi'] }} • {{ \Carbon\Carbon::parse($item['tanggal_kegiatan'])->translatedFormat('d M Y') }}</p>
                                </div>
                                <span class="ufo-kboard-pill {{ $pill }}">{{ $status }}</span>
                            </div>

                            @if(!empty($item['catatan_departemen']))
                                <div class="ufo-pg-review-note">
                                    <strong>Catatan Kemahasiswaan:</strong>
                                    <span>{{ $item['catatan_departemen'] }}</span>
                                </div>
                            @endif

                            <div class="ufo-kboard-item-actions">
                                <button class="ufo-kboard-btn ghost" type="button" data-bs-toggle="modal" data-bs-target="#proposalDetailModal" data-proposal-detail='@json($item)'>Lihat Detail</button>

                                @if(in_array($status, ['Draft', 'Revisi'], true))
                                    <form method="POST" action="{{ route('portal.pengurus.proposals.submit', ['id' => $item['id']]) }}">
                                        @csrf
                                        <button type="submit" class="ufo-kboard-btn gold">Kirim</button>
                                    </form>
                                @endif

                                @if($status === 'Disetujui')
                                    <button class="ufo-kboard-btn primary" type="button" data-bs-toggle="modal" data-bs-target="#reportCreateModal">Kirim Laporan</button>
                                @endif
                            </div>
                        </article>
                    @empty
                        <p class="ufo-kboard-item-meta">Belum ada data pengajuan kegiatan.</p>
                    @endforelse
                </div>
            </div>

            <div class="tab-pane fade" id="laporan-pane" role="tabpanel" aria-labelledby="laporan-tab" tabindex="0">
                <div class="ufo-kboard-list">
                    @forelse(($workflowLaporan ?? []) as $item)
                        @php
                            $pill = $statusToPill($item['status'] ?? '');
                            $status = (string) ($item['status'] ?? '');
                        @endphp

                        <article class="ufo-kboard-item">
                            <div class="ufo-kboard-item-head">
                                <div>
                                    <h3 class="ufo-kboard-item-title">{{ $item['judul'] }}</h3>
                                    <p class="ufo-kboard-item-meta">{{ $item['organisasi'] }} • {{ \Carbon\Carbon::parse($item['tanggal_laporan'])->translatedFormat('d M Y') }}</p>
                                </div>
                                <span class="ufo-kboard-pill {{ $pill }}">{{ $status }}</span>
                            </div>

                            @if(!empty($item['catatan_departemen']))
                                <div class="ufo-pg-review-note">
                                    <strong>Catatan Kemahasiswaan:</strong>
                                    <span>{{ $item['catatan_departemen'] }}</span>
                                </div>
                            @endif

                            <div class="ufo-kboard-item-actions">
                                <button class="ufo-kboard-btn ghost" type="button" data-bs-toggle="modal" data-bs-target="#reportDetailModal" data-report-detail='@json($item)'>Lihat Laporan</button>

                                @if(in_array($status, ['Draft', 'Revisi'], true))
                                    <form method="POST" action="{{ route('portal.pengurus.reports.submit', ['id' => $item['id']]) }}">
                                        @csrf
                                        <button type="submit" class="ufo-kboard-btn gold">Kirim</button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    @empty
                        <p class="ufo-kboard-item-meta">Belum ada data laporan kegiatan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <div class="ufo-kboard-row two">
        <section class="ufo-kboard-section">
            <div class="ufo-kboard-item-head">
                <h3 class="ufo-kboard-item-title">Jadwal Kegiatan Organisasi</h3>
                <span class="ufo-kboard-pill approved">{{ count($jadwalKegiatan ?? []) }} Jadwal</span>
            </div>

            <div class="ufo-kboard-table-wrap">
                <table class="ufo-kboard-table">
                    <thead>
                        <tr>
                            <th>Kegiatan</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($jadwalKegiatan ?? []) as $jadwal)
                            <tr>
                                <td>
                                    <strong>{{ $jadwal['judul'] }}</strong>
                                    <div class="ufo-kboard-item-meta">{{ $jadwal['organisasi'] }}</div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($jadwal['tanggal'])->translatedFormat('d M Y') }}</td>
                                <td>{{ $jadwal['lokasi'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Belum ada jadwal kegiatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="ufo-kboard-section">
            <div class="ufo-kboard-item-head">
                <h3 class="ufo-kboard-item-title">Kontak Pengurus UKM</h3>
                <span class="ufo-kboard-pill draft">{{ count($kontakPengurus ?? []) }} Kontak</span>
            </div>

            <div class="ufo-kboard-list">
                @forelse(($kontakPengurus ?? []) as $kontak)
                    <article class="ufo-kboard-item">
                        <div class="ufo-kboard-item-head">
                            <div>
                                <h4 class="ufo-kboard-item-title">{{ $kontak['nama'] }}</h4>
                                <p class="ufo-kboard-item-meta">{{ $kontak['jabatan'] }} • {{ $kontak['organisasi'] }}</p>
                            </div>
                            @php
                                $contactStatus = (string) ($kontak['status'] ?? '');
                            @endphp
                            @if($contactStatus !== '')
                                <span class="ufo-kboard-pill approved">{{ $contactStatus }}</span>
                            @endif
                        </div>

                        <p class="ufo-kboard-item-text"><i class="bi bi-telephone"></i> {{ $kontak['kontak'] }}</p>
                        <p class="ufo-kboard-item-text"><i class="bi bi-envelope"></i> {{ $kontak['email'] }}</p>
                    </article>
                @empty
                    <p class="ufo-kboard-item-meta">Belum ada kontak pengurus.</p>
                @endforelse
            </div>
        </section>
    </div>

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
