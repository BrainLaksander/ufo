@extends('layouts.kemahasiswaan')

@section('title', 'Manajemen Pengajuan & Laporan - Kemahasiswaan')

@section('content')

<!-- ===== PAGE HEADER ===== -->
<div class="page-header">
  <div class="container-fluid">
    <h1 class="mb-2">Manajemen Pengajuan Kegiatan & Laporan</h1>
    <p class="text-white-50 mb-0">Kelola pengajuan kegiatan dan laporan pertanggungjawaban (LPJ) organisasi mahasiswa</p>
  </div>
</div>

<div class="container-fluid py-4">

  <!-- ===== TAB NAVIGATION ===== -->
  <div class="row mb-4">
    <div class="col-12">
      <ul class="nav nav-tabs nav-tabs-custom" id="pengajuanTabs">
        <li class="nav-item">
          <a class="nav-link active" href="#pengajuanKegiatan" data-tab="pengajuan" role="tab">
            <i class="bi bi-file-earmark-check me-2"></i>Pengajuan Kegiatan
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#laporanKegiatan" data-tab="laporan" role="tab">
            <i class="bi bi-file-earmark-text me-2"></i>Laporan Kegiatan (LPJ)
          </a>
        </li>
      </ul>
    </div>
  </div>

  <!-- ===== TAB 1: PENGAJUAN KEGIATAN ===== -->
  <div id="pengajuanKegiatan" class="tab-content-section active">
    
    <!-- Filter & Search -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header">
            <h6 class="mb-0" style="font-weight: 600; color: #202124;">Filter & Pencarian</h6>
          </div>
          <div class="card-body">
            <div class="row align-items-end g-3">
              
              <!-- Search Input -->
              <div class="col-md-6">
                <label class="form-label">Cari Kegiatan atau Organisasi</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="searchPengajuan"
                  placeholder="Masukkan nama kegiatan atau organisasi..."
                >
              </div>

              <!-- Status Filter -->
              <div class="col-md-4">
                <label class="form-label">Status</label>
                <select class="form-select" id="filterStatusPengajuan">
                  <option value="">Semua Status</option>
                  <option value="Diajukan">Diajukan</option>
                  <option value="Sedang Direview">Sedang Direview</option>
                  <option value="Disetujui">Disetujui</option>
                  <option value="Ditolak">Ditolak</option>
                </select>
              </div>

              <!-- Action Button -->
              <div class="col-md-2 text-end">
                <button class="btn btn-primary w-100">
                  <i class="bi bi-download me-2"></i>Export
                </button>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel Pengajuan -->
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Pengajuan Kegiatan</h5>
            <small class="text-secondary" id="countPengajuan">15 pengajuan ditemukan</small>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0" id="tabelPengajuan">
                <thead class="table-light">
                  <tr>
                    <th style="width: 28%;" class="border-0">Judul Kegiatan</th>
                    <th style="width: 18%;" class="border-0">Organisasi</th>
                    <th style="width: 18%;" class="border-0">Tanggal Kegiatan</th>
                    <th style="width: 15%;" class="border-0">Status</th>
                    <th style="width: 21%;" class="border-0 text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  
                  <!-- Row 1 -->
                  <tr>
                    <td class="py-3">
                      <div>
                        <a href="#" class="fw-600 text-primary text-decoration-none">Seminar Kepemimpinan 2026</a>
                      </div>
                      <small class="text-secondary">Seminar</small>
                    </td>
                    <td class="py-3">BEM UNKLAB</td>
                    <td class="py-3">
                      <i class="bi bi-calendar3 me-2"></i>15 Feb 2026
                    </td>
                    <td class="py-3">
                      <span class="badge bg-success">Disetujui</span>
                    </td>
                    <td class="py-3 text-center">
                      <div class="actions-cell">
                        <a href="#" class="btn-action text-primary" title="Lihat Detail">
                          <i class="bi bi-eye"></i>
                        </a>
                        <a href="#" class="btn-action text-primary" title="Catatan">
                          <i class="bi bi-chat-dots"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                  <!-- Row 2 -->
                  <tr>
                    <td class="py-3">
                      <div>
                        <a href="#" class="fw-600 text-primary text-decoration-none">Workshop Fotografi Digital</a>
                      </div>
                      <small class="text-secondary">Workshop</small>
                    </td>
                    <td class="py-3">UKM Seni</td>
                    <td class="py-3">
                      <i class="bi bi-calendar3 me-2"></i>22 Feb 2026
                    </td>
                    <td class="py-3">
                      <span class="badge bg-warning text-dark">Sedang Direview</span>
                    </td>
                    <td class="py-3 text-center">
                      <div class="actions-cell">
                        <a href="#" class="btn-action text-primary" title="Lihat Detail">
                          <i class="bi bi-eye"></i>
                        </a>
                        <a href="#" class="btn-action text-success" title="Setujui">
                          <i class="bi bi-check-circle"></i>
                        </a>
                        <a href="#" class="btn-action text-danger" title="Tolak">
                          <i class="bi bi-x-circle"></i>
                        </a>
                        <a href="#" class="btn-action text-primary" title="Catatan">
                          <i class="bi bi-chat-dots"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                  <!-- Row 3 -->
                  <tr>
                    <td class="py-3">
                      <div>
                        <a href="#" class="fw-600 text-primary text-decoration-none">Festival Musik Campus 2026</a>
                      </div>
                      <small class="text-secondary">Festival</small>
                    </td>
                    <td class="py-3">HMTI</td>
                    <td class="py-3">
                      <i class="bi bi-calendar3 me-2"></i>01 Mar 2026
                    </td>
                    <td class="py-3">
                      <span class="badge bg-info text-dark">Diajukan</span>
                    </td>
                    <td class="py-3 text-center">
                      <div class="actions-cell">
                        <a href="#" class="btn-action text-primary" title="Lihat Detail">
                          <i class="bi bi-eye"></i>
                        </a>
                        <a href="#" class="btn-action text-success" title="Setujui">
                          <i class="bi bi-check-circle"></i>
                        </a>
                        <a href="#" class="btn-action text-danger" title="Tolak">
                          <i class="bi bi-x-circle"></i>
                        </a>
                        <a href="#" class="btn-action text-primary" title="Catatan">
                          <i class="bi bi-chat-dots"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                  <!-- Row 4 -->
                  <tr>
                    <td class="py-3">
                      <div>
                        <a href="#" class="fw-600 text-primary text-decoration-none">Gathering Mahasiswa Fisika</a>
                      </div>
                      <small class="text-secondary">Gathering</small>
                    </td>
                    <td class="py-3">HMDP</td>
                    <td class="py-3">
                      <i class="bi bi-calendar3 me-2"></i>08 Mar 2026
                    </td>
                    <td class="py-3">
                      <span class="badge bg-danger">Ditolak</span>
                    </td>
                    <td class="py-3 text-center">
                      <div class="actions-cell">
                        <a href="#" class="btn-action text-primary" title="Lihat Detail">
                          <i class="bi bi-eye"></i>
                        </a>
                        <a href="#" class="btn-action text-primary" title="Catatan">
                          <i class="bi bi-chat-dots"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                  <!-- Row 5 -->
                  <tr>
                    <td class="py-3">
                      <div>
                        <a href="#" class="fw-600 text-primary text-decoration-none">Pelatihan Kepemimpinan Pengurus</a>
                      </div>
                      <small class="text-secondary">Pelatihan</small>
                    </td>
                    <td class="py-3">BEM UNKLAB</td>
                    <td class="py-3">
                      <i class="bi bi-calendar3 me-2"></i>10 Mar 2026
                    </td>
                    <td class="py-3">
                      <span class="badge bg-success">Disetujui</span>
                    </td>
                    <td class="py-3 text-center">
                      <div class="actions-cell">
                        <a href="#" class="btn-action text-primary" title="Lihat Detail">
                          <i class="bi bi-eye"></i>
                        </a>
                        <a href="#" class="btn-action text-primary" title="Catatan">
                          <i class="bi bi-chat-dots"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>
            <!-- Empty State -->
            <div id="emptyPengajuan" class="text-center py-5 d-none">
              <i class="bi bi-inbox" style="font-size: 3rem; color: #d0d5dd;"></i>
              <p class="text-secondary mt-3">Tidak ada pengajuan kegiatan yang sesuai dengan filter</p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- ===== TAB 2: LAPORAN KEGIATAN (LPJ) ===== -->
  <div id="laporanKegiatan" class="tab-content-section" style="display: none;">
    
    <!-- Summary Cards -->
    <div class="row mb-4">
      <!-- Card 1: Total LPJ -->
      <div class="col-md-4 mb-3">
        <div class="card stat-card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex align-items-start justify-content-between">
              <div>
                <p class="card-text text-secondary small mb-2">Total LPJ Masuk</p>
                <h2 class="stat-number text-primary fw-bold mb-0">8</h2>
              </div>
              <div class="stat-icon bg-primary text-white rounded-3">
                <i class="bi bi-file-earmark-text"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2: Menunggu Review -->
      <div class="col-md-4 mb-3">
        <div class="card stat-card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex align-items-start justify-content-between">
              <div>
                <p class="card-text text-secondary small mb-2">Menunggu Review</p>
                <h2 class="stat-number text-warning fw-bold mb-0">3</h2>
              </div>
              <div class="stat-icon bg-warning text-dark rounded-3">
                <i class="bi bi-hourglass-split"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 3: Disetujui -->
      <div class="col-md-4 mb-3">
        <div class="card stat-card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex align-items-start justify-content-between">
              <div>
                <p class="card-text text-secondary small mb-2">Disetujui</p>
                <h2 class="stat-number text-success fw-bold mb-0">5</h2>
              </div>
              <div class="stat-icon bg-success text-white rounded-3">
                <i class="bi bi-check-circle"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter & Search LPJ -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header">
            <h6 class="mb-0" style="font-weight: 600; color: #202124;">Filter & Pencarian</h6>
          </div>
          <div class="card-body">
            <div class="row align-items-end g-3">
              
              <!-- Search Input -->
              <div class="col-md-6">
                <label class="form-label">Cari Laporan atau Organisasi</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="searchLaporan"
                  placeholder="Masukkan nama laporan atau organisasi..."
                >
              </div>

              <!-- Status Filter -->
              <div class="col-md-4">
                <label class="form-label">Status</label>
                <select class="form-select" id="filterStatusLaporan">
                  <option value="">Semua Status</option>
                  <option value="Menunggu Review">Menunggu Review</option>
                  <option value="Disetujui">Disetujui</option>
                  <option value="Revisi">Revisi</option>
                </select>
              </div>

              <!-- Action Button -->
              <div class="col-md-2 text-end">
                <button class="btn btn-primary w-100">
                  <i class="bi bi-download me-2"></i>Export
                </button>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel LPJ -->
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Laporan Kegiatan (LPJ)</h5>
            <small class="text-secondary" id="countLaporan">8 laporan ditemukan</small>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0" id="tabelLaporan">
                <thead class="table-light">
                  <tr>
                    <th style="width: 26%;" class="border-0">Judul Laporan</th>
                    <th style="width: 16%;" class="border-0">Organisasi</th>
                    <th style="width: 14%;" class="border-0">Tanggal Submit</th>
                    <th style="width: 18%;" class="border-0">Realisasi Anggaran</th>
                    <th style="width: 12%;" class="border-0">Status</th>
                    <th style="width: 14%;" class="border-0 text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  
                  <!-- Row 1 -->
                  <tr>
                    <td class="py-3">
                      <div>
                        <a href="#" class="fw-600 text-primary text-decoration-none">LPJ Seminar Kepemimpinan</a>
                      </div>
                      <small class="text-secondary">Seminar</small>
                    </td>
                    <td class="py-3">BEM UNKLAB</td>
                    <td class="py-3">20 Feb 2026</td>
                    <td class="py-3">
                      <div>Rp 5.500.000</div>
                      <small class="text-secondary">dari Rp 5.500.000</small>
                    </td>
                    <td class="py-3">
                      <span class="badge bg-success">Disetujui</span>
                    </td>
                    <td class="py-3 text-center">
                      <div class="actions-cell">
                        <a href="#" class="btn-action text-primary" title="Lihat">
                          <i class="bi bi-eye"></i>
                        </a>
                        <a href="#" class="btn-action text-primary" title="Catatan">
                          <i class="bi bi-chat-dots"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                  <!-- Row 2 -->
                  <tr>
                    <td class="py-3">
                      <div>
                        <a href="#" class="fw-600 text-primary text-decoration-none">LPJ Workshop Fotografi Digital</a>
                      </div>
                      <small class="text-secondary">Workshop</small>
                    </td>
                    <td class="py-3">UKM Seni</td>
                    <td class="py-3">25 Feb 2026</td>
                    <td class="py-3">
                      <div>Rp 3.200.000</div>
                      <small class="text-secondary">dari Rp 3.500.000</small>
                    </td>
                    <td class="py-3">
                      <span class="badge bg-danger">Revisi</span>
                    </td>
                    <td class="py-3 text-center">
                      <div class="actions-cell">
                        <a href="#" class="btn-action text-primary" title="Lihat">
                          <i class="bi bi-eye"></i>
                        </a>
                        <a href="#" class="btn-action text-success" title="Setujui">
                          <i class="bi bi-check-circle"></i>
                        </a>
                        <a href="#" class="btn-action text-primary" title="Catatan">
                          <i class="bi bi-chat-dots"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                  <!-- Row 3 -->
                  <tr>
                    <td class="py-3">
                      <div>
                        <a href="#" class="fw-600 text-primary text-decoration-none">LPJ Festival Musik Campus</a>
                      </div>
                      <small class="text-secondary">Festival</small>
                    </td>
                    <td class="py-3">HMTI</td>
                    <td class="py-3">05 Mar 2026</td>
                    <td class="py-3">
                      <div>Rp 12.750.000</div>
                      <small class="text-secondary">dari Rp 12.750.000</small>
                    </td>
                    <td class="py-3">
                      <span class="badge bg-warning text-dark">Menunggu Review</span>
                    </td>
                    <td class="py-3 text-center">
                      <div class="actions-cell">
                        <a href="#" class="btn-action text-primary" title="Lihat">
                          <i class="bi bi-eye"></i>
                        </a>
                        <a href="#" class="btn-action text-success" title="Setujui">
                          <i class="bi bi-check-circle"></i>
                        </a>
                        <a href="#" class="btn-action text-danger" title="Tolak">
                          <i class="bi bi-x-circle"></i>
                        </a>
                        <a href="#" class="btn-action text-primary" title="Catatan">
                          <i class="bi bi-chat-dots"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                  <!-- Row 4 -->
                  <tr>
                    <td class="py-3">
                      <div>
                        <a href="#" class="fw-600 text-primary text-decoration-none">LPJ Pelatihan Pengurus</a>
                      </div>
                      <small class="text-secondary">Pelatihan</small>
                    </td>
                    <td class="py-3">BEM UNKLAB</td>
                    <td class="py-3">12 Mar 2026</td>
                    <td class="py-3">
                      <div>Rp 2.100.000</div>
                      <small class="text-secondary">dari Rp 2.100.000</small>
                    </td>
                    <td class="py-3">
                      <span class="badge bg-success">Disetujui</span>
                    </td>
                    <td class="py-3 text-center">
                      <div class="actions-cell">
                        <a href="#" class="btn-action text-primary" title="Lihat">
                          <i class="bi bi-eye"></i>
                        </a>
                        <a href="#" class="btn-action text-primary" title="Catatan">
                          <i class="bi bi-chat-dots"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>
            <!-- Empty State -->
            <div id="emptyLaporan" class="text-center py-5 d-none">
              <i class="bi bi-inbox" style="font-size: 3rem; color: #d0d5dd;"></i>
              <p class="text-secondary mt-3">Tidak ada laporan kegiatan yang sesuai dengan filter</p>
            </div>
          </div>
        </div>
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

  /* ===== TAB STYLING ===== */
  .nav-tabs-custom {
    border-bottom: 2px solid var(--border-color);
    gap: 0;
  }

  .nav-tabs-custom .nav-link {
    color: var(--text-secondary);
    border: none;
    border-bottom: 3px solid transparent;
    padding: 1rem 1.5rem;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    margin-bottom: -2px;
  }

  .nav-tabs-custom .nav-link:hover {
    color: var(--primary-dark);
    border-bottom-color: rgba(95, 58, 116, 0.3);
  }

  .nav-tabs-custom .nav-link.active {
    color: var(--primary-dark);
    border-bottom-color: var(--primary-dark);
    background-color: transparent;
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

  /* ===== STAT CARDS ===== */
  .stat-card {
    transition: all 0.3s ease;
    border-radius: 10px;
  }

  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12) !important;
  }

  .stat-number {
    font-size: 2.2rem;
    line-height: 1;
  }

  .stat-icon {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    border-radius: 12px;
    flex-shrink: 0;
  }

  /* ===== FORM STYLING ===== */
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

  .table a {
    color: var(--primary-dark);
    text-decoration: none;
    font-weight: 500;
  }

  .table a:hover {
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

  /* ===== ACTION BUTTONS ===== */
  .actions-cell {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0;
    white-space: nowrap;
  }

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

  .btn-action.text-success {
    color: #10b981;
  }

  .btn-action.text-success:hover {
    background-color: rgba(16, 185, 129, 0.12);
    color: #059669;
  }

  .btn-action.text-danger {
    color: #dc3545;
  }

  .btn-action.text-danger:hover {
    background-color: rgba(220, 53, 69, 0.12);
    color: #bd2130;
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

  .badge.bg-danger {
    background-color: #dc3545 !important;
    color: white;
  }

  .badge.bg-warning {
    background-color: #f59e0b !important;
    color: #1f2937;
  }

  .badge.bg-info {
    background-color: #0ea5e9 !important;
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

    .nav-tabs-custom .nav-link {
      padding: 0.8rem 1rem;
      font-size: 0.85rem;
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
  }

  @media (max-width: 992px) {
    .row.g-3 {
      margin-bottom: 1.5rem;
    }

    .col-md-4,
    .col-md-6 {
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
</style>

<!-- ===== JAVASCRIPT: TAB & FILTER LOGIC ===== -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    
    // ===== TAB NAVIGATION =====
    const tabLinks = document.querySelectorAll('.nav-tabs-custom .nav-link');
    const tabSections = document.querySelectorAll('.tab-content-section');

    tabLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all
        tabLinks.forEach(l => l.classList.remove('active'));
        tabSections.forEach(s => s.style.display = 'none');
        
        // Add active to clicked
        this.classList.add('active');
        const tabId = this.getAttribute('href');
        document.querySelector(tabId).style.display = 'block';
      });
    });

    // ===== PENGAJUAN KEGIATAN FILTER =====
    const searchPengajuan = document.getElementById('searchPengajuan');
    const filterStatusPengajuan = document.getElementById('filterStatusPengajuan');
    const tabelPengajuan = document.getElementById('tabelPengajuan');
    const rowsPengajuan = tabelPengajuan.querySelectorAll('tbody tr');
    const emptyPengajuan = document.getElementById('emptyPengajuan');
    const countPengajuan = document.getElementById('countPengajuan');

    function filterPengajuan() {
      const searchValue = searchPengajuan.value.toLowerCase().trim();
      const statusValue = filterStatusPengajuan.value.toLowerCase().trim();
      let visibleCount = 0;

      rowsPengajuan.forEach(row => {
        let matchesSearch = true;
        let matchesStatus = true;

        if (searchValue) {
          const judul = row.cells[0].textContent.toLowerCase();
          const organisasi = row.cells[1].textContent.toLowerCase();
          matchesSearch = judul.includes(searchValue) || organisasi.includes(searchValue);
        }

        if (statusValue) {
          const status = row.cells[3].textContent.toLowerCase().trim();
          matchesStatus = status.includes(statusValue);
        }

        if (matchesSearch && matchesStatus) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });

      if (visibleCount === 0) {
        emptyPengajuan.classList.remove('d-none');
        countPengajuan.textContent = 'Tidak ada data';
      } else {
        emptyPengajuan.classList.add('d-none');
        countPengajuan.textContent = `${visibleCount} pengajuan ditemukan`;
      }
    }

    searchPengajuan.addEventListener('input', filterPengajuan);
    filterStatusPengajuan.addEventListener('change', filterPengajuan);

    // ===== LAPORAN KEGIATAN FILTER =====
    const searchLaporan = document.getElementById('searchLaporan');
    const filterStatusLaporan = document.getElementById('filterStatusLaporan');
    const tabelLaporan = document.getElementById('tabelLaporan');
    const rowsLaporan = tabelLaporan.querySelectorAll('tbody tr');
    const emptyLaporan = document.getElementById('emptyLaporan');
    const countLaporan = document.getElementById('countLaporan');

    function filterLaporan() {
      const searchValue = searchLaporan.value.toLowerCase().trim();
      const statusValue = filterStatusLaporan.value.toLowerCase().trim();
      let visibleCount = 0;

      rowsLaporan.forEach(row => {
        let matchesSearch = true;
        let matchesStatus = true;

        if (searchValue) {
          const judul = row.cells[0].textContent.toLowerCase();
          const organisasi = row.cells[1].textContent.toLowerCase();
          matchesSearch = judul.includes(searchValue) || organisasi.includes(searchValue);
        }

        if (statusValue) {
          const status = row.cells[4].textContent.toLowerCase().trim();
          matchesStatus = status.includes(statusValue);
        }

        if (matchesSearch && matchesStatus) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });

      if (visibleCount === 0) {
        emptyLaporan.classList.remove('d-none');
        countLaporan.textContent = 'Tidak ada data';
      } else {
        emptyLaporan.classList.add('d-none');
        countLaporan.textContent = `${visibleCount} laporan ditemukan`;
      }
    }

    searchLaporan.addEventListener('input', filterLaporan);
    filterStatusLaporan.addEventListener('change', filterLaporan);
  });
</script>

