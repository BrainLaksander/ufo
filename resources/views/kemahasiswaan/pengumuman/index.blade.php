@extends('layouts.app')

@section('title', 'Manajemen Pengumuman - Sistem Kemahasiswaan')

@section('content')
@php
    $currentStatus = request('status', '');
@endphp

<div class="org-page">
    <div>
        <h1>Manajemen Pengumuman & Distribusi Email</h1>
        <p>Buat, kelola, dan publikasikan pengumuman resmi serta distribusikan ke email mahasiswa dan organisasi.</p>
    </div>

    @if(session('success'))
        <div class="flash-banner" style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; margin-bottom: 24px; padding: 12px 16px; border-radius: 8px;">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="flash-banner" style="background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; margin-bottom: 24px; padding: 12px 16px; border-radius: 8px;">
            {{ session('error') }}
        </div>
    @endif

    <section class="org-stats-grid" aria-label="Statistik Pengumuman" style="margin-bottom: 32px;">
        <div class="org-stat-card" style="padding: 24px;">
            <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">Total Pengumuman</div>
            <div style="font-size: 28px; font-weight: 600; color: #3730a3;">{{ $totalCount }}</div>
        </div>
        <div class="org-stat-card" style="padding: 24px;">
            <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">Terpublikasi</div>
            <div style="font-size: 28px; font-weight: 600; color: #059669;">{{ $publishedCount }}</div>
        </div>
        <div class="org-stat-card" style="padding: 24px;">
            <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">Terjadwal</div>
            <div style="font-size: 28px; font-weight: 600; color: #2563eb;">{{ $scheduledCount }}</div>
        </div>
        <div class="org-stat-card" style="padding: 24px;">
            <div style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">Draft</div>
            <div style="font-size: 28px; font-weight: 600; color: #4b5563;">{{ $draftCount }}</div>
        </div>
    </section>

    <form class="org-toolbar" method="GET" action="{{ route('kemahasiswaan.announcements.index') }}" style="margin-bottom: 24px;">
        <input class="org-input" name="q" type="search" value="{{ request('q') }}" placeholder="Cari pengumuman..." aria-label="Cari pengumuman">
        <select class="org-select" name="status" aria-label="Filter status">
            <option value="" @selected($currentStatus === '')>Semua Status</option>
            <option value="terpublikasi" @selected($currentStatus === 'terpublikasi')>Terpublikasi</option>
            <option value="sent" @selected($currentStatus === 'sent')>Sent</option>
            <option value="terjadwal" @selected($currentStatus === 'terjadwal')>Terjadwal</option>
            <option value="draft" @selected($currentStatus === 'draft')>Draft / Menunggu Persetujuan</option>
        </select>
        <button type="button" class="btn-primary" id="openAnnouncementModal" style="display: flex; align-items: center; gap: 8px; font-weight: 500;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
            Buat Pengumuman Baru
        </button>
    </form>

    <section class="org-table-card">
        <div class="org-table-wrap">
            <table class="org-table">
                <thead>
                    <tr>
                        <th>Judul Pengumuman</th>
                        <th>Sumber</th>
                        <th>Kategori</th>
                        <th>Target</th>
                        <th>Jadwal Publish</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                        <tr data-id="{{ $announcement->id }}">
                            <td>
                                <div class="org-name" style="color: #3730a3; font-weight: 500;">{{ $announcement->title }}</div>
                                <div class="org-meta" style="font-size: 13px; color: #6b7280;">Dibuat: {{ $announcement->created_at->translatedFormat('d F Y') }}</div>
                            </td>
                            <td>
                                @if($announcement->organization)
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <div style="width: 22px; height: 22px; border-radius: 50%; background: linear-gradient(135deg, #6f3ba7, #a476d1); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 9px; font-weight: bold; flex-shrink: 0;">{{ substr($announcement->organization->name, 0, 1) }}</div>
                                        <span style="font-size: 13px; color: #1f2937; font-weight: 500;">{{ $announcement->organization->name }}</span>
                                    </div>
                                @else
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <div style="width: 22px; height: 22px; border-radius: 50%; background: linear-gradient(135deg, #059669, #34d399); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 9px; font-weight: bold; flex-shrink: 0;">K</div>
                                        <span style="font-size: 13px; color: #1f2937; font-weight: 500;">Kemahasiswaan</span>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $announcement->category }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px; color: #4b5563;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    {{ $announcement->target }}
                                </div>
                            </td>
                            <td>
                                @if($announcement->published_at)
                                    <div style="display: flex; align-items: center; gap: 6px; color: #4b5563;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                        {{ $announcement->published_at->translatedFormat('d F Y, H:i') }}
                                    </div>
                                @else
                                    <span style="color: #9ca3af;">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'terpublikasi' => 'background: #dcfce7; color: #166534;',
                                        'sent' => 'background: #dcfce7; color: #166534;',
                                        'terjadwal' => 'background: #dbeafe; color: #1e40af;',
                                        'draft' => 'background: #fef08a; color: #854d0e;',
                                        'ditolak' => 'background: #fee2e2; color: #dc2626;'
                                    ];
                                    $statusLabels = [
                                        'sent' => 'Terpublikasi',
                                        'draft' => $announcement->organization_id ? 'Menunggu Persetujuan' : 'Draft',
                                    ];
                                    $color = $statusColors[$announcement->status] ?? 'background: #f3f4f6; color: #4b5563;';
                                    $label = $statusLabels[$announcement->status] ?? ucfirst($announcement->status);
                                @endphp
                                <span style="display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 500; {{ $color }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td>
                                <div class="row-actions" style="justify-content: flex-end;">
                                    <button type="button" class="icon-action" style="color: #3b82f6;" data-view data-announcement="{{ base64_encode(json_encode($announcement)) }}" aria-label="Lihat pengumuman" title="Lihat">
                                        <svg viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </button>
                                    @if(!in_array($announcement->status, ['terpublikasi', 'sent']))
                                    <button type="button" class="icon-action" style="color: #4b5563;" data-edit data-id="{{ $announcement->id }}" aria-label="Edit pengumuman" title="Edit">
                                        <svg viewBox="0 0 24 24"><path d="M4 20h4"></path><path d="M14.5 5.5 18.5 9.5"></path><path d="M5 19l2-6 11-11a1.4 1.4 0 0 1 2 0l1 1a1.4 1.4 0 0 1 0 2l-11 11-6 2Z"></path></svg>
                                    </button>
                                    @endif
                                    @if($announcement->status === 'draft')
                                    <button type="button" class="icon-action" style="color: #059669;" data-publish data-id="{{ $announcement->id }}" aria-label="Setujui & Publish" title="Setujui & Publish">
                                        <svg viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                    </button>
                                    <button type="button" class="icon-action" style="color: #ea580c;" data-reject data-id="{{ $announcement->id }}" aria-label="Tolak pengumuman" title="Tolak">
                                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                    </button>
                                    @endif
                                    @if(!in_array($announcement->status, ['terpublikasi', 'sent']))
                                    <button type="button" class="icon-action" style="color: #ef4444;" data-delete data-id="{{ $announcement->id }}" aria-label="Hapus pengumuman" title="Hapus">
                                        <svg viewBox="0 0 24 24"><path d="M4 7h16"></path><path d="M8 7V5a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2"></path><path d="M6 7l1 13a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1l1-13"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <h3>Belum ada pengumuman</h3>
                                    <p class="dashboard-note org-empty-note">Anda belum membuat pengumuman apa pun.</p>
                                    <button type="button" class="btn-primary" onclick="document.getElementById('openAnnouncementModal').click()">Buat Pengumuman</button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if(method_exists($announcements, 'links'))
        <div class="pagination-wrap">
            @if($announcements->onFirstPage())
                <span class="page-link" aria-disabled="true">Prev</span>
            @else
                <a class="page-link" href="{{ $announcements->previousPageUrl() }}">Prev</a>
            @endif

            <span class="org-page-info">Halaman {{ $announcements->currentPage() }} dari {{ $announcements->lastPage() }}</span>

            @if($announcements->hasMorePages())
                <a class="page-link" href="{{ $announcements->nextPageUrl() }}">Next</a>
            @else
                <span class="page-link" aria-disabled="true">Next</span>
            @endif
        </div>
    @endif
