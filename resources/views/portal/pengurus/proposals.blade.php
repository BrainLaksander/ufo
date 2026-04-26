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

@section('content')
<div class="ufo-kboard-page">
    <section class="ufo-kboard-section">
        <div class="ufo-kboard-item-head">
            <div>
                <h1 class="ufo-kboard-heading">Pengajuan &amp; Laporan Kegiatan</h1>
                <p class="ufo-kboard-lead">Ajukan izin kegiatan dan kirim laporan ke Kemahasiswaan.</p>
            </div>

            <div class="ufo-kboard-item-actions mt-0">
                <button class="ufo-kboard-btn primary" type="button" data-bs-toggle="modal" data-bs-target="#modalAjukanKegiatan">
                    <i class="bi bi-plus-lg"></i>
                    Ajukan Kegiatan
                </button>
                <button class="ufo-kboard-btn gold" type="button" data-bs-toggle="modal" data-bs-target="#modalKirimLaporan">
                    <i class="bi bi-file-earmark-arrow-up"></i>
                    Kirim Laporan
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

                         <article class="ufo-kboard-item" 
                            data-detail-type="pengajuan"
                            data-detail-id="{{ $item['id'] }}"
                            data-detail-judul="{{ $item['judul'] }}"
                            data-detail-tipe="{{ $item['tipe'] }}"
                            data-detail-organisasi="{{ $item['organisasi'] }}"
                            data-detail-tanggal="{{ \Carbon\Carbon::parse($item['tanggal_kegiatan'])->translatedFormat('d M Y') }}"
                            data-detail-deskripsi="{{ $item['deskripsi'] ?? '' }}"
                            data-detail-file="{{ $item['file_path'] ?? '' }}"
                            data-detail-status="{{ $status }}"
                            data-detail-catatan="{{ $item['catatan_departemen'] ?? '' }}"
                        >
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
                                <button class="ufo-kboard-btn ghost ufo-detail-trigger" type="button" data-bs-toggle="modal" data-bs-target="#ufoDetailModal">Lihat Detail</button>

                                @if(in_array($status, ['Draft', 'Revisi'], true))
                                    <form method="POST" action="{{ route('portal.pengurus.proposals.submit', ['id' => $item['id']]) }}">
                                        @csrf
                                        <button type="submit" class="ufo-kboard-btn gold">Kirim</button>
                                    </form>
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
                        
                        <article class="ufo-kboard-item"
                            data-detail-type="laporan"
                            data-detail-id="{{ $item['id'] }}"
                            data-detail-judul="{{ $item['judul'] }}"
                            data-detail-tipe="{{ $item['tipe'] ?? 'activity' }}"
                            data-detail-organisasi="{{ $item['organisasi'] }}"
                            data-detail-tanggal="{{ \Carbon\Carbon::parse($item['tanggal_laporan'])->translatedFormat('d M Y') }}"
                            data-detail-deskripsi="{{ $item['konten'] ?? '' }}"
                            data-detail-file="{{ $item['attachment'] ?? '' }}"
                            data-detail-status="{{ $status }}"
                            data-detail-catatan="{{ $item['catatan_departemen'] ?? '' }}"
                        >
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
                                <button class="ufo-kboard-btn ghost ufo-detail-trigger" type="button" data-bs-toggle="modal" data-bs-target="#ufoDetailModal">Lihat Laporan</button>

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

    <!-- Modal Ajukan Kegiatan -->
    <div class="modal fade" id="modalAjukanKegiatan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('portal.pengurus.proposals.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Form Pengajuan Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="ufo-kboard-row two">
                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Nama Kegiatan</span>
                                <input type="text" name="title" class="ufo-kboard-field" placeholder="Masukkan nama kegiatan..." required @disabled(!($hasPengurusContext ?? false))>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Jenis Kegiatan</span>
                                <select name="type" class="ufo-kboard-select" required @disabled(!($hasPengurusContext ?? false))>
                                    <option value="proposal">Proposal Program Kerja</option>
                                    <option value="budget">Pengajuan Dana/Budget</option>
                                    <option value="activity_plan">Rencana Aktivitas</option>
                                </select>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Organisasi Penyelenggara</span>
                                <input type="text" class="ufo-kboard-field" value="{{ $pengurusOrganizationName ?? '' }}" disabled>
                            </label>

                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Deskripsi / Tujuan Kegiatan</span>
                                <textarea name="description" class="ufo-kboard-textarea" style="min-height: 120px;" placeholder="Tuliskan ringkasan tujuan dan deskripsi kegiatan..." required @disabled(!($hasPengurusContext ?? false))></textarea>
                            </label>

                            <div class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta mb-2 d-block">Lampiran Proposal (PDF)</span>
                                <input type="file" name="proposal_file" class="form-control" accept=".pdf" @disabled(!($hasPengurusContext ?? false))>
                                <small class="text-muted mt-1 d-block">Format PDF, maksimal 10MB.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="ufo-kboard-btn ghost" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="ufo-kboard-btn primary" @disabled(!($hasPengurusContext ?? false))>Ajukan Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Kirim Laporan -->
    <div class="modal fade" id="modalKirimLaporan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('portal.pengurus.reports.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Form Laporan Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="ufo-kboard-row two">
                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Judul Laporan</span>
                                <input type="text" name="title" class="ufo-kboard-field" placeholder="Masukkan judul laporan..." required @disabled(!($hasPengurusContext ?? false))>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Tipe Laporan</span>
                                <select name="report_type" class="ufo-kboard-select" required @disabled(!($hasPengurusContext ?? false))>
                                    <option value="activity">Laporan Kegiatan</option>
                                    <option value="financial">Laporan Keuangan</option>
                                    <option value="semester">Laporan Semester</option>
                                </select>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Jumlah Peserta (Realisasi)</span>
                                <input type="number" min="0" name="participants" class="ufo-kboard-field" value="0" required @disabled(!($hasPengurusContext ?? false))>
                            </label>

                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Event Terkait</span>
                                <select name="event_id" class="ufo-kboard-select" @disabled(!($hasPengurusContext ?? false))>
                                    <option value="">Tanpa event (Laporan Umum)</option>
                                    @foreach(($eventOptions ?? []) as $event)
                                        <option value="{{ $event['id'] }}">{{ $event['name'] }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Ringkasan Laporan</span>
                                <textarea name="content" class="ufo-kboard-textarea" style="min-height: 120px;" placeholder="Tuliskan ringkasan hasil kegiatan..." required @disabled(!($hasPengurusContext ?? false))></textarea>
                            </label>

                            <div class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta mb-2 d-block">Dokumentasi / Lampiran (PDF/Gambar)</span>
                                <input type="file" name="report_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" @disabled(!($hasPengurusContext ?? false))>
                                <small class="text-muted mt-1 d-block">Format PDF/JPG/PNG, maksimal 10MB.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="ufo-kboard-btn ghost" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="ufo-kboard-btn gold" @disabled(!($hasPengurusContext ?? false))>Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="ufoDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold">Detail <span id="ufo-detail-modal-type-label"></span></h5>
                    <small class="text-muted" id="ufo-detail-modal-org-label"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 id="ufo-detail-title" class="fw-bold mb-0"></h4>
                        <span id="ufo-detail-status-pill" class="ufo-kboard-pill"></span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <small class="text-muted d-block text-uppercase small fw-bold">Tipe Berkas</small>
                            <span id="ufo-detail-type" class="fw-medium"></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block text-uppercase small fw-bold">Tanggal Diajukan</small>
                            <span id="ufo-detail-date" class="fw-medium"></span>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-2">Deskripsi / Isi Berkas</h6>
                    <div id="ufo-detail-desc" class="p-3 bg-light rounded mb-4" style="white-space: pre-wrap; min-height: 100px;"></div>

                    <div id="ufo-detail-file-section">
                        <h6 class="fw-bold mb-2">Lampiran Berkas</h6>
                        <div class="p-3 border rounded d-flex align-items-center justify-content-between bg-white">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-pdf fs-3 text-danger me-3"></i>
                                <div>
                                    <span class="d-block fw-medium">Dokumen Pendukung</span>
                                    <small class="text-muted">Klik tombol di samping untuk melihat berkas</small>
                                </div>
                            </div>
                            <a id="ufo-detail-file-link" href="#" target="_blank" class="ufo-kboard-btn ghost sm">
                                <i class="bi bi-eye"></i> Lihat Berkas
                            </a>
                        </div>
                    </div>
                </div>

                <div id="ufo-detail-review-section" class="p-4 bg-primary bg-opacity-10 rounded border border-primary border-opacity-25 d-none">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-chat-left-dots-fill text-primary me-2"></i>
                        <h6 class="fw-bold mb-0 text-primary">Tanggapan Kemahasiswaan</h6>
                    </div>
                    <p id="ufo-detail-catatan" class="mb-0 text-dark" style="font-size: 0.95rem;"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="ufo-kboard-btn ghost" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var detailModalEl = document.getElementById('ufoDetailModal');
    if (detailModalEl) {
        var detailModal = new bootstrap.Modal(detailModalEl);
        
        document.querySelectorAll('.ufo-detail-trigger').forEach(function (button) {
            button.addEventListener('click', function () {
                var card = button.closest('[data-detail-type]');
                if (!card) return;

                var type = card.getAttribute('data-detail-type');
                var judul = card.getAttribute('data-detail-judul');
                var tipeBerkas = card.getAttribute('data-detail-tipe');
                var org = card.getAttribute('data-detail-organisasi');
                var tanggal = card.getAttribute('data-detail-tanggal');
                var deskripsi = card.getAttribute('data-detail-deskripsi');
                var filePath = card.getAttribute('data-detail-file');
                var status = card.getAttribute('data-detail-status');
                var catatan = card.getAttribute('data-detail-catatan');

                document.getElementById('ufo-detail-modal-type-label').textContent = type === 'pengajuan' ? 'Pengajuan' : 'Laporan';
                document.getElementById('ufo-detail-modal-org-label').textContent = org;
                document.getElementById('ufo-detail-title').textContent = judul;
                document.getElementById('ufo-detail-type').textContent = tipeBerkas || '-';
                document.getElementById('ufo-detail-date').textContent = tanggal;
                document.getElementById('ufo-detail-desc').textContent = deskripsi || '-';
                
                var statusPill = document.getElementById('ufo-detail-status-pill');
                statusPill.textContent = status;
                statusPill.className = 'ufo-kboard-pill ' + (card.querySelector('.ufo-kboard-pill').className.replace('ufo-kboard-pill', '').trim());

                var fileSection = document.getElementById('ufo-detail-file-section');
                var fileLink = document.getElementById('ufo-detail-file-link');
                if (filePath) {
                    fileLink.href = '/storage/' + filePath;
                    fileSection.classList.remove('d-none');
                } else {
                    fileSection.classList.add('d-none');
                }

                var reviewSection = document.getElementById('ufo-detail-review-section');
                var catatanEl = document.getElementById('ufo-detail-catatan');
                if (catatan) {
                    catatanEl.textContent = catatan;
                    reviewSection.classList.remove('d-none');
                } else {
                    reviewSection.classList.add('d-none');
                }

                detailModal.show();
            });
        });
    }
});
</script>
@endpush
