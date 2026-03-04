@extends('layouts.pengurus')

@section('title', 'Anggota Organisasi')

@section('content')
<div class="page-header mb-4">
    <h1>Anggota Organisasi</h1>
    <p class="page-subtitle">Kelola anggota aktif organisasi Anda</p>
</div>

<div class="bg-white rounded-3 shadow-sm border" style="border: 2px solid #f0f0f0; overflow: hidden;">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead style="background: var(--primary); color: white;">
                <tr>
                    <th style="border: none; padding: 15px; font-weight: 600;">Nama</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">NIM</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Jabatan</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Status</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 15px;">{{ $member['name'] }}</td>
                    <td style="padding: 15px;">{{ $member['nim'] }}</td>
                    <td style="padding: 15px;">
                        <strong style="color: var(--primary);">{{ $member['position'] }}</strong>
                    </td>
                    <td style="padding: 15px;">
                        @if($member['status'] == 'Aktif')
                            <span class="badge-org success">Aktif</span>
                        @else
                            <span class="badge-org danger">Nonaktif</span>
                        @endif
                    </td>
                    <td style="padding: 15px;">
                        <select style="padding: 6px 10px; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 12px; cursor: pointer;">
                            <option value="">Ubah Jabatan</option>
                            <option value="ketua">Ketua</option>
                            <option value="sekretaris">Sekretaris</option>
                            <option value="bendahara">Bendahara</option>
                            <option value="staff">Staff</option>
                        </select>
                        @if($member['status'] == 'Aktif')
                            <button class="btn btn-sm btn-danger" style="background: var(--danger); border: none; color: white; cursor: pointer; padding: 6px 12px; border-radius: 6px; margin-left: 5px;">Nonaktifkan</button>
                        @else
                            <button class="btn btn-sm btn-success" style="background: var(--success); border: none; color: white; cursor: pointer; padding: 6px 12px; border-radius: 6px; margin-left: 5px;">Aktifkan</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
