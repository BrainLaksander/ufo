@extends('layouts.app')

@section('title', 'Manajemen Pengumuman & Distribusi Email')

@section('content')
<div class="container-fluid py-4 pengumuman-page">
  <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-4">
    <div>
      <h1 class="display-6 fw-bold page-title mb-1">Manajemen Pengumuman & Distribusi Email</h1>
      <p class="text-muted mb-0">Kelola pengumuman, jadwalkan publikasi, dan distribusikan melalui email resmi UNKLAB.</p>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
      <div class="card stat-card stat-total h-100 border-0 shadow-sm">
        <div class="card-body text-center py-4">
          <small class="text-muted d-block mb-2">Total Pengumuman</small>
          <h2 class="fw-bold mb-0">6</h2>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6">
      <div class="card stat-card stat-published h-100 border-0 shadow-sm">
        <div class="card-body text-center py-4">
          <small class="text-muted d-block mb-2">Terpublikasi</small>
          <h2 class="fw-bold mb-0">3</h2>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6">
      <div class="card stat-card stat-scheduled h-100 border-0 shadow-sm">
        <div class="card-body text-center py-4">
          <small class="text-muted d-block mb-2">Terjadwal</small>
          <h2 class="fw-bold mb-0">2</h2>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6">
      <div class="card stat-card stat-draft h-100 border-0 shadow-sm">
        <div class="card-body text-center py-4">
          <small class="text-muted d-block mb-2">Draft</small>
          <h2 class="fw-bold mb-0">1</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 align-items-center mb-4">
    <div class="col-md-5">
      <div class="input-group input-group-lg">
        <span class="input-group-text bg-white"></span>
        <input
          type="text"
          class="form-control"
          id="searchPengumuman"
          placeholder="Cari pengumuman..."
        >
      </div>
    </div>

    <div class="col-md-4">
      <select class="form-select form-select-lg" id="filterStatus">
        <option value="">Semua Status</option>
        <option value="Terpublikasi">Terpublikasi</option>
        <option value="Terjadwal">Terjadwal</option>
        <option value="Draft">Draft</option>
      </select>
    </div>

    <div class="col-md-3 d-grid">
      <button type="button" class="btn btn-primary btn-lg btn-create-announcement">
        Buat Pengumuman Baru
      </button>
    </div>
  </div>

  <div class="alert email-distribution-alert border-0 mb-4" role="alert">
    <div class="d-flex gap-3">
      <div class="fs-4"></div>
      <div>
        <strong>Sistem Distribusi Email:</strong>
        <p class="mb-0 mt-1">Semua pengumuman yang disetujui akan dikirim otomatis ke email resmi UNKLAB dari organisasi atau mahasiswa yang ditargetkan. Pastikan target distribusi sudah benar sebelum mempublikasikan.</p>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="tablePengumuman">
        <thead>
          <tr>
            <th>Judul Pengumuman</th>
            <th>Kategori</th>
            <th>Target</th>
            <th>Jadwal Publish</th>
            <th>Status</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr data-status="Terpublikasi">
            <td>
              <a href="#" class="announcement-title">Pendaftaran Beasiswa Prestasi 2026</a>
              <small class="d-block text-muted">Dibuat: 20 Januari 2026</small>
            </td>
            <td>Akademik</td>
            <td>Semua Organisasi</td>
            <td>
              <div>16 Desember 2024</div>
              <small class="text-muted">08:00</small>
            </td>
            <td><span class="badge rounded-pill status-published">Terpublikasi</span></td>
            <td class="text-center">
              <div class="d-inline-flex gap-2">
                <a href="#" class="action-link action-view" title="Lihat">Lihat</a>
                <a href="#" class="action-link action-edit" title="Edit">Edit</a>
                <a href="#" class="action-link action-delete" title="Hapus">Hapus</a>
              </div>
            </td>
          </tr>

          <tr data-status="Terjadwal">
            <td>
              <a href="#" class="announcement-title">Undangan Webinar Internasional</a>
              <small class="d-block text-muted">Dibuat: 18 Januari 2026</small>
            </td>
            <td>Non-Akademik</td>
            <td>Fakultas & Mahasiswa</td>
            <td>
              <div>22 Desember 2024</div>
              <small class="text-muted">14:30</small>
            </td>
            <td><span class="badge rounded-pill status-scheduled">Terjadwal</span></td>
            <td class="text-center">
              <div class="d-inline-flex gap-2">
                <a href="#" class="action-link action-view" title="Lihat">Lihat</a>
                <a href="#" class="action-link action-edit" title="Edit">Edit</a>
                <a href="#" class="action-link action-delete" title="Hapus">Hapus</a>
              </div>
            </td>
          </tr>

          <tr data-status="Draft">
            <td>
              <a href="#" class="announcement-title">Pengumuman Libur Nasional 2026</a>
              <small class="d-block text-muted">Dibuat: 10 Januari 2026</small>
            </td>
            <td>Umum</td>
            <td>Semua Civitas</td>
            <td>
              <div class="text-muted">--</div>
              <small class="text-muted">&nbsp;</small>
            </td>
            <td><span class="badge rounded-pill status-draft">Draft</span></td>
            <td class="text-center">
              <div class="d-inline-flex gap-2">
                <a href="#" class="action-link action-view" title="Lihat">Lihat</a>
                <a href="#" class="action-link action-edit" title="Edit">Edit</a>
                <a href="#" class="action-link action-delete" title="Hapus">Hapus</a>
              </div>
            </td>
          </tr>

          <tr data-status="Terjadwal">
            <td>
              <a href="#" class="announcement-title">Pemilihan Pengurus Himpunan 2026</a>
              <small class="d-block text-muted">Dibuat: 25 Januari 2026</small>
            </td>
            <td>Organisasi</td>
            <td>Pengurus & Anggota</td>
            <td>
              <div>28 Februari 2026</div>
              <small class="text-muted">10:00</small>
            </td>
            <td><span class="badge rounded-pill status-scheduled">Terjadwal</span></td>
            <td class="text-center">
              <div class="d-inline-flex gap-2">
                <a href="#" class="action-link action-view" title="Lihat">Lihat</a>
                <a href="#" class="action-link action-edit" title="Edit">Edit</a>
                <a href="#" class="action-link action-delete" title="Hapus">Hapus</a>
              </div>
            </td>
          </tr>

          <tr data-status="Terpublikasi">
            <td>
              <a href="#" class="announcement-title">Informasi Registrasi PKM 2026</a>
              <small class="d-block text-muted">Dibuat: 05 Februari 2026</small>
            </td>
            <td>Akademik</td>
            <td>Mahasiswa S1</td>
            <td>
              <div>15 Maret 2026</div>
              <small class="text-muted">09:00</small>
            </td>
            <td><span class="badge rounded-pill status-published">Terpublikasi</span></td>
            <td class="text-center">
              <div class="d-inline-flex gap-2">
                <a href="#" class="action-link action-view" title="Lihat">Lihat</a>
                <a href="#" class="action-link action-edit" title="Edit">Edit</a>
                <a href="#" class="action-link action-delete" title="Hapus">Hapus</a>
              </div>
            </td>
          </tr>

          <tr data-status="Draft">
            <td>
              <a href="#" class="announcement-title">Pengumuman Jadwal Kuliah Semester Genap</a>
              <small class="d-block text-muted">Dibuat: 27 Januari 2026</small>
            </td>
            <td>Akademik</td>
            <td>Semua Mahasiswa</td>
            <td>
              <div class="text-muted">--</div>
              <small class="text-muted">&nbsp;</small>
            </td>
            <td><span class="badge rounded-pill status-draft">Draft</span></td>
            <td class="text-center">
              <div class="d-inline-flex gap-2">
                <a href="#" class="action-link action-view" title="Lihat">Lihat</a>
                <a href="#" class="action-link action-edit" title="Edit">Edit</a>
                <a href="#" class="action-link action-delete" title="Hapus">Hapus</a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>


@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchPengumuman');
    const filterDropdown = document.getElementById('filterStatus');
    const tableRows = document.querySelectorAll('#tablePengumuman tbody tr');

    function applyFilter() {
      const searchValue = searchInput.value.toLowerCase().trim();
      const filterValue = filterDropdown.value;

      tableRows.forEach(row => {
        const judul = row.cells[0].innerText.toLowerCase();
        const kategori = row.cells[1].innerText.toLowerCase();
        const target = row.cells[2].innerText.toLowerCase();
        const status = row.getAttribute('data-status');

        const matchesSearch =
          searchValue === '' ||
          judul.includes(searchValue) ||
          kategori.includes(searchValue) ||
          target.includes(searchValue);

        const matchesFilter = filterValue === '' || status === filterValue;
        row.style.display = matchesSearch && matchesFilter ? '' : 'none';
      });
    }

    searchInput.addEventListener('input', applyFilter);
    filterDropdown.addEventListener('change', applyFilter);
  });
</script>
@endpush
@endsection
