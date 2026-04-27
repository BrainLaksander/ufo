@extends('layouts.portal.pengurus')

@section('title', 'Event Organisasi')

@php
    $activeEvents = $activeEvents ?? [];
    $completedEvents = $completedEvents ?? [];
@endphp

@section('content')
<div class="ufo-kboard-page">
    <section class="ufo-kboard-section">
        <div class="ufo-kboard-item-head">
            <div>
                <h1 class="ufo-kboard-heading">Event Organisasi</h1>
                <p class="ufo-kboard-lead">Kelola event dan berita kegiatan organisasi Anda.</p>
            </div>

            <div class="ufo-kboard-item-actions mt-0">
                <button class="ufo-kboard-btn primary" type="button" data-bs-toggle="modal" data-bs-target="#modalCreateEvent">
                    <i class="bi bi-plus-lg"></i>
                    Buat Event
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
    </section>

    <!-- Modal Buat Event + Berita -->
    <div class="modal fade" id="modalCreateEvent" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('portal.pengurus.events.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Form Event + Berita</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @php $todayDate = now()->toDateString(); @endphp
                        <div class="ufo-kboard-row two">
                            <div class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta mb-2 d-block">Poster Event</span>
                                <input type="file" name="banner" class="form-control" accept="image/*">
                                <small class="text-muted">PNG, JPG (maks. 5MB)</small>
                            </div>

                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Nama Event</span>
                                <input type="text" name="name" class="ufo-kboard-field" placeholder="Contoh: Seminar Kepemimpinan 2026" value="{{ old('name') }}" required>
                            </label>

                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Deskripsi</span>
                                <textarea name="description" class="ufo-kboard-textarea" style="min-height: 100px;" placeholder="Tuliskan detail kegiatan..." required>{{ old('description') }}</textarea>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Tanggal Mulai</span>
                                <input type="date" name="start_date" class="ufo-kboard-field" value="{{ old('start_date', $todayDate) }}" min="{{ $todayDate }}" required>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Lokasi / Platform</span>
                                <input type="text" name="location" class="ufo-kboard-field" placeholder="Contoh: GK1 - Lt. 2 atau Zoom" value="{{ old('location') }}" required>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Kuota Peserta</span>
                                <input type="number" name="quota" class="ufo-kboard-field" value="{{ old('quota', 100) }}" min="1" required>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Status Awal</span>
                                <input type="text" class="ufo-kboard-field" value="Draft (Belum Publik)" readonly>
                                <input type="hidden" name="status" value="draft">
                            </label>

                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Judul Berita</span>
                                <input type="text" name="news_title" class="ufo-kboard-field" placeholder="Contoh: Suksesnya Acara Malam Akrab 2026" value="{{ old('news_title') }}" required>
                            </label>

                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Cerita / Konten Kegiatan</span>
                                <textarea name="news_content" class="ufo-kboard-textarea" style="min-height: 150px;" placeholder="Ceritakan keseruan acara Anda..." required>{{ old('news_content') }}</textarea>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="ufo-kboard-btn ghost" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="ufo-kboard-btn primary">Simpan Event & Berita</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <section class="ufo-kboard-section">
        <ul class="nav nav-pills mb-3" id="eventTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="event-active-tab" data-bs-toggle="pill" data-bs-target="#event-active-pane" type="button" role="tab" aria-controls="event-active-pane" aria-selected="true">
                    Event Aktif ({{ count($activeEvents) }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="event-completed-tab" data-bs-toggle="pill" data-bs-target="#event-completed-pane" type="button" role="tab" aria-controls="event-completed-pane" aria-selected="false">
                    Event Selesai ({{ count($completedEvents) }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="eventTabsContent">
            <div class="tab-pane fade show active" id="event-active-pane" role="tabpanel" aria-labelledby="event-active-tab" tabindex="0">
                <div class="ufo-pg-event-grid">
                    @foreach($activeEvents as $event)
                        <article class="ufo-pg-event-card" 
                            data-id="{{ $event['id'] }}"
                            data-title="{{ $event['title'] }}"
                            data-description="{{ $event['description'] }}"
                            data-date="{{ $event['date'] }}"
                            data-raw-date="{{ $event['raw_date'] }}"
                            data-time="{{ $event['time'] }}"
                            data-location="{{ $event['location'] }}"
                            data-quota="{{ $event['quota'] }}"
                            data-registrants="{{ $event['registrants'] }}"
                            data-status="{{ $event['status'] }}"
                            data-raw-status="{{ $event['raw_status'] }}"
                            data-pill="{{ $event['pill'] }}"
                            data-banner="{{ $event['banner'] }}">
                            <div class="ufo-pg-event-cover">
                                @if($event['banner'])
                                    <img src="{{ $event['banner'] }}" alt="{{ $event['title'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="bi bi-image"></i>
                                @endif
                            </div>

                            <div class="ufo-pg-event-card-body">
                                <div class="ufo-kboard-item-head mb-2">
                                    <h3 class="ufo-kboard-item-title">{{ $event['title'] }}</h3>
                                    <span class="ufo-kboard-pill {{ $event['pill'] }}">{{ $event['status'] }}</span>
                                </div>

                                <p class="ufo-kboard-item-meta mb-1"><i class="bi bi-calendar3"></i> {{ $event['date'] }} • {{ $event['time'] }}</p>
                                <p class="ufo-kboard-item-meta mb-1"><i class="bi bi-geo-alt"></i> {{ $event['location'] }}</p>
                                <p class="ufo-kboard-item-meta"><i class="bi bi-people"></i> {{ $event['registrants'] }} pendaftar</p>

                                <div class="ufo-kboard-item-actions mt-3">
                                    @if(($event['raw_status'] ?? '') === 'draft')
                                        <form method="POST" action="{{ route('portal.pengurus.events.submit', ['id' => $event['id']]) }}" onsubmit="return confirm('Submit event ini untuk publikasi?')" style="display:inline;">
                                            @csrf
                                            <button class="ufo-kboard-btn gold" type="submit">Submit</button>
                                        </form>
                                    @endif
                                    <button class="ufo-kboard-btn primary btn-edit-event" type="button">Edit Event</button>
                                    <button class="ufo-kboard-btn ghost btn-detail-event" type="button">Lihat Detail</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="tab-pane fade" id="event-completed-pane" role="tabpanel" aria-labelledby="event-completed-tab" tabindex="0">
                <div class="ufo-pg-event-grid">
                    @foreach($completedEvents as $event)
                        <article class="ufo-pg-event-card"
                            data-id="{{ $event['id'] }}"
                            data-title="{{ $event['title'] }}"
                            data-description="{{ $event['description'] }}"
                            data-date="{{ $event['date'] }}"
                            data-raw-date="{{ $event['raw_date'] }}"
                            data-time="{{ $event['time'] }}"
                            data-location="{{ $event['location'] }}"
                            data-quota="{{ $event['quota'] }}"
                            data-participants="{{ $event['participants'] }}"
                            data-status="{{ $event['status'] }}"
                            data-raw-status="{{ $event['raw_status'] }}"
                            data-pill="{{ $event['pill'] }}"
                            data-banner="{{ $event['banner'] }}">
                            <div class="ufo-pg-event-cover muted">
                                @if($event['banner'])
                                    <img src="{{ $event['banner'] }}" alt="{{ $event['title'] }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.7;">
                                @else
                                    <i class="bi bi-image"></i>
                                @endif
                            </div>

                            <div class="ufo-pg-event-card-body">
                                <div class="ufo-kboard-item-head mb-2">
                                    <h3 class="ufo-kboard-item-title">{{ $event['title'] }}</h3>
                                    @if($event['has_news'])
                                        <span class="ufo-kboard-pill approved">Ada Berita</span>
                                    @else
                                        <span class="ufo-kboard-pill draft">Belum Ada Berita</span>
                                    @endif
                                </div>

                                <p class="ufo-kboard-item-meta mb-1"><i class="bi bi-calendar3"></i> {{ $event['date'] }}</p>
                                <p class="ufo-kboard-item-meta mb-1"><i class="bi bi-geo-alt"></i> {{ $event['location'] }}</p>
                                <p class="ufo-kboard-item-meta"><i class="bi bi-people"></i> {{ $event['participants'] }} peserta</p>

                                <div class="ufo-kboard-item-actions mt-3">
                                    @if($event['has_news'])
                                        <button class="ufo-kboard-btn primary" data-bs-toggle="modal" data-bs-target="#ufoActionModal" data-modal-title="Lihat Berita" data-modal-message="Modal ini menampilkan ringkasan berita event." type="button">Lihat Berita</button>
                                    @else
                                        <button class="ufo-kboard-btn gold" data-bs-toggle="modal" data-bs-target="#ufoActionModal" data-modal-title="Buat Berita Event" data-modal-message="Modal ini dibuka untuk membuat berita event baru." type="button">Buat Berita Event</button>
                                    @endif
                                    <button class="ufo-kboard-btn ghost btn-detail-event" type="button">Detail</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Detail Event -->
    <div class="modal fade" id="modalEventDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="eventDetailBanner" style="height: 250px; background: #eee; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <i class="bi bi-image fs-1 text-muted"></i>
                    </div>
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h2 id="eventDetailTitle" class="ufo-kboard-heading"></h2>
                                <span id="eventDetailStatus" class="ufo-kboard-pill"></span>
                            </div>
                        </div>
                        <div class="ufo-kboard-row three mb-4">
                            <div class="ufo-kboard-stat blue">
                                <p class="mb-1 text-muted">Tanggal & Waktu</p>
                                <h6 id="eventDetailDate" class="mb-0"></h6>
                            </div>
                            <div class="ufo-kboard-stat gold">
                                <p class="mb-1 text-muted">Lokasi</p>
                                <h6 id="eventDetailLocation" class="mb-0"></h6>
                            </div>
                            <div class="ufo-kboard-stat purple">
                                <p class="mb-1 text-muted">Pendaftar</p>
                                <h6 id="eventDetailQuota" class="mb-0"></h6>
                            </div>
                        </div>
                        <h6 class="fw-bold mb-2">Deskripsi Kegiatan</h6>
                        <p id="eventDetailDescription" class="ufo-kboard-item-text"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="ufo-kboard-btn ghost" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Event -->
    <div class="modal fade" id="modalEditEvent" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="formEditEvent" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="ufo-kboard-row two">
                            <div class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta mb-2 d-block">Poster Event (Opsional)</span>
                                <input type="file" name="banner" class="form-control" accept="image/*">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah poster</small>
                            </div>

                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Nama Event</span>
                                <input type="text" name="name" id="editEventName" class="ufo-kboard-field" required>
                            </label>

                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Deskripsi</span>
                                <textarea name="description" id="editEventDescription" class="ufo-kboard-textarea" style="min-height: 100px;" required></textarea>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Tanggal Mulai</span>
                                <input type="date" name="start_date" id="editEventDate" class="ufo-kboard-field" min="{{ now()->toDateString() }}" required>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Lokasi / Platform</span>
                                <input type="text" name="location" id="editEventLocation" class="ufo-kboard-field" required>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Kuota Peserta</span>
                                <input type="number" name="quota" id="editEventQuota" class="ufo-kboard-field" min="1" required>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Status</span>
                                <select name="status" id="editEventStatus" class="ufo-kboard-select">
                                    <option value="draft">Draft</option>
                                    <option value="approved">Publik</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="ufo-kboard-btn ghost" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="ufo-kboard-btn primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hasCreateErrors = @json($errors->any());
    if (hasCreateErrors) {
        new bootstrap.Modal(document.getElementById('modalCreateEvent')).show();
    }

    const detailModal = new bootstrap.Modal(document.getElementById('modalEventDetail'));
    const editModal = new bootstrap.Modal(document.getElementById('modalEditEvent'));

    document.querySelectorAll('.btn-detail-event').forEach(btn => {
        btn.addEventListener('click', function() {
            const card = this.closest('.ufo-pg-event-card');
            const data = card.dataset;

            document.getElementById('eventDetailTitle').textContent = data.title;
            document.getElementById('eventDetailDescription').textContent = data.description;
            document.getElementById('eventDetailDate').textContent = data.date + ' • ' + data.time;
            document.getElementById('eventDetailLocation').textContent = data.location;
            document.getElementById('eventDetailQuota').textContent = data.registrants + ' / ' + data.quota;
            
            const statusPill = document.getElementById('eventDetailStatus');
            statusPill.textContent = data.status;
            statusPill.className = 'ufo-kboard-pill ' + data.pill;

            const bannerWrap = document.getElementById('eventDetailBanner');
            if (data.banner && data.banner !== 'null') {
                bannerWrap.innerHTML = `<img src="${data.banner}" style="width: 100%; height: 100%; object-fit: cover;">`;
            } else {
                bannerWrap.innerHTML = '<i class="bi bi-image fs-1 text-muted"></i>';
            }

            detailModal.show();
        });
    });

    document.querySelectorAll('.btn-edit-event').forEach(btn => {
        btn.addEventListener('click', function() {
            const card = this.closest('.ufo-pg-event-card');
            const data = card.dataset;

            document.getElementById('formEditEvent').action = `/pengurus/events/${data.id}/update`;
            document.getElementById('editEventName').value = data.title;
            document.getElementById('editEventDescription').value = data.description;
            document.getElementById('editEventDate').value = data.rawDate;
            document.getElementById('editEventLocation').value = data.location;
            document.getElementById('editEventQuota').value = data.quota;
            document.getElementById('editEventStatus').value = data.rawStatus;

            editModal.show();
        });
    });
});
</script>
@endsection
