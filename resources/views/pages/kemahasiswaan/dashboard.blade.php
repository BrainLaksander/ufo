@extends('layouts.kemahasiswaan')

@section('title', 'Dashboard Kemahasiswaan - UFO')

@section('content')
<div class="page-header">
  <div class="container-fluid">
    <h1 class="mb-2">Dashboard</h1>
    <p class="text-white-50 mb-0">Sistem Administrasi &amp; Kontrol Organisasi Mahasiswa</p>
  </div>
</div>

<div class="container-fluid py-4">
  
  <!-- ===== STATISTICS CARDS SECTION ===== -->
  <div class="row mb-4">
    <!-- Card 1: Total Organisasi Aktif -->
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="card stat-card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="flex-grow-1">
              <p class="card-text text-secondary small mb-2">Total Organisasi Aktif</p>
              <h2 class="stat-number text-primary fw-bold mb-0">24</h2>
            </div>
            <div class="stat-icon bg-primary text-white rounded-3">
              <i class="bi bi-building"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 2: Total Kegiatan Berjalan -->
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="card stat-card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="flex-grow-1">
              <p class="card-text text-secondary small mb-2">Total Kegiatan Berjalan</p>
              <h2 class="stat-number text-primary fw-bold mb-0">12</h2>
            </div>
            <div class="stat-icon bg-primary text-white rounded-3">
              <i class="bi bi-calendar-event"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 3: Total Kegiatan Menunggu Persetujuan -->
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="card stat-card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="flex-grow-1">
              <p class="card-text text-secondary small mb-2">Kegiatan Menunggu Persetujuan</p>
              <h2 class="stat-number text-warning fw-bold mb-0">8</h2>
            </div>
            <div class="stat-icon bg-warning text-dark rounded-3">
              <i class="bi bi-hourglass-split"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Card 4: Total Laporan Belum Direview -->
    <div class="col-md-6 col-lg-3 mb-3">
      <div class="card stat-card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="flex-grow-1">
              <p class="card-text text-secondary small mb-2">Laporan Belum Direview</p>
              <h2 class="stat-number text-primary fw-bold mb-0">5</h2>
            </div>
            <div class="stat-icon bg-primary text-white rounded-3">
              <i class="bi bi-file-earmark-text"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== MAIN CONTENT ROW ===== -->
  <div class="row">
    
    <!-- LEFT COLUMN: CHART -->
    <div class="col-lg-8 mb-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
          <h5 class="card-title mb-0">Grafik Kegiatan Bulanan 2024</h5>
        </div>
        <div class="card-body" style="min-height: 320px;">
          <!-- Custom Chart Container -->
          <div id="chart-container" class="position-relative" style="height: 280px;">
            <div class="chart-wrapper">
              <div class="chart-y-axis">
                <div class="chart-y-label">20</div>
                <div class="chart-y-label">15</div>
                <div class="chart-y-label">10</div>
                <div class="chart-y-label">5</div>
                <div class="chart-y-label">0</div>
              </div>
              <div class="chart-bars">
                <div class="chart-bar-group">
                  <div class="chart-bar" style="height: 65%;" data-value="13" data-month="Jan"></div>
                  <div class="chart-x-label">Jan</div>
                </div>
                <div class="chart-bar-group">
                  <div class="chart-bar" style="height: 80%;" data-value="16" data-month="Feb"></div>
                  <div class="chart-x-label">Feb</div>
                </div>
                <div class="chart-bar-group">
                  <div class="chart-bar" style="height: 45%;" data-value="9" data-month="Mar"></div>
                  <div class="chart-x-label">Mar</div>
                </div>
                <div class="chart-bar-group">
                  <div class="chart-bar" style="height: 70%;" data-value="14" data-month="Apr"></div>
                  <div class="chart-x-label">Apr</div>
                </div>
                <div class="chart-bar-group">
                  <div class="chart-bar" style="height: 55%;" data-value="11" data-month="May"></div>
                  <div class="chart-x-label">May</div>
                </div>
                <div class="chart-bar-group">
                  <div class="chart-bar" style="height: 75%;" data-value="15" data-month="Jun"></div>
                  <div class="chart-x-label">Jun</div>
                </div>
                <div class="chart-bar-group">
                  <div class="chart-bar" style="height: 50%;" data-value="10" data-month="Jul"></div>
                  <div class="chart-x-label">Jul</div>
                </div>
                <div class="chart-bar-group">
                  <div class="chart-bar" style="height: 85%;" data-value="17" data-month="Aug"></div>
                  <div class="chart-x-label">Aug</div>
                </div>
                <div class="chart-bar-group">
                  <div class="chart-bar" style="height: 60%;" data-value="12" data-month="Sep"></div>
                  <div class="chart-x-label">Sep</div>
                </div>
                <div class="chart-bar-group">
                  <div class="chart-bar" style="height: 72%;" data-value="14.4" data-month="Oct"></div>
                  <div class="chart-x-label">Oct</div>
                </div>
                <div class="chart-bar-group">
                  <div class="chart-bar" style="height: 68%;" data-value="13.6" data-month="Nov"></div>
                  <div class="chart-x-label">Nov</div>
                </div>
                <div class="chart-bar-group">
                  <div class="chart-bar" style="height: 78%;" data-value="15.6" data-month="Dec"></div>
                  <div class="chart-x-label">Dec</div>
                </div>
              </div>
            </div>
            <!-- Tooltip -->
            <div id="chart-tooltip" class="chart-tooltip d-none">
              <span id="tooltip-month"></span>: <strong id="tooltip-value"></strong>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT COLUMN: QUICK ACTIONS & UPCOMING EVENTS -->
    <div class="col-lg-4 mb-4">
      
      <!-- Quick Actions Card -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
          <h5 class="card-title mb-0">Aksi Cepat</h5>
        </div>
        <div class="card-body">
          <button class="btn btn-primary w-100 mb-3" onclick="location.href='/portal/kemahasiswaan/pengajuan'">
            <i class="bi bi-check-circle me-2"></i> Review Kegiatan
          </button>
          <button class="btn btn-warning w-100" onclick="location.href='/portal/kemahasiswaan/pengumuman'">
            <i class="bi bi-megaphone me-2"></i> Buat Pengumuman
          </button>
        </div>
      </div>

      <!-- Upcoming Events Card -->
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
          <h5 class="card-title mb-0">Kegiatan Mendatang</h5>
        </div>
        <div class="card-body p-0">
          <div class="list-group list-group-flush">
            <a href="#" class="list-group-item list-group-item-action border-0 px-3 py-3">
              <div class="d-flex align-items-start justify-content-between">
                <div>
                  <h6 class="mb-1 text-dark fw-500">Seminar Kepemimpinan</h6>
                  <small class="text-secondary">
                    <i class="bi bi-calendar3 me-1"></i>
                    12 Feb 2026
                  </small>
                </div>
                <span class="badge bg-success rounded-pill">Aktif</span>
              </div>
            </a>
            <a href="#" class="list-group-item list-group-item-action border-0 border-top px-3 py-3">
              <div class="d-flex align-items-start justify-content-between">
                <div>
                  <h6 class="mb-1 text-dark fw-500">Workshop Fotografi</h6>
                  <small class="text-secondary">
                    <i class="bi bi-calendar3 me-1"></i>
                    18 Feb 2026
                  </small>
                </div>
                <span class="badge bg-success rounded-pill">Aktif</span>
              </div>
            </a>
            <a href="#" class="list-group-item list-group-item-action border-0 border-top px-3 py-3">
              <div class="d-flex align-items-start justify-content-between">
                <div>
                  <h6 class="mb-1 text-dark fw-500">Gathering Organisasi</h6>
                  <small class="text-secondary">
                    <i class="bi bi-calendar3 me-1"></i>
                    25 Feb 2026
                  </small>
                </div>
                <span class="badge bg-warning text-dark rounded-pill">Pending</span>
              </div>
            </a>
          </div>
        </div>
      </div>

    </div>

  </div>

  <!-- ===== ANNOUNCEMENTS TABLE SECTION ===== -->
  <div class="row mt-3">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Pengumuman Terbaru</h5>
          <a href="/portal/kemahasiswaan/pengumuman" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th style="width: 50%;" class="border-0">Judul Pengumuman</th>
                  <th style="width: 25%;" class="border-0">Tanggal</th>
                  <th style="width: 25%;" class="border-0 text-center">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="fw-500">Pengumuman Jadwal Gathering Tahun 2026</td>
                  <td><small class="text-secondary">8 Feb 2026</small></td>
                  <td class="text-center">
                    <span class="badge bg-success">Terpublikasi</span>
                  </td>
                </tr>
                <tr>
                  <td class="fw-500">Pembukaan Pendaftaran Organisasi Baru</td>
                  <td><small class="text-secondary">5 Feb 2026</small></td>
                  <td class="text-center">
                    <span class="badge bg-success">Terpublikasi</span>
                  </td>
                </tr>
                <tr>
                  <td class="fw-500">Undangan Rapat Koordinasi Pengurus</td>
                  <td><small class="text-secondary">3 Feb 2026</small></td>
                  <td class="text-center">
                    <span class="badge bg-secondary">Draft</span>
                  </td>
                </tr>
                <tr>
                  <td class="fw-500">Laporan Evaluasi Kegiatan Semester 1</td>
                  <td><small class="text-secondary">1 Feb 2026</small></td>
                  <td class="text-center">
                    <span class="badge bg-success">Terpublikasi</span>
                  </td>
                </tr>
                <tr>
                  <td class="fw-500">Pembaruan Panduan Pengajuan Kegiatan</td>
                  <td><small class="text-secondary">25 Jan 2026</small></td>
                  <td class="text-center">
                    <span class="badge bg-secondary">Draft</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- ===== CUSTOM CSS FOR DASHBOARD ===== -->
