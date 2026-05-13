@extends('layouts.app')

@section('title', 'Pengumuman - Pengurus UKM')

@section('content')
<div class="pengurus-events-page">
    <div class="pengurus-events-head" style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h1 style="margin: 0; font-size: clamp(28px, 3vw, 36px); font-weight: 800; color: #111827; line-height: 1.1;">Pengumuman</h1>
            <p style="margin: 8px 0 0; color: #4b5563; font-size: 16px;">Kelola pengumuman organisasi dengan persetujuan kemahasiswaan</p>
        </div>
        <button type="button" onclick="openModal()" class="pengurus-add-event-btn" style="height: 48px; border-radius: 12px; padding: 0 20px; font-size: 15px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Buat Pengumuman
        </button>
    </div>

    @if(session('success'))
        <div style="background-color: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 16px; border-radius: 12px; margin-bottom: 28px; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; flex-direction: column; gap: 16px;">
        @forelse($announcements as $announcement)
            @php
                $statusText = 'Menunggu Persetujuan';
                $statusClass = 'pending';
                if ($announcement->status === 'terpublikasi') {
                    $statusText = 'Disetujui';
                    $statusClass = 'active';
                } elseif ($announcement->status === 'ditolak') {
                    $statusText = 'Ditolak';
                    $statusClass = 'rejected';
                }
            @endphp
            
            <div style="background: #fff; border: 1px solid #d9dee8; border-radius: 16px; overflow: hidden; position: relative;">
                <div style="padding: 24px;">
                    <svg style="position: absolute; top: 24px; right: 24px; color: #94a3b8;" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>

                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px; padding-right: 40px;">
                        <h3 style="margin: 0; font-size: 22px; font-weight: 800; color: #111827;">{{ $announcement->title }}</h3>
                        <span class="event-badge {{ $statusClass }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 0 12px; height: 28px; font-size: 13px; font-weight: 700; border-radius: 999px;">
                            @if($announcement->status === 'terpublikasi')
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            @elseif($announcement->status === 'draft')
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            @else
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            @endif
                            {{ $statusText }}
                        </span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                        <span style="background: #f1f5f9; color: #475569; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">{{ $announcement->category }}</span>
                        <span style="background: #f1f5f9; color: #475569; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">{{ $announcement->target === 'Semua Mahasiswa' ? 'Umum' : 'Internal' }}</span>
                        <span style="color: #64748b; font-size: 14px; font-weight: 500;">{{ $announcement->created_at->translatedFormat('d M Y') }}</span>
                    </div>

                    <div style="display: flex; gap: 12px;">
                        <button type="button" class="event-btn primary" style="padding: 0 20px;" onclick="openDetailModal({{ json_encode($announcement) }})">Lihat Detail</button>
                        @if($announcement->status !== 'terpublikasi')
                            <button type="button" class="event-btn" style="padding: 0 20px;" onclick="openEditModal({{ json_encode($announcement) }})">Edit</button>
                            <form action="{{ route('pengurus-ukm.announcements.destroy', $announcement->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan pengumuman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="event-btn" style="padding: 0 20px; color: #dc2626; border-color: #fca5a5; background: #fff1f2;">Batal Pengumuman</button>
                            </form>
                        @endif
                    </div>
                </div>

                @if($announcement->status === 'ditolak')
                <div style="background: #fff1f2; border-top: 1px solid #fecdd3; padding: 16px 24px;">
                    <p style="margin: 0; color: #e11d48; font-size: 14px; font-weight: 600;">Catatan Revisi: <span style="font-weight: 500;">{{ $announcement->revision_note ?? 'Mohon tinjau kembali konten pengumuman sesuai standar kemahasiswaan atau hubungi pihak kemahasiswaan.' }}</span></p>
                </div>
                @endif
            </div>
        @empty
            <div class="event-empty" style="grid-column: 1 / -1; padding: 60px 40px; text-align: center; background: #fff; border: 2px dashed #cbd5e1; border-radius: 16px; color: #64748b;">
                <svg style="margin: 0 auto 16px; display: block; color: #94a3b8;" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                <h3 style="margin: 0 0 8px; font-size: 18px; font-weight: 700; color: #1e293b;">Belum Ada Pengumuman</h3>
                <p style="margin: 0; font-size: 15px;">Anda belum membuat pengumuman apa pun untuk organisasi ini.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Create/Edit Modal --}}
