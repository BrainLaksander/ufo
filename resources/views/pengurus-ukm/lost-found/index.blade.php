@extends('layouts.app')
@section('title', 'Lost & Found - Pengurus UKM')
@section('content')
<div class="pengurus-events-page">
    <div class="pengurus-events-head" style="margin-bottom:32px">
        <div>
            <h1 style="margin:0;font-size:clamp(28px,3vw,36px);font-weight:800;color:#111827">Lost & Found</h1>
            <p style="margin:8px 0 0;color:#4b5563;font-size:16px">Kelola laporan barang hilang dan temuan dari mahasiswa</p>
        </div>
        <button type="button" onclick="openLFModal()" class="pengurus-add-event-btn" style="height:48px;border-radius:12px;padding:0 20px;font-size:15px">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Laporkan Barang
        </button>
    </div>

    @if(session('success'))
    <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#166534;padding:16px;border-radius:12px;margin-bottom:28px;font-weight:500">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <form method="GET" action="{{ route('pengurus-ukm.lost-found.index') }}" style="display:flex;gap:12px;margin-bottom:24px;background:#fff;border:1px solid #d9dee8;border-radius:12px;padding:16px 20px;align-items:center;flex-wrap:wrap">
        <div style="flex:1;min-width:180px;display:flex;align-items:center;gap:10px">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input type="search" name="q" value="{{ $q }}" placeholder="Cari barang..." style="border:none;outline:none;width:100%;font-size:14px;background:transparent">
        </div>
        <select name="type" onchange="this.form.submit()" style="height:40px;padding:0 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;font-weight:600;background:#fff;min-width:150px">
            <option value="" @selected($type==='')>Semua Tipe</option>
            <option value="lost" @selected($type==='lost')>Barang Hilang</option>
            <option value="found" @selected($type==='found')>Barang Ditemukan</option>
        </select>
        <select name="status" onchange="this.form.submit()" style="height:40px;padding:0 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;font-weight:600;background:#fff;min-width:150px">
            <option value="" @selected($statusFilter==='')>Semua Status</option>
            <option value="pending" @selected($statusFilter==='pending')>Menunggu Review</option>
            <option value="active" @selected($statusFilter==='active')>Belum Ditemukan</option>
            <option value="resolved" @selected($statusFilter==='resolved')>Sudah Ditemukan</option>
        </select>
    </form>

    {{-- Items List --}}
    <div style="background:#fff;border:1px solid #d9dee8;border-radius:12px;overflow:hidden;box-shadow:0 1px 2px rgba(0,0,0,0.05);">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;text-align:left;">
                <thead style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                    <tr>
                        <th style="padding:16px 20px;color:#475569;font-weight:600;font-size:14px;">Tanggal Lapor</th>
                        <th style="padding:16px 20px;color:#475569;font-weight:600;font-size:14px;">Tipe & Nama Barang</th>
                        <th style="padding:16px 20px;color:#475569;font-weight:600;font-size:14px;">Pelapor</th>
                        <th style="padding:16px 20px;color:#475569;font-weight:600;font-size:14px;">Status</th>
                        <th style="padding:16px 20px;color:#475569;font-weight:600;font-size:14px;text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php
                            $isLost = $item->type === 'lost';
                            $typeBg = $isLost ? '#fee2e2' : '#dcfce7';
                            $typeColor = $isLost ? '#dc2626' : '#166534';
                            $typeLabel = $isLost ? 'HILANG' : 'TEMUAN';
                            
                            $statusLabel = 'Menunggu';
                            $statusBg = '#f1f5f9';
                            $statusColor = '#475569';
                            
                            if($item->status === 'active') {
                                $statusLabel = 'Aktif/Disetujui';
                                $statusBg = '#fef9c3';
                                $statusColor = '#ca8a04';
                            } elseif($item->status === 'resolved') {
                                $statusLabel = 'Selesai';
                                $statusBg = '#dcfce7';
                                $statusColor = '#166534';
                            } elseif($item->status === 'rejected') {
                                $statusLabel = 'Ditolak';
                                $statusBg = '#fee2e2';
                                $statusColor = '#dc2626';
                            }
                        @endphp
                        <tr style="border-bottom:1px solid #e2e8f0;transition:background .2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding:16px 20px;color:#64748b;font-size:14px;">
                                {{ $item->created_at->format('d/m/Y') }}<br>
                                <span style="font-size:12px;">{{ $item->created_at->format('H:i') }}</span>
                            </td>
                            <td style="padding:16px 20px;">
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                                    <span style="padding:2px 8px;border-radius:999px;font-size:11px;font-weight:700;background:{{ $typeBg }};color:{{ $typeColor }}">{{ $typeLabel }}</span>
                                </div>
                                <strong style="color:#1e293b;font-size:15px;display:block;">{{ $item->title }}</strong>
                            </td>
                            <td style="padding:16px 20px;">
                                <div style="color:#334155;font-size:14px;font-weight:500;">{{ $item->contact_person }}</div>
                                <div style="color:#94a3b8;font-size:12px;">{{ $item->organization ? $item->organization->name : 'Mahasiswa' }}</div>
                            </td>
                            <td style="padding:16px 20px;">
                                <span style="padding:4px 10px;border-radius:6px;font-size:12px;font-weight:600;background:{{ $statusBg }};color:{{ $statusColor }}">{{ $statusLabel }}</span>
                            </td>
                            <td style="padding:16px 20px;text-align:right;">
                                <div style="display:flex;gap:8px;justify-content:flex-end;">
                                    <button type="button" onclick="openDetailModal({{ json_encode($item) }})" style="cursor:pointer;padding:6px 12px;font-size:13px;font-weight:600;background:#f8fafc;color:#475569;border:1px solid #cbd5e1;border-radius:6px;transition:background .2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">Detail</button>
                                    @if($item->status === 'pending')
                                        <form method="POST" action="{{ route('pengurus-ukm.lost-found.status', $item->id) }}" style="margin:0;">
                                            @csrf
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" style="cursor:pointer;padding:6px 12px;font-size:13px;font-weight:600;background:#2563eb;color:#fff;border:none;border-radius:6px;transition:opacity .2s;" onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('pengurus-ukm.lost-found.status', $item->id) }}" style="margin:0;" onsubmit="return confirm('Tolak laporan ini?')">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" style="cursor:pointer;padding:6px 12px;font-size:13px;font-weight:600;background:#fff;color:#dc2626;border:1px solid #dc2626;border-radius:6px;transition:background .2s;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fff'">Tolak</button>
                                        </form>
                                    @elseif($item->status === 'active')
                                        <form method="POST" action="{{ route('pengurus-ukm.lost-found.status', $item->id) }}" style="margin:0;">
                                            @csrf
                                            <input type="hidden" name="status" value="resolved">
                                            <button type="submit" style="cursor:pointer;padding:6px 12px;font-size:13px;font-weight:600;background:#16a34a;color:#fff;border:none;border-radius:6px;">Tandai Selesai</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('pengurus-ukm.lost-found.status', $item->id) }}" style="margin:0;">
                                            @csrf
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" style="cursor:pointer;padding:6px 12px;font-size:13px;font-weight:600;background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;border-radius:6px;">Buka Lagi</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding:40px;text-align:center;color:#64748b;">
                                <svg style="margin:0 auto 12px;display:block;color:#94a3b8" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                                <p style="margin:0;font-size:15px;font-weight:500;">Belum ada laporan barang hilang/temuan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Report Modal --}}
