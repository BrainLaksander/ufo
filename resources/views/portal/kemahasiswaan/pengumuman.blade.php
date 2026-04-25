@extends('layouts.portal.kemahasiswaan')

@section('title', 'Manajemen Pengumuman - Kemahasiswaan')
@section('page_title', 'Manajemen Pengumuman & Distribusi Email')
@section('page_subtitle', 'Kelola publikasi pengumuman dan alur review distribusi email organisasi')
@section('page_class', 'kmh-page-pengumuman')

@php
    $workflowPengumuman = $workflowPengumuman ?? [];
    $emailReviewQueue = $emailReviewQueue ?? [];
    $ukmAccounts = $ukmAccounts ?? [];

    $publishedCount = collect($workflowPengumuman)->where('status_code', 'published')->count();
    $scheduledCount = collect($workflowPengumuman)->where('status_code', 'scheduled')->count();
    $draftCount = collect($workflowPengumuman)->where('status_code', 'draft')->count();
    $hasAccounts = !empty($ukmAccounts);

    $statusClass = function (string $status): string {
        return match ($status) {
            'Terpublikasi' => 'is-success',
            'Terjadwal' => 'is-info',
            'Draft' => 'is-muted',
            'Arsip' => 'is-warning',
            default => 'is-muted',
        };
    };

    $reviewClass = function (string $status): string {
        return match ($status) {
            'Disetujui' => 'is-success',
            'Menunggu Review', 'Perlu Revisi' => 'is-warning',
            'Ditolak' => 'is-danger',
            default => 'is-muted',
        };
    };
@endphp