<div id="createModal" class="event-modal-backdrop" hidden></div>
<div id="createModalDialog" class="event-modal" hidden>
    <div class="event-modal-panel">
        <div class="event-modal-head">
            <div>
                <h2 id="submitModalTitle">Buat Pengumuman Baru</h2>
                <p class="event-modal-subtitle">Isi rincian pengumuman yang akan diajukan ke kemahasiswaan.</p>
            </div>
            <button type="button" class="event-modal-close" onclick="closeModal()">&times;</button>
        </div>
        
        <form action="{{ route('pengurus-ukm.announcements.store') }}" method="POST" id="submitModalForm" class="event-modal-form" style="padding: 8px 32px 32px;">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div style="display: grid; gap: 20px;">
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">Judul Pengumuman</label>
                    <input type="text" name="title" required placeholder="Contoh: Recruitment Anggota Baru 2024" style="width: 100%; height: 44px; padding: 0 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 15px; outline: none;">
                </div>

                <div>
                    <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">Konten Pengumuman</label>
                    <textarea name="content" required placeholder="Tuliskan konten pengumuman Anda di sini..." rows="5" style="width: 100%; padding: 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 15px; outline: none; resize: vertical;"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">Kategori</label>
                        <select name="category" required style="width: 100%; height: 44px; padding: 0 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 15px; outline: none; background: white;">
                            <option value="">Pilih kategori...</option>
                            <option value="Organisasi">Organisasi</option>
                            <option value="Umum">Umum</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">Target Audiens</label>
                        <select id="target" name="target" required onchange="toggleManualInput(this.value)" style="width: 100%; height: 44px; padding: 0 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 15px; outline: none; background: white;">
                            <option value="">Pilih target...</option>
                            <option value="Semua Mahasiswa">Semua Mahasiswa</option>
                            <option value="Input Manual">Input Manual</option>
                        </select>
                    </div>
                </div>

                <div id="manual_email_wrapper" style="display: none; margin-top: -4px;">
                    <label style="display: block; font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 8px;">Alamat Email Tujuan</label>
                    <input type="email" name="manual_email" placeholder="Contoh: user@example.com" style="width: 100%; height: 44px; padding: 0 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 15px; outline: none;">
                    @error('manual_email')
                        <div style="color: #dc2626; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-top: 8px; padding: 14px 16px; background: #fefce8; border: 1px solid #fef08a; border-radius: 10px; color: #a16207; font-size: 14px; display: flex; align-items: flex-start; gap: 10px; font-weight: 500;">
                    <svg style="flex-shrink: 0; margin-top: 2px;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <span>Pengumuman akan ditinjau oleh pihak kemahasiswaan sebelum dipublikasikan secara resmi ke platform mahasiswa.</span>
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" id="submitBtnText" class="event-btn primary" style="flex: 1; border: none; font-size: 15px;">Ajukan ke Kemahasiswaan</button>
                <button type="button" class="event-btn" onclick="closeModal()" style="padding: 0 24px; font-size: 15px;">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- Detail Modal --}}