<div id="lfBackdrop" class="event-modal-backdrop" hidden></div>
<div id="lfModal" class="event-modal" hidden>
<div class="event-modal-panel">
    <div class="event-modal-head">
        <div><h2>Laporkan Barang</h2><p class="event-modal-subtitle">Laporkan barang hilang atau temuan untuk dipublikasikan</p></div>
        <button type="button" class="event-modal-close" onclick="closeLFModal()">&times;</button>
    </div>
    <form action="{{ route('pengurus-ukm.lost-found.store') }}" method="POST" enctype="multipart/form-data" class="event-modal-form" style="padding:8px 32px 32px">
        @csrf
        <div style="display:grid;gap:20px">
            <div>
                <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Tipe Laporan</label>
                <div style="display:flex;gap:12px">
                    <label style="flex:1;display:flex;align-items:center;gap:10px;padding:14px 16px;border:2px solid #d1d5db;border-radius:10px;cursor:pointer;transition:border-color .2s" onclick="this.parentElement.querySelectorAll('label').forEach(l=>l.style.borderColor='#d1d5db');this.style.borderColor='#ef4444'">
                        <input type="radio" name="type" value="lost" required style="accent-color:#ef4444">
                        <div><strong style="color:#ef4444">Barang Hilang</strong><br><span style="font-size:12px;color:#64748b">Saya kehilangan barang</span></div>
                    </label>
                    <label style="flex:1;display:flex;align-items:center;gap:10px;padding:14px 16px;border:2px solid #d1d5db;border-radius:10px;cursor:pointer;transition:border-color .2s" onclick="this.parentElement.querySelectorAll('label').forEach(l=>l.style.borderColor='#d1d5db');this.style.borderColor='#22c55e'">
                        <input type="radio" name="type" value="found" required style="accent-color:#22c55e">
                        <div><strong style="color:#22c55e">Barang Ditemukan</strong><br><span style="font-size:12px;color:#64748b">Saya menemukan barang</span></div>
                    </label>
                </div>
            </div>
            <div>
                <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Nama Barang</label>
                <input type="text" name="title" required placeholder="Contoh: Dompet Coklat" style="width:100%;height:44px;padding:0 14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none">
            </div>
            <div>
                <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Deskripsi</label>
                <textarea name="description" required placeholder="Jelaskan ciri-ciri barang..." rows="3" style="width:100%;padding:14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none;resize:vertical"></textarea>
            </div>
            <div>
                <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Foto Barang</label>
                <input type="file" name="image" accept="image/png,image/jpeg,image/jpg" id="pengurusLfImage" required style="display:none">
                <label for="pengurusLfImage" style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:14px;border:2px dashed #d1d5db;border-radius:10px;cursor:pointer;color:#6b7280;font-size:14px;transition:border-color .2s">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                    <span id="pengurusLfImageText">Klik untuk upload foto (maks. 5MB)</span>
                </label>
                <img id="pengurusLfImagePreview" src="" alt="Preview" style="display:none;margin-top:8px;max-height:120px;border-radius:8px;object-fit:cover">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Tanggal</label>
                    <input type="date" name="date" required max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" style="width:100%;height:44px;padding:0 14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none">
                </div>
                <div>
                    <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Lokasi</label>
                    <input type="text" name="location" required placeholder="Contoh: Dekat Kantin Utama" style="width:100%;height:44px;padding:0 14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Nama Pelapor</label>
                    <input type="text" name="contact_person" required placeholder="Nama lengkap" style="width:100%;height:44px;padding:0 14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none">
                </div>
                <div>
                    <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">No. HP / WA</label>
                    <input type="tel" name="contact_phone" required placeholder="08xxxxxxxxxx" pattern="[0-9]{10,15}" title="Hanya angka (10-15 digit)" oninput="this.value = this.value.replace(/[^0-9]/g, '')" style="width:100%;height:44px;padding:0 14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none">
                </div>
            </div>
            <div style="display:flex;gap:12px;margin-top:4px">
                <button type="submit" class="event-btn primary" style="flex:1;border:none;font-size:15px">Laporkan Barang</button>
                <button type="button" class="event-btn" onclick="closeLFModal()" style="padding:0 24px;font-size:15px">Batal</button>
            </div>
        </div>
    </form>
