@extends('layouts.app')
@section('title', 'Pengajuan & Laporan Kegiatan - Pengurus UKM')
@section('content')
<div class="pengurus-events-page">
    <div class="pengurus-events-head" style="margin-bottom:32px">
        <div>
            <h1 style="margin:0;font-size:clamp(28px,3vw,36px);font-weight:800;color:#111827">Pengajuan & Laporan Kegiatan</h1>
            <p style="margin:8px 0 0;color:#4b5563;font-size:16px">Ajukan proposal kegiatan atau kirim laporan pertanggungjawaban (LPJ)</p>
        </div>
        <button type="button" onclick="openSubmissionModal()" class="pengurus-add-event-btn" style="height:48px;border-radius:12px;padding:0 20px;font-size:15px">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            {{ $tab === 'report' ? 'Submit Laporan (LPJ)' : 'Ajukan Kegiatan' }}
        </button>
    </div>

    @if(session('success'))
    <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#166534;padding:16px;border-radius:12px;margin-bottom:28px;font-weight:500">{{ session('success') }}</div>
    @endif

    <section style="display:flex;background:#fff;border-radius:12px;border:1px solid #d9dee8;margin-bottom:24px;overflow:hidden">
        <a href="{{ route('pengurus-ukm.submissions.index',['tab'=>'proposal']) }}" style="flex:1;text-align:center;padding:16px 24px;font-weight:700;font-size:15px;text-decoration:none;border-bottom:3px solid {{ $tab==='proposal'?'#6f3ba7':'transparent' }};color:{{ $tab==='proposal'?'#6f3ba7':'#64748b' }};background:{{ $tab==='proposal'?'#faf5ff':'transparent' }}">Pengajuan Kegiatan</a>
        <a href="{{ route('pengurus-ukm.submissions.index',['tab'=>'report']) }}" style="flex:1;text-align:center;padding:16px 24px;font-weight:700;font-size:15px;text-decoration:none;border-bottom:3px solid {{ $tab==='report'?'#6f3ba7':'transparent' }};color:{{ $tab==='report'?'#6f3ba7':'#64748b' }};background:{{ $tab==='report'?'#faf5ff':'transparent' }}">Laporan Kegiatan (LPJ)</a>
    </section>

    <form method="GET" action="{{ route('pengurus-ukm.submissions.index') }}" style="display:flex;gap:16px;margin-bottom:24px">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div style="flex:1;display:flex;align-items:center;gap:12px;background:#fff;border:1px solid #d9dee8;border-radius:10px;padding:12px 16px">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input type="search" name="q" value="{{ $q }}" placeholder="Cari kegiatan..." style="border:none;outline:none;width:100%;font-size:14px;background:transparent">
        </div>
        <div style="display:flex;align-items:center;gap:12px;background:#fff;border:1px solid #d9dee8;border-radius:10px;padding:12px 16px;min-width:200px">
            <select name="status" onchange="this.form.submit()" style="border:none;outline:none;width:100%;font-size:14px;background:transparent;cursor:pointer;font-weight:600">
                <option value="" @selected($status==='')>Semua Status</option>
                <option value="diajukan" @selected($status==='diajukan')>Diajukan</option>
                <option value="review" @selected($status==='review')>Sedang Direview</option>
                <option value="approved" @selected($status==='approved')>Disetujui</option>
                <option value="rejected" @selected($status==='rejected')>Ditolak</option>
            </select>
        </div>
    </form>

    <div style="display:flex;flex-direction:column;gap:16px">
        @forelse($items as $item)
        @php
            $bs=['diajukan'=>'background:#fef9c3;color:#ca8a04','review'=>'background:#dbeafe;color:#2563eb','approved'=>'background:#dcfce7;color:#16a34a','rejected'=>'background:#fee2e2;color:#dc2626'];
            $bst=$bs[$item['status']]??'background:#f3f4f6;color:#4b5563';
        @endphp
        <div style="background:#fff;border:1px solid #d9dee8;border-radius:16px;overflow:hidden;position:relative">
            <div style="padding:24px">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;padding-right:40px">
                    <h3 style="margin:0;font-size:22px;font-weight:800;color:#111827">{{ $item['title'] }}</h3>
                    <span style="display:inline-flex;align-items:center;gap:6px;padding:0 12px;height:28px;font-size:13px;font-weight:700;border-radius:999px;{{ $bst }}">{{ $item['status_label'] }}</span>
                </div>
                <div style="display:flex;flex-wrap:wrap;align-items:center;gap:12px;margin-bottom:20px">
                    @if($item['jenis_kegiatan'])<span style="background:#f1f5f9;color:#475569;padding:6px 14px;border-radius:8px;font-size:13px;font-weight:600">{{ $item['jenis_kegiatan'] }}</span>@endif
                    @if($item['lokasi'])<span style="background:#f1f5f9;color:#475569;padding:6px 14px;border-radius:8px;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:4px"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>{{ $item['lokasi'] }}</span>@endif
                    <span style="color:#64748b;font-size:14px;display:inline-flex;align-items:center;gap:4px"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>{{ $item['event_date'] ? \Illuminate\Support\Carbon::parse($item['event_date'])->translatedFormat('d M Y') : '-' }}</span>
                </div>
                <div style="display:flex;gap:12px">
                    <button type="button" class="event-btn primary" style="padding:0 20px" onclick="openDetailModal(this)" data-item="{{ json_encode($item) }}">Lihat Detail</button>
                    @if(in_array($item['status'], ['diajukan', 'revisi', 'rejected']))
                        <button type="button" class="event-btn" style="padding:0 20px" onclick="openEditModal(this)" data-item="{{ json_encode($item) }}">Edit / Perbaiki</button>
                    @endif
                </div>
            </div>
            @if($item['status']==='rejected' || $item['status']==='revisi')
                @if($item['revision_note'])
                <div style="background:#fff7ed;border-top:1px solid #fed7aa;padding:16px 24px">
                    <p style="margin:0 0 4px;color:#c2410c;font-size:14px;font-weight:700">Catatan Penolakan / Revisi:</p>
                    <p style="margin:0;color:#9a3412;font-size:14px;line-height:1.5;white-space:pre-wrap">{{ $item['revision_note'] }}</p>
                </div>
                @else
                <div style="background:#fff1f2;border-top:1px solid #fecdd3;padding:16px 24px">
                    <p style="margin:0;color:#e11d48;font-size:14px;font-weight:600">Pengajuan ditolak. <span style="font-weight:500">Anda dapat mengedit dan mengajukan ulang.</span></p>
                </div>
                @endif
            @endif
        </div>
        @empty
        <div style="padding:60px 40px;text-align:center;background:#fff;border:2px dashed #cbd5e1;border-radius:16px;color:#64748b">
            <svg style="margin:0 auto 16px;display:block;color:#94a3b8" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 12h6M9 16h6M9 8h6M5 20h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z"/></svg>
            <h3 style="margin:0 0 8px;font-size:18px;font-weight:700;color:#1e293b">Belum Ada {{ $tab==='report'?'Laporan':'Pengajuan' }}</h3>
            <p style="margin:0;font-size:15px">Anda belum {{ $tab==='report'?'mengirimkan laporan':'mengajukan kegiatan' }} apa pun.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Submit Modal --}}
