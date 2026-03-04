@extends('layouts.kemahasiswaan')

@section('title', 'Manajemen Organisasi - Kemahasiswaan')

@section('content')

<!-- ===== PAGE HEADER ===== -->
<div class="page-header">
  <div class="container-fluid">
    <h1 class="mb-2">Manajemen Organisasi</h1>
    <p class="text-white-50 mb-0">Kelola data dan informasi organisasi mahasiswa</p>
  </div>
</div>

<div class="container-fluid py-4">

  <!-- ===== FILTER & ACTION SECTION ===== -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header">
          <h6 class="mb-0" style="font-weight: 600; color: #202124;">Filter & Pencarian</h6>
        </div>
        <div class="card-body">
          <div class="row align-items-end g-3">
            
            <!-- Search Input -->
            <div class="col-md-4">
              <label class="form-label">Cari Organisasi</label>
              <input 
                type="text" 
                class="form-control" 
                id="searchOrganisasi"
                placeholder="Masukkan nama organisasi..."
              >
            </div>

            <!-- Category Filter -->
            <div class="col-md-3">
              <label class="form-label">Kategori</label>
              <select class="form-select" id="filterKategori">
                <option value="">Semua Kategori</option>
                <option value="BEM">BEM</option>
                <option value="Himpunan">Himpunan Jurusan</option>
                <option value="UKM">UKM</option>
                <option value="MPO">MPO</option>
              </select>
            </div>

            <!-- Status Filter -->
            <div class="col-md-2">
              <label class="form-label">Status</label>
              <select class="form-select" id="filterStatus">
                <option value="">Semua Status</option>
                <option value="Aktif">Aktif</option>
                <option value="Nonaktif">Nonaktif</option>
              </select>
            </div>

            <!-- Add Button -->
            <div class="col-md-3 text-end">
              <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#tambahOrganisasiModal">
                <i class="bi bi-plus-lg me-2"></i>Tambah Organisasi
              </button>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== ORGANIZATION TABLE SECTION ===== -->
  <div class="row">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Daftar Organisasi</h5>
          <small class="text-secondary" id="rowCount">6 organisasi ditemukan</small>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0" id="tabelOrganisasi">
              <thead class="table-light">
                <tr>
                  <th style="width: 22%;" class="border-0">Nama Organisasi</th>
                  <th style="width: 15%;" class="border-0">Kategori</th>
                  <th style="width: 12%;" class="border-0">Status</th>
                  <th style="width: 18%;" class="border-0">Ketua</th>
                  <th style="width: 18%;" class="border-0">Dokumen</th>
                  <th style="width: 15%;" class="border-0 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                
                <!-- Row 1: HMTI -->
                <tr>
                  <td class="py-3">
                    <div>
                      <a href="#" class="fw-600 text-primary text-decoration-none">
                        HMTI
                      </a>
                    </div>
                    <small class="text-secondary">12 Pengurus</small>
                  </td>
                  <td class="py-3">
                    <span>Himpunan Jurusan</span>
                  </td>
                  <td class="py-3">
                    <span class="badge bg-success">Aktif</span>
                  </td>
                  <td class="py-3">
                    <span>Ahmad Rizki P.</span>
                  </td>
                  <td class="py-3">
                    <a href="#" class="text-primary text-decoration-none">
                      <i class="bi bi-file-pdf me-2"></i> SK-HMTI-2024.pdf
                    </a>
                  </td>
                  <td class="py-3 text-center">
                    <div class="actions-cell">
                      <a href="#" class="btn-action text-primary" title="Lihat Detail">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="#" class="btn-action text-primary" title="Kelola Pengurus">
                        <i class="bi bi-people"></i>
                      </a>
                      <a href="#" class="btn-action text-secondary" title="Edit">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="#" class="btn-action text-danger" title="Hapus">
                        <i class="bi bi-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>

                <!-- Row 2: BEM -->
                <tr>
                  <td class="py-3">
                    <div>
                      <a href="#" class="fw-600 text-primary text-decoration-none">
                        BEM UNKLAB
                      </a>
                    </div>
                    <small class="text-secondary">18 Pengurus</small>
                  </td>
                  <td class="py-3">
                    <span>BEM</span>
                  </td>
                  <td class="py-3">
                    <span class="badge bg-success">Aktif</span>
                  </td>
                  <td class="py-3">
                    <span>Siti Nurhaliza</span>
                  </td>
                  <td class="py-3">
                    <a href="#" class="text-primary text-decoration-none">
                      <i class="bi bi-file-pdf me-2"></i> SK-BEM-2024.pdf
                    </a>
                  </td>
                  <td class="py-3 text-center">
                    <div class="actions-cell">
                      <a href="#" class="btn-action text-primary" title="Lihat Detail">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="#" class="btn-action text-primary" title="Kelola Pengurus">
                        <i class="bi bi-people"></i>
                      </a>
                      <a href="#" class="btn-action text-secondary" title="Edit">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="#" class="btn-action text-danger" title="Hapus">
                        <i class="bi bi-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>

                <!-- Row 3: UKM Seni -->
                <tr>
                  <td class="py-3">
                    <div>
                      <a href="#" class="fw-600 text-primary text-decoration-none">
                        UKM Seni
                      </a>
                    </div>
                    <small class="text-secondary">8 Pengurus</small>
                  </td>
                  <td class="py-3">
                    <span>UKM</span>
                  </td>
                  <td class="py-3">
                    <span class="badge bg-success">Aktif</span>
                  </td>
                  <td class="py-3">
                    <span>Dewi Kintamani</span>
                  </td>
                  <td class="py-3">
                    <a href="#" class="text-primary text-decoration-none">
                      <i class="bi bi-file-pdf me-2"></i> SK-UKM-SENI-2024.pdf
                    </a>
                  </td>
                  <td class="py-3 text-center">
                    <div class="actions-cell">
                      <a href="#" class="btn-action text-primary" title="Lihat Detail">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="#" class="btn-action text-primary" title="Kelola Pengurus">
                        <i class="bi bi-people"></i>
                      </a>
                      <a href="#" class="btn-action text-secondary" title="Edit">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="#" class="btn-action text-danger" title="Hapus">
                        <i class="bi bi-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>

                <!-- Row 4: HMDP -->
                <tr>
                  <td class="py-3">
                    <div>
                      <a href="#" class="fw-600 text-primary text-decoration-none">
                        HMDP
                      </a>
                    </div>
                    <small class="text-secondary">7 Pengurus</small>
                  </td>
                  <td class="py-3">
                    <span>Himpunan Jurusan</span>
                  </td>
                  <td class="py-3">
                    <span class="badge bg-success">Aktif</span>
                  </td>
                  <td class="py-3">
                    <span>Budi Santoso</span>
                  </td>
                  <td class="py-3">
                    <a href="#" class="text-primary text-decoration-none">
                      <i class="bi bi-file-pdf me-2"></i> SK-HMDP-2024.pdf
                    </a>
                  </td>
                  <td class="py-3 text-center">
                    <div class="actions-cell">
                      <a href="#" class="btn-action text-primary" title="Lihat Detail">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="#" class="btn-action text-primary" title="Kelola Pengurus">
                        <i class="bi bi-people"></i>
                      </a>
                      <a href="#" class="btn-action text-secondary" title="Edit">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="#" class="btn-action text-danger" title="Hapus">
                        <i class="bi bi-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>

                <!-- Row 5: UKM Olahraga -->
                <tr>
                  <td class="py-3">
                    <div>
                      <a href="#" class="fw-600 text-primary text-decoration-none">
                        UKM Olahraga
                      </a>
                    </div>
                    <small class="text-secondary">15 Pengurus</small>
                  </td>
                  <td class="py-3">
                    <span>UKM</span>
                  </td>
                  <td class="py-3">
                    <span class="badge bg-secondary">Nonaktif</span>
                  </td>
                  <td class="py-3">
                    <span>-</span>
                  </td>
                  <td class="py-3">
                    <span class="text-secondary">-</span>
                  </td>
                  <td class="py-3 text-center">
                    <div class="actions-cell">
                      <a href="#" class="btn-action text-primary" title="Lihat Detail">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="#" class="btn-action text-primary" title="Kelola Pengurus">
                        <i class="bi bi-people"></i>
                      </a>
                      <a href="#" class="btn-action text-secondary" title="Edit">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="#" class="btn-action text-danger" title="Hapus">
                        <i class="bi bi-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>

                <!-- Row 6: MPO -->
                <tr>
                  <td class="py-3">
                    <div>
                      <a href="#" class="fw-600 text-primary text-decoration-none">
                        MPO UNKLAB
                      </a>
                    </div>
                    <small class="text-secondary">6 Pengurus</small>
                  </td>
                  <td class="py-3">
                    <span>MPO</span>
                  </td>
                  <td class="py-3">
                    <span class="badge bg-success">Aktif</span>
                  </td>
                  <td class="py-3">
                    <span>Rinto Harahap</span>
                  </td>
                  <td class="py-3">
                    <a href="#" class="text-primary text-decoration-none">
                      <i class="bi bi-file-pdf me-2"></i> SK-MPO-2024.pdf
                    </a>
                  </td>
                  <td class="py-3 text-center">
                    <div class="actions-cell">
                      <a href="#" class="btn-action text-primary" title="Lihat Detail">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="#" class="btn-action text-primary" title="Kelola Pengurus">
                        <i class="bi bi-people"></i>
                      </a>
                      <a href="#" class="btn-action text-secondary" title="Edit">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <a href="#" class="btn-action text-danger" title="Hapus">
                        <i class="bi bi-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>

              </tbody>
            </table>
          </div>
          <!-- Empty State (hidden by default) -->
          <div id="emptyState" class="text-center py-5 d-none">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
            <p class="text-secondary mt-3">Tidak ada organisasi yang sesuai dengan filter</p>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- ===== MODAL: TAMBAH ORGANISASI (placeholder) ===== -->
