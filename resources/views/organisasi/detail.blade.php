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
<div class="max-w-4xl mx-auto p-4">
    <div class="rounded-lg overflow-hidden shadow">
        <img src="{{ $org['banner'] }}" alt="{{ $org['name'] }} banner" class="w-full h-56 object-cover">
        <div class="p-6 bg-white">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ $org['logo'] }} {{ $org['name'] }}</h1>
                <div class="text-sm text-gray-600">Anggota aktif: {{ $org['activeMembers'] }}</div>
            </div>

            <p class="mt-3 text-gray-700">{{ $org['tagline'] }}</p>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="col-span-2">
                    <h3 class="font-semibold">Visi</h3>
                    <p class="text-sm text-gray-700">{{ $org['visi'] }}</p>

                    <h3 class="mt-4 font-semibold">Misi</h3>
                    <ul class="list-disc ml-5 text-sm text-gray-700">
                        @foreach($org['misi'] as $m)
                            <li>{{ $m }}</li>
                        @endforeach
                    </ul>

                    <h3 class="mt-4 font-semibold">Budaya Organisasi</h3>
                    <p class="text-sm text-gray-700">{{ $org['culture'] }}</p>

                    <h3 class="mt-4 font-semibold">Program Unggulan</h3>
                    @forelse($org['programs'] as $p)
                        <div class="mt-2 p-3 border rounded">
                            <div class="font-medium">{{ $p['title'] }} <span class="text-xs text-gray-500">({{ $p['period'] ?? '' }})</span></div>
                            <div class="text-sm text-gray-600">{{ $p['goal'] }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500">Tidak ada program terdaftar.</div>
                    @endforelse
                </div>

                <aside>
                    <h3 class="font-semibold">Struktur</h3>
                    <ul class="text-sm text-gray-700">
                        @foreach($org['structure'] as $s)
                            <li>{{ $s['position'] }} — {{ $s['name'] }}</li>
                        @endforeach
                    </ul>

                    <h3 class="mt-4 font-semibold">Kontak</h3>
                    <div class="text-sm text-gray-700">
                        @if(!empty($org['socialMedia']['instagram']))<div>Instagram: <a href="{{ $org['socialMedia']['instagram'] }}" class="text-blue-600">@{{ parse_url($org['socialMedia']['instagram'], PHP_URL_PATH) }}</a></div>@endif
                        @if(!empty($org['socialMedia']['email']))<div>Email: <a href="mailto:{{ $org['socialMedia']['email'] }}" class="text-blue-600">{{ $org['socialMedia']['email'] }}</a></div>@endif
                        @if(!empty($org['socialMedia']['whatsapp']))<div>WhatsApp: <a href="{{ $org['socialMedia']['whatsapp'] }}" class="text-blue-600">Chat</a></div>@endif
                    </div>

                    <h3 class="mt-4 font-semibold">Acara Terbaru</h3>
                    @forelse($org['events'] as $e)
                        <div class="mt-2 p-2 border rounded">
                            <div class="font-medium">{{ $e['title'] }}</div>
                            <div class="text-xs text-gray-500">{{ $e['date'] }}</div>
                            @if(!empty($e['images']))
                                <div class="mt-2 grid grid-cols-3 gap-2">
                                    @foreach($e['images'] as $img)
                                        <img src="{{ $img }}" class="w-full h-20 object-cover rounded org-event-image cursor-pointer" alt="event image">
                                    @endforeach
                                </div>
                            @endif
                            @if(!empty($e['description']))
                                <div class="mt-2 text-sm text-gray-700">
                                    <div id="desc-{{ $e['id'] }}" class="hidden">{{ $e['description'] }}</div>
                                    <button data-target="#desc-{{ $e['id'] }}" class="org-show-more text-sm text-blue-600 mt-2">Tampilkan lebih</button>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-sm text-gray-500">Tidak ada acara.</div>
                    @endforelse
                </aside>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('organisasi.index') }}" class="inline-block px-4 py-2 bg-gray-100 rounded">Kembali ke daftar organisasi</a>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/organisasi-detail.js') }}"></script>
@endpush