</div>

<!-- Modal Form -->
<div class="org-modal-backdrop" id="announcementBackdrop" aria-hidden="true"></div>

<div class="org-modal" id="announcementModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="announcementModalTitle">
    <div class="org-modal-panel">
        <div class="org-modal-header">
            <div>
                <h2 id="announcementModalTitle">Buat Pengumuman Baru</h2>
                <p>Isi rincian pengumuman yang akan didistribusikan.</p>
            </div>
            <button type="button" class="close-btn" data-close-modal aria-label="Tutup">&times;</button>
        </div>

        <form class="org-modal-body" id="announcementForm" novalidate>
            <input type="hidden" id="announcement_id" name="id" value="">
            <div class="form-section">
                <div class="form-grid">
                    <div class="form-field full">
                        <label for="title">Judul Pengumuman</label>
                        <input id="title" name="title" type="text" placeholder="Contoh: Pendaftaran Kegiatan Semester Genap 2024/2025" required>
                        <div class="field-error" data-error-for="title"></div>
                    </div>
                    
                    <div class="form-field">
                        <label for="category">Kategori</label>
                        <select id="category" name="category" required>
                            <option value="Organisasi">Organisasi</option>
                            <option value="Umum">Umum</option>
                        </select>
                        <div class="field-error" data-error-for="category"></div>
                    </div>

                    <div class="form-field">
                        <label for="target">Target Distribusi</label>
                        <select id="target" name="target" required onchange="toggleManualInput(this.value)">
                            <option value="Semua Mahasiswa">Semua Mahasiswa</option>
                            <option value="Input Manual">Input Manual</option>
                        </select>
                        <div class="field-error" data-error-for="target"></div>
                    </div>
                    
                    <div class="form-field" id="manual_email_wrapper" style="display: none;">
                        <label for="manual_email">Alamat Email Tujuan</label>
                        <input id="manual_email" name="manual_email" type="email" placeholder="Contoh: kandoudavid1@gmail.com">
                        <div class="field-error" data-error-for="manual_email"></div>
                    </div>

                    <div class="form-field">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="draft">Draft (Simpan dulu)</option>
                            <option value="terjadwal">Terjadwal</option>
                            <option value="terpublikasi">Langsung Publish</option>
                        </select>
                        <div class="field-error" data-error-for="status"></div>
                    </div>

                    <div class="form-field">
                        <label for="published_at">Jadwal Publish (Jika Terjadwal)</label>
                        <input id="published_at" name="published_at" type="datetime-local" min="{{ now()->format('Y-m-d\TH:i') }}">
                        <div class="field-error" data-error-for="published_at"></div>
                    </div>

                    <div class="form-field full">
                        <label for="content">Isi Pengumuman</label>
                        <textarea id="content" name="content" rows="6" placeholder="Tuliskan isi pengumuman secara detail..."></textarea>
                        <div class="field-error" data-error-for="content"></div>
                    </div>
                </div>
            </div>
        </form>

        <div class="modal-footer">
            <button type="button" class="btn-secondary" data-close-modal>Batal</button>
            <button type="button" class="btn-primary" id="saveAnnouncementBtn">Simpan Pengumuman</button>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="org-modal-backdrop" id="detailModalBackdrop" aria-hidden="true"></div>

