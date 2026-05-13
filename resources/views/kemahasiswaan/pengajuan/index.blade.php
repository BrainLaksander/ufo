@extends('layouts.app')

@section('title', 'Manajemen Pengajuan Kegiatan & Laporan - Kemahasiswaan')

@section('content')
@push('head')
<link rel="stylesheet" href="/css/kemahasiswaan-compact.css">
@endpush
<div class="submission-page">
    <div class="submission-container">
        <section class="submission-tabs-container">
            <a class="submission-tab {{ $tab === 'kegiatan' ? 'active' : '' }}" href="{{ route('kemahasiswaan.submissions.index', ['tab' => 'kegiatan', 'q' => $q, 'status' => $status]) }}">
                Pengajuan Kegiatan
            </a>
            <a class="submission-tab {{ $tab === 'event' ? 'active' : '' }}" href="{{ route('kemahasiswaan.submissions.index', ['tab' => 'event', 'q' => $q, 'status' => $status]) }}">
                Manajemen Event
            </a>
            <a class="submission-tab {{ $tab === 'report' ? 'active' : '' }}" href="{{ route('kemahasiswaan.submissions.index', ['tab' => 'report', 'q' => $q, 'status' => $status]) }}">
                Laporan Kegiatan (LPJ)
            </a>
        </section>

        <form class="submission-filters" method="GET" action="{{ route('kemahasiswaan.submissions.index') }}">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="submission-search-wrap">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path></svg>
                <input type="search" name="q" value="{{ $q }}" placeholder="Cari kegiatan atau organisasi..." aria-label="Cari kegiatan atau organisasi">
            </div>
            <div class="submission-status-wrap">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"></path></svg>
                <select name="status" aria-label="Semua status" onchange="this.form.submit()">
                    <option value="" @selected($status === '')>Semua Status</option>
                    <option value="diajukan" @selected($status === 'diajukan')>Diajukan</option>
                    <option value="revisi" @selected($status === 'revisi')>Revisi</option>
                    <option value="approved" @selected($status === 'approved')>Disetujui</option>
                    <option value="rejected" @selected($status === 'rejected')>Ditolak</option>
                </select>
            </div>
        </form>

        <div class="submission-table-wrapper">
            <table class="submission-table">
                <thead>
                    <tr>
                        <th>Judul Kegiatan</th>
                        <th>Organisasi</th>
                        <th>Diajukan Oleh</th>
                        <th>Tanggal Kegiatan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>
                                <div class="td-title">{{ $item['title'] }}</div>
                                @if($item['jenis_kegiatan'])
                                    <div class="td-subtitle">{{ $item['jenis_kegiatan'] }}</div>
                                @elseif($item['subtitle'])
                                    <div class="td-subtitle">{{ $item['subtitle'] }}</div>
                                @endif
                            </td>
                            <td>{{ $item['organization'] ?? '-' }}</td>
                            <td>
                                <div style="font-size: 14px; font-weight: 600; color: #1e293b;">{{ $item['submitted_by'] ?? '-' }}</div>
                                @if($item['submitted_email'])
                                    <div style="font-size: 12px; color: #94a3b8;">{{ $item['submitted_email'] }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="td-date">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    {{ $item['event_date'] ? \Illuminate\Support\Carbon::parse($item['event_date'])->translatedFormat('d F Y') : '-' }}
                                </div>
                            </td>
                            <td>
                                <span class="submission-badge {{ $item['status'] }}">{{ $item['status_label'] }}</span>
                            </td>
                            <td>
                                <div class="td-actions">
                                    <button type="button" class="action-btn view" title="Lihat detail" onclick="openKemahasiswaanModal(this)" data-item="{{ json_encode($item) }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </button>
                                    <form id="review-form-{{ $item['id'] }}" method="POST" action="{{ route('kemahasiswaan.submissions.review', ['id' => $item['id']]) }}" style="display:inline">
                                        @csrf
                                        <input type="hidden" name="action" value="approved">
                                    </form>
                                    <form id="review-form-revisi-{{ $item['id'] }}" method="POST" action="{{ route('kemahasiswaan.submissions.review', ['id' => $item['id']]) }}" style="display:inline">
                                        @csrf
                                        <input type="hidden" name="action" value="revisi">
                                        <input type="hidden" name="revision_note" id="revision-note-{{ $item['id'] }}" value="">
                                    </form>
                                    <form id="review-form-reject-{{ $item['id'] }}" method="POST" action="{{ route('kemahasiswaan.submissions.review', ['id' => $item['id']]) }}" style="display:inline">
                                        @csrf
                                        <input type="hidden" name="action" value="rejected">
                                        <input type="hidden" name="revision_note" id="reject-note-{{ $item['id'] }}" value="">
                                    </form>

                                    <button type="button" class="action-btn accept" title="Setujui" onclick="if(confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')) document.getElementById('review-form-{{ $item['id'] }}').submit()">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </button>
                                    <button type="button" class="action-btn revise" title="Revisi" onclick="promptRevision({{ $item['id'] }})">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    <button type="button" class="action-btn reject" title="Tolak" onclick="promptReject({{ $item['id'] }})">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="submission-empty">Belum ada data untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal-overlay" id="submission-modal">
    <div class="modal-content" style="max-width: 680px; padding: 0;">
        <div class="modal-header">
            <h2 id="modal-title">Detail Pengajuan</h2>
            <button type="button" class="modal-close" onclick="closeKemahasiswaanModal()">&times;</button>
        </div>
        <div class="modal-body" style="padding: 24px; max-height: 70vh; overflow-y: auto;">
            {{-- Submitter Info --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #6f3ba7, #a476d1); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: 18px; flex-shrink: 0;" id="modal-avatar">U</div>
                <div>
                    <div style="font-weight: 700; font-size: 15px; color: #1e293b;" id="modal-submitter">-</div>
                    <div style="font-size: 13px; color: #64748b;" id="modal-submitter-email">-</div>
                    <div style="font-size: 12px; color: #94a3b8; margin-top: 2px;" id="modal-submitted-at">-</div>
                </div>
                <div style="margin-left: auto;">
                    <span class="submission-badge" id="modal-status-badge" style="font-size: 13px;">-</span>
                </div>
            </div>

            {{-- Event Title --}}
            <h3 id="modal-event-title" style="margin: 0 0 4px; font-size: 22px; font-weight: 800; color: #111827;"></h3>
            <p id="modal-org" style="color: #64748b; margin: 0 0 20px; font-size: 14px;"></p>

            {{-- Info Grid --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;" id="modal-info-grid">
                <div id="modal-jenis-wrap" style="display: none;">
                    <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Jenis Kegiatan</div>
                    <div style="font-size: 15px; font-weight: 600; color: #1e293b;" id="modal-jenis">-</div>
                </div>
                <div id="modal-pj-wrap" style="display: none;">
                    <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Penanggung Jawab</div>
                    <div style="font-size: 15px; font-weight: 600; color: #1e293b;" id="modal-pj">-</div>
                </div>
                <div>
                    <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Tanggal Kegiatan</div>
                    <div style="font-size: 15px; font-weight: 600; color: #1e293b;" id="modal-date">-</div>
                </div>
                <div id="modal-waktu-wrap" style="display: none;">
                    <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Waktu</div>
                    <div style="font-size: 15px; font-weight: 600; color: #1e293b;" id="modal-waktu">-</div>
                </div>
                <div id="modal-lokasi-wrap" style="display: none;">
                    <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Lokasi</div>
                    <div style="font-size: 15px; font-weight: 600; color: #1e293b;" id="modal-lokasi">-</div>
                </div>
                <div id="modal-peserta-wrap" style="display: none;">
                    <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Estimasi Peserta</div>
                    <div style="font-size: 15px; font-weight: 600; color: #1e293b;" id="modal-peserta">-</div>
                </div>
            </div>

            {{-- Description --}}
            <div style="margin-bottom: 20px;">
                <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Deskripsi</div>
                <div id="modal-desc" style="color: #374151; font-size: 15px; line-height: 1.7; white-space: pre-wrap; background: #f9fafb; border: 1px solid #f3f4f6; border-radius: 10px; padding: 16px;">-</div>
            </div>

            {{-- LPJ Catatan --}}
            <div id="modal-catatan-wrap" style="display: none; margin-bottom: 20px;">
                <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Catatan Tambahan (LPJ)</div>
                <div id="modal-catatan" style="color: #374151; font-size: 15px; line-height: 1.7; white-space: pre-wrap; background: #fefce8; border: 1px solid #fef08a; border-radius: 10px; padding: 16px;">-</div>
            </div>

            {{-- Documents --}}
            <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px;" id="modal-docs">
                <a id="modal-proposal-link" href="#" target="_blank" style="display: none; align-items: center; gap: 8px; background: #ede9fe; color: #6f3ba7; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; text-decoration: none; transition: background .2s;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    📄 Lihat Proposal (PDF)
                </a>
                <a id="modal-lpj-link" href="#" target="_blank" style="display: none; align-items: center; gap: 8px; background: #fef3c7; color: #92400e; padding: 12px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; text-decoration: none; transition: background .2s;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    📄 Lihat Laporan LPJ (PDF)
                </a>
            </div>

            {{-- Revision Note --}}
            <div id="modal-revision-wrap" style="display: none; margin-bottom: 20px;">
                <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Catatan Revisi</div>
                <div id="modal-revision-note" style="color: #374151; font-size: 15px; line-height: 1.7; white-space: pre-wrap; background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 16px;">-</div>
            </div>

            {{-- Registration link --}}
            <div id="modal-link-container" style="margin-top: 12px; display: none;">
                <div style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Link Pendaftaran</div>
                <a id="modal-link" href="#" target="_blank" style="color: #3b82f6; font-weight: 600; font-size: 14px;">-</a>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop" id="submission-backdrop" onclick="closeKemahasiswaanModal()"></div>

@push('scripts')
<script>
function openKemahasiswaanModal(btn) {
    var item = JSON.parse(btn.getAttribute('data-item'));

    // Submitter info
    var submitter = item.submitted_by || 'Unknown';
    document.getElementById('modal-submitter').textContent = submitter;
    document.getElementById('modal-submitter-email').textContent = item.submitted_email || '-';
    document.getElementById('modal-avatar').textContent = submitter.charAt(0).toUpperCase();
    document.getElementById('modal-submitted-at').textContent = item.created_at ? 'Diajukan: ' + item.created_at : '';

    // Status badge
    var badge = document.getElementById('modal-status-badge');
    badge.textContent = item.status_label;
    badge.className = 'submission-badge ' + item.status;

    // Title & org
    document.getElementById('modal-event-title').textContent = item.title;
    document.getElementById('modal-org').textContent = 'Organisasi: ' + (item.organization || '-');

    // Info grid
    function showField(wrapId, valId, val) {
        var w = document.getElementById(wrapId);
        if (val) { w.style.display = 'block'; document.getElementById(valId).textContent = val; }
        else { w.style.display = 'none'; }
    }
    showField('modal-jenis-wrap', 'modal-jenis', item.jenis_kegiatan);
    showField('modal-pj-wrap', 'modal-pj', item.penanggung_jawab);
    showField('modal-waktu-wrap', 'modal-waktu', item.waktu);
    showField('modal-lokasi-wrap', 'modal-lokasi', item.lokasi);
    showField('modal-peserta-wrap', 'modal-peserta', item.estimasi_peserta);

    document.getElementById('modal-date').textContent = item.event_date || '-';
    document.getElementById('modal-desc').textContent = item.description || 'Tidak ada deskripsi.';

    // LPJ catatan
    var catatanWrap = document.getElementById('modal-catatan-wrap');
    if (item.lpj_catatan) {
        catatanWrap.style.display = 'block';
        document.getElementById('modal-catatan').textContent = item.lpj_catatan;
    } else {
        catatanWrap.style.display = 'none';
    }

    // Documents
    var proposalLink = document.getElementById('modal-proposal-link');
    if (item.proposal_path) {
        proposalLink.href = '/storage/' + item.proposal_path;
        proposalLink.style.display = 'inline-flex';
    } else {
        proposalLink.style.display = 'none';
    }

    var lpjLink = document.getElementById('modal-lpj-link');
    if (item.lpj_path) {
        lpjLink.href = '/storage/' + item.lpj_path;
        lpjLink.style.display = 'inline-flex';
    } else {
        lpjLink.style.display = 'none';
    }

    // Registration link
    var link = document.getElementById('modal-link');
    var linkContainer = document.getElementById('modal-link-container');
    if (item.registration_link) {
        link.href = item.registration_link;
        link.textContent = item.registration_link;
        linkContainer.style.display = 'block';
    } else {
        linkContainer.style.display = 'none';
    }

    // Revision note in modal
    var revisionWrap = document.getElementById('modal-revision-wrap');
    if (item.revision_note) {
        revisionWrap.style.display = 'block';
        document.getElementById('modal-revision-note').textContent = item.revision_note;
    } else {
        revisionWrap.style.display = 'none';
    }

    document.getElementById('submission-modal').classList.add('open');
}

var _actionId = null;
var _actionType = null;

function promptRevision(id) {
    _actionId = id;
    _actionType = 'revisi';
    document.getElementById('noteModalTitle').textContent = 'Catatan Revisi';
    document.getElementById('noteModalSubtitle').textContent = 'Masukkan catatan revisi untuk pengajuan ini.';
    document.getElementById('noteModalTextarea').placeholder = 'Tuliskan catatan revisi...';
    document.getElementById('noteModalTextarea').value = '';
    document.getElementById('noteModalError').style.display = 'none';
    document.getElementById('noteModalSubmit').textContent = 'Kirim Revisi';
    document.getElementById('noteModalSubmit').style.background = '#f59e0b';
    document.getElementById('noteModalIcon').innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>';
    openNoteModal();
}

function promptReject(id) {
    _actionId = id;
    _actionType = 'reject';
    document.getElementById('noteModalTitle').textContent = 'Alasan Penolakan';
    document.getElementById('noteModalSubtitle').textContent = 'Masukkan alasan penolakan untuk pengajuan ini.';
    document.getElementById('noteModalTextarea').placeholder = 'Tuliskan alasan penolakan...';
    document.getElementById('noteModalTextarea').value = '';
    document.getElementById('noteModalError').style.display = 'none';
    document.getElementById('noteModalSubmit').textContent = 'Tolak Pengajuan';
    document.getElementById('noteModalSubmit').style.background = '#ef4444';
    document.getElementById('noteModalIcon').innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
    openNoteModal();
}

function openNoteModal() {
    document.getElementById('noteModal').classList.add('open');
    setTimeout(function() { document.getElementById('noteModalTextarea').focus(); }, 100);
}

function closeNoteModal() {
    document.getElementById('noteModal').classList.remove('open');
    _actionId = null;
    _actionType = null;
}

function submitNoteModal() {
    var note = document.getElementById('noteModalTextarea').value.trim();
    var errEl = document.getElementById('noteModalError');

    if (_actionType === 'revisi' && note === '') {
        errEl.textContent = 'Catatan revisi tidak boleh kosong.';
        errEl.style.display = 'block';
        return;
    }
    if (_actionType === 'reject' && note === '') {
        errEl.textContent = 'Alasan penolakan tidak boleh kosong.';
        errEl.style.display = 'block';
        return;
    }

    if (_actionType === 'revisi') {
        document.getElementById('revision-note-' + _actionId).value = note;
        document.getElementById('review-form-revisi-' + _actionId).submit();
    } else if (_actionType === 'reject') {
        var form = document.getElementById('review-form-reject-' + _actionId);
        document.getElementById('reject-note-' + _actionId).value = note;
        form.submit();
    }
    closeNoteModal();
}

function closeKemahasiswaanModal() {
    document.getElementById('submission-modal').classList.remove('open');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeKemahasiswaanModal(); closeNoteModal(); }
});
</script>
@endpush

{{-- Note Modal for Revision / Rejection --}}
<div class="modal-overlay" id="noteModal">
    <div class="modal-content" style="max-width: 480px; padding: 0;">
        <div class="modal-header" style="display:flex;align-items:center;gap:12px;">
            <div id="noteModalIcon" style="display:flex;align-items:center;justify-content:center;"></div>
            <h2 id="noteModalTitle" style="margin:0;">Catatan Revisi</h2>
            <button type="button" class="modal-close" onclick="closeNoteModal()" style="margin-left:auto;">&times;</button>
        </div>
        <div class="modal-body" style="padding: 24px;">
            <p id="noteModalSubtitle" style="margin: 0 0 16px; font-size: 14px; color: #6b7280;"></p>
            <textarea id="noteModalTextarea" rows="4" placeholder="Tuliskan catatan..." style="width:100%;padding:14px;border:1.5px solid #d1d5db;border-radius:12px;font-size:14px;font-family:inherit;resize:vertical;outline:none;transition:border-color .2s;box-sizing:border-box;" onfocus="this.style.borderColor='#6f3ba7'" onblur="this.style.borderColor='#d1d5db'"></textarea>
            <div id="noteModalError" style="display:none;color:#dc2626;font-size:13px;font-weight:600;margin-top:8px;"></div>
            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button type="button" onclick="closeNoteModal()" style="padding:10px 20px;border-radius:10px;border:1px solid #d1d5db;background:#fff;color:#374151;font-size:14px;font-weight:600;cursor:pointer;">Batal</button>
                <button type="button" id="noteModalSubmit" onclick="submitNoteModal()" style="padding:10px 24px;border-radius:10px;border:none;color:#fff;font-size:14px;font-weight:700;cursor:pointer;"></button>
            </div>
        </div>
    </div>
</div>
@endsection