</div>
</div>

{{-- Detail Modal --}}
<div id="lfDetailBackdrop" class="event-modal-backdrop" hidden></div>
<div id="lfDetailModal" class="event-modal" hidden>
<div class="event-modal-panel" style="max-width: 500px;">
    <div class="event-modal-head">
        <div><h2 id="detailTitle">Detail Barang</h2></div>
        <button type="button" class="event-modal-close" onclick="closeDetailModal()">&times;</button>
    </div>
    <div style="padding:24px 32px">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
            <span id="detailTypeBadge" style="padding:4px 12px;border-radius:999px;font-size:12px;font-weight:700;"></span>
            <span id="detailStatusBadge" style="padding:4px 12px;border-radius:999px;font-size:12px;font-weight:700;"></span>
        </div>
        
        <div id="detailImageWrap" style="display:none;margin-bottom:16px;border-radius:10px;overflow:hidden">
            <img id="detailImage" src="" alt="" style="width:100%;max-height:200px;object-fit:cover;display:block">
        </div>

        <div style="margin-bottom: 20px;">
            <h3 style="margin: 0 0 8px; font-size: 14px; color: #64748b;">Deskripsi</h3>
            <p id="detailDesc" style="margin: 0; font-size: 15px; color: #1e293b; line-height: 1.6; white-space: pre-wrap;"></p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; padding: 16px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
            <div>
                <span style="display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Tanggal</span>
                <span id="detailDate" style="font-size: 14px; font-weight: 500; color: #0f172a;"></span>
            </div>
            <div>
                <span style="display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Lokasi</span>
                <span id="detailLocation" style="font-size: 14px; font-weight: 500; color: #0f172a;"></span>
            </div>
            <div style="grid-column: 1 / -1;">
                <span style="display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Kontak Pelapor</span>
                <span id="detailContact" style="font-size: 14px; font-weight: 500; color: #0f172a;"></span>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;">
            <button type="button" class="event-btn" onclick="closeDetailModal()" style="padding:0 24px;font-size:15px">Tutup</button>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
