@extends('layouts.portal.pengurus')

@section('title', 'Pengumuman Organisasi')

@php
    $activeAnnouncements = $activeAnnouncements ?? [];
    $allAnnouncements = $allAnnouncements ?? [];
    $eventOptions = $eventOptions ?? [];
@endphp

@section('content')
<div class="ufo-kboard-page">
    <section class="ufo-kboard-section">
        <div class="ufo-kboard-item-head">
            <div>
                <h1 class="ufo-kboard-heading">Pengumuman Organisasi</h1>
                <p class="ufo-kboard-lead">Kelola pengumuman event organisasi Anda dengan mudah.</p>
            </div>

            <button class="ufo-kboard-btn primary" type="button" data-bs-toggle="modal" data-bs-target="#modalCreateAnnouncement">
                <i class="bi bi-plus-lg"></i>
                Buat Pengumuman
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-3 mb-0" role="alert">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mt-3 mb-0" role="alert">{{ session('error') }}</div>
        @endif
    </section>

    <!-- Modal Buat Pengumuman Baru -->
    <div class="modal fade" id="modalCreateAnnouncement" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('portal.pengurus.announcements.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Form Pengumuman Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="ufo-kboard-row two">
                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Judul Pengumuman</span>
                                <input type="text" name="title" class="ufo-kboard-field" placeholder="Contoh: Pendaftaran Anggota Baru Dibuka!" required>
                            </label>

                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Isi / Deskripsi Pengumuman</span>
                                <textarea name="description" class="ufo-kboard-textarea" style="min-height: 120px;" placeholder="Tuliskan isi pengumuman secara detail..." required></textarea>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Tanggal Mulai Tampil</span>
                                <input type="date" name="start_date" class="ufo-kboard-field" value="{{ date('Y-m-d') }}" required>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Tanggal Berakhir Tampil</span>
                                <input type="date" name="end_date" class="ufo-kboard-field" required>
                            </label>

                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Event Terkait (Opsional)</span>
                                <select name="event_id" class="ufo-kboard-select">
                                    <option value="">Pilih event jika pengumuman ini terkait event tertentu</option>
                                    @foreach(($eventOptions ?? []) as $event)
                                        <option value="{{ $event['id'] }}">{{ $event['name'] }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="ufo-kboard-btn ghost" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="ufo-kboard-btn primary">Publikasikan Pengumuman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <section class="ufo-kboard-section">
        <ul class="nav nav-pills mb-3" id="announcementTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="announcement-active-tab" data-bs-toggle="pill" data-bs-target="#announcement-active-pane" type="button" role="tab" aria-controls="announcement-active-pane" aria-selected="true">
                    Pengumuman Aktif ({{ count($activeAnnouncements) }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="announcement-all-tab" data-bs-toggle="pill" data-bs-target="#announcement-all-pane" type="button" role="tab" aria-controls="announcement-all-pane" aria-selected="false">
                    Semua Pengumuman ({{ count($allAnnouncements) }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="announcementTabsContent">
            <div class="tab-pane fade show active" id="announcement-active-pane" role="tabpanel" aria-labelledby="announcement-active-tab" tabindex="0">
                <div class="ufo-kboard-list">
                    @forelse($activeAnnouncements as $item)
                        <article class="ufo-kboard-item"
                            data-id="{{ $item['id'] }}"
                            data-title="{{ $item['title'] }}"
                            data-description="{{ $item['description'] }}"
                            data-full-content="{{ $item['full_content'] }}"
                            data-start-date="{{ $item['start_date'] }}"
                            data-raw-start-date="{{ $item['raw_start_date'] }}"
                            data-end-date="{{ $item['end_date'] }}"
                            data-raw-end-date="{{ $item['raw_end_date'] }}"
                            data-status="{{ $item['status'] }}"
                            data-raw-status="{{ $item['raw_status'] }}"
                            data-pill="{{ $item['pill'] }}">
                            <div class="ufo-kboard-item-head">
                                <div>
                                    <h3 class="ufo-kboard-item-title">{{ $item['title'] }}</h3>
                                    <p class="ufo-kboard-item-meta">{{ $item['start_date'] }} - {{ $item['end_date'] }}</p>
                                </div>
                                <span class="ufo-kboard-pill {{ $item['pill'] }}">{{ $item['status'] }}</span>
                            </div>

                            <p class="ufo-kboard-item-text">{{ $item['description'] }}</p>

                            <div class="ufo-kboard-item-actions">
                                <button class="ufo-kboard-btn primary btn-edit-announcement" type="button">Edit</button>
                                <button class="ufo-kboard-btn ghost btn-detail-announcement" type="button">Lihat</button>
                            </div>
                        </article>
                    @empty
                        <p class="ufo-kboard-item-meta mb-0">Belum ada pengumuman aktif.</p>
                    @endforelse
                </div>
            </div>

            <div class="tab-pane fade" id="announcement-all-pane" role="tabpanel" aria-labelledby="announcement-all-tab" tabindex="0">
                <div class="ufo-kboard-list">
                    @forelse($allAnnouncements as $item)
                        <article class="ufo-kboard-item"
                            data-id="{{ $item['id'] }}"
                            data-title="{{ $item['title'] }}"
                            data-description="{{ $item['description'] }}"
                            data-full-content="{{ $item['full_content'] }}"
                            data-start-date="{{ $item['start_date'] }}"
                            data-raw-start-date="{{ $item['raw_start_date'] }}"
                            data-end-date="{{ $item['end_date'] }}"
                            data-raw-end-date="{{ $item['raw_end_date'] }}"
                            data-status="{{ $item['status'] }}"
                            data-raw-status="{{ $item['raw_status'] }}"
                            data-pill="{{ $item['pill'] }}">
                            <div class="ufo-kboard-item-head">
                                <div>
                                    <h3 class="ufo-kboard-item-title">{{ $item['title'] }}</h3>
                                    <p class="ufo-kboard-item-meta">{{ $item['start_date'] }} - {{ $item['end_date'] }}</p>
                                </div>
                                <span class="ufo-kboard-pill {{ $item['pill'] }}">{{ $item['status'] }}</span>
                            </div>

                            <p class="ufo-kboard-item-text">{{ $item['description'] }}</p>

                            <div class="ufo-kboard-item-actions">
                                <button class="ufo-kboard-btn ghost btn-detail-announcement" type="button">Lihat</button>
                                @if($item['status'] === 'Aktif')
                                    <button class="ufo-kboard-btn primary btn-edit-announcement" type="button">Edit</button>
                                @endif
                            </div>
                        </article>
                    @empty
                        <p class="ufo-kboard-item-meta mb-0">Belum ada pengumuman yang ditambahkan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Detail Pengumuman -->
    <div class="modal fade" id="modalAnnouncementDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pengumuman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <span id="annDetailStatus" class="ufo-kboard-pill mb-2 d-inline-block"></span>
                        <h2 id="annDetailTitle" class="ufo-kboard-heading"></h2>
                        <p id="annDetailDate" class="ufo-kboard-item-meta text-muted"></p>
                    </div>
                    <hr>
                    <div id="annDetailContent" class="ufo-kboard-item-text" style="white-space: pre-wrap;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="ufo-kboard-btn ghost" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pengumuman -->
    <div class="modal fade" id="modalEditAnnouncement" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="formEditAnnouncement" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Pengumuman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="ufo-kboard-row two">
                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Judul Pengumuman</span>
                                <input type="text" name="title" id="editAnnTitle" class="ufo-kboard-field" required>
                            </label>

                            <label class="ufo-kboard-span-full">
                                <span class="ufo-kboard-item-meta">Isi / Deskripsi Pengumuman</span>
                                <textarea name="description" id="editAnnContent" class="ufo-kboard-textarea" style="min-height: 120px;" required></textarea>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Tanggal Mulai Tampil</span>
                                <input type="date" name="start_date" id="editAnnStartDate" class="ufo-kboard-field" required>
                            </label>

                            <label>
                                <span class="ufo-kboard-item-meta">Tanggal Berakhir Tampil</span>
                                <input type="date" name="end_date" id="editAnnEndDate" class="ufo-kboard-field" required>
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
    const detailModal = new bootstrap.Modal(document.getElementById('modalAnnouncementDetail'));
    const editModal = new bootstrap.Modal(document.getElementById('modalEditAnnouncement'));

    document.querySelectorAll('.btn-detail-announcement').forEach(btn => {
        btn.addEventListener('click', function() {
            const card = this.closest('.ufo-kboard-item');
            const data = card.dataset;

            document.getElementById('annDetailTitle').textContent = data.title;
            document.getElementById('annDetailContent').textContent = data.fullContent;
            document.getElementById('annDetailDate').textContent = 'Tayang: ' + data.startDate + ' - ' + data.endDate;
            
            const statusPill = document.getElementById('annDetailStatus');
            statusPill.textContent = data.status;
            statusPill.className = 'ufo-kboard-pill ' + data.pill;

            detailModal.show();
        });
    });

    document.querySelectorAll('.btn-edit-announcement').forEach(btn => {
        btn.addEventListener('click', function() {
            const card = this.closest('.ufo-kboard-item');
            const data = card.dataset;

            document.getElementById('formEditAnnouncement').action = `/pengurus/announcements/${data.id}/update`;
            document.getElementById('editAnnTitle').value = data.title;
            document.getElementById('editAnnContent').value = data.fullContent;
            document.getElementById('editAnnStartDate').value = data.rawStartDate;
            document.getElementById('editAnnEndDate').value = data.rawEndDate;

            editModal.show();
        });
    });
});
</script>
@endsection
