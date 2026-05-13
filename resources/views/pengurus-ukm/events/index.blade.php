@extends('layouts.app')

@section('title', 'Event Organisasi - Pengurus UKM')

@section('content')
<div class="pengurus-events-page">
    <div class="pengurus-events-head">
        <div>
            <h1>Event Organisasi</h1>
            <p>Kelola event dan berita kegiatan organisasi Anda</p>
        </div>
        @if($tab === 'pending')
        <button type="button" class="pengurus-add-event-btn" data-open-event-modal>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
            Buat Event Baru
        </button>
        @endif
    </div>

    <div class="event-modal-backdrop" data-event-modal-backdrop hidden></div>
    <section class="event-modal" data-event-modal aria-hidden="true" hidden>
        <div class="event-modal-panel" role="dialog" aria-modal="true" aria-labelledby="event-modal-title">
            <div class="event-modal-head">
                <div>
                    <h2 id="event-modal-title">Buat Event Baru</h2>
                    <p class="event-modal-subtitle" data-event-modal-subtitle>Buat event baru dengan poster, tanggal, dan link pendaftaran.</p>
                </div>
                <button type="button" class="event-modal-close" data-close-event-modal aria-label="Tutup">&times;</button>
            </div>

            <form class="event-modal-form" data-event-form action="{{ route('pengurus-ukm.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="" data-event-method>

                <label class="event-upload-box" data-event-upload-box>
                    <input type="file" name="poster" id="poster-input" accept="image/png,image/jpeg" hidden>
                    <div class="event-upload-visual" id="poster-visual">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="m21 15-5-5L5 21"></path></svg>
                        <strong>Klik untuk upload poster</strong>
                        <span id="poster-filename">PNG, JPG (maks. 5MB)</span>
                    </div>
                    <div class="event-upload-preview" id="poster-preview-wrapper" style="display: none;">
                        <img id="poster-preview-img" src="" alt="Poster Preview">
                        <div class="event-upload-change">Klik untuk mengganti poster</div>
                    </div>
                </label>

                <div class="event-form-field full">
                    <label>Nama Event</label>
                    <input type="text" name="title" placeholder="Contoh: Workshop AI & Machine Learning" required maxlength="255">
                </div>

                <div class="event-form-field full">
                    <label>Deskripsi</label>
                    <textarea name="description" placeholder="Jelaskan tentang event Anda..."></textarea>
                </div>

                <div class="event-form-field full">
                    <label>Kategori Event</label>
                    <select name="category" required style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px;">
                        <option value="Pelayanan/Sosial (PKM)">Pelayanan/Sosial (PKM)</option>
                        <option value="Kebudayaan">Kebudayaan</option>
                        <option value="Akademik">Akademik</option>
                        <option value="Keagamaan/Kerohanian">Keagamaan/Kerohanian</option>
                        <option value="Minat/Bakat">Minat/Bakat</option>
                        <option value="Kebangsaan">Kebangsaan</option>
                        <option value="Libur">Libur</option>
                        <option value="Tidak Boleh Berkegiatan">Tidak Boleh Berkegiatan</option>
                    </select>
                </div>

                <div class="event-form-grid two-col">
                    <div class="event-form-field">
                        <label>Tanggal</label>
                        <input type="date" name="event_date" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="event-form-field">
                        <label>Waktu</label>
                        <input type="time" name="event_time" required>
                    </div>
                </div>

                <div class="event-form-field full">
                    <label>Lokasi / Platform</label>
                    <input type="text" name="location" placeholder="Contoh: Auditorium Utama atau Zoom Meeting">
                </div>

                <div class="event-form-field full">
                    <label>Link Pendaftaran</label>
                    <input type="url" name="registration_link" placeholder="https://forms.gle/...">
                </div>

                <div class="event-form-actions">
                    <button type="submit" class="event-form-submit" data-event-submit>Ajukan Event</button>
                    <button type="button" class="event-form-delete" data-event-delete-btn style="display:none;" onclick="if(confirm('Yakin ingin menghapus event ini?')) document.querySelector('[data-event-delete-form]').submit();">Hapus Event</button>
                    <button type="button" class="event-form-cancel" data-close-event-modal>Batal</button>
                </div>
            </form>

            <form data-event-delete-form method="POST" action="" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </section>

    <div class="pengurus-events-tabs" aria-label="Kategori event">
        <a class="events-tab {{ $tab === 'pending' ? 'active' : '' }}" href="{{ route('pengurus-ukm.events.index', ['tab' => 'pending']) }}">
            Menunggu Persetujuan ({{ $pendingCount }})
        </a>
        <a class="events-tab {{ $tab === 'active' ? 'active' : '' }}" href="{{ route('pengurus-ukm.events.index', ['tab' => 'active']) }}">
            Event Aktif ({{ $activeCount }})
        </a>
        <a class="events-tab {{ $tab === 'completed' ? 'active' : '' }}" href="{{ route('pengurus-ukm.events.index', ['tab' => 'completed']) }}">
            Event Selesai ({{ $completedCount }})
        </a>
    </div>

    <section class="pengurus-events-grid" aria-label="Daftar event organisasi">
        @forelse($events as $event)
            <article class="event-card">
                <div class="event-card-cover" aria-hidden="true">
                    @if(!empty($event['poster_path']))
                        <img src="{{ asset('storage/' . $event['poster_path']) }}" alt="Poster {{ $event['title'] }}">
                    @else
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><path d="m21 15-5-5L5 21"></path></svg>
                    @endif
                </div>
                <div class="event-card-body">
                    <div class="event-card-row">
                        <h3>{{ $event['title'] }}</h3>
                        <span class="event-badge {{ $event['status'] }}">{{ $event['status_label'] }}</span>
                    </div>

                    <ul class="event-meta-list">
                        <li>
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            {{ $event['event_date_label'] }} • {{ $event['time_range'] }}
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 1 1 18 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            {{ $event['location'] }}
                        </li>

                    </ul>

                    @if(!empty($event['revision_note']) && in_array($event['status'], ['revision', 'rejected']))
                        <div style="margin-top: 12px; padding: 10px 12px; background: #fee2e2; border-left: 4px solid #ef4444; border-radius: 4px; font-size: 13px; color: #b91c1c;">
                            <strong>Catatan:</strong> {{ $event['revision_note'] }}
                        </div>
                    @endif

                    <div class="event-card-actions">
                        <button type="button" class="event-btn primary" data-payload="{{ json_encode($event) }}" onclick='window.openPengurusEventModal("{{ $event['status'] === 'completed' ? 'detail' : 'edit' }}", JSON.parse(this.getAttribute("data-payload")))'>
                            {{ $event['status'] === 'completed' ? 'Lihat Detail' : 'Lihat & Edit Event' }}
                        </button>
                        
                        @if($event['status'] === 'active')
                        <form action="{{ route('pengurus-ukm.events.complete', $event['id']) }}" method="POST" style="margin: 0; display: block;" onsubmit="return confirm('Tandai event ini sebagai selesai?')">
                            @csrf
                            <button type="submit" class="event-btn" style="width: 100%; background: #f0fdf4; color: #16a34a; border-color: #bbf7d0;">Selesaikan Event</button>
                        </form>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="event-empty">Belum ada event pada kategori ini.</div>
        @endforelse
    </section>
