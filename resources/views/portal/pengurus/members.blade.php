@extends('layouts.portal.pengurus')

@section('title', 'Profil Organisasi')

@php
    $kategoriOptions = $kategoriOptions ?? [];
    $activeKategori = $activeKategori ?? '';
    $programKegiatan = $programKegiatan ?? [];
    $struktur = $struktur ?? [];
    $kontakPengurus = $kontakPengurus ?? [];
    $socialMedia = $socialMedia ?? [];
    $values = $values ?? [];
    $profileCompletion = $profileCompletion ?? 0;
    $visionText = $visionText ?? '';
    $missionText = $missionText ?? '';
    $cultureText = $cultureText ?? '';
    $organizationType = $organizationType ?? '';
    $organizationLevel = $organizationLevel ?? '';
    $organizationField = $organizationField ?? '';
    $contactEmail = $contactEmail ?? '';
    $contactInstagram = $contactInstagram ?? '';
    $contactFacebook = $contactFacebook ?? '';
    $contactTiktok = $contactTiktok ?? '';
    $contactYoutube = $contactYoutube ?? '';
    $valuesText = $valuesText ?? '';
    $programsText = $programsText ?? '';
    $structureText = $structureText ?? '';
    $contactsText = $contactsText ?? '';
    $registrationOpen = $registrationOpen ?? false;
    $registrationPeriod = $registrationPeriod ?? '';
    $registrationOpenDate = $registrationOpenDate ?? '';
    $registrationFormLink = $registrationFormLink ?? '';
    $registrationGuidebookUrl = $registrationGuidebookUrl ?? '';
    $registrationDivisionsText = $registrationDivisionsText ?? '';
    $registrationDivisionsPreview = collect(preg_split('/\r\n|\r|\n/', (string) $registrationDivisionsText) ?: [])
        ->map(static function (string $line): array {
            [$name, $description] = array_pad(explode('|', $line, 2), 2, '');
            return [
                'name' => trim($name),
                'description' => trim($description),
            ];
        })
        ->filter(static fn (array $item): bool => $item['name'] !== '' || $item['description'] !== '')
        ->values()
        ->all();
    $loggedOrganizationName = $loggedOrganizationName ?? '';
    $loggedAccountName = $loggedAccountName ?? '';
    $logoUrl = $logoUrl ?? '';
    $bannerUrl = $bannerUrl ?? '';
@endphp

@push('styles')
<style>
#membersEditModal .modal-dialog {
    max-width: 1080px;
}

#membersEditModal .modal-content {
    max-height: calc(100vh - 1.5rem);
}

#membersEditModal .modal-body {
    max-height: calc(100vh - 11.5rem);
    overflow-y: auto;
    overflow-x: hidden;
}

#membersEditModal .nav-link {
    font-size: 0.9rem;
}

.members-media-preview {
    width: 100%;
    max-height: 220px;
    object-fit: cover;
    border-radius: 0.6rem;
    border: 1px solid rgba(0, 0, 0, 0.08);
    background: #f4f6f8;
}

.members-media-empty {
    min-height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px dashed rgba(0, 0, 0, 0.18);
    border-radius: 0.6rem;
    color: #5f6b7a;
    background: #f8fafc;
}

.members-editor-sticky-tabs {
    position: sticky;
    top: -1rem;
    z-index: 2;
    background: #fff;
    padding-top: 0.25rem;
    padding-bottom: 0.5rem;
}

.members-readonly-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 0.75rem;
}

.members-readonly-item {
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 0.6rem;
    padding: 0.75rem;
    background: #fff;
}

.members-readonly-item h6 {
    margin: 0 0 0.25rem;
    font-size: 0.82rem;
    color: #6b7280;
}

.members-readonly-item p {
    margin: 0;
    font-weight: 600;
    color: #111827;
}

.members-simple-text {
    margin: 0;
    white-space: pre-line;
    color: #111827;
}

.members-section-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.75rem;
}

