@extends('layouts.pengurus')

@section('title', 'Detail Event')

@section('content')
<div class="page-header mb-4">
    <a href="{{ route('portal.pengurus.events') }}" class="btn-secondary-org" style="text-decoration: none; margin-bottom: 10px; display: inline-block;">← Kembali ke Daftar Event</a>
    <h1>{{ $event['name'] }}</h1>
    <p class="page-subtitle"> {{ date('d F Y', strtotime($event['date'])) }} |  {{ $event['location'] }}</p>
</div>

<div class="row g-3">
    <!-- EVENT INFO -->
    <div class="col-lg-8">
        <div class="bg-white rounded-3 shadow-sm border p-4" style="border: 2px solid #f0f0f0;">
            <h3 class="text-primary mb-3" style="font-weight: 700;"> Informasi Event</h3>
            <div class="mb-3">
                <strong>Deskripsi:</strong>
                <p>{{ $event['description'] }}</p>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <strong>Tanggal & Waktu:</strong>
                    <p> {{ date('d M Y H:i', strtotime($event['date'] . ' ' . $event['time'])) }}</p>
                </div>
                <div class="col-md-6">
                    <strong>Lokasi:</strong>
                    <p> {{ $event['location'] }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <strong>Kuota:</strong>
                    <p>{{ $event['quota'] }} peserta</p>
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong>
                    <p>
                        @if($event['status'] == 'Open')
                            <span class="badge-org success">Open</span>
                        @else
                            <span class="badge-org info">{{ $event['status'] }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <hr>

            <h4 class="text-primary mb-3" style="font-weight: 700;"> Daftar Peserta ({{ count($event['participants']) }})</h4>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead style="background: var(--primary); color: white;">
                        <tr>
                            <th style="border: none;">Nama</th>
                            <th style="border: none;">NIM</th>
                            <th style="border: none;">Status Kehadiran</th>
                            <th style="border: none;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event['participants'] as $participant)
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 10px;">{{ $participant['name'] }}</td>
                            <td style="padding: 10px;">{{ $participant['nim'] }}</td>
                            <td style="padding: 10px;">
                                @if($participant['status'] == 'Hadir')
                                    <span class="badge-org success"> Hadir</span>
                                @else
                                    <span class="badge-org warning">Belum Hadir</span>
                                @endif
                            </td>
                            <td style="padding: 10px;">
                                <button class="btn btn-sm btn-success" style="background: var(--success); border: none; color: white; cursor: pointer; padding: 4px 8px; border-radius: 4px; font-size: 11px;">Hadir</button>
                                <button class="btn btn-sm btn-danger" style="background: var(--danger); border: none; color: white; cursor: pointer; padding: 4px 8px; border-radius: 4px; font-size: 11px; margin-left: 3px;">Tidak</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 15px;">
                <button class="btn-primary-org"> Export Peserta (CSV)</button>
            </div>
        </div>
    </div>

    <!-- ACTIONS -->
    <div class="col-lg-4">
        <div class="bg-white rounded-3 shadow-sm border p-4" style="border: 2px solid #f0f0f0;">
            <h4 class="text-primary mb-3" style="font-weight: 700;"> Aksi</h4>
            <div class="d-flex flex-column gap-2">
                <button class="btn-primary-org"> Edit Event</button>
                <button class="btn-secondary-org"> Tutup Pendaftaran</button>
                <button class="btn-secondary-org"> Cetak Sertifikat</button>
                <button class="btn-danger-org" style="width: 100%; padding: 10px; margin-top: 10px;"> Batalkan Event</button>
            </div>
        </div>
    </div>
</div>
@endsection