<div class="org-modal" id="detailModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="detailModalTitle">
    <div class="org-modal-panel">
        <div class="org-modal-header">
            <div>
                <h2 id="detailModalTitle">Detail Pengumuman</h2>
                <p id="detailModalMeta" style="margin-top: 4px; color: #6b7280; font-size: 14px;"></p>
            </div>
            <button type="button" class="close-btn" data-close-detail aria-label="Tutup">&times;</button>
        </div>
        <div class="org-modal-body" style="padding: 24px;">
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 15px; color: #334155; line-height: 1.6; white-space: pre-wrap;" id="detailModalContent"></div>
            <div id="detailEmailWrapper" style="display: none; margin-top: 16px; padding: 12px; background: #f1f5f9; border-radius: 8px;">
                <strong style="color: #475569; font-size: 13px;">Email Tujuan Manual:</strong>
                <div id="detailEmail" style="margin-top: 4px; color: #1e293b; font-weight: 500;"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-primary" data-close-detail style="margin-left: auto;">Tutup</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleManualInput(value) {
        const wrapper = document.getElementById('manual_email_wrapper');
        const input = document.getElementById('manual_email');
        if (value === 'Input Manual') {
            wrapper.style.display = 'block';
            input.setAttribute('required', 'required');
        } else {
            wrapper.style.display = 'none';
            input.removeAttribute('required');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const modal = document.getElementById('announcementModal');
        const backdrop = document.getElementById('announcementBackdrop');
        const openButton = document.getElementById('openAnnouncementModal');
        const form = document.getElementById('announcementForm');
        const saveBtn = document.getElementById('saveAnnouncementBtn');
        const title = document.getElementById('announcementModalTitle');
        const idInput = document.getElementById('announcement_id');
        let editingId = null;

        if (!modal || !backdrop || !form || !saveBtn) return;

        function openDialog(element, backdropElement) {
            element.classList.add('open');
            backdropElement.classList.add('open');
            element.setAttribute('aria-hidden', 'false');
            backdropElement.setAttribute('aria-hidden', 'false');
        }

        function closeDialog(element, backdropElement) {
            element.classList.remove('open');
            backdropElement.classList.remove('open');
            element.setAttribute('aria-hidden', 'true');
            backdropElement.setAttribute('aria-hidden', 'true');
        }

        function clearErrors() {
            document.querySelectorAll('.field-error').forEach(function (errorNode) {
                errorNode.textContent = '';
                errorNode.style.display = 'none';
            });
        }

        function resetForm() {
            form.reset();
            idInput.value = '';
            editingId = null;
            title.textContent = 'Buat Pengumuman Baru';
            clearErrors();
        }

        function showFormModal() {
            openDialog(modal, backdrop);
        }

        function hideFormModal() {
            closeDialog(modal, backdrop);
            resetForm();
        }

        async function submitAnnouncement() {
            clearErrors();
            saveBtn.disabled = true;
            saveBtn.textContent = 'Menyimpan...';

            try {
                const formData = new FormData(form);
                const url = editingId ? '/kemahasiswaan/pengumuman/' + editingId : '{{ route('kemahasiswaan.announcements.store') }}';
                if (editingId) {
                    formData.append('_method', 'PUT');
                }

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData,
                });

                if (response.ok) {
                    location.reload();
                    return;
                }

                if (response.status === 422) {
                    const payload = await response.json();
                    const errors = payload.errors || {};
                    Object.keys(errors).forEach(function (fieldName) {
                        const errorNode = document.querySelector('[data-error-for="' + fieldName + '"]');
                        if (errorNode) {
                            errorNode.textContent = errors[fieldName][0];
                            errorNode.style.display = 'block';
                        }
                    });
                    return;
                }

                alert('Terjadi kesalahan: ' + response.status);
            } catch (error) {
                console.error(error);
                alert('Gagal menyimpan pengumuman.');
            } finally {
                saveBtn.disabled = false;
                saveBtn.textContent = 'Simpan Pengumuman';
            }
        }

        async function handlePublish(id) {
            if (!confirm('Setujui dan publikasikan pengumuman ini?')) return;
            try {
                const response = await fetch('/kemahasiswaan/pengumuman/' + id + '/publish', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                });
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Gagal mempublikasikan pengumuman.');
                }
            } catch (e) { console.error(e); }
        }

        async function handleReject(id) {
            if (!confirm('Tolak pengumuman ini?')) return;
            try {
                const response = await fetch('/kemahasiswaan/pengumuman/' + id + '/reject', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                });
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Gagal menolak pengumuman.');
                }
            } catch (e) { console.error(e); }
        }

        async function handleDelete(id) {
            if (!confirm('Hapus pengumuman ini?')) return;
            try {
                const response = await fetch('/kemahasiswaan/pengumuman/' + id, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                });
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Gagal menghapus pengumuman.');
                }
            } catch (e) { console.error(e); }
        }

        if (openButton) {
            openButton.addEventListener('click', function () {
                resetForm();
                showFormModal();
            });
        }

        saveBtn.addEventListener('click', submitAnnouncement);

        modal.querySelectorAll('[data-close-modal]').forEach(function (button) {
            button.addEventListener('click', hideFormModal);
        });

        backdrop.addEventListener('click', function () {
            if (modal.classList.contains('open')) hideFormModal();
        });

        document.querySelectorAll('[data-edit]').forEach(function (button) {
            button.addEventListener('click', function () {
                const row = this.closest('tr');
                const titleText = row.querySelector('.org-name').textContent;
                const catText = row.querySelectorAll('td')[2].textContent.trim();
                const targetText = row.querySelectorAll('td')[3].textContent.trim();
                
                // For a proper edit, we would fetch the data from server. For simplicity here:
                idInput.value = this.getAttribute('data-id');
                editingId = this.getAttribute('data-id');
                title.textContent = 'Edit Pengumuman';
                document.getElementById('title').value = titleText;
                
                // Find matching category/target options
                Array.from(document.getElementById('category').options).forEach(o => { if(o.text === catText) o.selected = true; });
                
                // For target, if it's not "Semua Mahasiswa", assume it's manual
                if (targetText !== 'Semua Mahasiswa') {
                    document.getElementById('target').value = 'Input Manual';
                    document.getElementById('manual_email').value = targetText;
                    toggleManualInput('Input Manual');
                } else {
                    document.getElementById('target').value = 'Semua Mahasiswa';
                    document.getElementById('manual_email').value = '';
                    toggleManualInput('Semua Mahasiswa');
                }
                
                showFormModal();
            });
        });

        document.querySelectorAll('[data-delete]').forEach(function (button) {
            button.addEventListener('click', function () {
                handleDelete(this.getAttribute('data-id'));
            });
        });
        document.querySelectorAll('[data-publish]').forEach(function (button) {
            button.addEventListener('click', function () {
                handlePublish(this.getAttribute('data-id'));
            });
        });
        
        document.querySelectorAll('[data-reject]').forEach(function (button) {
            button.addEventListener('click', function () {
                handleReject(this.getAttribute('data-id'));
            });
        });

        // Detail Modal Logic
        const detailModal = document.getElementById('detailModal');
        const detailBackdrop = document.getElementById('detailModalBackdrop');
        const detailTitle = document.getElementById('detailModalTitle');
        const detailMeta = document.getElementById('detailModalMeta');
        const detailContent = document.getElementById('detailModalContent');
        const detailEmailWrapper = document.getElementById('detailEmailWrapper');
        const detailEmail = document.getElementById('detailEmail');

        function hideDetailModal() {
            closeDialog(detailModal, detailBackdrop);
        }

        if (detailModal && detailBackdrop) {
            document.querySelectorAll('[data-close-detail]').forEach(function (button) {
                button.addEventListener('click', hideDetailModal);
            });

            detailBackdrop.addEventListener('click', function () {
                if (detailModal.classList.contains('open')) hideDetailModal();
            });
        }

        document.querySelectorAll('[data-view]').forEach(function (button) {
            button.addEventListener('click', function () {
                try {
                    const encodedData = this.getAttribute('data-announcement');
                    // Use decodeURIComponent and escape to properly decode unicode base64 characters
                    const announcement = JSON.parse(decodeURIComponent(escape(atob(encodedData))));
                    
                    detailTitle.textContent = announcement.title;
                    const targetDisplay = announcement.target === 'Semua Mahasiswa' ? 'Umum' : 'Internal';
                    detailMeta.textContent = announcement.category + ' • ' + targetDisplay;
                    detailContent.textContent = announcement.content;
                    
                    if (announcement.target === 'Input Manual' && announcement.manual_email) {
                        detailEmailWrapper.style.display = 'block';
                        detailEmail.textContent = announcement.manual_email;
                    } else {
                        detailEmailWrapper.style.display = 'none';
                    }
                    
                    openDialog(detailModal, detailBackdrop);
                } catch (e) {
                    console.error("Error parsing announcement data", e);
                }
            });
        });
    });
</script>
@endpush