// Image upload preview for Pengurus
var pengurusLfImageInput = document.getElementById('pengurusLfImage');
if (pengurusLfImageInput) {
    pengurusLfImageInput.addEventListener('change', function() {
        var file = this.files[0];
        var textEl = document.getElementById('pengurusLfImageText');
        var previewEl = document.getElementById('pengurusLfImagePreview');
        if (file) {
            textEl.textContent = file.name;
            var reader = new FileReader();
            reader.onload = function(e) {
                previewEl.src = e.target.result;
                previewEl.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            textEl.textContent = 'Klik untuk upload foto (maks. 5MB)';
            previewEl.style.display = 'none';
        }
    });
}

function openLFModal(){var b=document.getElementById('lfBackdrop'),d=document.getElementById('lfModal');b.removeAttribute('hidden');d.removeAttribute('hidden');setTimeout(()=>{b.classList.add('open');d.classList.add('open')},10);document.body.style.overflow='hidden'}
function closeLFModal(){var b=document.getElementById('lfBackdrop'),d=document.getElementById('lfModal');b.classList.remove('open');d.classList.remove('open');setTimeout(()=>{b.setAttribute('hidden','');d.setAttribute('hidden','')},200);document.body.style.overflow=''}

function openDetailModal(item) {
    document.getElementById('detailTitle').textContent = item.title;
    
    const typeBadge = document.getElementById('detailTypeBadge');
    if (item.type === 'lost') {
        typeBadge.textContent = 'HILANG';
        typeBadge.style.background = '#fee2e2';
        typeBadge.style.color = '#dc2626';
    } else {
        typeBadge.textContent = 'DITEMUKAN';
        typeBadge.style.background = '#dcfce7';
        typeBadge.style.color = '#166534';
    }

    const statusBadge = document.getElementById('detailStatusBadge');
    if (item.status === 'pending') {
        statusBadge.textContent = 'Menunggu Review';
        statusBadge.style.background = '#f1f5f9';
        statusBadge.style.color = '#475569';
    } else if (item.status === 'active') {
        statusBadge.textContent = 'Belum Ditemukan';
        statusBadge.style.background = '#fef9c3';
        statusBadge.style.color = '#ca8a04';
    } else if (item.status === 'resolved') {
        statusBadge.textContent = 'Sudah Ditemukan';
        statusBadge.style.background = '#dcfce7';
        statusBadge.style.color = '#166534';
    } else {
        statusBadge.textContent = 'Ditolak';
        statusBadge.style.background = '#fee2e2';
        statusBadge.style.color = '#dc2626';
    }

    document.getElementById('detailDesc').textContent = item.description;

    // Show image if available
    var detailImageWrap = document.getElementById('detailImageWrap');
    var detailImage = document.getElementById('detailImage');
    if (item.image_path) {
        detailImage.src = '/storage/' + item.image_path;
        detailImageWrap.style.display = 'block';
    } else {
        detailImageWrap.style.display = 'none';
    }
    
    // Format date
    let dateStr = item.date;
    if (dateStr && dateStr.indexOf('T') !== -1) {
        const d = new Date(dateStr);
        dateStr = d.toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'});
    }
    document.getElementById('detailDate').textContent = dateStr;
    
    document.getElementById('detailLocation').textContent = item.location;
    
    let contact = item.contact_person;
    if (item.organization) contact += ' (' + item.organization.name + ')';
    if (item.contact_phone) contact += ' - ' + item.contact_phone;
    document.getElementById('detailContact').textContent = contact;

    var b=document.getElementById('lfDetailBackdrop'),d=document.getElementById('lfDetailModal');
    b.removeAttribute('hidden');d.removeAttribute('hidden');
    setTimeout(()=>{b.classList.add('open');d.classList.add('open')},10);
    document.body.style.overflow='hidden';
}

function closeDetailModal() {
    var b=document.getElementById('lfDetailBackdrop'),d=document.getElementById('lfDetailModal');
    b.classList.remove('open');d.classList.remove('open');
    setTimeout(()=>{b.setAttribute('hidden','');d.setAttribute('hidden','')},200);
    document.body.style.overflow='';
}

document.addEventListener('keydown',e=>{
    if(e.key==='Escape'){closeLFModal();closeDetailModal();}
});
document.getElementById('lfBackdrop').addEventListener('click',closeLFModal);
document.getElementById('lfDetailBackdrop').addEventListener('click',closeDetailModal);
</script>
@endpush
@endsection