<div id="submitBackdrop" class="event-modal-backdrop" hidden></div>
<div id="submitModal" class="event-modal" hidden>
<div class="event-modal-panel">
    <div class="event-modal-head">
        <div><h2 id="submitModalTitle">{{ $tab==='report'?'Form Laporan Kegiatan (LPJ)':'Form Pengajuan Kegiatan' }}</h2><p class="event-modal-subtitle">{{ $tab==='report'?'Kirim laporan pertanggungjawaban kegiatan':'Isi rincian kegiatan yang akan diajukan ke kemahasiswaan' }}</p></div>
        <button type="button" class="event-modal-close" onclick="closeSubmissionModal()">&times;</button>
    </div>
    <form id="submitModalForm" action="{{ route('pengurus-ukm.submissions.store') }}" method="POST" enctype="multipart/form-data" class="event-modal-form" style="padding:8px 32px 32px">
        @csrf
        <input type="hidden" name="kind" value="{{ $tab }}">
        <div style="display:grid;gap:20px">
            <div>
                <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Nama Kegiatan</label>
                <input type="text" name="title" required placeholder="Contoh: Workshop Artificial Intelligence" style="width:100%;height:44px;padding:0 14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none">
            </div>
            @if($tab === 'proposal')
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Jenis Kegiatan</label>
                    <select name="jenis_kegiatan" required style="width:100%;height:44px;padding:0 14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none;background:#fff">
                        <option value="">Pilih jenis kegiatan...</option>
                        <option value="Seminar">Seminar</option>
                        <option value="Workshop">Workshop</option>
                        <option value="Lomba">Lomba / Kompetisi</option>
                        <option value="Bakti Sosial">Bakti Sosial</option>
                        <option value="Pelatihan">Pelatihan</option>
                        <option value="Rapat">Rapat Organisasi</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Penanggung Jawab</label>
                    <input type="text" name="penanggung_jawab" required placeholder="Nama penanggung jawab" style="width:100%;height:44px;padding:0 14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Tanggal Kegiatan</label>
                    <input type="date" name="event_date" required {{ $tab === 'proposal' ? 'min='.date('Y-m-d') : '' }} style="width:100%;height:44px;padding:0 14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none">
                </div>
                <div>
                    <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Waktu</label>
                    <input type="time" name="waktu" required style="width:100%;height:44px;padding:0 14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none">
                </div>
            </div>
            <div>
                <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Lokasi / Platform</label>
                <input type="text" name="lokasi" required placeholder="Contoh: Auditorium Utama atau Zoom Meeting" style="width:100%;height:44px;padding:0 14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none">
            </div>
            <div>
                <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Estimasi Peserta</label>
                <input type="number" name="estimasi_peserta" min="1" placeholder="Jumlah estimasi peserta" style="width:50%;height:44px;padding:0 14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none">
            </div>
            @else
            <div>
                <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Tanggal Kegiatan</label>
                <input type="date" name="event_date" required style="width:100%;height:44px;padding:0 14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none">
            </div>
            @endif
            <div>
                <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">{{ $tab==='report'?'Ringkasan Laporan':'Deskripsi Kegiatan' }}</label>
                <textarea name="description" required placeholder="{{ $tab==='report'?'Tuliskan ringkasan pelaksanaan kegiatan...':'Jelaskan detail kegiatan yang akan dilaksanakan...' }}" rows="5" style="width:100%;padding:14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none;resize:vertical"></textarea>
            </div>
            @if($tab === 'report')
            <div>
                <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">Catatan Tambahan <span style="color:#94a3b8;font-weight:400">(opsional)</span></label>
                <textarea name="lpj_catatan" placeholder="Catatan atau kendala selama kegiatan..." rows="3" style="width:100%;padding:14px;border:1px solid #cbd5e1;border-radius:10px;font-size:15px;outline:none;resize:vertical"></textarea>
            </div>
            @endif
            <div>
                <label style="display:block;font-size:14px;font-weight:700;color:#374151;margin-bottom:8px">{{ $tab==='report'?'Dokumen LPJ (PDF)':'Proposal Kegiatan (PDF)' }}</label>
                <label style="display:block;border:2px dashed #cbd5e1;border-radius:12px;padding:24px;text-align:center;cursor:pointer;color:#64748b" onmouseover="this.style.borderColor='#6f3ba7'" onmouseout="this.style.borderColor='#cbd5e1'">
                    <svg style="display:block;margin:0 auto 8px;color:#94a3b8" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    <strong style="display:block;font-size:14px;color:#334155" id="file-upload-title">Klik untuk upload {{ $tab==='report'?'LPJ':'proposal' }}</strong>
                    <span style="font-size:13px" id="file-upload-desc">PDF (maks. 10MB)</span>
                    <input type="file" name="{{ $tab==='report'?'lpj_file':'proposal_file' }}" id="file-upload-input" accept=".pdf" style="display:none" onchange="
                        let file = this.files[0];
                        let title = document.getElementById('file-upload-title');
                        let desc = document.getElementById('file-upload-desc');
                        if(file) {
                            title.textContent = file.name;
                            desc.textContent = 'Dokumen dipilih';
                        } else {
                            title.textContent = 'Klik untuk upload {{ $tab==='report'?'LPJ':'proposal' }}';
                            desc.textContent = 'PDF (maks. 10MB)';
                        }
                    ">
                </label>
            </div>
            <div style="display:flex;gap:12px;margin-top:4px">
                <button type="submit" class="event-btn primary" style="flex:1;border:none;font-size:15px;background:#eab308;color:#111827">{{ $tab==='report'?'Kirim Laporan ke Kemahasiswaan':'Ajukan Kegiatan ke Kemahasiswaan' }}</button>
                <button type="button" class="event-btn" onclick="closeSubmissionModal()" style="padding:0 24px;font-size:15px">Batal</button>
            </div>
            <div style="padding:14px 16px;background:#dbeafe;border:1px solid #93c5fd;border-radius:10px;color:#1e40af;font-size:14px;display:flex;align-items:flex-start;gap:10px;font-weight:500">
                <svg style="flex-shrink:0;margin-top:2px" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <span><strong>Info:</strong> {{ $tab==='report'?'Laporan akan ditinjau oleh Departemen Kemahasiswaan.':'Pengajuan kegiatan akan ditinjau oleh Departemen Kemahasiswaan. Pastikan semua informasi lengkap dan akurat.' }}</span>
            </div>
        </div>
    </form>
