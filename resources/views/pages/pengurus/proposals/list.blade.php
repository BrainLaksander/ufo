@extends('layouts.pengurus')

@section('title', 'Proposal & Arsip')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Proposal & Arsip</h1>
        <p class="page-subtitle">Kelola dokumen dan proposal organisasi</p>
    </div>
    <button class="btn-primary-org" onclick="document.getElementById('fileInput').click()"> Upload File</button>
    <input type="file" id="fileInput" style="display: none;">
</div>

<div class="bg-white rounded-3 shadow-sm border" style="border: 2px solid #f0f0f0; overflow: hidden;">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead style="background: var(--primary); color: white;">
                <tr>
                    <th style="border: none; padding: 15px; font-weight: 600;">Nama File</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Tanggal Upload</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Ukuran</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Status</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proposals as $proposal)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 15px;">
                        <strong> {{ $proposal['name'] }}</strong>
                    </td>
                    <td style="padding: 15px;"> {{ date('d M Y', strtotime($proposal['date'])) }}</td>
                    <td style="padding: 15px;">{{ $proposal['size'] }}</td>
                    <td style="padding: 15px;">
                        @if($proposal['status'] == 'Approved')
                            <span class="badge-org success"> Approved</span>
                        @else
                            <span class="badge-org warning">Draft</span>
                        @endif
                    </td>
                    <td style="padding: 15px;">
                        <button class="btn btn-sm" style="background: var(--primary); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer;"> Download</button>
                        @if($proposal['status'] == 'Draft')
                            <button class="btn btn-sm" style="background: var(--success); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; margin-left: 5px;"> Approve</button>
                        @endif
                        <button class="btn btn-sm btn-danger" style="background: var(--danger); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; margin-left: 5px;"> Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
