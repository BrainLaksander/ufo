@extends('layouts.portal.pengurus')

@section('title', 'Lost & Found')

@php
    $isBem = $isBem ?? false;
    $items = $items ?? [];
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
            {{ $isBem ? 'Kelola laporan barang hilang dan temuan dari mahasiswa.' : 'Laporkan atau cari barang yang hilang atau ditemukan.' }}
        </p>

        <div class="ufo-kboard-row three mt-3">
            <article class="ufo-kboard-stat blue">
                <h3>{{ count($items) }}</h3>
                <p>Total Laporan</p>
            </article>
            <article class="ufo-kboard-stat gold">
                <h3>{{ collect($items)->where('status', 'pending')->count() }}</h3>
                <p>Menunggu Persetujuan</p>
            </article>
            <article class="ufo-kboard-stat green">
                <h3>{{ count($priorityItems) }}</h3>
                <p>Prioritas Tinggi</p>
            </article>
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
        <div class="ufo-kboard-list">
            @forelse($items as $item)
                <article class="ufo-pg-lf-card">
                    <div class="ufo-pg-lf-main">
                        <div class="ufo-pg-lf-icon {{ $item['type'] === 'lost' ? 'lost' : 'found' }}">
                            <i class="bi {{ $item['type'] === 'lost' ? 'bi-exclamation-triangle' : 'bi-check-circle' }}"></i>
                        </div>

                        <div class="ufo-pg-lf-body">
                            <div class="ufo-kboard-item-head mb-2">
                                <div>
                                    <h3 class="ufo-kboard-item-title">{{ $item['title'] }}</h3>
                                    <p class="ufo-kboard-item-meta">{{ $item['type'] === 'lost' ? 'Barang Hilang' : 'Barang Ditemukan' }}</p>
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
                            </div>

                            @if(!empty($item['notes']))
                                <div class="ufo-pg-lf-note">
                                    <strong>Catatan BEM:</strong>
                                    <span>{{ $item['notes'] }}</span>
                                </div>
                            @endif

                            <div class="ufo-kboard-item-actions mt-3">
                                @if($isBem && $item['status'] === 'pending')
                                    <button class="ufo-kboard-btn primary" data-bs-toggle="modal" data-bs-target="#reviewModal" type="button">Review</button>
                                @endif

                                @if($isBem && $item['status'] === 'approved')
                                    <button class="ufo-kboard-btn gold" type="button">{{ $item['priority'] ? 'Prioritas Aktif' : 'Set Prioritas' }}</button>
                                    <button class="ufo-kboard-btn ghost" type="button">Kelola Status</button>
                                @endif

                                <button class="ufo-kboard-btn ghost" type="button">Lihat Detail</button>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <p class="ufo-kboard-item-meta mb-0">Belum ada laporan Lost &amp; Found di database.</p>
            @endforelse
        </div>
    </section>
</div>

<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Review Laporan Lost &amp; Found</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Catatan Review</label>
                        <textarea class="form-control" rows="4" placeholder="Tulis catatan validasi atau instruksi revisi"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Keputusan</label>
                        <select class="form-select">
                            <option value="">Pilih keputusan</option>
                            @foreach($statusOptions as $code => $label)
                                <option value="{{ $code }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status Barang</label>
                        <select class="form-select">
                            <option value="">Pilih status barang</option>
                            @foreach($itemStatusOptions as $status)
                                <option value="{{ $status }}">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', (string) $status)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger">Tolak</button>
                <button type="button" class="btn btn-success">Setujui</button>
            </div>
        </div>
    </div>
</div>
@endsection