</div>
</div>

{{-- Detail Modal --}}
<div id="detailBackdrop" class="event-modal-backdrop" hidden></div>
<div id="detailModal" class="event-modal" hidden>
<div class="event-modal-panel">
    <div class="event-modal-head">
        <div><h2 id="detailTitle">Detail</h2><p class="event-modal-subtitle" id="detailOrg"></p></div>
        <button type="button" class="event-modal-close" onclick="closeDetailModal()">&times;</button>
    </div>
    <div style="padding:8px 32px 32px">
        <div style="display:grid;gap:14px">
            <div id="dJenis" style="display:none"><strong style="color:#374151;font-size:13px">Jenis Kegiatan:</strong><p id="dJenisVal" style="margin:4px 0 0;color:#475569;font-size:15px"></p></div>
            <div id="dPJ" style="display:none"><strong style="color:#374151;font-size:13px">Penanggung Jawab:</strong><p id="dPJVal" style="margin:4px 0 0;color:#475569;font-size:15px"></p></div>
            <div><strong style="color:#374151;font-size:13px">Tanggal:</strong><span id="dDate" style="margin-left:8px;color:#475569;font-size:15px"></span></div>
            <div id="dWaktu" style="display:none"><strong style="color:#374151;font-size:13px">Waktu:</strong><span id="dWaktuVal" style="margin-left:8px;color:#475569;font-size:15px"></span></div>
            <div id="dLokasi" style="display:none"><strong style="color:#374151;font-size:13px">Lokasi:</strong><span id="dLokasiVal" style="margin-left:8px;color:#475569;font-size:15px"></span></div>
            <div id="dPeserta" style="display:none"><strong style="color:#374151;font-size:13px">Estimasi Peserta:</strong><span id="dPesertaVal" style="margin-left:8px;color:#475569;font-size:15px"></span></div>
            <div><strong style="color:#374151;font-size:13px">Deskripsi:</strong><p id="dDesc" style="margin:4px 0 0;color:#475569;line-height:1.7;font-size:15px"></p></div>
            <div id="dProposal" style="display:none"><a id="dProposalLink" href="#" target="_blank" style="color:#6f3ba7;font-weight:600">📄 Lihat Proposal (PDF)</a></div>
            <div id="dLpj" style="display:none"><a id="dLpjLink" href="#" target="_blank" style="color:#6f3ba7;font-weight:600">📄 Lihat LPJ (PDF)</a></div>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
