@extends('layouts.pengurus')

@section('title', 'Event Organisasi')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Event Organisasi</h1>
        <p class="page-subtitle">Kelola event dan kegiatan organisasi Anda</p>
    </div>
    <a href="/portal/pengurus/events/create" class="btn-primary-org">➕ Buat Event</a>
</div>

<div class="bg-white rounded-3 shadow-sm border" style="border: 2px solid #f0f0f0; overflow: hidden;">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead style="background: var(--primary); color: white;">
                <tr>
                    <th style="border: none; padding: 15px; font-weight: 600;">Nama Event</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Tanggal</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Lokasi</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Peserta</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Status</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 15px;">
                        <strong>{{ $event['name'] }}</strong><br>
                        <small class="text-muted">{{ $event['time'] }}</small>
                    </td>
                    <td style="padding: 15px;">📅 {{ date('d M Y', strtotime($event['date'])) }}</td>
                    <td style="padding: 15px;">📍 {{ $event['location'] }}</td>
                    <td style="padding: 15px;">👥 {{ $event['participants'] }}/{{ $event['quota'] }}</td>
                    <td style="padding: 15px;">
                        @if($event['status'] == 'Draft')
                            <span class="badge-org warning">Draft</span>
                        @elseif($event['status'] == 'Open')
                            <span class="badge-org success">Open</span>
                        @elseif($event['status'] == 'Closed')
                            <span class="badge-org info">Closed</span>
                        @else
                            <span class="badge-org danger">{{ $event['status'] }}</span>
                        @endif
                    </td>
                    <td style="padding: 15px;">
                        <a href="/portal/pengurus/events/{{ $event['id'] }}" class="btn btn-sm" style="background: var(--primary); color: white; border: none; padding: 6px 12px; border-radius: 6px; text-decoration: none; cursor: pointer;">Detail</a>
                        <button class="btn btn-sm" style="background: var(--accent); color: #333; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; margin-left: 5px;">Edit</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
