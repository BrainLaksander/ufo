@extends('layouts.pengurus')

@section('title', 'Pendaftaran Anggota')

@section('content')
<div class="page-header mb-4">
    <h1>Pendaftaran Anggota Baru</h1>
    <p class="page-subtitle">Review dan proses pendaftaran anggota baru</p>
</div>

<div class="bg-white rounded-3 shadow-sm border" style="border: 2px solid #f0f0f0; overflow: hidden;">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead style="background: var(--primary); color: white;">
                <tr>
                    <th style="border: none; padding: 15px; font-weight: 600;">Nama</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">NIM</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Fakultas</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Tgl Daftar</th>
                    <th style="border: none; padding: 15px; font-weight: 600;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $app)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 15px;">
                        <strong>{{ $app['name'] }}</strong>
                    </td>
                    <td style="padding: 15px;">{{ $app['nim'] }}</td>
                    <td style="padding: 15px;">{{ $app['faculty'] }}</td>
                    <td style="padding: 15px;"><i class="bi bi-calendar-date me-1"></i>{{ date('d M Y', strtotime($app['date'])) }}</td>
                    <td style="padding: 15px;">
                        <button class="btn btn-sm btn-success" style="background: var(--success); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer;"><i class="bi bi-check-lg me-1"></i>Terima</button>
                        <button class="btn btn-sm btn-danger" style="background: var(--danger); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; margin-left: 5px;"><i class="bi bi-x-lg me-1"></i>Tolak</button>
                        <button class="btn btn-sm" style="background: var(--primary); color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; margin-left: 5px;"><i class="bi bi-eye-fill me-1"></i>Detail</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