const baseSubmitUrl = "{{ route('pengurus-ukm.submissions.store') }}";

function openSubmissionModal(){
    document.getElementById('submitModalForm').action = baseSubmitUrl;
    document.getElementById('submitModalTitle').textContent = "{{ $tab==='report'?'Form Laporan Kegiatan (LPJ)':'Form Pengajuan Kegiatan' }}";
    document.getElementById('submitModalForm').reset();
    var title = document.getElementById('file-upload-title');
    var desc = document.getElementById('file-upload-desc');
    if (title && desc) {
        title.textContent = 'Klik untuk upload {{ $tab==='report'?'LPJ':'proposal' }}';
        desc.textContent = 'PDF (maks. 10MB)';
    }
    var b=document.getElementById('submitBackdrop'),d=document.getElementById('submitModal');
    b.removeAttribute('hidden');d.removeAttribute('hidden');
    setTimeout(()=>{b.classList.add('open');d.classList.add('open')},10);
    document.body.style.overflow='hidden';
}

function openEditModal(btn) {
    var item = JSON.parse(btn.getAttribute('data-item'));
    document.getElementById('submitModalForm').action = `/pengurus-ukm/pengajuan-laporan/${item.id}/update`;
    document.getElementById('submitModalTitle').textContent = "Edit " + (item.kind === 'report' ? 'Laporan' : 'Pengajuan');
    
    // Populate fields
    const form = document.getElementById('submitModalForm');
    if(form.elements['title']) form.elements['title'].value = item.title;
    if(form.elements['event_date']) form.elements['event_date'].value = item.event_date;
    if(form.elements['description']) form.elements['description'].value = item.description;
    
    var title = document.getElementById('file-upload-title');
    var desc = document.getElementById('file-upload-desc');
    var docPath = item.kind === 'report' ? item.lpj_path : item.proposal_path;
    if (title && desc) {
        if (docPath) {
            title.textContent = 'Dokumen sudah diupload (klik untuk ganti)';
            desc.textContent = 'Biarkan jika tidak ingin mengubah';
        } else {
            title.textContent = 'Klik untuk upload ' + (item.kind === 'report' ? 'LPJ' : 'proposal');
            desc.textContent = 'PDF (maks. 10MB)';
        }
    }

    if(item.kind === 'proposal' || !item.kind) {
        if(form.elements['jenis_kegiatan']) form.elements['jenis_kegiatan'].value = item.jenis_kegiatan;
        if(form.elements['penanggung_jawab']) form.elements['penanggung_jawab'].value = item.penanggung_jawab;
        if(form.elements['waktu']) form.elements['waktu'].value = item.waktu;
        if(form.elements['lokasi']) form.elements['lokasi'].value = item.lokasi;
        if(form.elements['estimasi_peserta']) form.elements['estimasi_peserta'].value = item.estimasi_peserta;
    } else {
        if(form.elements['lpj_catatan']) form.elements['lpj_catatan'].value = item.lpj_catatan;
    }
    
    var b=document.getElementById('submitBackdrop'),d=document.getElementById('submitModal');
    b.removeAttribute('hidden');d.removeAttribute('hidden');
    setTimeout(()=>{b.classList.add('open');d.classList.add('open')},10);
    document.body.style.overflow='hidden';
}