</div>
@endsection

@push('scripts')
<script>
window.openPengurusEventModal = function (mode, payload) {
    var modal = document.querySelector('[data-event-modal]');
    var backdrop = document.querySelector('[data-event-modal-backdrop]');
    var form = document.querySelector('[data-event-form]');
    var deleteForm = document.querySelector('[data-event-delete-form]');
    var methodInput = document.querySelector('[data-event-method]');
    var submitBtn = document.querySelector('[data-event-submit]');
    var title = document.querySelector('#event-modal-title');
    var subtitle = document.querySelector('[data-event-modal-subtitle]');
    var uploadBox = document.querySelector('[data-event-upload-box]');

    if (!modal || !backdrop || !form) {
        return;
    }

    var eventData = payload || {};
    var isEdit = mode === 'edit';
    var isDetail = mode === 'detail';

    form.action = isEdit && eventData.id ? ('/pengurus-ukm/events/' + eventData.id) : '{{ route('pengurus-ukm.events.store') }}';
    methodInput.value = isEdit ? 'PUT' : '';
    submitBtn.style.display = isDetail ? 'none' : 'inline-flex';
    submitBtn.textContent = isEdit ? 'Simpan Perubahan' : 'Ajukan Event';
    title.textContent = isEdit ? 'Ubah Event' : isDetail ? 'Detail Event' : 'Buat Event Baru';
    subtitle.textContent = isEdit
        ? 'Perbarui detail event dan simpan perubahan.'
        : isDetail
            ? 'Lihat detail event yang tersimpan.'
            : 'Buat event baru dengan poster, tanggal, dan link pendaftaran.';

    var deleteBtn = document.querySelector('[data-event-delete-btn]');
    var cancelBtn = form.querySelector('.event-form-cancel');

    if (deleteForm && deleteBtn) {
        deleteBtn.style.display = isEdit ? 'inline-flex' : 'none';
        deleteForm.action = eventData.id ? ('/pengurus-ukm/events/' + eventData.id) : '';
    }

    if (cancelBtn) {
        cancelBtn.style.display = isDetail ? 'none' : 'inline-block';
    }

    form.querySelector('[name="title"]').value = eventData.title || '';
    form.querySelector('[name="description"]').value = eventData.description || '';
    if (form.querySelector('[name="category"]')) {
        form.querySelector('[name="category"]').value = eventData.category || 'Akademik';
    }
    form.querySelector('[name="event_date"]').value = eventData.event_date || '';
    form.querySelector('[name="event_time"]').value = eventData.event_time || '';
    form.querySelector('[name="location"]').value = eventData.location || '';
    form.querySelector('[name="registration_link"]').value = eventData.registration_link || '';

    var posterVisual = document.getElementById('poster-visual');
    var posterPreviewWrapper = document.getElementById('poster-preview-wrapper');
    var posterPreviewImg = document.getElementById('poster-preview-img');

    Array.prototype.forEach.call(form.querySelectorAll('input, textarea, select'), function (field) {
        if (field.name === '_token' || field.name === '_method') {
            return;
        }
        if (field.type === 'file') {
            field.value = '';
            return;
        }
        field.disabled = isDetail;
    });

    if (posterVisual && posterPreviewWrapper && posterPreviewImg) {
        if ((isEdit || isDetail) && eventData.poster_path) {
            posterPreviewImg.src = '/storage/' + eventData.poster_path;
            posterVisual.style.display = 'none';
            posterPreviewWrapper.style.display = 'block';
        } else {
            posterPreviewImg.src = '';
            posterPreviewWrapper.style.display = 'none';
            posterVisual.style.display = 'grid';
        }
    }

    if (uploadBox) {
        uploadBox.classList.toggle('is-disabled', isDetail);
    }

    modal.hidden = false;
    backdrop.hidden = false;
    requestAnimationFrame(function () {
        modal.classList.add('open');
        backdrop.classList.add('open');
    });
};