.members-section-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.members-context-chip {
    display: inline-flex;
    flex-wrap: wrap;
    gap: 0.35rem;
    margin-top: 0.35rem;
}

.members-context-chip span {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.78rem;
    color: #334155;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-radius: 999px;
    padding: 0.2rem 0.6rem;
}

@media (max-width: 768px) {
    #membersEditModal .modal-dialog {
        margin: 0.5rem;
    }

    #membersEditModal .modal-content {
        max-height: calc(100vh - 1rem);
    }

    #membersEditModal .modal-body {
        max-height: calc(100vh - 10rem);
    }

    .members-section-head {
        flex-direction: column;
    }

    .members-section-actions {
        width: 100%;
        display: grid;
        grid-template-columns: 1fr;
    }

    .members-section-actions .ufo-kboard-btn {
        width: 100%;
    }
}
</style>
@endpush

@section('content')
<div class="ufo-kboard-page">
    @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
    @endif

    <section class="ufo-kboard-section">
        <div class="members-section-head">
            <div>
                <h1 class="ufo-kboard-heading">Profil Organisasi</h1>
                <p class="ufo-kboard-lead">Kelola informasi organisasi yang ditampilkan kepada mahasiswa.</p>
                <div class="members-context-chip">
                    <span>
                        <i class="bi bi-building"></i>
                        Organisasi: {{ $loggedOrganizationName !== '' ? $loggedOrganizationName : '-' }}
                    </span>
                </div>
            </div>
            <div class="members-section-actions">
                <button class="ufo-kboard-btn primary" type="button" data-bs-toggle="modal" data-bs-target="#membersEditModal" data-edit-target="kategori" data-edit-title="Edit Profil Organisasi">
                    <i class="bi bi-pencil-square"></i>
                    Edit Profil
                </button>
                <button class="ufo-kboard-btn" type="button" data-bs-toggle="modal" data-bs-target="#membersEditModal" data-edit-target="pendaftaran" data-edit-title="Edit Info Pendaftaran">
                    <i class="bi bi-clipboard-plus"></i>
                    Edit Info Pendaftaran
                </button>
            </div>
        </div>

        <div class="ufo-kboard-row three mt-3">
            <article class="ufo-kboard-stat blue">
                <h3>{{ $profileCompletion }}%</h3>
                <p>Kelengkapan Profil</p>
            </article>
            <article class="ufo-kboard-stat green">
                <h3>{{ count($programKegiatan) }}</h3>
                <p>Program Aktif</p>
            </article>
            <article class="ufo-kboard-stat purple">
                <h3>{{ count($socialMedia) }}</h3>
                <p>Kanal Komunikasi</p>
            </article>
        </div>
    </section>

    <section class="ufo-kboard-section">
        <div class="ufo-kboard-item-head d-flex justify-content-between align-items-center gap-2 flex-wrap">
            <h3 class="ufo-kboard-item-title mb-0">Info Pendaftaran Organisasi</h3>
            <button class="ufo-kboard-btn" type="button" data-bs-toggle="modal" data-bs-target="#membersEditModal" data-edit-target="pendaftaran" data-edit-title="Edit Info Pendaftaran">
                <i class="bi bi-pencil-square"></i>
                Edit Info Pendaftaran
            </button>
        </div>

        <div class="members-readonly-grid mt-3">
            <article class="members-readonly-item">
                <h6>Status Pendaftaran</h6>
                <p>{{ $registrationOpen ? 'Dibuka' : 'Belum Dibuka' }}</p>
            </article>
            <article class="members-readonly-item">
                <h6>Periode</h6>
                <p>{{ $registrationPeriod !== '' ? $registrationPeriod : '-' }}</p>
            </article>
            <article class="members-readonly-item">
                <h6>Tanggal Dibuka</h6>
                <p>{{ $registrationOpenDate !== '' ? $registrationOpenDate : '-' }}</p>
            </article>
            <article class="members-readonly-item">
                <h6>Link Form</h6>
                <p>{{ $registrationFormLink !== '' ? $registrationFormLink : '-' }}</p>
            </article>
            <article class="members-readonly-item">
                <h6>Link Buku Panduan</h6>
                <p>{{ $registrationGuidebookUrl !== '' ? $registrationGuidebookUrl : '-' }}</p>
            </article>
            <article class="members-readonly-item">
                <h6>Total Divisi</h6>
                <p>{{ count($registrationDivisionsPreview) }}</p>
            </article>
        </div>
    </section>

    <section class="ufo-kboard-section">
        <div class="ufo-kboard-item-head">
            <h3 class="ufo-kboard-item-title">Kategori Organisasi</h3>
        </div>

        <div class="members-readonly-grid">
            <article class="members-readonly-item">
                <h6>Kategori</h6>
                <p>{{ $activeKategori !== '' ? $activeKategori : '-' }}</p>
            </article>
            <article class="members-readonly-item">
                <h6>Tipe Organisasi</h6>
                <p>{{ $organizationType !== '' ? $organizationType : '-' }}</p>
            </article>
            <article class="members-readonly-item">
                <h6>Bidang</h6>
                <p>{{ $organizationField !== '' ? $organizationField : '-' }}</p>
            </article>
        </div>
    </section>

    <section class="ufo-kboard-section">
        <div class="ufo-kboard-item-head">
            <h3 class="ufo-kboard-item-title">Logo &amp; Banner</h3>
        </div>

        <div class="ufo-pg-media-grid">
            <article>
                <p class="ufo-kboard-item-meta mb-2">Logo Organisasi</p>
                <div class="ufo-pg-upload-box">
                    @if($logoUrl !== '')
                        <img src="{{ $logoUrl }}" alt="Logo organisasi" class="members-media-preview">
                    @else
                        <i class="bi bi-image"></i>
                        <p class="mb-0">Klik untuk upload logo</p>
                        <small>PNG, JPG (maks. 2MB)</small>
                    @endif
                </div>
            </article>

            <article>
                <p class="ufo-kboard-item-meta mb-2">Banner Organisasi</p>
                <div class="ufo-pg-upload-box">
                    @if($bannerUrl !== '')
                        <img src="{{ $bannerUrl }}" alt="Banner organisasi" class="members-media-preview">
                    @else
                        <i class="bi bi-card-image"></i>
                        <p class="mb-0">Klik untuk upload banner</p>
                        <small>PNG, JPG (maks. 5MB)</small>
                    @endif
                </div>
            </article>
        </div>
    </section>

    <div class="ufo-kboard-row two">
        <section class="ufo-kboard-section">
            <div class="ufo-kboard-item-head">
                <h3 class="ufo-kboard-item-title">Visi &amp; Misi</h3>
            </div>

            <p class="ufo-kboard-item-meta mb-1">Visi</p>
            <p class="members-simple-text mb-3">{{ $visionText !== '' ? $visionText : '-' }}</p>

            <p class="ufo-kboard-item-meta mb-1">Misi</p>
            <p class="members-simple-text">{{ $missionText !== '' ? $missionText : '-' }}</p>
        </section>

        <section class="ufo-kboard-section">
            <div class="ufo-kboard-item-head">
                <h3 class="ufo-kboard-item-title">Values &amp; Culture</h3>
            </div>

            <p class="ufo-kboard-item-meta mb-1">Culture</p>
            <p class="members-simple-text mb-3">{{ $cultureText !== '' ? $cultureText : '-' }}</p>

            <div class="ufo-kboard-list">
                @forelse($values as $value)
                    <article class="ufo-kboard-item ufo-pg-soft-card">
                        <h4 class="ufo-kboard-item-title">{{ $value['name'] }}</h4>
                        <p class="ufo-kboard-item-text">{{ $value['desc'] }}</p>
                    </article>
                @empty
                    <p class="ufo-kboard-item-meta mb-0">Belum ada nilai organisasi yang tersimpan.</p>
                @endforelse
            </div>
        </section>

        <section class="ufo-kboard-section">
            <div class="ufo-kboard-item-head">
                <h3 class="ufo-kboard-item-title">Program Kegiatan</h3>
            </div>

            <div class="ufo-kboard-list">
                @forelse($programKegiatan as $program)
                    <details class="ufo-kboard-item ufo-pg-program-card">
                        <summary>
                            <div>
                                <h4 class="ufo-kboard-item-title">{{ $program['nama'] }}</h4>
                            </div>
                            <span class="ufo-kboard-pill draft">Detail</span>
                        </summary>

                        <div class="ufo-pg-program-detail">
                            <p><strong>Deskripsi:</strong> {{ $program['deskripsi'] }}</p>
                            <p><strong>Tujuan:</strong> {{ $program['tujuan'] }}</p>
                        </div>
                    </details>
                @empty
                    <p class="ufo-kboard-item-meta mb-0">Belum ada program kegiatan yang tersimpan.</p>
                @endforelse
            </div>
        </section>
    </div>

    <div class="ufo-kboard-row two">
        <section class="ufo-kboard-section">
            <div class="ufo-kboard-item-head">
                <h3 class="ufo-kboard-item-title">Struktur Kepengurusan</h3>
            </div>

            <div class="ufo-kboard-list">
                @forelse($struktur as $row)
                    <article class="ufo-kboard-item">
                        <div class="ufo-kboard-item-head mb-0">
                            <h4 class="ufo-kboard-item-title">{{ $row['jabatan'] }}</h4>
                            <span class="ufo-kboard-pill pending">{{ $row['nama'] }}</span>
                        </div>
                    </article>
                @empty
                    <p class="ufo-kboard-item-meta mb-0">Struktur kepengurusan belum tersedia.</p>
                @endforelse
            </div>
        </section>

        <section class="ufo-kboard-section">
            <div class="ufo-kboard-item-head">
                <h3 class="ufo-kboard-item-title">Kontak Pengurus</h3>
            </div>

            <div class="ufo-kboard-list">
                @forelse($kontakPengurus as $contact)
                    <article class="ufo-kboard-item ufo-pg-contact-mini">
                        <h4 class="ufo-kboard-item-title">{{ $contact['nama'] }}</h4>
                        <p class="ufo-kboard-item-meta">{{ $contact['jabatan'] }}</p>
                        <p class="ufo-kboard-item-text"><i class="bi bi-whatsapp"></i> {{ $contact['whatsapp'] }}</p>
                    </article>
                @empty
                    <p class="ufo-kboard-item-meta mb-0">Kontak pengurus belum tersedia.</p>
                @endforelse
            </div>
        </section>
    </div>

    <section class="ufo-kboard-section">
        <div class="ufo-kboard-item-head">
            <h3 class="ufo-kboard-item-title">Social Media &amp; Kontak</h3>
        </div>

        <div class="members-readonly-grid">
            @forelse($socialMedia as $item)
                <article class="members-readonly-item">
                    <h6>{{ $item['platform'] }}</h6>
                    <p>{{ $item['value'] }}</p>
                </article>
            @empty
                <p class="ufo-kboard-item-meta mb-0">Belum ada kanal sosial media/kontak yang tersimpan.</p>
            @endforelse
        </div>
    </section>
