@extends('layouts.kemahasiswaan')

@section('title', 'Manajemen Pengumuman - Kemahasiswaan')

@section('content')
<div class="page-header">
  <div class="container-fluid">
    <h1 class="mb-2">Manajemen Pengumuman & Distribusi Email</h1>
    <p class="text-white-50 mb-0">Seluruh data pengumuman dan review email diambil langsung dari database.</p>
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
          <h5 class="mb-0">Buat Pengumuman Baru</h5>
          <small class="text-secondary">Pengumuman akan masuk antrean review email</small>
        </div>
        <div class="card-body">
          @php $hasAccounts = !empty($ukmAccounts ?? []); @endphp

          @unless($hasAccounts)
            <div class="alert alert-warning" role="alert">
              Belum ada akun UKM aktif. Tambahkan akun UKM terlebih dahulu pada menu Manajemen Organisasi.
            </div>
          @endunless

          <form method="POST" action="{{ route('portal.kemahasiswaan.pengumuman.store') }}" class="row g-3">
            @csrf
            <div class="col-md-4">
              <label class="form-label">Judul Pengumuman</label>
              <input type="text" name="judul" class="form-control" maxlength="140" required>
            </div>
            <div class="col-md-2">
              <label class="form-label">Kategori</label>
              <input type="text" name="kategori" class="form-control" required>
            </div>
            <div class="col-md-2">
              <label class="form-label">Target</label>
              <input type="text" name="target" class="form-control" required>
            </div>
            <div class="col-md-2">
              <label class="form-label">Tanggal Publish</label>
              <input type="date" name="publish_at" class="form-control">
            </div>
            <div class="col-md-2">
              <label class="form-label">Akun UKM</label>
              <select name="ukm_account_id" class="form-select" required @disabled(!$hasAccounts)>
                <option value="">Pilih akun</option>
                @foreach(($ukmAccounts ?? []) as $akun)
                  <option value="{{ $akun['id'] }}">{{ $akun['organisasi'] }} - {{ $akun['nama'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-10">
              <label class="form-label">Ringkasan</label>
              <input type="text" name="ringkasan" class="form-control" maxlength="240" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <button type="submit" class="btn btn-primary w-100" @disabled(!$hasAccounts)>Simpan</button>
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
          <h5 class="mb-0">Review Izin Pengumuman ke Email</h5>
          <small class="text-secondary">{{ count($emailReviewQueue ?? []) }} item menunggu review</small>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Judul</th>
                  <th>Pemohon</th>
                  <th>Organisasi</th>
                  <th>Status Publikasi</th>
                  <th>Status Review Email</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse(($emailReviewQueue ?? []) as $item)
                  <tr>
                    <td>
                      <div class="fw-semibold">{{ $item['judul'] }}</div>
                      <small class="text-muted">{{ $item['ringkasan'] }}</small>
                    </td>
                    <td>{{ $item['ukm_account_name'] }}</td>
                    <td>{{ $item['organisasi'] }}</td>
                    <td>{{ $item['status'] }}</td>
                    <td>{{ $item['email_review_status'] }}</td>
                    <td class="text-center">
                      <form method="POST" action="{{ route('portal.kemahasiswaan.pengumuman.email-review', ['id' => $item['id']]) }}" class="d-flex gap-2 justify-content-center flex-wrap">
                        @csrf
                        <select name="decision" class="form-select form-select-sm" style="min-width: 130px;">
                          <option value="setujui">Setujui</option>
                          <option value="tolak">Tolak</option>
                          <option value="revisi">Revisi</option>
                        </select>
                        <input type="text" name="catatan" class="form-control form-control-sm" style="min-width: 220px;" placeholder="Catatan (wajib jika tolak/revisi)">
                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted py-3">Tidak ada antrean review email.</td>
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
          <h5 class="mb-0">Daftar Pengumuman</h5>
          <small class="text-secondary">{{ count($workflowPengumuman ?? []) }} total data</small>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Judul</th>
                  <th>Kategori</th>
                  <th>Target</th>
                  <th>Pemohon</th>
                  <th>Publish</th>
                  <th>Status</th>
                  <th>Review Email</th>
                </tr>
              </thead>
              <tbody>
                @forelse(($workflowPengumuman ?? []) as $item)
                  <tr>
                    <td>{{ $item['judul'] }}</td>
                    <td>{{ $item['kategori'] }}</td>
                    <td>{{ $item['target'] }}</td>
                    <td>{{ $item['organisasi'] }}</td>
                    <td>
                      @if(!empty($item['publish_at']))
                        {{ \Carbon\Carbon::parse($item['publish_at'])->format('d M Y') }}
                      @else
                        -
                      @endif
                    </td>
                    <td>{{ $item['status'] }}</td>
                    <td>
                      <div>{{ $item['email_review_status'] }}</div>
                      @if(!empty($item['email_review_note']))
                        <small class="text-muted">{{ $item['email_review_note'] }}</small>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center text-muted py-3">Belum ada pengumuman.</td>
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
