@extends('layouts.app')

@section('title', $title ?? 'Kontak Organisasi')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/contacts.css') }}">
@endpush

@section('content')
<div class="contacts-page">
    <div class="contacts-header-card">
        <div class="contacts-header-icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <div class="contacts-header-title">
            <h1>{{ $title ?? 'Kontak Pengurus UKM' }}</h1>
            <p>Daftar kontak pengurus organisasi mahasiswa Universitas Klabat</p>
        </div>
    </div>

    <form method="GET" action="" class="contacts-search">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input type="text" name="q" placeholder="Cari organisasi berdasarkan nama, kategori, atau bidang..." value="{{ $q ?? '' }}" onchange="this.form.submit()">
    </form>

    <div class="contacts-stats">
        <div class="contacts-stat-card">
            <div class="contacts-stat-info">
                <p>Total Organisasi</p>
                <h3>{{ $totalOrgs ?? 0 }}</h3>
            </div>
            <div class="contacts-stat-icon" style="background: #e0e7ff; color: #4338ca;">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
        </div>
        <div class="contacts-stat-card">
            <div class="contacts-stat-info">
                <p>BEM</p>
                <h3>{{ $countBEM ?? 0 }}</h3>
            </div>
            <div class="contacts-stat-icon" style="background: #fef3c7; color: #b45309;">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
        </div>
        <div class="contacts-stat-card">
            <div class="contacts-stat-info">
                <p>UKM</p>
                <h3>{{ $countUKM ?? 0 }}</h3>
            </div>
            <div class="contacts-stat-icon" style="background: #fef3c7; color: #b45309;">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
        </div>
    </div>

    <div class="contacts-grid">
        @forelse($organizations ?? [] as $org)
            <div class="contact-card">
                <div class="contact-card-head">
                    <div class="contact-card-title">
                        <h3>{{ $org->name }}</h3>
                        <div class="contact-card-badges">
                            @if($org->kategori)
                                <span class="contact-badge">{{ $org->kategori }}</span>
                            @endif
                            @if($org->level)
                                <span class="contact-badge">{{ $org->level }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="contact-card-icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="contact-card-body">
                    <!-- Ketua -->
                    <div class="contact-person">
                        <div class="contact-person-role">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Ketua
                        </div>
                        <h4 class="contact-person-name">{{ $org->ketua_name ?: 'Belum diatur' }}</h4>
                        @if($org->chair_phone)
                        <div class="contact-person-detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            {{ $org->chair_phone }}
                        </div>
                        @endif
                        @if($org->chair_email)
                        <div class="contact-person-detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                            {{ $org->chair_email }}
                        </div>
                        @endif
                    </div>

                    <!-- Sekretaris -->
                    <div class="contact-person">
                        <div class="contact-person-role">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Sekretaris
                        </div>
                        <h4 class="contact-person-name">{{ $org->secretary_name ?: 'Belum diatur' }}</h4>
                        @if($org->secretary_phone)
                        <div class="contact-person-detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            {{ $org->secretary_phone }}
                        </div>
                        @endif
                        @if($org->secretary_email)
                        <div class="contact-person-detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                            {{ $org->secretary_email }}
                        </div>
                        @endif
                    </div>

                    <!-- Bendahara -->
                    <div class="contact-person treasurer">
                        <div class="contact-person-role">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Bendahara
                        </div>
                        <h4 class="contact-person-name">{{ $org->treasurer_name ?: 'Belum diatur' }}</h4>
                        @if($org->treasurer_phone)
                        <div class="contact-person-detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            {{ $org->treasurer_phone }}
                        </div>
                        @endif
                        @if($org->treasurer_email)
                        <div class="contact-person-detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                            {{ $org->treasurer_email }}
                        </div>
                        @endif
                    </div>

                    <!-- Pembina / Advisor -->
                    @if($org->advisor_name)
                    <div class="contact-person" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                        <div class="contact-person-role" style="color: #059669;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Pembina
                        </div>
                        <h4 class="contact-person-name">{{ $org->advisor_name }}</h4>
                        @if($org->advisor_phone)
                        <div class="contact-person-detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            {{ $org->advisor_phone }}
                        </div>
                        @endif
                        @if($org->advisor_email)
                        <div class="contact-person-detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                            {{ $org->advisor_email }}
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Sosial Media -->
                    @if($org->instagram || $org->whatsapp || $org->website)
                    <div class="contact-person" style="background: #f5f3ff; border: 1px solid #e9d5ff; border-top: 2px solid #7c3aed;">
                        <div class="contact-person-role" style="color: #7c3aed;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            Media Sosial
                        </div>
                        @if($org->instagram)
                        <div class="contact-person-detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                            <a href="{{ str_starts_with($org->instagram, 'http') ? $org->instagram : 'https://' . $org->instagram }}" target="_blank" style="color: #4338ca; text-decoration: none;">{{ $org->instagram }}</a>
                        </div>
                        @endif
                        @if($org->whatsapp)
                        <div class="contact-person-detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                            {{ $org->whatsapp }}
                        </div>
                        @endif
                        @if($org->website)
                        <div class="contact-person-detail">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                            <a href="{{ str_starts_with($org->website, 'http') ? $org->website : 'https://' . $org->website }}" target="_blank" style="color: #4338ca; text-decoration: none;">{{ $org->website }}</a>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: white; border: 1px solid #e5e7eb; border-radius: 8px;">
                <p style="color: #6b7280;">Tidak ada organisasi yang ditemukan.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