window.closePengurusEventModal = function () {
    var modal = document.querySelector('[data-event-modal]');
    var backdrop = document.querySelector('[data-event-modal-backdrop]');
    if (!modal || !backdrop) {
        return;
    }

    modal.classList.remove('open');
    backdrop.classList.remove('open');
    setTimeout(function () {
        modal.hidden = true;
        backdrop.hidden = true;
    }, 160);
};

document.addEventListener('DOMContentLoaded', function () {
    var openBtn = document.querySelector('[data-open-event-modal]');
    var closeBtns = document.querySelectorAll('[data-close-event-modal]');
    var backdrop = document.querySelector('[data-event-modal-backdrop]');

    if (openBtn) {
        openBtn.addEventListener('click', function () {
            window.openPengurusEventModal('create');
        });
    }

    closeBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            window.closePengurusEventModal();
        });
    });

    if (backdrop) {
        backdrop.addEventListener('click', function () {
            window.closePengurusEventModal();
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            window.closePengurusEventModal();
        }
    });

    var posterInput = document.getElementById('poster-input');
    var posterVisual = document.getElementById('poster-visual');
    var posterPreviewWrapper = document.getElementById('poster-preview-wrapper');
    var posterPreviewImg = document.getElementById('poster-preview-img');
    var posterFilename = document.getElementById('poster-filename');

    if (posterInput) {
        posterInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (posterPreviewImg && posterVisual && posterPreviewWrapper) {
                        posterPreviewImg.src = e.target.result;
                        posterVisual.style.display = 'none';
                        posterPreviewWrapper.style.display = 'block';
                    }
                    if (posterFilename) {
                        posterFilename.textContent = file.name;
                    }
                }
                reader.readAsDataURL(file);
            } else {
                if (posterPreviewImg && posterVisual && posterPreviewWrapper) {
                    posterPreviewImg.src = '';
                    posterPreviewWrapper.style.display = 'none';
                    posterVisual.style.display = 'grid';
                }
                if (posterFilename) {
                    posterFilename.textContent = 'PNG, JPG (maks. 5MB)';
                }
            }
        });
    }
});
</script>
@endpush
