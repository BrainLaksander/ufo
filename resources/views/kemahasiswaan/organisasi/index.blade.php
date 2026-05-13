@extends('layouts.app')

@section('title', 'Manajemen Organisasi - Sistem Kemahasiswaan')

@section('content')
<style>
.org-tabs {
    display: flex;
    gap: 0;
    border-bottom: 2px solid #e2e8f0;
    margin-bottom: 0;
    padding: 0 4px;
    overflow-x: auto;
}
.org-tab {
    background: none;
    border: none;
    padding: 12px 16px;
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.2s ease;
    white-space: nowrap;
    margin-bottom: -2px;
}
.org-tab:hover {
    color: #0f172a;
    background: rgba(111, 59, 167, 0.04);
}
.org-tab.active {
    color: #6f3ba7;
    border-bottom-color: #6f3ba7;
}
.org-tab-content {
    display: none;
}
.org-tab-content.active {
    display: block;
    animation: fadeIn 0.3s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@php
    $currentCategory = request('category', '');
@endphp

<div class="org-page">
    <div>
        <h1>Manajemen Organisasi</h1>
        <p>Sistem administrasi, data, dan struktur pengurus organisasi mahasiswa.</p>
    </div>
    @if(session('status'))
        <div class="flash-banner">{{ session('status') }}</div>
    @endif

    <form class="org-toolbar" method="GET" action="{{ route('kemahasiswaan.organizations.index') }}">
        <input class="org-input" name="q" type="search" value="{{ request('q') }}" placeholder="Cari organisasi..." aria-label="Cari organisasi">
        <select class="org-select" name="category" aria-label="Semua kategori" onchange="this.form.submit()">
            <option value="" @selected($currentCategory === '')>Semua Kategori</option>
            <option value="BEM" @selected($currentCategory === 'BEM')>BEM</option>
            <option value="Choir" @selected($currentCategory === 'Choir')>Choir</option>
            <option value="Creative Club" @selected($currentCategory === 'Creative Club')>Creative Club</option>
            <option value="Ministries" @selected($currentCategory === 'Ministries')>Ministries</option>
            <option value="Ikatan Daerah" @selected($currentCategory === 'Ikatan Daerah')>Ikatan Daerah</option>
        </select>
        <button type="button" class="org-button add" id="openOrganizationModal">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 5v14"></path>
                <path d="M5 12h14"></path>
            </svg>
            Tambah Organisasi
        </button>
    </form>

    <section class="org-stats-grid" aria-label="Statistik organisasi" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
        <div class="org-stat-card">
            <div>
                <div class="org-stat-label">Total Organisasi</div>
                <div class="org-stat-value">{{ $totalOrgs ?? (is_countable($organizations) ? count($organizations) : 0) }}</div>
            </div>
            <div class="org-stat-icon purple" aria-hidden="true">
                <svg viewBox="0 0 24 24">
                    <path d="M7 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"></path>
                    <path d="M17 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"></path>
                    <path d="M5 20v-1a4 4 0 0 1 4-4h2"></path>
                    <path d="M19 20v-1a4 4 0 0 0-4-4h-2"></path>
                </svg>
            </div>
        </div>

        <div class="org-stat-card">
            <div>
                <div class="org-stat-label">BEM</div>
                <div class="org-stat-value">{{ $countBEM ?? collect($organizations)->where('kategori', 'BEM')->count() }}</div>
            </div>
            <div class="org-stat-icon gold" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M7 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"></path><path d="M17 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"></path><path d="M5 20v-1a4 4 0 0 1 4-4h2"></path><path d="M19 20v-1a4 4 0 0 0-4-4h-2"></path></svg>
            </div>
        </div>

        <div class="org-stat-card">
            <div>
                <div class="org-stat-label">Choir</div>
                <div class="org-stat-value">{{ $countChoir ?? collect($organizations)->where('kategori', 'Choir')->count() }}</div>
            </div>
            <div class="org-stat-icon purple" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M7 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"></path><path d="M17 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"></path><path d="M5 20v-1a4 4 0 0 1 4-4h2"></path><path d="M19 20v-1a4 4 0 0 0-4-4h-2"></path></svg>
            </div>
        </div>

        <div class="org-stat-card">
            <div>
                <div class="org-stat-label">Creative Club</div>
                <div class="org-stat-value">{{ $countCreativeClub ?? collect($organizations)->where('kategori', 'Creative Club')->count() }}</div>
            </div>
            <div class="org-stat-icon gold" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M7 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"></path><path d="M17 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"></path><path d="M5 20v-1a4 4 0 0 1 4-4h2"></path><path d="M19 20v-1a4 4 0 0 0-4-4h-2"></path></svg>
            </div>
        </div>

        <div class="org-stat-card">
            <div>
                <div class="org-stat-label">Ministries</div>
                <div class="org-stat-value">{{ $countMinistries ?? collect($organizations)->where('kategori', 'Ministries')->count() }}</div>
            </div>
            <div class="org-stat-icon purple" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M7 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"></path><path d="M17 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"></path><path d="M5 20v-1a4 4 0 0 1 4-4h2"></path><path d="M19 20v-1a4 4 0 0 0-4-4h-2"></path></svg>
            </div>
        </div>

        <div class="org-stat-card">
            <div>
                <div class="org-stat-label">Ikatan Daerah</div>
                <div class="org-stat-value">{{ $countIkatanDaerah ?? collect($organizations)->where('kategori', 'Ikatan Daerah')->count() }}</div>
            </div>
            <div class="org-stat-icon gold" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M7 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"></path><path d="M17 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"></path><path d="M5 20v-1a4 4 0 0 1 4-4h2"></path><path d="M19 20v-1a4 4 0 0 0-4-4h-2"></path></svg>
            </div>
        </div>
    </section>

    <section class="org-table-card">
        <div class="org-table-head">
            <h2>Daftar Organisasi</h2>
            <p>Kelola data organisasi dengan tampilan yang lebih bersih dan fokus.</p>
        </div>

        <div class="org-table-wrap">
            <table class="org-table">
                <thead>
                    <tr>
                        <th>Nama Organisasi</th>
                        <th>Kategori</th>
                        <th>Advisor</th>
                        <th>Ketua</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organizations as $organization)
                        @php
                            $status = $organization->status ?? 'Aktif';
                            $leader = $organization->ketua_name ?? '';
                            $subLabel = $organization->level ?? $organization->field ?? '';
                        @endphp
                        <tr data-id="{{ $organization->id }}" data-status="{{ $status }}" data-advisor-email="{{ $organization->advisor_email ?? '' }}" data-account-email="{{ $organization->account_email ?? '' }}" data-advisor-name="{{ $organization->advisor_name ?? '' }}">
                            <td>
                                <div class="org-name">{{ $organization->name }}</div>
                                <div class="org-meta">{{ $subLabel }}</div>
                            </td>
                            <td><span class="chip chip-purple">{{ $organization->kategori }}</span></td>
                            <td>{{ $organization->advisor_name ?? '-' }}</td>
                            <td>{{ $organization->ketua_name ?? '-' }}</td>
                            @php
                                $statusClass = $status === 'Aktif' ? 'chip-purple' : 'chip-gray';
                            @endphp
                            <td><span class="chip {{ $statusClass }}">{{ $status }}</span></td>
                            <td>
                                <div class="row-actions">
                                    <button type="button" class="icon-action" data-view data-id="{{ $organization->id }}" aria-label="Lihat organisasi" title="Lihat">
                                        <svg viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </button>
                                    <button type="button" class="icon-action edit" data-edit data-id="{{ $organization->id }}" aria-label="Edit organisasi" title="Edit">
                                        <svg viewBox="0 0 24 24"><path d="M4 20h4"></path><path d="M14.5 5.5 18.5 9.5"></path><path d="M5 19l2-6 11-11a1.4 1.4 0 0 1 2 0l1 1a1.4 1.4 0 0 1 0 2l-11 11-6 2Z"></path></svg>
                                    </button>
                                    @if($organization->kategori !== 'BEM')
                                    <button type="button" class="icon-action" data-toggle-status data-id="{{ $organization->id }}" aria-label="Toggle Status" title="{{ $status === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        @if($status === 'Aktif')
                                            <svg viewBox="0 0 24 24"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                                        @else
                                            <svg viewBox="0 0 24 24"><path d="M12 2v10"></path><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path></svg>
                                        @endif
                                    </button>
                                    @else
                                    <button type="button" class="icon-action" disabled style="opacity:0.3; cursor:not-allowed" aria-label="BEM tidak bisa dinonaktifkan" title="BEM tidak bisa dinonaktifkan">
                                        <svg viewBox="0 0 24 24"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                                    </button>
                                    @endif
                                    <button type="button" class="icon-action delete" data-delete data-id="{{ $organization->id }}" aria-label="Hapus organisasi" title="Hapus">
                                        <svg viewBox="0 0 24 24"><path d="M4 7h16"></path><path d="M8 7V5a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2"></path><path d="M6 7l1 13a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1l1-13"></path><path d="M10 11v6"></path><path d="M14 11v6"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <h3>Belum ada organisasi</h3>
                                    <p class="dashboard-note org-empty-note">Anda belum menambahkan organisasi. Klik tombol di bawah untuk menambah organisasi baru.</p>
                                    <button type="button" class="btn-primary" id="openOrganizationModalEmpty">Tambah Organisasi</button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if(method_exists($organizations, 'links'))
        <div class="pagination-wrap">
            @if($organizations->onFirstPage())
                <span class="page-link" aria-disabled="true">Prev</span>
            @else
                <a class="page-link" href="{{ $organizations->previousPageUrl() }}">Prev</a>
            @endif

            <span class="org-page-info">Halaman {{ $organizations->currentPage() }} dari {{ $organizations->lastPage() }}</span>

            @if($organizations->hasMorePages())
                <a class="page-link" href="{{ $organizations->nextPageUrl() }}">Next</a>
            @else
                <span class="page-link" aria-disabled="true">Next</span>
            @endif
        </div>
    @endif