@section('content')
<div class="kmh-page kmh-pengumuman-page">
    @if(session('success'))
        <div class="alert alert-success mb-0" role="alert">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-0" role="alert">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-0" role="alert">{{ $errors->first() }}</div>
    @endif

    <section class="kmh-stats-grid">
        <article class="kmh-stat-card tone-primary">
            <div>
                <p>Total Pengumuman</p>
                <strong class="kmh-stat-value">{{ count($workflowPengumuman) }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-megaphone-fill"></i></span>
        </article>

        <article class="kmh-stat-card tone-success">
            <div>
                <p>Terpublikasi</p>
                <strong class="kmh-stat-value">{{ $publishedCount }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-check-circle-fill"></i></span>
        </article>

        <article class="kmh-stat-card tone-info">
            <div>
                <p>Terjadwal</p>
                <strong class="kmh-stat-value">{{ $scheduledCount }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-calendar2-check-fill"></i></span>
        </article>

        <article class="kmh-stat-card tone-muted">
            <div>
                <p>Draft</p>
                <strong class="kmh-stat-value">{{ $draftCount }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-pencil-square"></i></span>
        </article>
    </section>

    <section class="kmh-card">
        <div class="kmh-card-body">
            <div class="kmh-toolbar">
                <div class="kmh-toolbar-group">
                    <label class="kmh-toolbar-field is-search mb-0" for="kmh-pengumuman-search">
                        <i class="bi bi-search kmh-toolbar-icon"></i>
                        <input
                            id="kmh-pengumuman-search"
                            type="search"
                            class="kmh-toolbar-input"
                            placeholder="Cari judul, kategori, target, atau organisasi..."
                            aria-label="Cari pengumuman"
                        >
                    </label>

                    <label class="kmh-toolbar-field mb-0" for="kmh-pengumuman-status">
                        <i class="bi bi-funnel kmh-toolbar-icon"></i>
                        <select id="kmh-pengumuman-status" class="kmh-toolbar-select" aria-label="Filter status pengumuman">
                            <option value="semua">Semua Status</option>
                            <option value="published">Terpublikasi</option>
                            <option value="scheduled">Terjadwal</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Arsip</option>
                        </select>
                    </label>
                </div>

                <div class="kmh-toolbar-end">
                    <a href="#kmh-form-pengumuman" class="kmh-quick-btn is-primary kmh-pengumuman-cta">
                        <i class="bi bi-plus-circle"></i>
                        <span>Buat Pengumuman Baru</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="kmh-form-pengumuman" class="kmh-card">
        <header class="kmh-card-head">
            <h2>Buat Pengumuman Baru</h2>
            <small>Pengumuman baru akan masuk antrean review email</small>
        </header>
        <div class="kmh-card-body">
            @unless($hasAccounts)
                <div class="alert alert-warning" role="alert">
                    Belum ada akun UKM aktif. Tambahkan akun UKM terlebih dahulu pada menu Manajemen Organisasi.
                </div>
            @endunless

            <form method="POST" action="{{ route('portal.kemahasiswaan.pengumuman.store') }}" class="row g-3">
                @csrf
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Judul Pengumuman</label>
                    <input type="text" name="judul" class="form-control" maxlength="140" required>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control" required>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Target</label>
                    <input type="text" name="target" class="form-control" required>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Tanggal Publish</label>
                    <input type="date" name="publish_at" class="form-control">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Akun UKM</label>
                    <select name="ukm_account_id" class="form-select" required @disabled(!$hasAccounts)>
                        <option value="">Pilih akun</option>
                        @foreach($ukmAccounts as $akun)
                            <option value="{{ $akun['id'] }}">{{ $akun['organisasi'] }} - {{ $akun['nama'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-10 col-md-8">
                    <label class="form-label">Ringkasan</label>
                    <input type="text" name="ringkasan" class="form-control" maxlength="240" required>
                </div>
                <div class="col-lg-2 col-md-4 d-grid">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary kmh-form-action-btn" @disabled(!$hasAccounts)>Simpan</button>
                </div>
            </form>
        </div>
    </section>

    <div class="kmh-info-banner">
        <strong>Sistem Distribusi Email:</strong>
        Semua pengumuman yang disetujui akan dikirim otomatis ke email resmi organisasi yang ditargetkan.
        Pastikan target distribusi dan ringkasan sudah benar sebelum dipublikasikan.
    </div>

    <section class="kmh-card">
        <header class="kmh-card-head">
            <h2>Review Izin Pengumuman ke Email</h2>
            <small>{{ count($emailReviewQueue) }} item menunggu review</small>
        </header>
        <div class="kmh-card-body pt-0">
            <div class="table-responsive">
                <table class="table kmh-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Pemohon</th>
                            <th>Organisasi</th>
                            <th>Status Publikasi</th>
                            <th>Status Review Email</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emailReviewQueue as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $item['judul'] }}</div>
                                    <small class="text-muted">{{ $item['ringkasan'] }}</small>
                                </td>
                                <td>{{ $item['ukm_account_name'] }}</td>
                                <td>{{ $item['organisasi'] }}</td>
                                <td>
                                    <span class="kmh-status-pill {{ $statusClass($item['status']) }}">{{ $item['status'] }}</span>
                                </td>
                                <td>
                                    <span class="kmh-status-pill {{ $reviewClass($item['email_review_status']) }}">{{ $item['email_review_status'] }}</span>
                                </td>
                                <td class="text-center">
                                    <form method="POST" action="{{ route('portal.kemahasiswaan.pengumuman.email-review', ['id' => $item['id']]) }}" class="kmh-inline-review">
                                        @csrf
                                        <select name="decision" class="form-select form-select-sm">
                                            <option value="setujui">Setujui</option>
                                            <option value="tolak">Tolak</option>
                                            <option value="revisi">Revisi</option>
                                        </select>
                                        <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Catatan (wajib jika tolak/revisi)">
                                        <button type="submit" class="btn btn-sm btn-primary kmh-form-action-btn">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="kmh-empty-row">Tidak ada antrean review email.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="kmh-card">
        <header class="kmh-card-head">
            <h2>Daftar Pengumuman</h2>
            <small>{{ count($workflowPengumuman) }} total data</small>
        </header>
        <div class="kmh-card-body pt-0">
            <div class="table-responsive">
                <table class="table kmh-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Target</th>
                            <th>Pemohon</th>
                            <th>Publish</th>
                            <th>Status</th>
                            <th>Review Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workflowPengumuman as $item)
                            @php
                                $rowSearch = \Illuminate\Support\Str::lower(implode(' ', [
                                    (string) ($item['judul'] ?? ''),
                                    (string) ($item['kategori'] ?? ''),
                                    (string) ($item['target'] ?? ''),
                                    (string) ($item['organisasi'] ?? ''),
                                    (string) ($item['ringkasan'] ?? ''),
                                ]));
                            @endphp
                            <tr
                                data-pengumuman-row
                                data-pengumuman-search="{{ $rowSearch }}"
                                data-pengumuman-status="{{ $item['status_code'] }}"
                            >
                                <td>{{ $item['judul'] }}</td>
                                <td>{{ $item['kategori'] }}</td>
                                <td>{{ $item['target'] }}</td>
                                <td>{{ $item['organisasi'] }}</td>
                                <td>
                                    @if(!empty($item['publish_at']))
                                        {{ \Carbon\Carbon::parse($item['publish_at'])->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="kmh-status-pill {{ $statusClass($item['status']) }}">{{ $item['status'] }}</span>
                                </td>
                                <td>
                                    <div>
                                        <span class="kmh-status-pill {{ $reviewClass($item['email_review_status']) }}">{{ $item['email_review_status'] }}</span>
                                    </div>
                                    @if(!empty($item['email_review_note']))
                                        <small class="text-muted d-block mt-1">{{ $item['email_review_note'] }}</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="kmh-empty-row">Belum ada pengumuman.</td>
                            </tr>
                        @endforelse

                        @if(count($workflowPengumuman) > 0)
                            <tr class="kmh-filter-empty-row d-none" data-pengumuman-empty>
                                <td colspan="7" class="kmh-empty-row">Tidak ada pengumuman yang cocok dengan filter.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('kmh-pengumuman-search');
    var statusSelect = document.getElementById('kmh-pengumuman-status');
    var rows = Array.prototype.slice.call(document.querySelectorAll('[data-pengumuman-row]'));
    var emptyRow = document.querySelector('[data-pengumuman-empty]');

    if (!searchInput || !statusSelect || rows.length === 0) {
        return;
    }

    function applyFilter() {
        var query = (searchInput.value || '').toLowerCase().trim();
        var status = (statusSelect.value || 'semua').toLowerCase();
        var visibleCount = 0;

        rows.forEach(function (row) {
            var rowSearch = (row.getAttribute('data-pengumuman-search') || '').toLowerCase();
            var rowStatus = (row.getAttribute('data-pengumuman-status') || '').toLowerCase();
            var textMatch = query === '' || rowSearch.indexOf(query) !== -1;
            var statusMatch = status === 'semua' || rowStatus === status;
            var showRow = textMatch && statusMatch;

            row.classList.toggle('d-none', !showRow);
            if (showRow) {
                visibleCount += 1;
            }
        });

        if (emptyRow) {
            emptyRow.classList.toggle('d-none', visibleCount !== 0);
        }
    }

    searchInput.addEventListener('input', applyFilter);
    statusSelect.addEventListener('change', applyFilter);
    applyFilter();
});
</script>
@endpush
