@extends('layouts.pengurus')

@section('title', 'Pengajuan Laporan & Arsip')

@section('content')
<div class="page-header mb-4">
    <h1>Pengajuan Laporan & Arsip</h1>
    <p>Data halaman ini diambil langsung dari database tanpa data hardcoded.</p>
</div>

@if(session('success'))
    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
@endif

@if(!($hasPengurusContext ?? false))
    <div class="alert alert-warning" role="alert">
        Akun pengurus belum terhubung ke data organisasi/member. Form pengajuan dan laporan dinonaktifkan.
    </div>
@endif

<div class="card card-dashboard mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Ajukan Izin Kegiatan Baru</h5>
            <small class="text-muted">Organisasi: {{ $pengurusOrganizationName ?? '-' }}</small>
        </div>

        <form method="POST" action="{{ route('portal.pengurus.proposals.store') }}" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Judul Kegiatan</label>
                <input type="text" name="title" class="form-control" required @disabled(!($hasPengurusContext ?? false))>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipe Pengajuan</label>
                <select name="type" class="form-select" required @disabled(!($hasPengurusContext ?? false))>
                    <option value="proposal">Proposal</option>
                    <option value="budget">Budget</option>
                    <option value="activity_plan">Activity Plan</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">Deskripsi Ringkas</label>
                <input type="text" name="description" class="form-control" required @disabled(!($hasPengurusContext ?? false))>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" @disabled(!($hasPengurusContext ?? false))>Ajukan Sekarang</button>
            </div>
        </form>
    </div>
</div>

<div class="card card-dashboard mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Buat Draft Laporan Kegiatan</h5>
            <small class="text-muted">Simpan sebagai draft, lalu kirim untuk review</small>
        </div>

        <form method="POST" action="{{ route('portal.pengurus.reports.store') }}" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Judul Laporan</label>
                <input type="text" name="title" class="form-control" required @disabled(!($hasPengurusContext ?? false))>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipe Laporan</label>
                <select name="report_type" class="form-select" required @disabled(!($hasPengurusContext ?? false))>
                    <option value="activity">Activity</option>
                    <option value="financial">Financial</option>
                    <option value="semester">Semester</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Peserta</label>
                <input type="number" min="0" name="participants" class="form-control" value="0" required @disabled(!($hasPengurusContext ?? false))>
            </div>
            <div class="col-md-3">
                <label class="form-label">Event (opsional)</label>
                <select name="event_id" class="form-select" @disabled(!($hasPengurusContext ?? false))>
                    <option value="">Tanpa event</option>
                    @foreach(($eventOptions ?? []) as $event)
                        <option value="{{ $event['id'] }}">{{ $event['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Isi Laporan</label>
                <textarea name="content" class="form-control" rows="3" required @disabled(!($hasPengurusContext ?? false))></textarea>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" @disabled(!($hasPengurusContext ?? false))>Simpan Draft</button>
            </div>
        </form>
    </div>
</div>

<div class="card card-dashboard mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Pengajuan Izin Kegiatan</h5>
            <small class="text-muted">Draft/Revisi dapat dikirim ke Kemahasiswaan</small>
        </div>

        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th>Judul Kegiatan</th>
                        <th>Organisasi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Catatan Departemen</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($workflowPengajuan ?? []) as $item)
                        <tr>
                            <td>{{ $item['judul'] }}</td>
                            <td>{{ $item['organisasi'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($item['tanggal_kegiatan'])->format('d M Y') }}</td>
                            <td>{{ $item['status'] }}</td>
                            <td>{{ $item['catatan_departemen'] ?? '-' }}</td>
                            <td class="text-center">
                                @if(in_array($item['status'], ['Draft', 'Revisi'], true))
                                    <form method="POST" action="{{ route('portal.pengurus.proposals.submit', ['id' => $item['id']]) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">Kirim Pengajuan</button>
                                    </form>
                                @elseif($item['status'] === 'Diajukan')
                                    <small class="text-muted">Menunggu review</small>
                                @else
                                    <small class="text-muted">Selesai</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Belum ada data pengajuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card card-dashboard mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Laporan Kegiatan (LPJ)</h5>
            <small class="text-muted">Draft/Revisi dapat dikirim ke Kemahasiswaan</small>
        </div>

        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th>Judul Laporan</th>
                        <th>Organisasi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Catatan Departemen</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($workflowLaporan ?? []) as $item)
                        <tr>
                            <td>{{ $item['judul'] }}</td>
                            <td>{{ $item['organisasi'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($item['tanggal_laporan'])->format('d M Y') }}</td>
                            <td>{{ $item['status'] }}</td>
                            <td>{{ $item['catatan_departemen'] ?? '-' }}</td>
                            <td class="text-center">
                                @if(in_array($item['status'], ['Draft', 'Revisi'], true))
                                    <form method="POST" action="{{ route('portal.pengurus.reports.submit', ['id' => $item['id']]) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">Kirim Laporan</button>
                                    </form>
                                @elseif($item['status'] === 'Diajukan')
                                    <small class="text-muted">Menunggu review</small>
                                @else
                                    <small class="text-muted">Selesai</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Belum ada data laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card card-dashboard h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Jadwal Kegiatan Organisasi</h5>
                    <small class="text-muted">{{ count($jadwalKegiatan ?? []) }} jadwal</small>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Kegiatan</th>
                                <th>Organisasi</th>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($jadwalKegiatan ?? []) as $jadwal)
                                <tr>
                                    <td>{{ $jadwal['judul'] }}</td>
                                    <td>{{ $jadwal['organisasi'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($jadwal['tanggal'])->format('d M Y') }}</td>
                                    <td>{{ $jadwal['lokasi'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Belum ada jadwal kegiatan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="card card-dashboard h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Kontak Pengurus UKM</h5>
                    <small class="text-muted">{{ count($kontakPengurus ?? []) }} kontak</small>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Kontak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($kontakPengurus ?? []) as $kontak)
                                <tr>
                                    <td>
                                        <div>{{ $kontak['nama'] }}</div>
                                        <small class="text-muted">{{ $kontak['organisasi'] }}</small>
                                    </td>
                                    <td>{{ $kontak['jabatan'] }}</td>
                                    <td>
                                        <div>{{ $kontak['kontak'] }}</div>
                                        <small class="text-muted">{{ $kontak['email'] }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Belum ada kontak pengurus.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