</div>

<div class="org-modal-backdrop" id="organizationBackdrop" aria-hidden="true"></div>

<div class="org-modal" id="organizationModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="organizationModalTitle">
    <div class="org-modal-panel" style="max-width: 720px;">
        <div class="org-modal-header" style="background: linear-gradient(135deg, #6f3ba7 0%, #a476d1 100%); color: #fff; border-radius: 16px 16px 0 0; padding: 24px 28px;">
            <div>
                <h2 id="organizationModalTitle" style="color: #fff; margin: 0 0 4px;">Tambah Organisasi</h2>
                <p style="color: rgba(255,255,255,0.8); margin: 0; font-size: 14px;">Isi informasi dasar dan struktur pengurus organisasi.</p>
            </div>
            <button type="button" class="close-btn" data-close-modal aria-label="Tutup" style="color: #fff; font-size: 28px; background: rgba(255,255,255,0.15); border: none; width: 36px; height: 36px; border-radius: 10px; cursor: pointer; display: grid; place-items: center;">&times;</button>
        </div>

        <form class="org-modal-body" id="organizationForm" novalidate style="padding: 0;">
            <input type="hidden" id="org_id" name="id" value="">

            <div class="org-tabs" style="padding: 0 28px; gap: 0; background: #faf8fc;">
                <button type="button" class="org-tab active" data-tab="info" style="padding: 14px 20px; font-size: 13px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -2px; margin-right: 6px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Informasi Dasar
                </button>
                <button type="button" class="org-tab" data-tab="pengurus" style="padding: 14px 20px; font-size: 13px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -2px; margin-right: 6px;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Struktur Pengurus
                </button>
                <button type="button" class="org-tab" data-tab="account" style="padding: 14px 20px; font-size: 13px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: -2px; margin-right: 6px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Akun & Password
                </button>
            </div>

            {{-- Tab 1: Informasi Dasar --}}
            <div class="org-tab-content active" id="tab-info" style="padding: 24px 28px;">
                <div class="form-grid">
                    <div class="form-field full">
                        <label for="nama_organisasi">Nama Organisasi <span style="color: #ef4444;">*</span></label>
                        <input id="nama_organisasi" name="name" type="text" placeholder="Contoh: BEM UNKLAB" required>
                        <div class="field-error" data-error-for="name"></div>
                    </div>
                    <div class="form-field">
                        <label for="singkatan">Singkatan</label>
                        <input id="singkatan" name="abbreviation" type="text" placeholder="Contoh: BEM">
                        <div class="field-error" data-error-for="abbreviation"></div>
                    </div>
                    <div class="form-field">
                        <label for="kategori">Kategori <span style="color: #ef4444;">*</span></label>
                        <select id="kategori" name="kategori" required>
                            <option value="" selected disabled>Pilih kategori</option>
                            <option value="BEM">BEM</option>
                            <option value="Choir">Choir</option>
                            <option value="Creative Club">Creative Club</option>
                            <option value="Ministries">Ministries</option>
                            <option value="Ikatan Daerah">Ikatan Daerah</option>
                        </select>
                        <div class="field-error" data-error-for="kategori"></div>
                    </div>
                    <div class="form-field">
                        <label for="level">Level</label>
                        <select id="level" name="level">
                            <option value="" selected disabled>Pilih level</option>
                            <option value="Universitas">Universitas</option>
                            <option value="Fakultas">Fakultas</option>
                            <option value="Umum">Umum</option>
                        </select>
                        <div class="field-error" data-error-for="level"></div>
                    </div>
                    {{-- Status field hanya muncul saat edit --}}
                    <div class="form-field" id="statusFieldWrapper" style="display: none;">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div class="form-field full">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="description" placeholder="Tulis deskripsi singkat organisasi" rows="3"></textarea>
                        <div class="field-error" data-error-for="description"></div>
                    </div>
                </div>
            </div>

            {{-- Tab 2: Struktur Pengurus --}}
            <div class="org-tab-content" id="tab-pengurus" style="display: none; padding: 24px 28px;">
                <p style="margin: 0 0 20px; font-size: 13px; color: #6b7280; line-height: 1.5;">
                    Data pengurus yang diinput di sini akan ditampilkan di halaman profil organisasi dan halaman mahasiswa.
                </p>

                {{-- Sekretaris, Bendahara dikelola oleh pengurus organisasi masing-masing --}}
                <p style="margin: 0 0 20px; padding: 14px; background: #fef9c3; border: 1px solid #fde68a; border-radius: 10px; font-size: 13px; color: #854d0e; line-height: 1.5;">
                    <strong>Info:</strong> Data Sekretaris dan Bendahara dikelola langsung oleh pengurus organisasi masing-masing melalui portal mereka.
                </p>

                {{-- Ketua --}}
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: linear-gradient(135deg, #f59e0b, #fbbf24); display: grid; place-items: center; color: #fff; font-weight: 800; font-size: 13px;">K</div>
                        <h4 style="margin: 0; font-size: 15px; color: #1e1b4b; font-weight: 700;">Ketua</h4>
                    </div>
                    <div class="form-grid">
                        <div class="form-field full">
                            <label>Nama Ketua</label>
                            <select name="ketua_name" id="ketua_name">
                                <option value="" selected>Pilih Ketua</option>
                                @foreach($students as $s)
                                    <option value="{{ $s }}">{{ $s }}</option>
                                @endforeach
                            </select>
                            <div class="field-error" data-error-for="ketua_name"></div>
                        </div>
                        <div class="form-field">
                            <label>No. HP Ketua</label>
                            <input type="text" name="chair_phone" id="chair_phone" placeholder="08xx-xxxx-xxxx">
                        </div>
                        <div class="form-field">
                            <label>Email Ketua</label>
                            <input type="email" name="chair_email" id="chair_email" placeholder="email@unklab.ac.id">
                        </div>
                    </div>
                </div>

                {{-- Pembina / Advisor --}}
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: linear-gradient(135deg, #059669, #34d399); display: grid; place-items: center; color: #fff; font-weight: 800; font-size: 13px;">P</div>
                        <h4 style="margin: 0; font-size: 15px; color: #1e1b4b; font-weight: 700;">Pembina (Advisor)</h4>
                    </div>
                    <div class="form-grid">
                        <div class="form-field full">
                            <label>Nama Pembina</label>
                            <select name="advisor_name" id="advisor_name">
                                <option value="" selected>Pilih Pembina</option>
                                @foreach($dosen as $d)
                                    <option value="{{ $d }}">{{ $d }}</option>
                                @endforeach
                            </select>
                            <div class="field-error" data-error-for="advisor_name"></div>
                        </div>
                        <div class="form-field">
                            <label>No. HP Pembina</label>
                            <input type="text" name="advisor_phone" id="advisor_phone" placeholder="08xx-xxxx-xxxx">
                        </div>
                        <div class="form-field">
                            <label>Email Pembina</label>
                            <input type="email" name="advisor_email" id="advisor_email" placeholder="nama@unklab.ac.id">
                            <div class="field-error" data-error-for="advisor_email"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 3: Akun & Password --}}
            <div class="org-tab-content" id="tab-account" style="display: none; padding: 24px 28px;">
                <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <p style="margin: 0; font-size: 13px; color: #92400e; line-height: 1.5;">Akun ini digunakan oleh pengurus untuk login ke sistem. Jika password dikosongkan, sistem akan membuat password otomatis saat organisasi pertama kali dibuat.</p>
                </div>
                <div class="form-grid">
                    <div class="form-field full">
                        <label for="account_email">Email Organisasi <span style="color: #ef4444;">*</span></label>
                        <input id="account_email" name="account_email" type="email" placeholder="akun@organisasi.example" required>
                        <div class="field-error" data-error-for="account_email"></div>
                    </div>
                    <div class="form-field full">
                        <label for="account_password">Password</label>
                        <input id="account_password" name="account_password" type="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                        <div class="field-error" data-error-for="account_password"></div>
                    </div>
                </div>
            </div>
        </form>

        <div class="modal-footer" style="padding: 16px 28px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 12px;">
            <button type="button" class="btn-secondary" data-close-modal>Tutup</button>
            <button type="button" class="btn-primary" id="saveOrganizationBtn" style="background: linear-gradient(135deg, #6f3ba7, #a476d1); border: none; color: #fff; padding: 10px 28px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; transition: all 0.2s;">Simpan Organisasi</button>
        </div>
    </div>
