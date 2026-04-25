@extends('layouts.portal.kemahasiswaan')

@section('title', 'Kontak Pengurus UKM - Kemahasiswaan')
@section('page_title', 'Kontak Pengurus UKM')
@section('page_subtitle', 'Daftar kontak pengurus organisasi untuk koordinasi kegiatan dan administrasi')
@section('page_class', 'kmh-page-kontak')

@php
    $kontakPengurus = $kontakPengurus ?? [];
    $contactSummary = $contactSummary ?? [
        'total_kontak' => 0,
        'dengan_email' => 0,
        'dengan_kontak' => 0,
        'total_organisasi' => 0,
    ];

    $organizationGroups = collect($kontakPengurus)
        ->groupBy(fn (array $item) => trim((string) ($item['organisasi'] ?? '-')) !== ''
            ? (string) ($item['organisasi'] ?? '-')
            : '-')
        ->map(function ($items, $organizationName) {
            $organization = (string) $organizationName;
            $isBem = \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($organization), 'bem');

            $facultyKeywords = [
                'filsafat',
                'teknik',
                'keguruan',
                'ekonomi',
                'keperawatan',
                'hukum',
                'fakultas',
            ];

            $scope = \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($organization), $facultyKeywords)
                ? 'Fakultas'
                : 'Universitas';

            return [
                'name' => $organization,
                'type' => $isBem ? 'BEM' : 'UKM',
                'scope' => $scope,
                'members' => collect($items)
                    ->map(function (array $member) {
                        $searchBlob = \Illuminate\Support\Str::lower(implode(' ', [
                            (string) ($member['organisasi'] ?? ''),
                            (string) ($member['nama'] ?? ''),
                            (string) ($member['jabatan'] ?? ''),
                            (string) ($member['email'] ?? ''),
                            (string) ($member['kontak'] ?? ''),
                        ]));

                        return [
                            'nama' => (string) ($member['nama'] ?? '-'),
                            'jabatan' => (string) ($member['jabatan'] ?? '-'),
                            'email' => (string) ($member['email'] ?? '-'),
                            'kontak' => (string) ($member['kontak'] ?? '-'),
                            'status_code' => (string) ($member['status_code'] ?? 'inactive'),
                            'status_label' => (string) ($member['status_label'] ?? 'Nonaktif'),
                            'search_blob' => $searchBlob,
                        ];
                    })
                    ->values()
                    ->all(),
                'search_blob' => \Illuminate\Support\Str::lower($organization . ' ' . implode(' ', collect($items)->map(function (array $m) {
                    return implode(' ', [
                        (string) ($m['nama'] ?? ''),
                        (string) ($m['jabatan'] ?? ''),
                        (string) ($m['email'] ?? ''),
                        (string) ($m['kontak'] ?? ''),
                    ]);
                })->all())),
            ];
        })
        ->sortBy('name')
        ->values();

    $bemCount = $organizationGroups->where('type', 'BEM')->count();
    $ukmCount = $organizationGroups->where('type', 'UKM')->count();
@endphp