</div>

<div class="modal fade" id="membersEditModal" tabindex="-1" aria-labelledby="membersEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="{{ route('portal.pengurus.members.profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="membersEditModalLabel">Edit Profil Organisasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info" role="alert">
                        Format data baris: gunakan pemisah <strong>|</strong>.
                    </div>

                    <div class="members-editor-sticky-tabs">
                        <ul class="nav nav-pills nav-fill" id="membersEditorTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="members-tab-kategori" data-bs-toggle="pill" data-bs-target="#members-pane-kategori" type="button" role="tab" aria-controls="members-pane-kategori" aria-selected="true">Kategori</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="members-tab-media" data-bs-toggle="pill" data-bs-target="#members-pane-media" type="button" role="tab" aria-controls="members-pane-media" aria-selected="false">Media</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="members-tab-visi-misi" data-bs-toggle="pill" data-bs-target="#members-pane-visi-misi" type="button" role="tab" aria-controls="members-pane-visi-misi" aria-selected="false">Visi & Misi</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="members-tab-kontak" data-bs-toggle="pill" data-bs-target="#members-pane-kontak" type="button" role="tab" aria-controls="members-pane-kontak" aria-selected="false">Kontak</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="members-tab-values" data-bs-toggle="pill" data-bs-target="#members-pane-values" type="button" role="tab" aria-controls="members-pane-values" aria-selected="false">Values & Culture</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="members-tab-program" data-bs-toggle="pill" data-bs-target="#members-pane-program" type="button" role="tab" aria-controls="members-pane-program" aria-selected="false">Program</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="members-tab-pendaftaran" data-bs-toggle="pill" data-bs-target="#members-pane-pendaftaran" type="button" role="tab" aria-controls="members-pane-pendaftaran" aria-selected="false">Pendaftaran</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="members-tab-struktur" data-bs-toggle="pill" data-bs-target="#members-pane-struktur" type="button" role="tab" aria-controls="members-pane-struktur" aria-selected="false">Struktur</button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content pt-3" id="membersEditorTabsContent">
                        <div class="tab-pane fade show active" id="members-pane-kategori" role="tabpanel" aria-labelledby="members-tab-kategori" tabindex="0">
                            <section class="mb-4" data-edit-section="kategori">
                                <h6 class="mb-3">Kategori Organisasi</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Kategori</label>
                                        <input type="text" class="form-control" name="category" value="{{ old('category', $activeKategori) }}" list="members-category-options">
                                        <datalist id="members-category-options">
                                            @foreach($kategoriOptions as $kategori)
                                                <option value="{{ $kategori }}"></option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tipe Organisasi</label>
                                        <input type="text" class="form-control" name="type" value="{{ old('type', $organizationType) }}" placeholder="Contoh: UKM / BEM">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Bidang</label>
                                        <input type="text" class="form-control" name="field" value="{{ old('field', $organizationField) }}" placeholder="Contoh: Seni / Olahraga / Akademik">
                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="tab-pane fade" id="members-pane-media" role="tabpanel" aria-labelledby="members-tab-media" tabindex="0">
                            <section class="mb-4" data-edit-section="media">
                                <h6 class="mb-3">Logo dan Banner</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Logo Organisasi</label>
                                        <input id="members-logo-input" type="file" class="form-control" name="logo_file" accept="image/png,image/jpeg,image/webp">
                                        <small class="text-muted">PNG/JPG/WEBP maksimal 2MB.</small>
                                        <div class="mt-2">
                                            @if($logoUrl !== '')
                                                <img id="members-logo-preview" src="{{ $logoUrl }}" alt="Preview logo" class="members-media-preview">
                                            @else
                                                <div id="members-logo-preview-empty" class="members-media-empty">Belum ada logo</div>
                                                <img id="members-logo-preview" src="" alt="Preview logo" class="members-media-preview d-none">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Banner Organisasi</label>
                                        <input id="members-banner-input" type="file" class="form-control" name="banner_file" accept="image/png,image/jpeg,image/webp">
                                        <small class="text-muted">PNG/JPG/WEBP maksimal 5MB.</small>
                                        <div class="mt-2">
                                            @if($bannerUrl !== '')
                                                <img id="members-banner-preview" src="{{ $bannerUrl }}" alt="Preview banner" class="members-media-preview">
                                            @else
                                                <div id="members-banner-preview-empty" class="members-media-empty">Belum ada banner</div>
                                                <img id="members-banner-preview" src="" alt="Preview banner" class="members-media-preview d-none">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="tab-pane fade" id="members-pane-visi-misi" role="tabpanel" aria-labelledby="members-tab-visi-misi" tabindex="0">
                            <section class="mb-4" data-edit-section="visi-misi">
                                <h6 class="mb-3">Visi, Misi, dan Culture</h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Visi</label>
                                        <textarea class="form-control" name="vision_text" rows="3">{{ old('vision_text', $visionText) }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Misi</label>
                                        <textarea class="form-control" name="mission_text" rows="4">{{ old('mission_text', $missionText) }}</textarea>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="tab-pane fade" id="members-pane-kontak" role="tabpanel" aria-labelledby="members-tab-kontak" tabindex="0">
                            <section class="mb-4" data-edit-section="kontak">
                                <h6 class="mb-3">Kontak dan Social Media</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email', $contactEmail) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Instagram</label>
                                        <input type="text" class="form-control" name="instagram" value="{{ old('instagram', $contactInstagram) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Facebook</label>
                                        <input type="text" class="form-control" name="facebook" value="{{ old('facebook', $contactFacebook) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">TikTok</label>
                                        <input type="text" class="form-control" name="tiktok" value="{{ old('tiktok', $contactTiktok) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">YouTube</label>
                                        <input type="text" class="form-control" name="youtube" value="{{ old('youtube', $contactYoutube) }}">
                                    </div>
                                </div>
                            </section>
                        </div>

                        <div class="tab-pane fade" id="members-pane-values" role="tabpanel" aria-labelledby="members-tab-values" tabindex="0">
                            <section class="mb-4" data-edit-section="values">
                                <h6 class="mb-3">Values dan Culture</h6>
                                <div class="mb-3">
                                    <label class="form-label">Culture / Deskripsi</label>
                                    <textarea class="form-control" name="culture_text" rows="4">{{ old('culture_text', $cultureText) }}</textarea>
                                </div>
                                <label class="form-label">Format per baris: Nama Value|Deskripsi</label>
                                <textarea class="form-control" name="values_text" rows="8" placeholder="Integritas|Menjaga komitmen organisasi">{{ old('values_text', $valuesText) }}</textarea>
                            </section>
                        </div>

                        <div class="tab-pane fade" id="members-pane-program" role="tabpanel" aria-labelledby="members-tab-program" tabindex="0">
                            <section class="mb-4" data-edit-section="program">
                                <h6 class="mb-3">Program Kegiatan</h6>
                                <label class="form-label">Format per baris: Nama Program|Deskripsi|Tujuan</label>
                                <textarea class="form-control" name="programs_text" rows="10" placeholder="Seminar Teknologi|Workshop dan seminar teknologi|Meningkatkan literasi teknologi">{{ old('programs_text', $programsText) }}</textarea>
                            </section>
                        </div>

                        <div class="tab-pane fade" id="members-pane-pendaftaran" role="tabpanel" aria-labelledby="members-tab-pendaftaran" tabindex="0">
                            <section class="mb-4" data-edit-section="pendaftaran">
                                <h6 class="mb-3">Info Pendaftaran Organisasi</h6>

                                <div class="form-check form-switch mb-3">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        role="switch"
                                        id="members-registration-open"
                                        name="registration_open"
                                        value="1"
                                        @checked(old('registration_open', $registrationOpen ? '1' : '0') === '1')
                                    >
                                    <label class="form-check-label" for="members-registration-open">Pendaftaran sedang dibuka</label>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Periode Pendaftaran</label>
                                        <input type="text" class="form-control" name="registration_period" value="{{ old('registration_period', $registrationPeriod) }}" placeholder="Contoh: 1 - 14 Mei 2026">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Dibuka (jika belum dibuka)</label>
                                        <input type="text" class="form-control" name="registration_open_date" value="{{ old('registration_open_date', $registrationOpenDate) }}" placeholder="Contoh: Minggu ke-2 Agustus 2026">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Link Form Pendaftaran</label>
                                        <input type="text" class="form-control" name="registration_form_link" value="{{ old('registration_form_link', $registrationFormLink) }}" placeholder="https://...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Link Buku Panduan</label>
                                        <input type="text" class="form-control" name="registration_guidebook_url" value="{{ old('registration_guidebook_url', $registrationGuidebookUrl) }}" placeholder="https://...">
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">Divisi (format per baris: Nama Divisi|Deskripsi)</label>
                                    <textarea class="form-control" name="registration_divisions_text" rows="8" placeholder="Pengembangan|Fokus pada riset dan pengembangan anggota">{{ old('registration_divisions_text', $registrationDivisionsText) }}</textarea>
                                </div>
                            </section>
                        </div>

                        <div class="tab-pane fade" id="members-pane-struktur" role="tabpanel" aria-labelledby="members-tab-struktur" tabindex="0">
                            <section class="mb-4" data-edit-section="struktur">
                                <h6 class="mb-3">Struktur dan Kontak Pengurus</h6>
                                <label class="form-label">Struktur (format per baris: Jabatan|Nama)</label>
                                <textarea class="form-control mb-3" name="structure_text" rows="8" placeholder="Ketua|Nama Ketua">{{ old('structure_text', $structureText) }}</textarea>

                                <label class="form-label">Kontak (format per baris: Nama|Jabatan|WhatsApp)</label>
                                <textarea class="form-control" name="contacts_text" rows="8" placeholder="Nama Pengurus|Sekretaris|08123456789">{{ old('contacts_text', $contactsText) }}</textarea>
                            </section>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('membersEditModal');
    if (!modalEl) {
        return;
    }

    var titleEl = document.getElementById('membersEditModalLabel');
    var tabMap = {
        'kategori': '#members-tab-kategori',
        'media': '#members-tab-media',
        'visi-misi': '#members-tab-visi-misi',
        'kontak': '#members-tab-kontak',
        'values': '#members-tab-values',
        'program': '#members-tab-program',
        'pendaftaran': '#members-tab-pendaftaran',
        'struktur': '#members-tab-struktur'
    };

    function showTabByTarget(sectionName) {
        var tabSelector = tabMap[sectionName] || tabMap['kategori'];
        var tabButton = tabSelector ? modalEl.querySelector(tabSelector) : null;
        if (!tabButton) {
            return;
        }

        var tabInstance = bootstrap.Tab.getOrCreateInstance(tabButton);
        tabInstance.show();
    }

    function bindFilePreview(inputId, imageId, emptyId) {
        var inputEl = document.getElementById(inputId);
        var imageEl = document.getElementById(imageId);
        var emptyEl = document.getElementById(emptyId);

        if (!inputEl || !imageEl) {
            return;
        }

        inputEl.addEventListener('change', function () {
            var file = inputEl.files && inputEl.files[0] ? inputEl.files[0] : null;
            if (!file) {
                return;
            }

            var objectUrl = URL.createObjectURL(file);
            imageEl.src = objectUrl;
            imageEl.classList.remove('d-none');
            if (emptyEl) {
                emptyEl.classList.add('d-none');
            }
        });
    }

    modalEl.addEventListener('show.bs.modal', function (event) {
        var trigger = event.relatedTarget;
        var sectionName = trigger && trigger.getAttribute('data-edit-target') ? trigger.getAttribute('data-edit-target') : '';
        var sectionTitle = trigger && trigger.getAttribute('data-edit-title') ? trigger.getAttribute('data-edit-title') : 'Edit Profil Organisasi';

        if (titleEl) {
            titleEl.textContent = sectionTitle;
        }

        showTabByTarget(sectionName);

        if (!sectionName) {
            return;
        }

        setTimeout(function () {
            var section = modalEl.querySelector('[data-edit-section="' + sectionName + '"]');
            if (section) {
                section.scrollIntoView({ behavior: 'auto', block: 'start' });
                var firstInput = section.querySelector('input, textarea, select');
                if (firstInput) {
                    firstInput.focus();
                }
            }
        }, 120);
    });

    bindFilePreview('members-logo-input', 'members-logo-preview', 'members-logo-preview-empty');
    bindFilePreview('members-banner-input', 'members-banner-preview', 'members-banner-preview-empty');
});
</script>
@endsection
