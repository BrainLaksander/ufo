@extends('layouts.pengurus')

@section('title', 'Lost & Found Moderation')

@section('content')
<div class="page-header mb-4">
    <h1>Lost & Found - Moderasi</h1>
    <p class="page-subtitle">Moderasi laporan barang hilang dan ditemukan</p>
</div>

<ul class="nav nav-tabs mb-4" role="tablist" style="border-bottom: 2px solid var(--primary);">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="lost-tab" data-bs-toggle="tab" data-bs-target="#lost" type="button" role="tab">🔍 Barang Hilang</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="found-tab" data-bs-toggle="tab" data-bs-target="#found" type="button" role="tab">✓ Barang Ditemukan</button>
    </li>
</ul>

<div class="tab-content">
    <!-- BARANG HILANG -->
    <div class="tab-pane fade show active" id="lost" role="tabpanel">
        <div class="bg-white rounded-3 shadow-sm border" style="border: 2px solid #f0f0f0; overflow: hidden;">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead style="background: var(--primary); color: white;">
                        <tr>
                            <th style="border: none; padding: 15px;">Barang</th>
                            <th style="border: none; padding: 15px;">Kategori</th>
                            <th style="border: none; padding: 15px;">Lokasi Terakhir</th>
                            <th style="border: none; padding: 15px;">Pelapor</th>
                            <th style="border: none; padding: 15px;">Status</th>
                            <th style="border: none; padding: 15px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            @if($item['status'] != 'Found')
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 15px;"><strong>{{ $item['item'] }}</strong></td>
                                <td style="padding: 15px;">{{ $item['category'] }}</td>
                                <td style="padding: 15px;">📍 {{ $item['location'] }}</td>
                                <td style="padding: 15px;">{{ $item['reporter'] }}</td>
                                <td style="padding: 15px;">
                                    @if($item['status'] == 'Pending')
                                        <span class="badge-org warning">Pending</span>
                                    @else
                                        <span class="badge-org success">{{ $item['status'] }}</span>
                                    @endif
                                </td>
                                <td style="padding: 15px;">
                                    <button class="btn btn-sm" style="background: var(--primary); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer;">Detail</button>
                                    <button class="btn btn-sm btn-success" style="background: var(--success); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; margin-left: 5px;">✓ Selesai</button>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- BARANG DITEMUKAN -->
    <div class="tab-pane fade" id="found" role="tabpanel">
        <div class="bg-white rounded-3 shadow-sm border" style="border: 2px solid #f0f0f0; overflow: hidden;">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead style="background: var(--primary); color: white;">
                        <tr>
                            <th style="border: none; padding: 15px;">Barang</th>
                            <th style="border: none; padding: 15px;">Kategori</th>
                            <th style="border: none; padding: 15px;">Lokasi Ditemukan</th>
                            <th style="border: none; padding: 15px;">Pelapor</th>
                            <th style="border: none; padding: 15px;">Status</th>
                            <th style="border: none; padding: 15px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            @if($item['status'] == 'Found')
                            <tr style="border-bottom: 1px solid #f0f0f0;">
                                <td style="padding: 15px;"><strong>{{ $item['item'] }}</strong></td>
                                <td style="padding: 15px;">{{ $item['category'] }}</td>
                                <td style="padding: 15px;">📍 {{ $item['location'] }}</td>
                                <td style="padding: 15px;">{{ $item['reporter'] }}</td>
                                <td style="padding: 15px;">
                                    <span class="badge-org success">✓ Ditemukan</span>
                                </td>
                                <td style="padding: 15px;">
                                    <button class="btn btn-sm" style="background: var(--primary); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer;">Detail</button>
                                    <button class="btn btn-sm btn-success" style="background: var(--success); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; margin-left: 5px;">✓ Selesai</button>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
