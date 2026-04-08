@extends('layouts.pengurus')

@section('title', 'Pengumuman Organisasi')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Pengumuman Organisasi</h1>
        <p class="page-subtitle">Kelola pengumuman untuk anggota organisasi</p>
    </div>
    <a href="{{ route('portal.pengurus.announcements.create') }}" class="btn-primary-org"> Buat Pengumuman</a>
</div>

<div class="bg-white rounded-3 shadow-sm border" style="border: 2px solid #f0f0f0; overflow: hidden;">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead style="background: var(--primary); color: white;">
                <tr>
                    <th style="border: none; padding: 15px; font-weight: 600;">Judul</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Kategori</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Tanggal</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Status</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($announcements as $announcement)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 15px;">
                        <strong>{{ $announcement['title'] }}</strong>
                    </td>
                    <td style="padding: 15px;">
                        <span class="badge-org info">{{ $announcement['category'] }}</span>
                    </td>
                    <td style="padding: 15px;"> {{ date('d M Y', strtotime($announcement['date'])) }}</td>
                    <td style="padding: 15px;">
                        @if($announcement['status'] == 'Published')
                            <span class="badge-org success">Published</span>
                        @else
                            <span class="badge-org warning">Draft</span>
                        @endif
                    </td>
                    <td style="padding: 15px;">
                        <button class="btn btn-sm" style="background: var(--primary); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer;">Edit</button>
                        @if($announcement['status'] == 'Draft')
                            <button class="btn btn-sm" style="background: var(--success); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; margin-left: 5px;">Publish</button>
                        @endif
                        <button class="btn btn-sm" style="background: var(--danger); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; margin-left: 5px;">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