function closeSubmissionModal(){var b=document.getElementById('submitBackdrop'),d=document.getElementById('submitModal');b.classList.remove('open');d.classList.remove('open');setTimeout(()=>{b.setAttribute('hidden','');d.setAttribute('hidden','')},200);document.body.style.overflow=''}
function openDetailModal(btn){
    var item = JSON.parse(btn.getAttribute('data-item'));
    document.getElementById('detailTitle').textContent=item.title;
    document.getElementById('detailOrg').textContent='Organisasi: '+(item.organization||'-');
    document.getElementById('dDesc').textContent=item.description||'-';
    document.getElementById('dDate').textContent=item.event_date||'-';
    function show(id,valId,val){var el=document.getElementById(id);if(val){el.style.display='block';if(valId)document.getElementById(valId).textContent=val}else{el.style.display='none'}}
    show('dJenis','dJenisVal',item.jenis_kegiatan);
    show('dPJ','dPJVal',item.penanggung_jawab);
    show('dWaktu','dWaktuVal',item.waktu);
    show('dLokasi','dLokasiVal',item.lokasi);
    show('dPeserta','dPesertaVal',item.estimasi_peserta);
    if(item.proposal_path){document.getElementById('dProposal').style.display='block';document.getElementById('dProposalLink').href='/storage/'+item.proposal_path}else{document.getElementById('dProposal').style.display='none'}
    if(item.lpj_path){document.getElementById('dLpj').style.display='block';document.getElementById('dLpjLink').href='/storage/'+item.lpj_path}else{document.getElementById('dLpj').style.display='none'}
    var b=document.getElementById('detailBackdrop'),d=document.getElementById('detailModal');b.removeAttribute('hidden');d.removeAttribute('hidden');setTimeout(()=>{b.classList.add('open');d.classList.add('open')},10);document.body.style.overflow='hidden'
}
function closeDetailModal(){var b=document.getElementById('detailBackdrop'),d=document.getElementById('detailModal');b.classList.remove('open');d.classList.remove('open');setTimeout(()=>{b.setAttribute('hidden','');d.setAttribute('hidden','')},200);document.body.style.overflow=''}
document.addEventListener('keydown',e=>{if(e.key==='Escape'){closeSubmissionModal();closeDetailModal()}});
document.getElementById('submitBackdrop').addEventListener('click',closeSubmissionModal);
document.getElementById('detailBackdrop').addEventListener('click',closeDetailModal);
</script>
@endpush
@endsection
