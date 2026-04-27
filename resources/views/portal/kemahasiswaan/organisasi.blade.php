@extends('layouts.portal.kemahasiswaan')

@section('title', 'Manajemen Organisasi - Kemahasiswaan')
@section('page_title', 'Manajemen Organisasi')
@section('page_subtitle', 'Sistem Administrasi & Kontrol Organisasi Mahasiswa')
@section('page_class', 'kmh-page-organisasi')

@php
    $organizationDirectory = $organizationDirectory ?? [];
    $organizationSummary = $organizationSummary ?? [
        'total' => count($organizationDirectory),
        'ukm_umum' => collect($organizationDirectory)
            ->where('type', 'UKM')
            ->filter(fn ($org) => \Illuminate\Support\Str::lower((string) ($org['scope'] ?? '')) === 'umum'
                || \Illuminate\Support\Str::lower((string) ($org['category'] ?? '')) === 'ukm umum')
            ->count(),
        'ukm_level_universitas' => collect($organizationDirectory)
            ->where('type', 'UKM')
            ->filter(fn ($org) => \Illuminate\Support\Str::lower((string) ($org['scope'] ?? '')) === 'universitas')
            ->count(),
        'ukm_level_fakultas' => collect($organizationDirectory)
            ->where('type', 'UKM')
            ->filter(fn ($org) => \Illuminate\Support\Str::lower((string) ($org['scope'] ?? '')) === 'fakultas')
            ->count(),
        'categories' => collect($organizationDirectory)->pluck('category')->filter()->unique()->count(),
    ];

    $totalOrganizations = (int) ($organizationSummary['total'] ?? 0);
    $totalUkmUmum = (int) ($organizationSummary['ukm_umum'] ?? 0);
    $totalUkmUniversitas = (int) ($organizationSummary['ukm_level_universitas'] ?? 0);
    $totalUkmFakultas = (int) ($organizationSummary['ukm_level_fakultas'] ?? 0);
    $totalCategories = (int) ($organizationSummary['categories'] ?? 0);
    $categoryOptions = collect(array_merge(
        ['BEM', 'UKM Umum', 'Minat & Bakat', 'Kerohanian', 'Akademik & Teknologi', 'Kedaerahan'],
        collect($organizationDirectory)->pluck('category')->filter()->all()
    ))->filter()->unique()->sort()->values()->all();
@endphp

@push('styles')
<style>
    .kmh-org-modal .modal-dialog {
        margin: 0.75rem auto;
    }

    .kmh-org-modal .modal-content {
        max-height: calc(100dvh - 1.5rem);
        border: 0;
        border-radius: 1rem;
        overflow: hidden;
    }

    .kmh-org-modal .modal-header,
    .kmh-org-modal .modal-footer {
        flex: 0 0 auto;
    }

    .kmh-org-modal .modal-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
        scrollbar-gutter: stable;
    }

    .kmh-org-modal form {
        display: flex;
        flex-direction: column;
        min-height: 0;
    }

    .kmh-org-create-dialog {
        width: min(1100px, calc(100vw - 2rem));
    }

    .kmh-org-create-dialog .modal-content {
        max-height: calc(100dvh - 1.5rem);
    }

    .kmh-org-create-dialog .form-label {
        font-weight: 600;
    }

    .kmh-org-create-dialog .form-text,
    .kmh-org-create-dialog small.text-muted {
        font-size: 0.86rem;
        line-height: 1.35;
    }

    @media (max-width: 575.98px) {
        .kmh-org-create-dialog {
            width: calc(100vw - 1rem);
        }

        .kmh-org-modal .modal-dialog {
            margin: 0.5rem;
        }

        .kmh-org-modal .modal-content,
        .kmh-org-create-dialog .modal-content {
            max-height: calc(100dvh - 1rem);
        }
    }
</style>
@endpush