<div class="modal fade" id="tambahOrganisasiModal" tabindex="-1" aria-labelledby="tambahOrganisasiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahOrganisasiModalLabel">Tambah Organisasi Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-secondary">Form tambah organisasi akan ditampilkan di sini</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary">Simpan Organisasi</button>
      </div>
    </div>
  </div>
</div>

<!-- ===== CUSTOM STYLES ===== -->
<style>
  /* ===== COLOR PALETTE ===== */
  :root {
    --primary-dark: #5f3a74;
    --primary-light: #7a5a94;
    --border-color: #e8eaed;
    --bg-light: #fafbfc;
    --text-primary: #202124;
    --text-secondary: #5f6368;
  }

  /* ===== PAGE HEADER ===== */
  .page-header {
    background: linear-gradient(135deg, #5f3a74 0%, #7a5a94 100%);
    color: white;
    padding: 2.5rem 0;
    margin-bottom: 2.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  }

  .page-header h1 {
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: -0.5px;
  }

  .page-header .text-white-50 {
    font-weight: 400;
    font-size: 1rem;
    opacity: 0.85;
  }

  /* ===== CARD STYLING ===== */
  .card {
    border: 1px solid var(--border-color) !important;
    border-radius: 10px !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
    transition: all 0.3s ease;
  }

  .card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
  }

  .card-header {
    background-color: var(--bg-light) !important;
    border-bottom: 1px solid var(--border-color) !important;
    padding: 1.5rem !important;
  }

  .card-header .card-title {
    color: var(--text-primary);
    font-weight: 700;
    font-size: 1.15rem;
    letter-spacing: -0.3px;
  }

  .card-body {
    padding: 1.5rem !important;
  }

  /* ===== FILTER SECTION ===== */
  .form-label {
    margin-bottom: 0.6rem;
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text-primary);
    letter-spacing: 0.2px;
  }

  .form-control,
  .form-select {
    border: 1px solid var(--border-color) !important;
    border-radius: 8px !important;
    font-size: 0.95rem;
    padding: 0.7rem 0.95rem !important;
    background-color: #fff !important;
    color: var(--text-primary);
    transition: all 0.2s ease;
  }

  .form-control::placeholder {
    color: #9aa0a6;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: var(--primary-dark) !important;
    box-shadow: 0 0 0 0.2rem rgba(95, 58, 116, 0.12) !important;
  }

  /* ===== BUTTON STYLING ===== */
  .btn-primary {
    background-color: var(--primary-dark);
    border-color: var(--primary-dark);
    font-weight: 600;
    font-size: 0.95rem;
    border-radius: 8px;
    padding: 0.7rem 1.25rem;
    letter-spacing: 0.3px;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(95, 58, 116, 0.15);
  }

  .btn-primary:hover {
    background-color: #4a2e5e;
    border-color: #4a2e5e;
    box-shadow: 0 4px 8px rgba(95, 58, 116, 0.25);
    transform: translateY(-1px);
  }

  .btn-secondary {
    font-weight: 600;
    border-radius: 8px;
  }

  /* ===== TABLE STYLING ===== */
  .table {
    margin-bottom: 0;
    font-size: 0.95rem;
  }

  .table thead th {
    padding: 1.3rem 1.2rem;
    font-weight: 700;
    font-size: 0.88rem;
    text-transform: uppercase;
    color: var(--text-secondary);
    background-color: var(--bg-light);
    border-color: var(--border-color);
    letter-spacing: 0.6px;
    vertical-align: middle;
  }

  .table tbody td {
    padding: 1.3rem 1.2rem;
    vertical-align: middle;
    border-color: var(--border-color);
    color: var(--text-primary);
    line-height: 1.5;
  }

  .table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid var(--border-color);
  }

  .table tbody tr:hover {
    background-color: var(--bg-light);
    box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.02);
  }

  .table tbody tr:last-child {
    border-bottom: 1px solid var(--border-color);
  }

  /* ===== TABLE TEXT STYLING ===== */
  .table a.fw-600 {
    color: var(--primary-dark);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s ease;
  }

  .table a.fw-600:hover {
    color: var(--primary-light);
    text-decoration: underline;
  }

  .table tbody small {
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--text-secondary);
    display: block;
    margin-top: 0.4rem;
  }

  .table a {
    color: var(--primary-dark);
    text-decoration: none;
    font-weight: 500;
  }

  .table a:hover {
    color: var(--primary-light);
    text-decoration: underline;
  }

  /* ===== ACTION BUTTONS ===== */
  .btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 7px;
    transition: all 0.2s ease;
    cursor: pointer;
    font-size: 1.05rem;
    text-decoration: none;
    margin: 0 2px;
  }

  .btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
  }

  .btn-action.text-primary {
    color: var(--primary-dark);
  }

  .btn-action.text-primary:hover {
    background-color: rgba(95, 58, 116, 0.12);
    color: var(--primary-light);
  }

  .btn-action.text-secondary {
    color: var(--text-secondary);
  }

  .btn-action.text-secondary:hover {
    background-color: rgba(95, 113, 154, 0.12);
    color: var(--primary-dark);
  }

  .btn-action.text-danger {
    color: #dc3545;
  }

  .btn-action.text-danger:hover {
    background-color: rgba(220, 53, 69, 0.12);
    color: #bd2130;
  }

  /* Action column container */
  .actions-cell {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0;
    white-space: nowrap;
  }

  /* ===== BADGE STYLING ===== */
  .badge {
    font-weight: 600;
    padding: 0.6rem 0.9rem;
    border-radius: 6px;
    font-size: 0.85rem;
    letter-spacing: 0.3px;
    text-transform: capitalize;
  }

  .badge.bg-success {
    background-color: #10b981 !important;
    color: white;
  }

  .badge.bg-secondary {
    background-color: #6c757d !important;
    color: white;
  }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 768px) {
    .page-header {
      padding: 1.8rem 0;
      margin-bottom: 2rem;
    }

    .page-header h1 {
      font-size: 1.5rem;
    }

    .card-header,
    .card-body {
      padding: 1.2rem !important;
    }

    .table {
      font-size: 0.9rem;
    }

    .table thead th {
      padding: 1rem 0.85rem;
      font-size: 0.8rem;
    }

    .table tbody td {
      padding: 1rem 0.85rem;
      font-size: 0.9rem;
    }

    .btn-action {
      width: 34px;
      height: 34px;
      font-size: 0.95rem;
    }

    .btn-action.ms-2 {
      margin-left: 0.4rem !important;
    }
  }

  @media (max-width: 992px) {
    .row.g-3 {
      margin-bottom: 1.5rem;
    }

    .col-md-4,
    .col-md-3,
    .col-md-1 {
      flex: 0 0 100%;
      max-width: 100%;
      margin-bottom: 1rem;
    }

    .btn.w-100 {
      width: 100% !important;
    }

    .text-end {
      text-align: left !important;
    }
  }

  /* ===== MODAL ===== */
  .modal-content {
    border: 1px solid var(--border-color) !important;
    border-radius: 10px !important;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15) !important;
  }

  .modal-header {
    border-bottom: 1px solid var(--border-color) !important;
    background-color: var(--bg-light);
  }

  .modal-header .modal-title {
    font-weight: 700;
    color: var(--text-primary);
  }

  /* ===== EMPTY STATE ===== */
  #emptyState {
    color: var(--text-secondary);
  }

  #emptyState i {
    color: #d0d5dd;
  }

  /* ===== UTILITIES ===== */
  .fw-600 {
    font-weight: 600;
  }

  .text-secondary {
    color: var(--text-secondary) !important;
  }
