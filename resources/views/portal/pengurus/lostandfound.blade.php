@extends('layouts.portal.pengurus')

@section('title', 'Lost & Found')

@php
    $isBem = $isBem ?? false;
    $items = $items ?? [];
    $lostItems = $lostItems ?? [];
    $foundItems = $foundItems ?? [];
    $openLostOptions = $openLostOptions ?? [];
    $priorityItems = $priorityItems ?? [];
    $statusLabel = $statusLabel ?? [];
    $statusPill = $statusPill ?? [];
    $statusOptions = collect($statusLabel)
        ->filter(fn ($label, $code) => trim((string) $code) !== '' && trim((string) $label) !== '')
        ->all();
    $itemStatusOptions = collect($items)
        ->pluck('item_status')
        ->filter(fn ($value) => trim((string) $value) !== '')
        ->unique()
        ->values()
        ->all();
@endphp

@section('content')
<div class="ufo-kboard-page">
    <section class="ufo-kboard-section">
        <h1 class="ufo-kboard-heading">Lost &amp; Found</h1>
        <p class="ufo-kboard-lead">
            {{ $isBem
                ? 'Semua laporan kehilangan dan penemuan diproses terpusat melalui BEM. Laporan penemuan wajib disertai foto real-time dan ditautkan ke laporan kehilangan.'
                : 'Laporkan atau cari barang yang hilang atau ditemukan.' }}
        </p>

        <div class="ufo-kboard-row three mt-3">
            <article class="ufo-kboard-stat blue">
                <h3>{{ count($lostItems) }}</h3>
                <p>Barang Hilang</p>
            </article>
            <article class="ufo-kboard-stat gold">
                <h3>{{ count($foundItems) }}</h3>
                <p>Barang Ditemukan</p>
            </article>
            <article class="ufo-kboard-stat green">
                <button class="ufo-kboard-btn light w-100 h-100" data-bs-toggle="modal" data-bs-target="#createLostFoundModal">
                    <i class="bi bi-plus-circle mb-2 d-block fs-4"></i>
                    Input Laporan BEM
                </button>
            </article>
        </div>

        <!-- Create Lost & Found Modal -->
        <div class="modal fade" id="createLostFoundModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('portal.pengurus.lostandfound.store') }}" method="POST" class="modal-content" id="bemLostFoundForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Input Laporan Lost &amp; Found (BEM)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="item_name" class="form-control" placeholder="Contoh: Kunci Motor Honda, KTM atas nama..." required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jenis Laporan</label>
                                <select name="type" id="lfTypeSelect" class="form-select" required>
                                    <option value="lost">Barang Hilang</option>
                                    <option value="found">Barang Ditemukan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Pelapor</label>
                                <input type="text" name="reporter_name" class="form-control" placeholder="Nama mahasiswa / penemu" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kontak Pelapor</label>
                                <input type="text" name="reporter_contact" class="form-control" placeholder="No. WA atau email" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Lokasi Ditemukan / Hilang</label>
                                <input type="text" name="location_found" class="form-control" placeholder="Contoh: Depan GK1, Kantin, dll" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Deskripsi & Ciri-ciri</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Jelaskan detail barang agar mudah diidentifikasi" required></textarea>
                            </div>

                            <div class="col-12 d-none" id="linkedLostContainer">
                                <label class="form-label">Tautkan Ke Laporan Barang Hilang</label>
                                <select name="linked_lost_item_id" id="linkedLostSelect" class="form-select">
                                    <option value="">Pilih laporan kehilangan yang sesuai</option>
                                    @foreach($openLostOptions as $option)
                                        <option value="{{ $option['id'] }}">#{{ $option['id'] }} - {{ $option['title'] }} ({{ $option['location'] }})</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Wajib untuk laporan Barang Ditemukan agar tidak salah pencocokan.</div>
                            </div>

                            <div class="col-12 d-none" id="cameraContainer">
                                <label class="form-label">Foto Real-Time (Wajib untuk Barang Ditemukan)</label>
                                <div class="border rounded p-2 bg-light">
                                    <video id="lfCameraPreview" class="w-100 rounded" autoplay playsinline muted style="max-height: 280px; object-fit: cover;"></video>
                                    <canvas id="lfCameraCanvas" class="d-none"></canvas>
                                    <input type="hidden" name="photo_data" id="lfPhotoData">
                                    <div class="d-flex gap-2 mt-2">
                                        <button type="button" class="btn btn-outline-primary" id="lfStartCameraBtn">Aktifkan Kamera</button>
                                        <button type="button" class="btn btn-primary" id="lfCaptureBtn">Ambil Foto</button>
                                    </div>
                                    <div class="form-text">Upload file dinonaktifkan. Gunakan kamera langsung saat pelaporan.</div>
                                </div>
                            </div>

                            <div class="col-12 d-none" id="capturedPreviewContainer">
                                <label class="form-label">Preview Foto Tertangkap</label>
                                <img id="capturedPreview" src="" alt="Preview foto real-time" class="img-fluid rounded border" style="max-height: 220px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    @if(count($priorityItems) > 0)
        <section class="ufo-kboard-section ufo-pg-priority-wrap">
            <div class="ufo-kboard-item-head mb-2">
                <h3 class="ufo-kboard-item-title text-white mb-0">Prioritas Tinggi</h3>
                <span class="ufo-kboard-pill pending">{{ count($priorityItems) }} item aktif</span>
            </div>

            <div class="ufo-pg-priority-grid">
                @foreach($priorityItems as $item)
                    <article>
                        <div class="top">
                            <span class="ufo-kboard-pill {{ $item['type'] === 'lost' ? 'rejected' : 'approved' }}">
                                {{ $item['type'] === 'lost' ? 'Hilang' : 'Ditemukan' }}
                            </span>
                            <span class="ufo-kboard-pill draft">{{ str_replace('_', ' ', ucfirst($item['item_status'])) }}</span>
                        </div>
                        <h4>{{ $item['title'] }}</h4>
                        <p>{{ $item['description'] }}</p>
                        <small><i class="bi bi-geo-alt"></i> {{ $item['location'] }}</small>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section class="ufo-kboard-section">
        <div class="ufo-kboard-toolbar">
            <input type="text" class="ufo-kboard-field" placeholder="Cari barang, pelapor, atau lokasi...">
            <select class="ufo-kboard-select">
                <option value="">Semua Status</option>
                @foreach($statusOptions as $code => $label)
                    <option value="{{ $code }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </section>

    <section class="ufo-kboard-section">
        <h3 class="ufo-kboard-item-title mb-3">Barang Hilang</h3>
        <div class="ufo-kboard-list">
            @forelse($lostItems as $item)
                <article class="ufo-pg-lf-card">
                    <div class="ufo-pg-lf-main">
                        <div class="ufo-pg-lf-icon lost">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>

                        <div class="ufo-pg-lf-body">
                            <div class="ufo-kboard-item-head mb-2">
                                <div>
                                    <h3 class="ufo-kboard-item-title">{{ $item['title'] }}</h3>
                                    <p class="ufo-kboard-item-meta">Barang Hilang</p>
                                </div>
                                <div class="d-flex flex-wrap gap-1 justify-content-end">
                                    @if($item['priority'])
                                        <span class="ufo-kboard-pill pending">Prioritas</span>
                                    @endif
                                    <span class="ufo-kboard-pill {{ $statusPill[$item['status']] ?? 'draft' }}">{{ $statusLabel[$item['status']] ?? \Illuminate\Support\Str::title(str_replace('_', ' ', (string) $item['status'])) }}</span>
                                    <span class="ufo-kboard-pill draft">{{ str_replace('_', ' ', ucfirst($item['item_status'])) }}</span>
                                </div>
                            </div>

                            <p class="ufo-kboard-item-text mb-2">{{ $item['description'] }}</p>

                            <div class="ufo-pg-lf-meta">
                                <span><i class="bi bi-geo-alt"></i> {{ $item['location'] }}</span>
                                <span><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($item['date'])->translatedFormat('d M Y') }}</span>
                                <span><i class="bi bi-person"></i> {{ $item['reporter'] }}</span>
                                    @if(!empty($item['reporter_contact']))
                                        <span><i class="bi bi-telephone"></i> {{ $item['reporter_contact'] }}</span>
                                    @endif
                            </div>

                            @if(!empty($item['notes']))
                                <div class="ufo-pg-lf-note">
                                    <strong>Catatan BEM:</strong>
                                    <span>{{ $item['notes'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <p class="ufo-kboard-item-meta mb-0">Belum ada laporan Barang Hilang.</p>
            @endforelse
        </div>
    </section>

    <section class="ufo-kboard-section">
        <h3 class="ufo-kboard-item-title mb-3">Barang Hilang Ditemukan</h3>
        <div class="ufo-kboard-list">
            @forelse($foundItems as $item)
                <article class="ufo-pg-lf-card">
                    <div class="ufo-pg-lf-main">
                        <div class="ufo-pg-lf-icon found">
                            <i class="bi bi-check-circle"></i>
                        </div>

                        <div class="ufo-pg-lf-body">
                            <div class="ufo-kboard-item-head mb-2">
                                <div>
                                    <h3 class="ufo-kboard-item-title">{{ $item['title'] }}</h3>
                                    <p class="ufo-kboard-item-meta">Barang Ditemukan</p>
                                </div>
                                <div class="d-flex flex-wrap gap-1 justify-content-end">
                                    <span class="ufo-kboard-pill {{ $statusPill[$item['status']] ?? 'draft' }}">{{ $statusLabel[$item['status']] ?? \Illuminate\Support\Str::title(str_replace('_', ' ', (string) $item['status'])) }}</span>
                                </div>
                            </div>

                            <p class="ufo-kboard-item-text mb-2">{{ $item['description'] }}</p>

                            @if(!empty($item['linked_item_name']))
                                <div class="ufo-pg-lf-note mb-2">
                                    <strong>Tautan kehilangan:</strong>
                                    <span>{{ $item['linked_item_name'] }}</span>
                                </div>
                            @endif

                            @if(!empty($item['image']))
                                <img src="{{ $item['image'] }}" alt="Foto barang ditemukan" class="img-fluid rounded border mb-2" style="max-height: 220px; object-fit: cover;">
                            @endif

                            <div class="ufo-pg-lf-meta">
                                <span><i class="bi bi-geo-alt"></i> {{ $item['location'] }}</span>
                                <span><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($item['date'])->translatedFormat('d M Y') }}</span>
                                <span><i class="bi bi-person"></i> {{ $item['reporter'] }}</span>
                                @if(!empty($item['reporter_contact']))
                                    <span><i class="bi bi-telephone"></i> {{ $item['reporter_contact'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <p class="ufo-kboard-item-meta mb-0">Belum ada laporan Barang Ditemukan.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var modal = document.getElementById('createLostFoundModal');
    if (!modal) return;

    var typeSelect = document.getElementById('lfTypeSelect');
    var linkedLostContainer = document.getElementById('linkedLostContainer');
    var linkedLostSelect = document.getElementById('linkedLostSelect');
    var cameraContainer = document.getElementById('cameraContainer');
    var video = document.getElementById('lfCameraPreview');
    var canvas = document.getElementById('lfCameraCanvas');
    var startCameraBtn = document.getElementById('lfStartCameraBtn');
    var captureBtn = document.getElementById('lfCaptureBtn');
    var photoDataInput = document.getElementById('lfPhotoData');
    var capturedPreviewContainer = document.getElementById('capturedPreviewContainer');
    var capturedPreview = document.getElementById('capturedPreview');

    var stream = null;

    function isFoundType() {
        return typeSelect && typeSelect.value === 'found';
    }

    function setFoundModeUI() {
        var foundMode = isFoundType();

        if (linkedLostContainer) linkedLostContainer.classList.toggle('d-none', !foundMode);
        if (cameraContainer) cameraContainer.classList.toggle('d-none', !foundMode);

        if (linkedLostSelect) {
            linkedLostSelect.required = foundMode;
        }

        if (!foundMode) {
            if (photoDataInput) photoDataInput.value = '';
            if (capturedPreview) capturedPreview.src = '';
            if (capturedPreviewContainer) capturedPreviewContainer.classList.add('d-none');
            stopCamera();
        }
    }

    async function startCamera() {
        if (!video || !navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            window.alert('Browser tidak mendukung kamera real-time. Gunakan browser modern (Chrome/Edge/Safari terbaru).');
            return;
        }

        try {
            stopCamera();
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment' },
                audio: false,
            });
            video.srcObject = stream;
        } catch (error) {
            window.alert('Kamera tidak dapat diakses. Pastikan izin kamera diizinkan.');
        }
    }

    function stopCamera() {
        if (!stream) return;
        stream.getTracks().forEach(function (track) { track.stop(); });
        stream = null;
        if (video) video.srcObject = null;
    }

    function capturePhoto() {
        if (!video || !canvas || !photoDataInput) return;
        if (!stream) {
            window.alert('Aktifkan kamera terlebih dahulu.');
            return;
        }

        var width = video.videoWidth || 1280;
        var height = video.videoHeight || 720;
        canvas.width = width;
        canvas.height = height;

        var context = canvas.getContext('2d');
        if (!context) return;

        context.drawImage(video, 0, 0, width, height);
        var dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        photoDataInput.value = dataUrl;

        if (capturedPreview) {
            capturedPreview.src = dataUrl;
        }

        if (capturedPreviewContainer) {
            capturedPreviewContainer.classList.remove('d-none');
        }
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', function () {
            setFoundModeUI();
            if (isFoundType()) {
                startCamera();
            }
        });
    }

    if (startCameraBtn) {
        startCameraBtn.addEventListener('click', function () {
            startCamera();
        });
    }

    if (captureBtn) {
        captureBtn.addEventListener('click', function () {
            capturePhoto();
        });
    }

    modal.addEventListener('shown.bs.modal', function () {
        setFoundModeUI();
        if (isFoundType()) {
            startCamera();
        }
    });

    modal.addEventListener('hidden.bs.modal', function () {
        stopCamera();
    });

    setFoundModeUI();
})();
</script>
@endpush