@section('content')
<div class="kmh-page kmh-kontak-page">
    <section class="kmh-card kmh-contact-hero-card">
        <div class="kmh-card-body">
            <div class="kmh-contact-hero">
                <span class="kmh-contact-hero-icon"><i class="bi bi-people"></i></span>
                <div>
                    <h2>Kontak Pengurus UKM</h2>
                    <p>Daftar kontak pengurus organisasi mahasiswa Universitas Klabat</p>
                </div>
            </div>
        </div>
    </section>

    <section class="kmh-card kmh-contact-search-card">
        <div class="kmh-card-body">
            <label class="kmh-contact-search-wrap" for="kmh-kontak-search">
                <i class="bi bi-search" aria-hidden="true"></i>
                <input
                    id="kmh-kontak-search"
                    type="search"
                    placeholder="Cari organisasi berdasarkan nama, kategori, atau bidang..."
                    aria-label="Cari organisasi berdasarkan nama, kategori, atau bidang"
                >
            </label>
        </div>
    </section>

    <section class="kmh-contact-mini-stats" aria-label="Ringkasan organisasi">
        <article class="kmh-contact-mini-card">
            <div>
                <p>Total Organisasi</p>
                <strong>{{ $organizationGroups->count() }}</strong>
            </div>
            <span><i class="bi bi-people"></i></span>
        </article>
        <article class="kmh-contact-mini-card">
            <div>
                <p>BEM</p>
                <strong>{{ $bemCount }}</strong>
            </div>
            <span><i class="bi bi-people"></i></span>
        </article>
        <article class="kmh-contact-mini-card">
            <div>
                <p>UKM</p>
                <strong>{{ $ukmCount }}</strong>
            </div>
            <span><i class="bi bi-people"></i></span>
        </article>
    </section>

    <section class="kmh-contact-org-grid" aria-label="Daftar kontak organisasi">
        @forelse($organizationGroups as $org)
            <article class="kmh-contact-org-card" data-kmh-org-card data-kmh-contact-search="{{ $org['search_blob'] }}">
                <header class="kmh-contact-org-head">
                    <div>
                        <h3>{{ $org['name'] }}</h3>
                        <div class="kmh-contact-org-tags">
                            <span class="kmh-contact-tag">{{ $org['type'] }}</span>
                            <span class="kmh-contact-tag is-muted">{{ $org['scope'] }}</span>
                        </div>
                    </div>
                    <span class="kmh-contact-org-head-icon" aria-hidden="true"><i class="bi bi-people"></i></span>
                </header>

                <div class="kmh-contact-members">
                    @foreach($org['members'] as $member)
                        <article class="kmh-contact-member-card">
                            <p class="kmh-contact-member-role"><i class="bi bi-person"></i> {{ $member['jabatan'] }}</p>
                            <h4>{{ $member['nama'] }}</h4>

                            <p>
                                <i class="bi bi-telephone"></i>
                                <span>{{ !empty($member['kontak']) && $member['kontak'] !== '-' ? $member['kontak'] : 'Kontak belum tersedia' }}</span>
                            </p>

                            <p>
                                <i class="bi bi-envelope"></i>
                                @if(!empty($member['email']) && $member['email'] !== '-')
                                    <a href="mailto:{{ $member['email'] }}">{{ $member['email'] }}</a>
                                @else
                                    <span>Email belum tersedia</span>
                                @endif
                            </p>
                        </article>
                    @endforeach
                </div>
            </article>
        @empty
            <article class="kmh-card">
                <div class="kmh-card-body">
                    <p class="kmh-empty-row mb-0">Belum ada data kontak pengurus UKM.</p>
                </div>
            </article>
        @endforelse
    </section>

    @if($organizationGroups->count() > 0)
        <section class="kmh-card d-none" data-kmh-contact-empty-state>
            <div class="kmh-card-body">
                <p class="kmh-empty-row mb-0">Tidak ada organisasi yang cocok dengan kata kunci pencarian.</p>
            </div>
        </section>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    const searchInput = document.getElementById('kmh-kontak-search');
    const cards = Array.from(document.querySelectorAll('[data-kmh-org-card]'));
    const emptyState = document.querySelector('[data-kmh-contact-empty-state]');

    if (!searchInput || !cards.length) {
        return;
    }

    searchInput.addEventListener('input', function () {
        const keyword = (searchInput.value || '').toLowerCase().trim();
        let visibleCount = 0;

        cards.forEach(function (card) {
            const blob = (card.getAttribute('data-kmh-contact-search') || '').toLowerCase();
            const matched = keyword === '' || blob.indexOf(keyword) !== -1;
            card.classList.toggle('d-none', !matched);

            if (matched) {
                visibleCount += 1;
            }
        });

        if (emptyState) {
            emptyState.classList.toggle('d-none', visibleCount !== 0);
        }
    });
})();
</script>
@endpush
