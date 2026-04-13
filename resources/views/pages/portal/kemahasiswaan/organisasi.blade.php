@extends('layouts.kemahasiswaan')

@section('title', 'Manajemen Akun UKM - Kemahasiswaan')

@section('content')
<div class="page-header">
  <div class="container-fluid">
    <h1 class="mb-2">Manajemen Akun UKM</h1>
    <p class="text-white-50 mb-0">Kelola akun UKM dan lihat log aktivitas tiap akun tanpa data hardcoded.</p>
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
        <div class="card-header">
          <h5 class="mb-0">Tambah Akun UKM</h5>
        </div>
        <div class="card-body">
          @php $hasOrganizations = !empty($organizations ?? []); @endphp

          @unless($hasOrganizations)
            <div class="alert alert-warning mb-3" role="alert">
              Data organisasi belum tersedia. Tambahkan organisasi terlebih dahulu sebelum membuat akun UKM.
            </div>
          @endunless

          <form method="POST" action="{{ route('portal.kemahasiswaan.organisasi.akun.store') }}" class="row g-3">
            @csrf
            <div class="col-md-3">
              <label class="form-label">Nama Pengurus</label>
              <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Organisasi</label>
              <select name="organization_id" class="form-select" required @disabled(!$hasOrganizations)>
                <option value="">Pilih organisasi</option>
                @foreach(($organizations ?? []) as $organization)
                  <option value="{{ $organization['id'] }}">{{ $organization['name'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label">Jabatan</label>
              <input type="text" name="jabatan" class="form-control" required>
            </div>
            <div class="col-md-1 d-flex align-items-end">
              <button type="submit" class="btn btn-primary w-100" @disabled(!$hasOrganizations)>Tambah</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Daftar Akun UKM</h5>
          <small class="text-secondary">{{ count($ukmAccounts ?? []) }} akun</small>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Organisasi</th>
                  <th>Jabatan</th>
                  <th>Status</th>
                  <th>Login Terakhir</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse(($ukmAccounts ?? []) as $akun)
                  <tr>
                    <td>{{ $akun['nama'] }}</td>
                    <td>{{ $akun['email'] }}</td>
                    <td>{{ $akun['organisasi'] }}</td>
                    <td>{{ $akun['jabatan'] }}</td>
                    <td>
                      <span class="badge {{ $akun['status_code'] === 'active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $akun['status_label'] }}
                      </span>
                    </td>
                    <td>
                      @if(!empty($akun['last_login_at']))
                        {{ \Carbon\Carbon::parse($akun['last_login_at'])->format('d M Y H:i') }}
                      @else
                        -
                      @endif
                    </td>
                    <td class="text-center">
                      <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#edit-akun-{{ $akun['id'] }}">
                          Ubah
                        </button>

                        <form method="POST" action="{{ route('portal.kemahasiswaan.organisasi.akun.reset-password', ['id' => $akun['id']]) }}">
                          @csrf
                          <button type="submit" class="btn btn-sm btn-outline-warning">Reset Password</button>
                        </form>

                        @if($akun['status_code'] === 'active')
                          <form method="POST" action="{{ route('portal.kemahasiswaan.organisasi.akun.deactivate', ['id' => $akun['id']]) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">Non-aktifkan</button>
                          </form>
                        @endif
                      </div>
                    </td>
                  </tr>
                  <tr class="collapse" id="edit-akun-{{ $akun['id'] }}">
                    <td colspan="7" class="bg-light">
                      <form method="POST" action="{{ route('portal.kemahasiswaan.organisasi.akun.update', ['id' => $akun['id']]) }}" class="row g-2 align-items-end">
                        @csrf
                        <div class="col-md-2">
                          <label class="form-label">Nama</label>
                          <input type="text" name="nama" class="form-control form-control-sm" value="{{ $akun['nama'] }}" required>
                        </div>
                        <div class="col-md-3">
                          <label class="form-label">Email</label>
                          <input type="email" name="email" class="form-control form-control-sm" value="{{ $akun['email'] }}" required>
                        </div>
                        <div class="col-md-2">
                          <label class="form-label">Organisasi</label>
                          <select name="organization_id" class="form-select form-select-sm" required>
                            @foreach(($organizations ?? []) as $organization)
                              <option value="{{ $organization['id'] }}" @selected(($akun['organization_id'] ?? null) === $organization['id'])>
                                {{ $organization['name'] }}
                              </option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-2">
                          <label class="form-label">Jabatan</label>
                          <input type="text" name="jabatan" class="form-control form-control-sm" value="{{ $akun['jabatan'] }}" required>
                        </div>
                        <div class="col-md-2">
                          <label class="form-label">Status</label>
                          <select name="status" class="form-select form-select-sm" required>
                            <option value="active" @selected($akun['status_code'] === 'active')>Aktif</option>
                            <option value="inactive" @selected($akun['status_code'] === 'inactive')>Nonaktif</option>
                          </select>
                        </div>
                        <div class="col-md-1">
                          <button type="submit" class="btn btn-sm btn-primary w-100">Simpan</button>
                        </div>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center text-muted py-3">Belum ada akun UKM.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Log Activity Tiap Akun</h5>
          <small class="text-secondary">Audit trail dari database</small>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Waktu</th>
                  <th>Akun</th>
                  <th>Organisasi</th>
                  <th>Aksi</th>
                  <th>Deskripsi</th>
                </tr>
              </thead>
              <tbody>
                @forelse(($accountActivityLogs ?? []) as $log)
                  <tr>
                    <td>{{ \Carbon\Carbon::parse($log['created_at'])->format('d M Y H:i') }}</td>
                    <td>{{ $log['account_name'] }}</td>
                    <td>{{ $log['organization'] }}</td>
                    <td>{{ $log['action'] }}</td>
                    <td>{{ $log['description'] }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-3">Belum ada log aktivitas.</td>
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
