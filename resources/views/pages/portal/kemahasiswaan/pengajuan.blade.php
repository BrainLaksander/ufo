@extends('layouts.kemahasiswaan')

@section('title', 'Manajemen Pengajuan & Laporan - Kemahasiswaan')

@section('content')
<div class="page-header">
  <div class="container-fluid">
    <h1 class="mb-2">Manajemen Pengajuan Kegiatan & Laporan</h1>
    <p class="text-white-50 mb-0">Semua data ditarik dari database tanpa seed hardcoded.</p>
  </div>
</div>

<div class="container-fluid py-4">
  @if(session('success'))
    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
  @endif

  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Review Izin Kegiatan</h5>
          <small class="text-secondary">{{ count($workflowPengajuan ?? []) }} data pengajuan</small>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th>Judul</th>
                  <th>Organisasi</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  <th>Catatan</th>
                  <th class="text-center">Aksi Review</th>
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
                      @if(in_array($item['status'], ['Diajukan', 'Revisi', 'Sedang Direview'], true))
                        <form method="POST" action="{{ route('portal.kemahasiswaan.pengajuan.review', ['id' => $item['id']]) }}" class="d-flex gap-2 justify-content-center flex-wrap">
                          @csrf
                          <select name="decision" class="form-select form-select-sm" style="min-width: 130px;">
                            <option value="disetujui">Setujui</option>
                            <option value="ditolak">Tolak</option>
                            <option value="revisi">Revisi</option>
                          </select>
                          <input type="text" name="catatan" class="form-control form-control-sm" style="min-width: 220px;" placeholder="Catatan (wajib jika tolak/revisi)">
                          <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                        </form>
                      @else
                        <small class="text-muted">Tidak ada aksi lanjutan</small>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted py-3">Belum ada pengajuan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Review Laporan Kegiatan (LPJ)</h5>
          <small class="text-secondary">{{ count($workflowLaporan ?? []) }} data laporan</small>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th>Judul Laporan</th>
                  <th>Organisasi</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                  <th>Catatan</th>
                  <th class="text-center">Aksi Review</th>
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
                      @if(in_array($item['status'], ['Diajukan', 'Revisi', 'Sedang Direview'], true))
                        <form method="POST" action="{{ route('portal.kemahasiswaan.laporan.review', ['id' => $item['id']]) }}" class="d-flex gap-2 justify-content-center flex-wrap">
                          @csrf
                          <select name="decision" class="form-select form-select-sm" style="min-width: 130px;">
                            <option value="disetujui">Setujui</option>
                            <option value="ditolak">Tolak</option>
                            <option value="revisi">Revisi</option>
                          </select>
                          <input type="text" name="catatan" class="form-control form-control-sm" style="min-width: 220px;" placeholder="Catatan (wajib jika tolak/revisi)">
                          <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                        </form>
                      @else
                        <small class="text-muted">Tidak ada aksi lanjutan</small>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted py-3">Belum ada laporan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-lg-5 mb-3 mb-lg-0">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header">
          <h6 class="mb-0">Masukkan Jadwal Kegiatan</h6>
        </div>
        <div class="card-body">
          @php $hasOrganizations = !empty($organizations ?? []); @endphp

          @unless($hasOrganizations)
            <div class="alert alert-warning" role="alert">
              Belum ada data organisasi. Tambahkan organisasi terlebih dahulu.
            </div>
          @endunless

          <form method="POST" action="{{ route('portal.kemahasiswaan.jadwal.store') }}" class="row g-2">
            @csrf
            <div class="col-12">
              <label class="form-label">Judul Kegiatan</label>
              <input type="text" name="judul" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Organisasi</label>
              <select name="organization_id" class="form-select" required @disabled(!$hasOrganizations)>
                <option value="">Pilih organisasi</option>
                @foreach(($organizations ?? []) as $organization)
                  <option value="{{ $organization['id'] }}">{{ $organization['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Tanggal</label>
              <input type="date" name="tanggal" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Lokasi</label>
              <input type="text" name="lokasi" class="form-control" required>
            </div>
            <div class="col-12 mt-3">
              <button type="submit" class="btn btn-primary w-100" @disabled(!$hasOrganizations)>Simpan Jadwal</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h6 class="mb-0">Jadwal Kegiatan Aktif</h6>
          <small class="text-secondary">{{ count($jadwalKegiatan ?? []) }} data jadwal</small>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-hover mb-0 align-middle">
              <thead class="table-light">
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
  </div>
</div>
@endsection