</div>

<div class="org-modal" id="organizationDetailModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="organizationDetailTitle">
    <div class="org-modal-panel">
        <div class="org-modal-header">
            <div>
                <h2 id="organizationDetailTitle">Detail Organisasi</h2>
                <p id="organizationDetailSubtitle">Informasi organisasi</p>
            </div>
            <button type="button" class="close-btn" data-close-detail aria-label="Tutup">&times;</button>
        </div>
        <div class="org-modal-body" id="organizationDetailBody"></div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" data-close-detail>Tutup</button>
        </div>
    </div>
</div>

<div class="org-modal" id="confirmStatusModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="confirmStatusTitle">
    <div class="org-modal-panel" style="max-width: 460px;">
        <div style="padding: 32px 28px; text-align: center;">
            <div id="confirmStatusIconWrap" style="width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <svg id="confirmStatusIcon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></svg>
            </div>
            <h2 id="confirmStatusTitle" style="font-size: 20px; font-weight: 700; color: #1e1b4b; margin: 0 0 8px;">Konfirmasi</h2>
            <p id="confirmStatusMessage" style="font-size: 14px; color: #64748b; line-height: 1.6; margin: 0 0 20px;"></p>

            {{-- Email notification info (shown only for deactivation) --}}
            <div id="confirmEmailInfo" style="display: none; text-align: left; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6f3ba7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    <span style="font-size: 13px; font-weight: 700; color: #1e1b4b;">Email notifikasi akan dikirim ke:</span>
                </div>
                <div id="confirmEmailList" style="display: flex; flex-direction: column; gap: 8px;"></div>
            </div>

            <div style="display: flex; gap: 12px; justify-content: center;">
                <button type="button" class="btn-secondary" data-close-confirm style="flex: 1; padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; border: 1px solid #e5e7eb; background: #fff; color: #475569; transition: all .15s;">Batal</button>
                <button type="button" class="btn-primary" id="confirmStatusBtn" style="flex: 1; padding: 12px; border-radius: 10px; font-weight: 600; cursor: pointer; border: none; color: #fff; transition: all .15s;">Lanjutkan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const modal = document.getElementById('organizationModal');
        const backdrop = document.getElementById('organizationBackdrop');
        const detailModal = document.getElementById('organizationDetailModal');
        const openButton = document.getElementById('openOrganizationModal');
        const emptyButton = document.getElementById('openOrganizationModalEmpty');
        const form = document.getElementById('organizationForm');
        const saveBtn = document.getElementById('saveOrganizationBtn');
        const title = document.getElementById('organizationModalTitle');
        const idInput = document.getElementById('org_id');
        let editingId = null;

        if (!modal || !backdrop || !form || !saveBtn) {
            return;
        }

        const fields = ['name', 'abbreviation', 'kategori', 'level', 'status', 'description', 'account_email', 'account_password',
            'ketua_name', 'secretary_name', 'treasurer_name', 'advisor_name',
            'chair_phone', 'chair_email', 'secretary_phone', 'secretary_email',
            'treasurer_phone', 'treasurer_email', 'advisor_phone', 'advisor_email'];

        const statusFieldWrapper = document.getElementById('statusFieldWrapper');
        const tabsBtn = modal.querySelectorAll('.org-tab');
        const tabContentsList = modal.querySelectorAll('.org-tab-content');

        tabsBtn.forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabsBtn.forEach(function (t) { t.classList.remove('active'); });
                tabContentsList.forEach(function (c) { 
                    c.classList.remove('active'); 
                    c.style.display = 'none';
                });
                tab.classList.add('active');
                const targetId = 'tab-' + tab.getAttribute('data-tab');
                const targetContent = document.getElementById(targetId);
                if (targetContent) {
                    targetContent.classList.add('active');
                    targetContent.style.display = 'block';
                }
            });
        });

        function resetTabsToInfo() {
            if (tabsBtn.length > 0) {
                tabsBtn[0].click();
            }
        }

        function openDialog(element, backdropElement) {
            element.classList.add('open');
            backdropElement.classList.add('open');
            element.setAttribute('aria-hidden', 'false');
            backdropElement.setAttribute('aria-hidden', 'false');
        }

        function closeDialog(element, backdropElement) {
            element.classList.remove('open');
            backdropElement.classList.remove('open');
            element.setAttribute('aria-hidden', 'true');
            backdropElement.setAttribute('aria-hidden', 'true');
        }

        let currentOrgStatus = '';

        const confirmStatusModal = document.getElementById('confirmStatusModal');
        const confirmStatusBtn = document.getElementById('confirmStatusBtn');
        const confirmStatusMessage = document.getElementById('confirmStatusMessage');
        const confirmStatusTitle = document.getElementById('confirmStatusTitle');
        const confirmStatusIconWrap = document.getElementById('confirmStatusIconWrap');
        const confirmStatusIcon = document.getElementById('confirmStatusIcon');
        const confirmEmailInfo = document.getElementById('confirmEmailInfo');
        const confirmEmailList = document.getElementById('confirmEmailList');
        let pendingToggleId = null;

        function clearErrors() {
            document.querySelectorAll('.field-error').forEach(function (errorNode) {
                errorNode.textContent = '';
                errorNode.style.display = 'none';
            });
        }

        function resetForm() {
            form.reset();
            idInput.value = '';
            editingId = null;
            currentOrgStatus = '';
            title.textContent = 'Tambah Organisasi';
            clearErrors();
            resetTabsToInfo();
            // Hide status for add mode
            if (statusFieldWrapper) statusFieldWrapper.style.display = 'none';
        }

        function fillForm(org) {
            fields.forEach(function (fieldName) {
                const input = form.querySelector('[name="' + fieldName + '"]');
                if (!input) return;
                input.value = org[fieldName] ?? '';
            });
            idInput.value = org.id ?? '';
            editingId = org.id ?? null;
            currentOrgStatus = org.status || '';
            title.textContent = 'Edit Organisasi';
            clearErrors();
            resetTabsToInfo();
            // Show status for edit mode
            if (statusFieldWrapper) statusFieldWrapper.style.display = '';
        }

        function openConfirmStatusModal(orgId, orgName, isDeactivating, advisorEmail, accountEmail, advisorName) {
            pendingToggleId = orgId;
            
            if (isDeactivating) {
                confirmStatusTitle.textContent = 'Nonaktifkan Organisasi?';
                confirmStatusMessage.innerHTML = 'Apakah Anda yakin ingin menonaktifkan <strong>' + orgName + '</strong>?';
                confirmStatusIconWrap.style.background = '#fee2e2';
                confirmStatusIcon.innerHTML = '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line>';
                confirmStatusIcon.setAttribute('stroke', '#ef4444');
                confirmStatusBtn.textContent = 'Ya, Nonaktifkan';
                confirmStatusBtn.style.background = '#ef4444';

                // Show email notification targets
                confirmEmailInfo.style.display = 'block';
                let emailHtml = '';
                if (advisorEmail) {
                    emailHtml += '<div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;">' +
                        '<div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#059669,#34d399);display:grid;place-items:center;color:#fff;font-size:11px;font-weight:800;flex-shrink:0;">P</div>' +
                        '<div><div style="font-size:12px;font-weight:600;color:#1e1b4b;">Pembina' + (advisorName ? ' - ' + advisorName : '') + '</div><div style="font-size:12px;color:#6f3ba7;">' + advisorEmail + '</div></div></div>';
                }
                if (accountEmail) {
                    emailHtml += '<div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;">' +
                        '<div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#6f3ba7,#a476d1);display:grid;place-items:center;color:#fff;font-size:11px;font-weight:800;flex-shrink:0;">O</div>' +
                        '<div><div style="font-size:12px;font-weight:600;color:#1e1b4b;">Akun Organisasi</div><div style="font-size:12px;color:#6f3ba7;">' + accountEmail + '</div></div></div>';
                }
                if (!advisorEmail && !accountEmail) {
                    emailHtml = '<div style="font-size:13px;color:#94a3b8;text-align:center;padding:8px 0;">Tidak ada email terdaftar</div>';
                }
                confirmEmailList.innerHTML = emailHtml;
            } else {
                confirmStatusTitle.textContent = 'Aktifkan Organisasi?';
                confirmStatusMessage.innerHTML = 'Apakah Anda yakin ingin mengaktifkan kembali <strong>' + orgName + '</strong>?';
                confirmStatusIconWrap.style.background = '#dcfce7';
                confirmStatusIcon.innerHTML = '<polyline points="20 6 9 17 4 12"></polyline>';
                confirmStatusIcon.setAttribute('stroke', '#22c55e');
                confirmStatusBtn.textContent = 'Ya, Aktifkan';
                confirmStatusBtn.style.background = '#22c55e';
                confirmEmailInfo.style.display = 'none';
            }
            
            openDialog(confirmStatusModal, backdrop);
        }

        if (confirmStatusModal) {
            confirmStatusModal.querySelectorAll('[data-close-confirm]').forEach(function (button) {
                button.addEventListener('click', function() {
                    closeDialog(confirmStatusModal, backdrop);
                    pendingToggleId = null;
                });
            });

            confirmStatusBtn.addEventListener('click', function() {
                if (pendingToggleId) {
                    closeDialog(confirmStatusModal, backdrop);
                    executeToggleStatus(pendingToggleId);
                    pendingToggleId = null;
                }
            });
        }

        function detailRow(label, value) {
            return '<div class="detail-item"><span>' + label + '</span><strong>' + (value || '-') + '</strong></div>';
        }

        function renderDetail(org) {
            const body = document.getElementById('organizationDetailBody');
            const subtitle = document.getElementById('organizationDetailSubtitle');
            if (!body || !subtitle) {
                return;
            }

            subtitle.textContent = org.name || 'Informasi organisasi';

            let html = '<div class="detail-grid">';
            html += detailRow('Nama Organisasi', org.name);
            html += detailRow('Singkatan', org.abbreviation);
            html += detailRow('Kategori', org.kategori);
            html += detailRow('Level', org.level);
            html += detailRow('Status', org.status === 'Aktif' || org.status === 'active' ? 'Aktif' : 'Non-Aktif');
            html += detailRow('Email', org.account_email);
            html += detailRow('Deskripsi', org.description);
            html += '</div>';

            // Struktur Pengurus section
            html += '<h3 style="font-size: 16px; font-weight: 700; color: #1e1b4b; margin: 24px 0 12px; border-top: 1px solid #e5e7eb; padding-top: 20px;">Struktur Pengurus</h3>';
            html += '<div class="detail-grid">';
            html += detailRow('Ketua', org.ketua_name || '-');
            html += detailRow('HP Ketua', org.chair_phone);
            html += detailRow('Email Ketua', org.chair_email);
            html += detailRow('Sekretaris', org.secretary_name);
            html += detailRow('HP Sekretaris', org.secretary_phone);
            html += detailRow('Email Sekretaris', org.secretary_email);
            html += detailRow('Bendahara', org.treasurer_name);
            html += detailRow('HP Bendahara', org.treasurer_phone);
            html += detailRow('Email Bendahara', org.treasurer_email);
            html += detailRow('Pembina', org.advisor_name || '-');
            html += detailRow('HP Pembina', org.advisor_phone);
            html += detailRow('Email Pembina', org.advisor_email);
            html += '</div>';

            // Kontak & Media Sosial section
            if (org.whatsapp || org.instagram || org.website) {
                html += '<h3 style="font-size: 16px; font-weight: 700; color: #1e1b4b; margin: 24px 0 12px; border-top: 1px solid #e5e7eb; padding-top: 20px;">Kontak & Media Sosial</h3>';
                html += '<div class="detail-grid">';
                html += detailRow('WhatsApp', org.whatsapp);
                html += detailRow('Instagram', org.instagram);
                html += detailRow('Website', org.website);
                html += '</div>';
            }

            body.innerHTML = html;
        }

        function showDetailModal() {
            openDialog(detailModal, backdrop);
        }

        function hideDetailModal() {
            closeDialog(detailModal, backdrop);
        }

        function showFormModal() {
            openDialog(modal, backdrop);
            const firstField = modal.querySelector('input, select, textarea, button');
            if (firstField) {
                firstField.focus();
            }
        }

        function hideFormModal() {
            closeDialog(modal, backdrop);
            resetForm();
        }

        async function submitOrganization(customEmailData = null) {
            clearErrors();
            
            const statusInput = form.querySelector('[name="status"]');
            let isDeactivating = false;
            
            if (!customEmailData && editingId && statusInput && statusInput.value === 'Nonaktif' && currentOrgStatus !== 'Nonaktif') {
                const orgName = form.querySelector('[name="name"]')?.value || 'Organisasi';
                const defaultEmail = form.querySelector('[name="account_email"]')?.value || '';
                
                openEmailModal(orgName, defaultEmail, function(data) {
                    submitOrganization(data);
                });
                return; // Stop and wait for modal submission
            }
            
            if (editingId && statusInput && statusInput.value === 'Nonaktif' && currentOrgStatus !== 'Nonaktif') {
                isDeactivating = true;
            }

            saveBtn.disabled = true;
            saveBtn.textContent = 'Menyimpan...';

            try {
                const formData = new FormData(form);
                const url = editingId ? '/kemahasiswaan/organisasi/' + editingId : '{{ route('kemahasiswaan.organizations.store') }}';
                if (editingId) {
                    formData.append('_method', 'PUT');
                }
                
                if (customEmailData) {
                    formData.append('custom_email_to', customEmailData.custom_email_to);
                    formData.append('custom_email_subject', customEmailData.custom_email_subject);
                    formData.append('custom_email_message', customEmailData.custom_email_message);
                }

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData,
                });

                if (response.ok) {
                    if (isDeactivating) {
                        alert('Organisasi telah berhasil dinonaktifkan dan email pemberitahuan telah terkirim.');
                    }
                    location.reload();
                    return;
                }

                if (response.status === 422) {
                    const payload = await response.json();
                    const errors = payload.errors || {};
                    Object.keys(errors).forEach(function (fieldName) {
                        const errorNode = document.querySelector('[data-error-for="' + fieldName + '"]');
                        if (errorNode) {
                            errorNode.textContent = errors[fieldName][0] || 'Input tidak valid';
                            errorNode.style.display = 'block';
                        }
                    });
                    return;
                }

                const text = await response.text();
                alert('Terjadi kesalahan: ' + response.status + '\n' + text);
            } catch (error) {
                console.error(error);
                alert('Gagal menyimpan organisasi.');
            } finally {
                saveBtn.disabled = false;
                saveBtn.textContent = 'Simpan Organisasi';
            }
        }

        async function fetchOrganization(id) {
            const response = await fetch('/kemahasiswaan/organisasi/' + id, {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) {
                throw new Error('Gagal memuat data organisasi');
            }

            const payload = await response.json();
            return payload.organization || payload;
        }

        async function handleView(id) {
            try {
                const org = await fetchOrganization(id);
                renderDetail(org);
                showDetailModal();
            } catch (error) {
                console.error(error);
                alert('Gagal memuat detail organisasi.');
            }
        }

        async function handleEdit(id) {
            try {
                const org = await fetchOrganization(id);
                fillForm(org);
                showFormModal();
            } catch (error) {
                console.error(error);
                alert('Gagal memuat data organisasi untuk edit.');
            }
        }

        async function handleDelete(id) {
            if (!confirm('Hapus organisasi ini?')) {
                return;
            }

            try {
                const response = await fetch('/kemahasiswaan/organisasi/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    location.reload();
                    return;
                }

                const text = await response.text();
                alert('Gagal menghapus organisasi: ' + text);
            } catch (error) {
                console.error(error);
                alert('Gagal menghapus organisasi.');
            }
        }

        function handleToggleStatus(id) {
            const row = document.querySelector('tr[data-id="' + id + '"]');
            const currentStatus = row.getAttribute('data-status') || 'Aktif';
            const orgName = row.querySelector('.org-name')?.textContent || 'Organisasi';
            const isDeactivating = currentStatus === 'Aktif';
            const advisorEmail = row.getAttribute('data-advisor-email') || '';
            const accountEmail = row.getAttribute('data-account-email') || '';
            const advisorName = row.getAttribute('data-advisor-name') || '';
            openConfirmStatusModal(id, orgName, isDeactivating, advisorEmail, accountEmail, advisorName);
        }

        async function executeToggleStatus(id) {
            try {
                const response = await fetch('/kemahasiswaan/organisasi/' + id + '/toggle-status', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                });

                if (response.ok) {
                    location.reload();
                    return;
                }

                const text = await response.text();
                alert('Gagal mengubah status: ' + text);
            } catch (error) {
                console.error(error);
                alert('Gagal mengubah status organisasi.');
            }
        }

        if (openButton) {
            openButton.addEventListener('click', function () {
                resetForm();
                showFormModal();
            });
        }

        if (emptyButton) {
            emptyButton.addEventListener('click', function () {
                resetForm();
                showFormModal();
            });
        }

        saveBtn.addEventListener('click', submitOrganization);

        modal.querySelectorAll('[data-close-modal]').forEach(function (button) {
            button.addEventListener('click', hideFormModal);
        });

        detailModal.querySelectorAll('[data-close-detail]').forEach(function (button) {
            button.addEventListener('click', hideDetailModal);
        });

        backdrop.addEventListener('click', function () {
            if (modal.classList.contains('open')) {
                hideFormModal();
            }
            if (detailModal.classList.contains('open')) {
                hideDetailModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                hideFormModal();
                hideDetailModal();
            }
        });

        document.querySelectorAll('[data-view]').forEach(function (button) {
            button.addEventListener('click', function () {
                handleView(button.getAttribute('data-id'));
            });
        });

        document.querySelectorAll('[data-edit]').forEach(function (button) {
            button.addEventListener('click', function () {
                handleEdit(button.getAttribute('data-id'));
            });
        });

        document.querySelectorAll('[data-delete]').forEach(function (button) {
            button.addEventListener('click', function () {
                handleDelete(button.getAttribute('data-id'));
            });
        });

        document.querySelectorAll('[data-toggle-status]').forEach(function (button) {
            button.addEventListener('click', function () {
                handleToggleStatus(button.getAttribute('data-id'));
            });
        });
    });
</script>
@endpush