</style>

<!-- ===== FILTER & SEARCH SCRIPT ===== -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchOrganisasi');
    const filterKategori = document.getElementById('filterKategori');
    const filterStatus = document.getElementById('filterStatus');
    const tableRows = document.querySelectorAll('#tabelOrganisasi tbody tr');
    const emptyState = document.getElementById('emptyState');
    const rowCount = document.getElementById('rowCount');

    // Combined filter function
    function applyFilters() {
      const searchValue = searchInput.value.toLowerCase().trim();
      const kategoriValue = filterKategori.value.toLowerCase().trim();
      const statusValue = filterStatus.value.toLowerCase().trim();

      let visibleCount = 0;

      tableRows.forEach(row => {
        let matchesSearch = true;
        let matchesKategori = true;
        let matchesStatus = true;

        // Search filter
        if (searchValue) {
          const namaOrg = row.cells[0].textContent.toLowerCase();
          matchesSearch = namaOrg.includes(searchValue);
        }

        // Category filter
        if (kategoriValue) {
          const kategori = row.cells[1].textContent.toLowerCase().trim();
          matchesKategori = kategori === kategoriValue;
        }

        // Status filter
        if (statusValue) {
          const status = row.cells[2].textContent.toLowerCase().trim();
          matchesStatus = status === statusValue;
        }

        if (matchesSearch && matchesKategori && matchesStatus) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });

      // Show/hide empty state & update count
      if (visibleCount === 0) {
        emptyState.classList.remove('d-none');
        rowCount.textContent = 'Tidak ada data';
      } else {
        emptyState.classList.add('d-none');
        const plural = visibleCount === 1 ? 'organisasi' : 'organisasi';
        rowCount.textContent = `${visibleCount} ${plural} ditemukan`;
      }
    }

    // Event listeners
    searchInput.addEventListener('input', applyFilters);
    filterKategori.addEventListener('change', applyFilters);
    filterStatus.addEventListener('change', applyFilters);
  });
</script>

@endsection