@section('content')
<div class="kmh-page kmh-organisasi-page">
    @if(session('success'))
        <div class="alert alert-success mb-0" role="alert">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-0" role="alert">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-0" role="alert">{{ $errors->first() }}</div>
    @endif

    <section class="kmh-card kmh-organisasi-toolbar-card">
        <div class="kmh-card-body">
            <div class="kmh-toolbar">
                <div class="kmh-toolbar-group">
                    <span class="kmh-org-page-badge" aria-hidden="true">{{ $totalCategories }}</span>

                    <label class="kmh-toolbar-field is-search mb-0" for="kmh-org-search">
                        <i class="bi bi-search kmh-toolbar-icon"></i>
                        <input
                            id="kmh-org-search"
                            type="search"
                            class="kmh-toolbar-input"
                            placeholder="Cari organisasi..."
                            aria-label="Cari organisasi"
                        >
                    </label>

                    <label class="kmh-toolbar-field mb-0" for="kmh-org-category">
                        <i class="bi bi-funnel kmh-toolbar-icon"></i>
                        <select id="kmh-org-category" class="kmh-toolbar-select" aria-label="Filter kategori organisasi">
                            <option value="semua">Semua kategori</option>
                            @foreach($categoryOptions as $category)
                                <option value="{{ \Illuminate\Support\Str::lower($category) }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <div class="kmh-toolbar-end">
                    <button type="button" class="kmh-quick-btn is-primary kmh-org-add-btn" data-bs-toggle="modal" data-bs-target="#kmh-org-create-modal">
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Organisasi</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="kmh-stats-grid">
        <article class="kmh-stat-card tone-primary">
            <div>
                <p>Total Organisasi</p>
                <strong class="kmh-stat-value">{{ $totalOrganizations }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-buildings-fill"></i></span>
        </article>

        <article class="kmh-stat-card tone-success">
            <div>
                <p>Total UKM Umum</p>
                <strong class="kmh-stat-value">{{ $totalUkmUmum }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-bank2"></i></span>
        </article>

        <article class="kmh-stat-card tone-warning">
            <div>
                <p>Total UKM Level Universitas</p>
                <strong class="kmh-stat-value">{{ $totalUkmUniversitas }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-diagram-3-fill"></i></span>
        </article>

        <article class="kmh-stat-card tone-info">
            <div>
                <p>Total UKM Level Fakultas</p>
                <strong class="kmh-stat-value">{{ $totalUkmFakultas }}</strong>
            </div>
            <span class="kmh-stat-icon"><i class="bi bi-collection-fill"></i></span>
        </article>
    </section>

    <section class="kmh-card">
        <header class="kmh-card-head">
            <h2>Direktori Organisasi</h2>
            <small><span id="kmh-org-visible-count">{{ $totalOrganizations }}</span> organisasi ditampilkan</small>
        </header>
        <div class="kmh-card-body pt-0">
            <div class="table-responsive">
                <table class="table kmh-table kmh-org-table">
                    <thead>
                        <tr>
                            <th>Nama Organisasi</th>
                            <th>Kategori</th>
                            <th>Type</th>
                            <th>Bidang</th>
                            <th>Advisor</th>
                            <th>Ketua</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($organizationDirectory as $org)
                            @php
                                $searchBlob = \Illuminate\Support\Str::lower(implode(' ', [
                                    (string) ($org['name'] ?? ''),
                                    (string) ($org['shortname'] ?? ''),
                                    (string) ($org['scope'] ?? ''),
                                    (string) ($org['category'] ?? ''),
                                    (string) ($org['type'] ?? ''),
                                    (string) ($org['field'] ?? ''),
                                    (string) ($org['advisor'] ?? ''),
                                    (string) ($org['leader'] ?? ''),
                                    (string) ($org['description'] ?? ''),
                                    (string) ($org['email'] ?? ''),
                                    (string) ($org['phone'] ?? ''),
                                    (string) ($org['status'] ?? ''),
                                ]));
                            @endphp
                            <tr
                                data-org-row
                                data-org-search="{{ $searchBlob }}"
                                data-org-category="{{ \Illuminate\Support\Str::lower((string) ($org['category'] ?? '')) }}"
                                data-org-category-label="{{ $org['category'] ?? '' }}"
                                data-org-id="{{ $org['id'] }}"
                                data-org-name="{{ $org['name'] ?? '' }}"
                                data-org-shortname="{{ $org['shortname'] ?? '' }}"
                                data-org-description="{{ $org['description'] ?? '' }}"
                                data-org-email="{{ $org['email'] ?? '' }}"
                                data-org-phone="{{ $org['phone'] ?? '' }}"
                                data-org-status="{{ $org['status'] ?? '' }}"
                                data-org-scope="{{ $org['scope'] ?? '' }}"
                                data-org-level="{{ $org['scope'] ?? '' }}"
                                data-org-type="{{ $org['type'] ?? '' }}"
                                data-org-field="{{ $org['field'] ?? '' }}"
                                data-org-advisor="{{ $org['advisor'] ?? '' }}"
                                data-org-leader="{{ $org['leader'] ?? '' }}"
                                 data-org-account-email="{{ $org['account_email'] ?? '' }}"
                            >
                                <td>
                                    <div class="kmh-org-name-cell">
                                        <span class="kmh-org-avatar" aria-hidden="true">
                                            <i class="bi bi-building-fill"></i>
                                        </span>
                                        <div>
                                            <div class="kmh-org-name">{{ $org['name'] }}</div>
                                            <small class="text-muted d-block">{{ $org['scope'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $org['category'] }}</td>
                                <td>
                                    @if($org['type'] === 'BEM')
                                        <span class="kmh-status-pill is-primary">BEM</span>
                                    @else
                                        <span class="kmh-status-pill is-info">UKM</span>
                                    @endif
                                </td>
                                <td>{{ $org['field'] }}</td>
                                <td>{{ $org['advisor'] ?? '-' }}</td>
                                <td>{{ $org['leader'] }}</td>
                                <td class="text-center">
                                    <div class="kmh-action-links justify-content-center kmh-org-actions">
                                        <button class="kmh-action-link is-primary" type="button" title="Lihat Detail" data-org-detail-trigger>
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="kmh-action-link is-warning" type="button" title="Edit Organisasi" data-org-edit-trigger>
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form method="POST" action="{{ route('portal.kemahasiswaan.organisasi.destroy', ['id' => $org['id']]) }}" class="d-inline" onsubmit="return confirm('Nonaktifkan organisasi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="kmh-action-link is-danger" type="submit" title="Hapus Organisasi">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="kmh-empty-row">Belum ada data organisasi.</td>
                            </tr>
                        @endforelse

                        @if($totalOrganizations > 0)
                            <tr class="kmh-filter-empty-row d-none" data-org-empty>
                                <td colspan="7" class="kmh-empty-row">Tidak ada organisasi yang cocok dengan filter.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="modal fade kmh-org-modal" id="kmh-org-create-modal" tabindex="-1" aria-labelledby="kmh-org-create-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable kmh-org-create-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="kmh-org-create-modal-label">Tambah Organisasi</h5>
                        <small class="text-muted">Tambahkan data organisasi baru ke direktori kemahasiswaan.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form method="POST" action="{{ route('portal.kemahasiswaan.organisasi.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info mb-4" role="alert">
                            Ketik nama organisasi terlebih dahulu. Kategori, Type, Level, dan Bidang akan terisi otomatis dan tetap bisa Anda ubah.
                        </div>

                        <h6 class="mb-2">Profil Organisasi</h6>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="kmh-org-name" class="form-label">Nama Organisasi</label>
                                <input
                                    id="kmh-org-name"
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name') }}"
                                    maxlength="120"
                                    required
                                    autocomplete="off"
                                    data-org-autofill-source="true"
                                >
                                <small class="text-muted d-block mt-1">Nama yang Anda ketik akan dipakai untuk menyarankan kategori, type, level, dan bidang.</small>
                            </div>
                            <div class="col-md-4">
                                <label for="kmh-org-category-input" class="form-label">Kategori</label>
                                <select id="kmh-org-category-input" name="category" class="form-select" data-org-autofill-target="category">
                                    <option value="">Pilih kategori</option>
                                    @foreach($categoryOptions as $category)
                                        <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-1">Bisa diubah manual jika perlu.</small>
                            </div>
                            <div class="col-md-4">
                                <label for="kmh-org-type-input" class="form-label">Type</label>
                                <select id="kmh-org-type-input" name="type" class="form-select" data-org-autofill-target="type">
                                    <option value="">Pilih type</option>
                                    <option value="BEM" @selected(old('type') === 'BEM')>BEM</option>
                                    <option value="UKM" @selected(old('type', 'UKM') === 'UKM')>UKM</option>
                                </select>
                                <small class="text-muted d-block mt-1">Otomatis menyesuaikan kata kunci pada nama organisasi.</small>
                            </div>
                            <div class="col-md-4">
                                <label for="kmh-org-level-input" class="form-label">Level</label>
                                <select id="kmh-org-level-input" name="level" class="form-select" data-org-autofill-target="level">
                                    <option value="">Pilih level</option>
                                    <option value="Universitas" @selected(old('level', 'Universitas') === 'Universitas')>Universitas</option>
                                    <option value="Fakultas" @selected(old('level') === 'Fakultas')>Fakultas</option>
                                    <option value="Umum" @selected(old('level') === 'Umum')>Umum</option>
                                </select>
                                <small class="text-muted d-block mt-1">Dapat berubah bila nama organisasi mengarah ke fakultas tertentu.</small>
                            </div>
                            <div class="col-md-12">
                                <label for="kmh-org-field-input" class="form-label">Bidang</label>
                                <input
                                    id="kmh-org-field-input"
                                    type="text"
                                    name="field"
                                    class="form-control"
                                    value="{{ old('field') }}"
                                    maxlength="120"
                                    placeholder="Contoh: Pemerintahan Mahasiswa"
                                    data-org-autofill-target="field"
                                >
                                <small class="text-muted d-block mt-1">Auto-fill akan memberi saran bidang paling relevan dari nama organisasi.</small>
                            </div>
                            <div class="col-md-12">
                                <label for="kmh-org-advisor" class="form-label">Nama Advisor</label>
                                <input
                                    id="kmh-org-advisor"
                                    type="text"
                                    name="advisor"
                                    class="form-control"
                                    value="{{ old('advisor') }}"
                                    maxlength="255"
                                    placeholder="Masukkan nama advisor organisasi"
                                    required
                                >
                            </div>
                            <div class="col-md-12">
                                <label for="kmh-org-leader-name" class="form-label">Nama Ketua Organisasi</label>
                                <input
                                    id="kmh-org-leader-name"
                                    type="text"
                                    name="leader_name"
                                    class="form-control"
                                    value="{{ old('leader_name') }}"
                                    maxlength="120"
                                    placeholder="Masukkan nama ketua organisasi"
                                    required
                                >
                            </div>
                            <div class="col-12">
                                <label for="kmh-org-description" class="form-label">Deskripsi</label>
                                <textarea
                                    id="kmh-org-description"
                                    name="description"
                                    class="form-control"
                                    rows="4"
                                    maxlength="1000"
                                    placeholder="Ringkasan singkat fungsi atau bidang organisasi"
                                    required
                                >{{ old('description') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="kmh-org-shortname" class="form-label">Singkatan (opsional, otomatis jika kosong)</label>
                                <input
                                    id="kmh-org-shortname"
                                    type="text"
                                    name="shortname"
                                    class="form-control"
                                    value="{{ old('shortname') }}"
                                    maxlength="40"
                                    placeholder="Contoh: BEM"
                                >
                            </div>
                            <div class="col-md-6">
                                <label for="kmh-org-email" class="form-label">Email Organisasi</label>
                                <input
                                    id="kmh-org-email"
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ old('email') }}"
                                    maxlength="120"
                                    placeholder="opsional"
                                >
                            </div>
                            <div class="col-md-6">
                                <label for="kmh-org-phone" class="form-label">Telepon</label>
                                <input
                                    id="kmh-org-phone"
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                    value="{{ old('phone') }}"
                                    maxlength="30"
                                 >
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-2">Akun Pengurus UKM</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="kmh-org-account-email" class="form-label">Email Akun (untuk Login)</label>
                                <input
                                    id="kmh-org-account-email"
                                    type="email"
                                    name="account_email"
                                    class="form-control"
                                    value="{{ old('account_email') }}"
                                    maxlength="120"
                                    placeholder="Contoh: ukm_seni@unklab.ac.id"
                                    required
                                >
                                <small class="text-muted d-block mt-1">Email ini akan digunakan pengurus untuk masuk ke portal.</small>
                            </div>
                            <div class="col-md-6">
                                <label for="kmh-org-account-password" class="form-label">Buatkan Password</label>
                                <input
                                    id="kmh-org-account-password"
                                    type="password"
                                    name="account_password"
                                    class="form-control"
                                    minlength="6"
                                    maxlength="40"
                                    required
                                    autocomplete="new-password"
                                >
                                <small class="text-muted d-block mt-1">Minimal 6 karakter. Berikan password ini kepada pengurus.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Organisasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade kmh-org-modal" id="kmh-org-detail-modal" tabindex="-1" aria-labelledby="kmh-org-detail-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="kmh-org-detail-modal-label">Detail Organisasi</h5>
                        <small class="text-muted">Informasi resmi organisasi dari database.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <dl class="row mb-0 g-3">
                        <div class="col-md-6">
                            <dt>Nama Organisasi</dt>
                            <dd id="kmh-org-detail-name" class="mb-0"></dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Singkatan</dt>
                            <dd id="kmh-org-detail-shortname" class="mb-0"></dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Status</dt>
                            <dd id="kmh-org-detail-status" class="mb-0"></dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Kategori</dt>
                            <dd id="kmh-org-detail-category" class="mb-0"></dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Type</dt>
                            <dd id="kmh-org-detail-type" class="mb-0"></dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Level</dt>
                            <dd id="kmh-org-detail-scope" class="mb-0"></dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Bidang</dt>
                            <dd id="kmh-org-detail-field" class="mb-0"></dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Advisor</dt>
                            <dd id="kmh-org-detail-advisor" class="mb-0"></dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Ketua</dt>
                            <dd id="kmh-org-detail-leader" class="mb-0"></dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Email</dt>
                            <dd id="kmh-org-detail-email" class="mb-0"></dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Telepon</dt>
                            <dd id="kmh-org-detail-phone" class="mb-0"></dd>
                        </div>
                        <div class="col-12">
                            <dt>Deskripsi</dt>
                            <dd id="kmh-org-detail-description" class="mb-0"></dd>
                        </div>
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade kmh-org-modal" id="kmh-org-edit-modal" tabindex="-1" aria-labelledby="kmh-org-edit-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="kmh-org-edit-modal-label">Edit Organisasi</h5>
                        <small class="text-muted">Perbarui data organisasi yang tersimpan di database.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form method="POST" id="kmh-org-edit-form" action="{{ route('portal.kemahasiswaan.organisasi.update', ['id' => 0]) }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="kmh-org-edit-id" name="organization_id" value="{{ old('organization_id') }}">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="kmh-org-edit-name" class="form-label">Nama Organisasi</label>
                                <input id="kmh-org-edit-name" type="text" name="name" class="form-control" value="{{ old('name') }}" maxlength="120" required>
                            </div>
                            <div class="col-md-4">
                                <label for="kmh-org-edit-shortname" class="form-label">Singkatan</label>
                                <input id="kmh-org-edit-shortname" type="text" name="shortname" class="form-control" value="{{ old('shortname') }}" maxlength="40" required>
                            </div>
                            <div class="col-md-4">
                                <label for="kmh-org-edit-status" class="form-label">Status</label>
                                <select id="kmh-org-edit-status" name="status" class="form-select" required>
                                    <option value="active" @selected(old('status', 'active') === 'active')>Aktif</option>
                                    <option value="inactive" @selected(old('status') === 'inactive')>Nonaktif</option>
                                    <option value="suspended" @selected(old('status') === 'suspended')>Suspended</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="kmh-org-edit-category" class="form-label">Kategori</label>
                                <input id="kmh-org-edit-category" type="text" name="category" class="form-control" value="{{ old('category') }}" maxlength="80">
                            </div>
                            <div class="col-md-4">
                                <label for="kmh-org-edit-type" class="form-label">Type</label>
                                <select id="kmh-org-edit-type" name="type" class="form-select">
                                    <option value="">-</option>
                                    <option value="BEM" @selected(old('type') === 'BEM')>BEM</option>
                                    <option value="UKM" @selected(old('type') === 'UKM')>UKM</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="kmh-org-edit-level" class="form-label">Level</label>
                                <select id="kmh-org-edit-level" name="level" class="form-select">
                                    <option value="">-</option>
                                    <option value="Universitas" @selected(old('level') === 'Universitas')>Universitas</option>
                                    <option value="Fakultas" @selected(old('level') === 'Fakultas')>Fakultas</option>
                                    <option value="Umum" @selected(old('level') === 'Umum')>Umum</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="kmh-org-edit-field" class="form-label">Bidang</label>
                                <input id="kmh-org-edit-field" type="text" name="field" class="form-control" value="{{ old('field') }}" maxlength="120">
                            </div>
                            <div class="col-md-6">
                                <label for="kmh-org-edit-advisor" class="form-label">Nama Advisor</label>
                                <input id="kmh-org-edit-advisor" type="text" name="advisor" class="form-control" value="{{ old('advisor') }}" maxlength="255" required>
                            </div>
                            <div class="col-md-6">
                                <label for="kmh-org-edit-leader-name" class="form-label">Nama Ketua Organisasi</label>
                                <input id="kmh-org-edit-leader-name" type="text" name="leader_name" class="form-control" value="{{ old('leader_name') }}" maxlength="120" required>
                            </div>
                            <div class="col-12">
                                <label for="kmh-org-edit-description" class="form-label">Deskripsi</label>
                                <textarea id="kmh-org-edit-description" name="description" class="form-control" rows="4" maxlength="1000">{{ old('description') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="kmh-org-edit-email" class="form-label">Email Organisasi</label>
                                <input id="kmh-org-edit-email" type="email" name="email" class="form-control" value="{{ old('email') }}" maxlength="120">
                            </div>
                            <div class="col-md-6">
                                <label for="kmh-org-edit-phone" class="form-label">Telepon</label>
                                <input id="kmh-org-edit-phone" type="text" name="phone" class="form-control" value="{{ old('phone') }}" maxlength="30">
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-2">Akun Pengurus UKM</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="kmh-org-edit-account-email" class="form-label">Email Akun</label>
                                <input id="kmh-org-edit-account-email" type="email" name="account_email" class="form-control" value="{{ old('account_email') }}" maxlength="120">
                                <small class="text-muted d-block mt-1">Email login pengurus UKM.</small>
                            </div>
                            <div class="col-md-6">
                                <label for="kmh-org-edit-account-password" class="form-label">Ubah Password (opsional)</label>
                                <input id="kmh-org-edit-account-password" type="password" name="account_password" class="form-control" minlength="6" maxlength="40" autocomplete="new-password">
                                <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengubah password.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    @php
        $editOldValues = [
            'name' => old('name'),
            'shortname' => old('shortname'),
            'description' => old('description'),
            'email' => old('email'),
            'phone' => old('phone'),
            'category' => old('category'),
            'type' => old('type'),
            'level' => old('level'),
            'field' => old('field'),
            'advisor' => old('advisor'),
            'leader_name' => old('leader_name'),
            'account_email' => old('account_email'),
            'account_password' => old('account_password'),
            'status' => old('status'),
            'organization_id' => old('organization_id'),
        ];
    @endphp
    var searchInput = document.getElementById('kmh-org-search');
    var categorySelect = document.getElementById('kmh-org-category');
    var visibleCount = document.getElementById('kmh-org-visible-count');
    var createForm = document.querySelector('#kmh-org-create-modal form');
    var createNameInput = document.getElementById('kmh-org-name');
    var createCategoryInput = document.getElementById('kmh-org-category-input');
    var createTypeInput = document.getElementById('kmh-org-type-input');
    var createLevelInput = document.getElementById('kmh-org-level-input');
    var createFieldInput = document.getElementById('kmh-org-field-input');
    var createModalEl = document.getElementById('kmh-org-create-modal');
    var detailModalEl = document.getElementById('kmh-org-detail-modal');
    var editModalEl = document.getElementById('kmh-org-edit-modal');
    var editForm = document.getElementById('kmh-org-edit-form');
    var editIdInput = document.getElementById('kmh-org-edit-id');
    var editNameInput = document.getElementById('kmh-org-edit-name');
    var editShortnameInput = document.getElementById('kmh-org-edit-shortname');
    var editDescriptionInput = document.getElementById('kmh-org-edit-description');
    var editEmailInput = document.getElementById('kmh-org-edit-email');
    var editPhoneInput = document.getElementById('kmh-org-edit-phone');
    var editStatusInput = document.getElementById('kmh-org-edit-status');
    var editCategoryInput = document.getElementById('kmh-org-edit-category');
    var editTypeInput = document.getElementById('kmh-org-edit-type');
    var editLevelInput = document.getElementById('kmh-org-edit-level');
    var editFieldInput = document.getElementById('kmh-org-edit-field');
    var editAdvisorInput = document.getElementById('kmh-org-edit-advisor');
    var editLeaderNameInput = document.getElementById('kmh-org-edit-leader-name');
    var editAccountEmailInput = document.getElementById('kmh-org-edit-account-email');
    var editAccountPasswordInput = document.getElementById('kmh-org-edit-account-password');
    var detailName = document.getElementById('kmh-org-detail-name');
    var detailShortname = document.getElementById('kmh-org-detail-shortname');
    var detailStatus = document.getElementById('kmh-org-detail-status');
    var detailCategory = document.getElementById('kmh-org-detail-category');
    var detailType = document.getElementById('kmh-org-detail-type');
    var detailScope = document.getElementById('kmh-org-detail-scope');
    var detailField = document.getElementById('kmh-org-detail-field');
    var detailAdvisor = document.getElementById('kmh-org-detail-advisor');
    var detailLeader = document.getElementById('kmh-org-detail-leader');
    var detailEmail = document.getElementById('kmh-org-detail-email');
    var detailPhone = document.getElementById('kmh-org-detail-phone');
    var detailDescription = document.getElementById('kmh-org-detail-description');
    var rows = Array.prototype.slice.call(document.querySelectorAll('[data-org-row]'));
    var emptyRow = document.querySelector('[data-org-empty]');
    var shouldOpenCreateModal = {{ ($errors->any() && !session('organization_edit_id')) ? 'true' : 'false' }};
    var editOrganizationId = @json(session('organization_edit_id'));
    var editOldValues = @json($editOldValues);

    function applyFilter() {
        var query = (searchInput.value || '').toLowerCase().trim();
        var category = (categorySelect.value || 'semua').toLowerCase();
        var visibleTotal = 0;

        rows.forEach(function (row) {
            var rowSearch = (row.getAttribute('data-org-search') || '').toLowerCase();
            var rowCategory = (row.getAttribute('data-org-category') || '').toLowerCase();

            var textMatch = query === '' || rowSearch.indexOf(query) !== -1;
            var categoryMatch = category === 'semua' || rowCategory === category;
            var showRow = textMatch && categoryMatch;

            row.classList.toggle('d-none', !showRow);

            if (showRow) {
                visibleTotal += 1;
            }
        });

        if (emptyRow) {
            emptyRow.classList.toggle('d-none', visibleTotal !== 0);
        }

        if (visibleCount) {
            visibleCount.textContent = String(visibleTotal);
        }
    }

    searchInput.addEventListener('input', applyFilter);
    categorySelect.addEventListener('change', applyFilter);
    applyFilter();

    var autofillState = {
        category: false,
        type: false,
        level: false,
        field: false,
    };

    function normalizeText(value) {
        return String(value || '').trim().toLowerCase();
    }

    function hasKeywords(source, keywords) {
        return keywords.some(function (keyword) {
            return source.indexOf(keyword) !== -1;
        });
    }

    function suggestOrganizationProfile(name) {
        var normalized = normalizeText(name);

        if (!normalized) {
            return {
                category: '',
                type: '',
                level: '',
                field: '',
            };
        }

        var hasBemSignals = hasKeywords(normalized, [
            'bem',
            'badan eksekutif mahasiswa',
            'senat mahasiswa',
            'dpm',
            'dewan perwakilan mahasiswa',
        ]);

        var hasFacultySignals = hasKeywords(normalized, [
            'fakultas',
            'himpunan mahasiswa',
            'hima',
            'himaf',
            'himatika',
            'himti',
            'himsi',
            'fkep',
            'fkip',
            'filsafat',
            'fakultas kedokteran',
        ]);

        var type = hasBemSignals ? 'BEM' : 'UKM';
        var level = hasFacultySignals ? 'Fakultas' : 'Universitas';
        var category = 'UKM Umum';
        var field = 'Bidang Umum';

        if (type === 'BEM') {
            category = 'BEM';
            field = 'Pemerintahan Mahasiswa';
        } else if (hasKeywords(normalized, ['kerohanian', 'rohani', 'ministry', 'pelayanan'])) {
            category = 'Kerohanian';
            field = 'Kerohanian';
        } else if (hasKeywords(normalized, ['paduan suara', 'choir', 'musik', 'seni'])) {
            category = 'Minat & Bakat';
            field = hasKeywords(normalized, ['musik', 'choir', 'paduan suara']) ? 'Paduan Suara' : 'Seni & Kreativitas';
        } else if (hasKeywords(normalized, ['kedaerahan', 'ikm', 'ikatan daerah', 'maluku', 'minahasa'])) {
            category = 'Kedaerahan';
            field = 'Organisasi Kedaerahan';
        } else if (hasKeywords(normalized, ['teknologi', 'tech', 'it', 'developer', 'coding', 'ai', 'robot', 'pasar modal', 'kspm'])) {
            category = 'Akademik & Teknologi';
            if (hasKeywords(normalized, ['pasar modal', 'kspm'])) {
                field = 'Pasar Modal';
            } else if (hasKeywords(normalized, ['ai', 'robot', 'coding', 'developer'])) {
                field = 'Inovasi Digital';
            } else {
                field = 'Akademik & Teknologi';
            }
        }

        return {
            category: category,
            type: type,
            level: level,
            field: field,
        };
    }

    function applyAutofill(force) {
        if (!createNameInput) {
            return;
        }

        var suggestion = suggestOrganizationProfile(createNameInput.value);

        if (createCategoryInput && (force || !autofillState.category || !createCategoryInput.value)) {
            createCategoryInput.value = suggestion.category;
        }

        if (createTypeInput && (force || !autofillState.type || !createTypeInput.value)) {
            createTypeInput.value = suggestion.type;
        }

        if (createLevelInput && (force || !autofillState.level || !createLevelInput.value)) {
            createLevelInput.value = suggestion.level;
        }

        if (createFieldInput && (force || !autofillState.field || !createFieldInput.value)) {
            createFieldInput.value = suggestion.field;
        }
    }

    function markManual(fieldName) {
        autofillState[fieldName] = true;
    }

    if (createNameInput) {
        createNameInput.addEventListener('input', function () {
            applyAutofill(false);
        });
        createNameInput.addEventListener('blur', function () {
            applyAutofill(false);
        });
    }

    if (createCategoryInput) {
        createCategoryInput.addEventListener('change', function () {
            markManual('category');
        });
    }

    if (createTypeInput) {
        createTypeInput.addEventListener('change', function () {
            markManual('type');
        });
    }

    if (createLevelInput) {
        createLevelInput.addEventListener('change', function () {
            markManual('level');
        });
    }

    if (createFieldInput) {
        createFieldInput.addEventListener('input', function () {
            markManual('field');
        });
    }

    if (createModalEl) {
        createModalEl.addEventListener('shown.bs.modal', function () {
            applyAutofill(false);
        });
    }

    if (createForm) {
        createForm.addEventListener('submit', function () {
            applyAutofill(true);
        });
    }

    if (createModalEl && shouldOpenCreateModal) {
        new bootstrap.Modal(createModalEl).show();
    } else if (editModalEl && editOrganizationId) {
        var row = document.querySelector('[data-org-id="' + editOrganizationId + '"]');
        if (row) {
            populateEditModal(row);
            new bootstrap.Modal(editModalEl).show();
        }
    }

    function populateDetailModal(row) {
        if (!detailModalEl) {
            return;
        }

        if (detailName) detailName.textContent = row.getAttribute('data-org-name') || '-';
        if (detailShortname) detailShortname.textContent = row.getAttribute('data-org-shortname') || '-';
        if (detailStatus) detailStatus.textContent = formatOrganizationStatus(row.getAttribute('data-org-status') || '');
        if (detailCategory) detailCategory.textContent = row.getAttribute('data-org-category-label') || '-';
        if (detailType) detailType.textContent = row.getAttribute('data-org-type') || '-';
        if (detailScope) detailScope.textContent = row.getAttribute('data-org-scope') || '-';
        if (detailField) detailField.textContent = row.getAttribute('data-org-field') || '-';
        if (detailAdvisor) detailAdvisor.textContent = row.getAttribute('data-org-advisor') || '-';
        if (detailLeader) detailLeader.textContent = row.getAttribute('data-org-leader') || '-';
        if (detailEmail) detailEmail.textContent = row.getAttribute('data-org-email') || '-';
        if (detailPhone) detailPhone.textContent = row.getAttribute('data-org-phone') || '-';
        if (detailDescription) detailDescription.textContent = row.getAttribute('data-org-description') || '-';

        new bootstrap.Modal(detailModalEl).show();
    }

    function populateEditModal(row) {
        if (!editModalEl || !editForm) {
            return;
        }

        var orgId = row.getAttribute('data-org-id') || '';
        var action = editForm.getAttribute('action') || '';
        editForm.setAttribute('action', action.replace(/\/(\d+)\/update$/, '/' + orgId + '/update'));

        function useOld(fieldName, fallback) {
            var value = editOldValues && editOldValues[fieldName];
            return value !== null && value !== undefined && String(value).trim() !== '' ? String(value) : fallback;
        }

        if (editIdInput) editIdInput.value = orgId;
        if (editNameInput) editNameInput.value = useOld('name', row.getAttribute('data-org-name') || '');
        if (editShortnameInput) editShortnameInput.value = useOld('shortname', row.getAttribute('data-org-shortname') || '');
        if (editDescriptionInput) editDescriptionInput.value = useOld('description', row.getAttribute('data-org-description') || '');
        if (editEmailInput) editEmailInput.value = useOld('email', row.getAttribute('data-org-email') || '');
        if (editPhoneInput) editPhoneInput.value = useOld('phone', row.getAttribute('data-org-phone') || '');
        if (editCategoryInput) editCategoryInput.value = useOld('category', row.getAttribute('data-org-category-label') || '');
        if (editTypeInput) editTypeInput.value = useOld('type', row.getAttribute('data-org-type') || '');
        if (editLevelInput) editLevelInput.value = useOld('level', row.getAttribute('data-org-level') || row.getAttribute('data-org-scope') || '');
        if (editFieldInput) editFieldInput.value = useOld('field', row.getAttribute('data-org-field') || '');
        if (editAdvisorInput) editAdvisorInput.value = useOld('advisor', row.getAttribute('data-org-advisor') || '');
        if (editLeaderNameInput) editLeaderNameInput.value = useOld('leader_name', row.getAttribute('data-org-leader') || '');
        if (editAccountEmailInput) editAccountEmailInput.value = useOld('account_email', row.getAttribute('data-org-account-email') || '');
        if (editAccountPasswordInput) editAccountPasswordInput.value = '';

        var status = useOld('status', row.getAttribute('data-org-status') || 'active').toLowerCase();
        if (editStatusInput) editStatusInput.value = ['active', 'inactive', 'suspended'].indexOf(status) !== -1 ? status : 'active';

        new bootstrap.Modal(editModalEl).show();
    }

    function formatOrganizationStatus(status) {
        var value = (status || '').toLowerCase();

        if (value === 'active') {
            return 'Aktif';
        }

        if (value === 'inactive') {
            return 'Nonaktif';
        }

        if (value === 'suspended') {
            return 'Suspended';
        }

        return status || '-';
    }

    document.querySelectorAll('[data-org-detail-trigger]').forEach(function (button) {
        button.addEventListener('click', function () {
            var row = button.closest('[data-org-row]');
            if (row) {
                populateDetailModal(row);
            }
        });
    });

    document.querySelectorAll('[data-org-edit-trigger]').forEach(function (button) {
        button.addEventListener('click', function () {
            var row = button.closest('[data-org-row]');
            if (row) {
                populateEditModal(row);
            }
        });
    });

    if (editModalEl) {
        editModalEl.addEventListener('hidden.bs.modal', function () {
            if (editForm) {
                editForm.setAttribute('action', '{{ route('portal.kemahasiswaan.organisasi.update', ['id' => 0]) }}');
            }
        });
    }
});
</script>
@endpush