<div id="detailModalBackdrop" class="event-modal-backdrop" hidden></div>
<div id="detailModalDialog" class="event-modal" hidden>
    <div class="event-modal-panel">
        <div class="event-modal-head">
            <div>
                <h2 id="detailTitle">Detail Pengumuman</h2>
                <p class="event-modal-subtitle" id="detailMeta">Kategori • Target</p>
            </div>
            <button type="button" class="event-modal-close" onclick="closeDetailModal()">&times;</button>
        </div>
        <div style="padding: 24px 32px;">
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 15px; color: #334155; line-height: 1.6; white-space: pre-wrap;" id="detailContent"></div>
            <div id="detailEmailWrapper" style="display: none; margin-top: 16px; padding: 12px; background: #f1f5f9; border-radius: 8px;">
                <strong style="color: #475569; font-size: 13px;">Email Tujuan Manual:</strong>
                <div id="detailEmail" style="margin-top: 4px; color: #1e293b; font-weight: 500;"></div>
            </div>
        </div>
        <div style="padding: 0 32px 32px; display: flex; justify-content: flex-end;">
            <button type="button" class="event-btn primary" onclick="closeDetailModal()" style="padding: 0 24px;">Tutup</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const baseSubmitUrl = "{{ route('pengurus-ukm.announcements.store') }}";

    function openModal() {
        const backdrop = document.getElementById('createModal');
        const dialog = document.getElementById('createModalDialog');
        
        document.getElementById('submitModalForm').action = baseSubmitUrl;
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('submitModalTitle').textContent = 'Buat Pengumuman Baru';
        document.getElementById('submitBtnText').textContent = 'Ajukan ke Kemahasiswaan';
        document.getElementById('submitModalForm').reset();
        document.getElementById('manual_email_wrapper').style.display = 'none';

        backdrop.removeAttribute('hidden');
        dialog.removeAttribute('hidden');
        
        setTimeout(() => {
            backdrop.classList.add('open');
            dialog.classList.add('open');
        }, 10);
        
        document.body.style.overflow = 'hidden';
    }

    function openEditModal(item) {
        const backdrop = document.getElementById('createModal');
        const dialog = document.getElementById('createModalDialog');
        
        document.getElementById('submitModalForm').action = `/pengurus-ukm/pengumuman/${item.id}`;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('submitModalTitle').textContent = 'Edit Pengumuman';
        document.getElementById('submitBtnText').textContent = 'Simpan & Ajukan Ulang';
        
        const form = document.getElementById('submitModalForm');
        form.elements['title'].value = item.title;
        form.elements['content'].value = item.content;
        form.elements['category'].value = item.category;
        form.elements['target'].value = item.target;
        
        if (item.target === 'Input Manual') {
            document.getElementById('manual_email_wrapper').style.display = 'block';
            form.elements['manual_email'].value = item.manual_email || '';
        } else {
            document.getElementById('manual_email_wrapper').style.display = 'none';
        }

        backdrop.removeAttribute('hidden');
        dialog.removeAttribute('hidden');
        
        setTimeout(() => {
            backdrop.classList.add('open');
            dialog.classList.add('open');
        }, 10);
        
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const backdrop = document.getElementById('createModal');
        const dialog = document.getElementById('createModalDialog');
        
        backdrop.classList.remove('open');
        dialog.classList.remove('open');
        
        setTimeout(() => {
            backdrop.setAttribute('hidden', '');
            dialog.setAttribute('hidden', '');
        }, 200);
        
        document.body.style.overflow = '';
    }

    function openDetailModal(item) {
        document.getElementById('detailTitle').textContent = item.title;
        document.getElementById('detailMeta').textContent = item.category + ' • ' + (item.target === 'Semua Mahasiswa' ? 'Umum' : 'Internal');
        document.getElementById('detailContent').textContent = item.content;
        
        if (item.target === 'Input Manual' && item.manual_email) {
            document.getElementById('detailEmailWrapper').style.display = 'block';
            document.getElementById('detailEmail').textContent = item.manual_email;
        } else {
            document.getElementById('detailEmailWrapper').style.display = 'none';
        }
        
        const backdrop = document.getElementById('detailModalBackdrop');
        const dialog = document.getElementById('detailModalDialog');
        backdrop.removeAttribute('hidden'); dialog.removeAttribute('hidden');
        setTimeout(() => { backdrop.classList.add('open'); dialog.classList.add('open'); }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeDetailModal() {
        const backdrop = document.getElementById('detailModalBackdrop');
        const dialog = document.getElementById('detailModalDialog');
        backdrop.classList.remove('open'); dialog.classList.remove('open');
        setTimeout(() => { backdrop.setAttribute('hidden', ''); dialog.setAttribute('hidden', ''); }, 200);
        document.body.style.overflow = '';
    }

    function toggleManualInput(value) {
        const wrapper = document.getElementById('manual_email_wrapper');
        if (value === 'Input Manual') {
            wrapper.style.display = 'block';
        } else {
            wrapper.style.display = 'none';
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
            closeDetailModal();
        }
    });
    
    document.getElementById('createModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
    document.getElementById('detailModalBackdrop').addEventListener('click', function(e) {
        if (e.target === this) closeDetailModal();
    });
</script>
@endpush
@endsection
