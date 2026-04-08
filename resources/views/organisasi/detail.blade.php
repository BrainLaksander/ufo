@php
    $orgs = require base_path('resources/data/organizationData.php');
    $org = null;
    if(isset($id)){
        foreach($orgs as $o) if($o['id'] == $id) { $org = $o; break; }
    }
    if(!$org) abort(404);
@endphp

@extends('layouts.mahasiswa')

@section('title', $org['name'])

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm overflow-hidden">
        <img src="{{ $org['banner'] }}" alt="{{ $org['name'] }} banner" style="width: 100%; height: 220px; object-fit: cover;">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                <h1 class="h3 fw-bold mb-0">{{ $org['logo'] }} {{ $org['name'] }}</h1>
                <span class="text-muted small">Anggota aktif: {{ $org['activeMembers'] }}</span>
            </div>

            <p class="text-secondary mb-4">{{ $org['tagline'] }}</p>

            <div class="row g-4">
                <div class="col-lg-8">
                    <h2 class="h6 fw-bold">Visi</h2>
                    <p class="small text-secondary">{{ $org['visi'] }}</p>

                    <h2 class="h6 fw-bold mt-4">Misi</h2>
                    <ul class="small text-secondary ps-3">
                        @foreach($org['misi'] as $m)
                            <li>{{ $m }}</li>
                        @endforeach
                    </ul>

                    <h2 class="h6 fw-bold mt-4">Budaya Organisasi</h2>
                    <p class="small text-secondary">{{ $org['culture'] }}</p>

                    <h2 class="h6 fw-bold mt-4">Program Unggulan</h2>
                    @forelse($org['programs'] as $p)
                        <div class="border rounded-3 p-3 mb-2">
                            <div class="fw-semibold">{{ $p['title'] }} <span class="small text-muted">({{ $p['period'] ?? '' }})</span></div>
                            <div class="small text-secondary">{{ $p['goal'] }}</div>
                        </div>
                    @empty
                        <div class="small text-muted">Tidak ada program terdaftar.</div>
                    @endforelse
                </div>

                <aside class="col-lg-4">
                    <h2 class="h6 fw-bold">Struktur</h2>
                    <ul class="small text-secondary ps-3">
                        @foreach($org['structure'] as $s)
                            <li>{{ $s['position'] }} - {{ $s['name'] }}</li>
                        @endforeach
                    </ul>

                    <h2 class="h6 fw-bold mt-4">Kontak</h2>
                    <div class="small text-secondary">
                        @if(!empty($org['socialMedia']['instagram']))<div>Instagram: <a href="{{ $org['socialMedia']['instagram'] }}">@{{ parse_url($org['socialMedia']['instagram'], PHP_URL_PATH) }}</a></div>@endif
                        @if(!empty($org['socialMedia']['email']))<div>Email: <a href="mailto:{{ $org['socialMedia']['email'] }}">{{ $org['socialMedia']['email'] }}</a></div>@endif
                        @if(!empty($org['socialMedia']['whatsapp']))<div>WhatsApp: <a href="{{ $org['socialMedia']['whatsapp'] }}">Chat</a></div>@endif
                    </div>

                    <h2 class="h6 fw-bold mt-4">Acara Terbaru</h2>
                    @forelse($org['events'] as $e)
                        <div class="border rounded-3 p-2 mb-2">
                            <div class="fw-semibold small">{{ $e['title'] }}</div>
                            <div class="small text-muted">{{ $e['date'] }}</div>
                            @if(!empty($e['images']))
                                <div class="row g-2 mt-1">
                                    @foreach($e['images'] as $img)
                                        <div class="col-4">
                                            <img src="{{ $img }}" class="w-100 rounded org-event-image" style="height: 72px; object-fit: cover;" alt="event image">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @if(!empty($e['description']))
                                <div class="small text-secondary mt-2">
                                    <div id="desc-{{ $e['id'] }}" class="d-none">{{ $e['description'] }}</div>
                                    <button data-target="#desc-{{ $e['id'] }}" class="org-show-more btn btn-link btn-sm p-0">Tampilkan lebih</button>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="small text-muted">Tidak ada acara.</div>
                    @endforelse
                </aside>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('organisasi.index') }}" class="btn btn-light border">Kembali ke daftar organisasi</a>
    </div>
</div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/mahasiswa/organisasi-detail.js') }}"></script>
@endpush