<style>
  /* Statistics Cards */
  .stat-card {
    transition: all 0.3s ease;
    border-radius: 12px;
  }
  
  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12) !important;
  }

  .stat-number {
    font-size: 2.5rem;
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

  /* Chart Styling */
  .chart-wrapper {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    height: 100%;
    padding: 20px 0;
  }

  .chart-y-axis {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-end;
    width: 30px;
    height: 100%;
    padding-right: 10px;
    border-right: 2px solid #e9ecef;
    font-size: 0.75rem;
    color: #6c757d;
  }

  .chart-y-label {
    font-weight: 500;
  }

  .chart-bars {
    display: flex;
    justify-content: space-around;
    align-items: flex-end;
    flex: 1;
    height: 100%;
    gap: 4px;
  }

  .chart-bar-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    height: 100%;
    justify-content: flex-end;
  }

  .chart-bar {
    width: 100%;
    background: linear-gradient(180deg, #5f3a74 0%, #7a5a94 100%);
    border-radius: 6px 6px 0 0;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    min-height: 8px;
  }

  .chart-bar:hover {
    background: linear-gradient(180deg, #4a2e5e 0%, #6a4a84 100%);
    filter: brightness(1.1);
  }

  .chart-x-label {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 8px;
    font-weight: 500;
  }

  .chart-tooltip {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: #333;
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.85rem;
    white-space: nowrap;
    z-index: 10;
    pointer-events: none;
  }

  .chart-tooltip::before {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 4px solid #333;
  }

  /* Table Styling */
  .table {
    margin-bottom: 0;
  }

  .table th {
    padding: 1rem;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    color: #495057;
    letter-spacing: 0.5px;
  }

  .table td {
    padding: 1rem;
    vertical-align: middle;
    border-color: #f0f0f0;
  }

  .table tbody tr:hover {
    background-color: #f9f9f9;
  }

  /* Button Tweaks */
  .btn-primary {
    background-color: #5f3a74;
    border-color: #5f3a74;
  }

  .btn-primary:hover {
    background-color: #4a2e5e;
    border-color: #4a2e5e;
  }

  .btn-warning {
    background-color: #ffcc00;
    color: #333;
    border-color: #ffcc00;
  }

  .btn-warning:hover {
    background-color: #e6b800;
    border-color: #e6b800;
    color: #333;
  }

  /* List Group Tweaks */
  .list-group-item {
    padding: 1rem 1.25rem;
    transition: background-color 0.2s ease;
  }

  .list-group-item:hover {
    background-color: #f8f9fa;
  }

  /* Page Header */
  .page-header {
    background: linear-gradient(135deg, #5f3a74 0%, #7a5a94 100%);
    color: white;
    padding: 1.5rem 0;
    margin-bottom: 2rem;
  }

  .page-header h1 {
    font-size: 2rem;
    font-weight: 700;
  }

  @media (max-width: 768px) {
    .stat-number {
      font-size: 2rem;
    }

    .stat-icon {
      width: 48px;
      height: 48px;
      font-size: 1.5rem;
    }

    .chart-bars {
      gap: 2px;
    }
  }
</style>

<!-- ===== CHART INTERACTION SCRIPT ===== -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const chartBars = document.querySelectorAll('.chart-bar');
    const tooltip = document.getElementById('chart-tooltip');
    const tooltipMonth = document.getElementById('tooltip-month');
    const tooltipValue = document.getElementById('tooltip-value');

    chartBars.forEach(bar => {
      bar.addEventListener('mouseenter', function() {
        const value = this.getAttribute('data-value');
        const month = this.getAttribute('data-month');
        
        tooltipMonth.textContent = month;
        tooltipValue.textContent = value + ' kegiatan';
        
        const rect = this.getBoundingClientRect();
        const chartContainer = document.getElementById('chart-container');
        const containerRect = chartContainer.getBoundingClientRect();
        
        tooltip.style.left = (rect.left - containerRect.left + rect.width / 2) + 'px';
        tooltip.style.bottom = (containerRect.bottom - rect.top + 10) + 'px';
        tooltip.classList.remove('d-none');
      });

      bar.addEventListener('mouseleave', function() {
        tooltip.classList.add('d-none');
      });
    });
  });
</script>

@endsection
