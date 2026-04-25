@extends('layouts.portal.pengurus')

@section('title', 'Detail Event')

@php
    $eventDate = \Carbon\Carbon::parse($event['date']);
    $eventDateTime = \Carbon\Carbon::parse($event['date'] . ' ' . $event['time']);
    $statusRaw = (string) ($event['status'] ?? 'Draft');
    $statusText = strtolower($statusRaw);
    $statusPill = 'draft';

    if (str_contains($statusText, 'open') || str_contains($statusText, 'berlangsung')) {
        $statusPill = 'approved';
    } elseif (str_contains($statusText, 'upcoming') || str_contains($statusText, 'akan datang')) {
        $statusPill = 'pending';
    } elseif (str_contains($statusText, 'selesai') || str_contains($statusText, 'closed')) {
        $statusPill = 'revision';
    }
@endphp

@section('content')
<div class="ufo-kboard-page">
    <section class="ufo-kboard-section">
        <a href="{{ route('portal.pengurus.events') }}" class="ufo-kboard-btn ghost">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Daftar Event
        </a>

        <div class="ufo-kboard-item-head mt-3">
            <div>
                <h1 class="ufo-kboard-heading mb-1">{{ $event['name'] }}</h1>
                <p class="ufo-kboard-lead">{{ $eventDate->translatedFormat('d F Y') }} • {{ $event['location'] }}</p>
            </div>
            <span class="ufo-kboard-pill {{ $statusPill }}">{{ $statusRaw }}</span>
        </div>

        <div class="ufo-kboard-row three mt-3">
            <article class="ufo-kboard-stat blue">
                <h3>{{ $eventDate->translatedFormat('d M') }}</h3>
                <p>Tanggal Event</p>
            </article>
            <article class="ufo-kboard-stat green">
                <h3>{{ $eventDateTime->format('H:i') }}</h3>
                <p>Waktu Mulai</p>
            </article>
            <article class="ufo-kboard-stat gold">
                <h3>{{ $event['quota'] }}</h3>
                <p>Kuota Peserta</p>
            </article>
        </div>
    </section>

    <div class="ufo-kboard-row two">
        <section class="ufo-kboard-section">
            <h3 class="ufo-kboard-item-title">Informasi Event</h3>
            <p class="ufo-kboard-item-text mt-2 mb-3">{{ $event['description'] }}</p>

            <div class="ufo-kboard-list">
                <article class="ufo-kboard-item">
                    <p class="ufo-kboard-item-meta mb-1"><i class="bi bi-calendar3"></i> Jadwal</p>
                    <h4 class="ufo-kboard-item-title">{{ $eventDateTime->translatedFormat('d M Y H:i') }}</h4>
                </article>

                <article class="ufo-kboard-item">
                    <p class="ufo-kboard-item-meta mb-1"><i class="bi bi-geo-alt"></i> Lokasi</p>
                    <h4 class="ufo-kboard-item-title">{{ $event['location'] }}</h4>
                </article>
            </div>

            <div class="ufo-kboard-item-head mt-4 mb-2">
                <h4 class="ufo-kboard-item-title">Daftar Peserta</h4>
                <span class="ufo-kboard-pill approved">{{ count($event['participants']) }} Peserta</span>
            </div>

            <div class="ufo-kboard-table-wrap">
                <table class="ufo-kboard-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Status Kehadiran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($event['participants'] as $participant)
                            @php
                                $participantStatus = strtolower((string) $participant['status']);
                                $participantPill = $participantStatus === 'hadir' ? 'approved' : 'pending';
                            @endphp
                            <tr>
                                <td>{{ $participant['name'] }}</td>
                                <td>{{ $participant['nim'] }}</td>
                                <td>
                                    <span class="ufo-kboard-pill {{ $participantPill }}">{{ $participant['status'] }}</span>
                                </td>
                                <td>
                                    <div class="ufo-kboard-item-actions mt-0">
                                        <button class="ufo-kboard-btn success" type="button">Hadir</button>
                                        <button class="ufo-kboard-btn danger" type="button">Tidak</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Belum ada peserta terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="ufo-kboard-item-actions mt-3">
                <button class="ufo-kboard-btn primary" type="button">
                    <i class="bi bi-download"></i>
                    Export Peserta (CSV)
                </button>
            </div>
        </section>

        <section class="ufo-kboard-section">
            <h3 class="ufo-kboard-item-title">Aksi Event</h3>
            <p class="ufo-kboard-item-meta">Kelola status event dan tindak lanjut kegiatan.</p>

            <div class="ufo-kboard-list mt-3">
                <article class="ufo-kboard-item">
                    <a href="{{ route('portal.pengurus.events.create') }}" class="ufo-kboard-btn primary w-100 justify-content-center">
                        <i class="bi bi-pencil-square"></i>
                        Edit Event
                    </a>
                </article>

                <article class="ufo-kboard-item">
                    <button class="ufo-kboard-btn gold w-100 justify-content-center" type="button">
                        <i class="bi bi-door-closed"></i>
                        Tutup Pendaftaran
                    </button>
                </article>

                <article class="ufo-kboard-item">
                    <button class="ufo-kboard-btn ghost w-100 justify-content-center" type="button">
                        <i class="bi bi-award"></i>
                        Cetak Sertifikat
                    </button>
                </article>

                <article class="ufo-kboard-item ufo-pg-soft-card">
                    <h4 class="ufo-kboard-item-title">Catatan</h4>
                    <p class="ufo-kboard-item-text mb-0">Pastikan status kehadiran peserta sudah diperbarui sebelum event ditutup.</p>
                </article>

                <article class="ufo-kboard-item">
                    <button class="ufo-kboard-btn danger w-100 justify-content-center" type="button">
                        <i class="bi bi-x-octagon"></i>
                        Batalkan Event
                    </button>
                </article>
            </div>
        </section>
    </div>
</div>
@endsection
