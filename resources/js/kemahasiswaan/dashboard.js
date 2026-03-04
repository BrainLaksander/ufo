import Chart from 'chart.js/auto';

// Data (sample) — in production you might fetch via API endpoints
const stats = [
  { label: 'Total Organisasi Aktif', value: 24, color: '--primary-purple' },
  { label: 'Total Kegiatan Berjalan', value: 12, color: '--accent-purple' },
  {
    label: 'Total Kegiatan Menunggu Persetujuan',
    value: 8,
    color: '--accent-yellow',
  },
  {
    label: 'Total Laporan Belum Direview',
    value: 5,
    color: '--primary-purple',
  },
];

const monthly = [
  { label: 'Jan', value: 8 },
  { label: 'Feb', value: 12 },
  { label: 'Mar', value: 15 },
  { label: 'Apr', value: 10 },
  { label: 'Mei', value: 18 },
  { label: 'Jun', value: 14 },
  { label: 'Jul', value: 20 },
  { label: 'Agu', value: 16 },
  { label: 'Sep', value: 22 },
  { label: 'Okt', value: 19 },
  { label: 'Nov', value: 17 },
  { label: 'Des', value: 13 },
];

function renderCard(stat) {
  return `
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="bg-white p-3 p-md-4 stat-card border">
        <div class="d-flex align-items-start justify-content-between">
          <div class="flex-grow-1 pe-2">
            <div class="text-muted small">${stat.label}</div>
            <div class="display-6 fw-semibold text-primary" style="color:var(${stat.color})">${stat.value}</div>
          </div>
          <div class="icon-box" style="background:var(${stat.color});color:#fff;font-weight:600;border-radius:12px"> 
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="20" height="20" rx="4" fill="currentColor"/></svg>
          </div>
        </div>
      </div>
    </div>
  `;
}

function renderDashboard(root) {
  root.innerHTML = `
  <div class="container-fluid">
    <div class="kemahasa-header d-flex align-items-center justify-content-between mb-4">
      <div>
        <h2 class="h4 mb-0">Dashboard</h2>
        <div class="small text-muted">Sistem Administrasi & Kontrol Organisasi Mahasiswa</div>
      </div>
      <div class="position-relative">
        <button class="btn btn-sm btn-link text-white position-relative">
          <span class="badge bg-warning text-dark position-absolute" style="top:-6px;right:-6px">5</span>
          🔔
        </button>
      </div>
    </div>

    <div class="row mb-4" id="stats-row">
      ${stats.map(renderCard).join('')}
    </div>

    <div class="row gy-4">
      <div class="col-lg-8">
        <div class="bg-white p-3 p-md-4 stat-card border chart-card">
          <h5 class="mb-3">Grafik Kegiatan Bulanan 2024</h5>
          <canvas id="activities-chart" style="width:100%;height:100%"></canvas>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="bg-white p-3 p-md-4 stat-card border">
          <h5>Quick Actions</h5>
          <div class="d-grid gap-2 mt-3">
            <button class="btn btn-dark quick-action-btn">Review Kegiatan</button>
            <button class="btn btn-warning quick-action-btn">Buat Pengumuman</button>
          </div>

          <hr />
          <h6 class="mt-3">Kegiatan Mendatang</h6>
          <ul class="list-unstyled mt-2">
            <li class="mb-2"><strong>Seminar Nasional HMTI</strong><div class="small text-muted">20 Desember 2024</div></li>
            <li class="mb-2"><strong>Festival Seni UKM</strong><div class="small text-muted">22 Desember 2024</div></li>
            <li class="mb-2"><strong>Rapat Koordinasi BEM</strong><div class="small text-muted">25 Desember 2024</div></li>
          </ul>
        </div>
      </div>

      <div class="col-12">
        <div class="bg-white p-3 p-md-4 stat-card border">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Pengumuman Terbaru</h5>
            <a href="#" class="small">Lihat Semua</a>
          </div>
          <div class="table-responsive">
            <table class="table table-borderless mb-0">
              <thead class="small text-muted">
                <tr><th>Judul Pengumuman</th><th class="d-none d-sm-table-cell">Tanggal</th><th>Status</th></tr>
              </thead>
              <tbody>
                <tr><td class="fw-medium">Pendaftaran Kegiatan Semester Genap 2024/2025</td><td class="d-none d-sm-table-cell">15 Desember 2024</td><td><span class="badge bg-success">Terpublikasi</span></td></tr>
                <tr><td class="fw-medium">Perubahan Format Pengajuan Proposal Kegiatan</td><td class="d-none d-sm-table-cell">10 Desember 2024</td><td><span class="badge bg-success">Terpublikasi</span></td></tr>
                <tr><td class="fw-medium">Batas Waktu Pengumpulan LPJ Kegiatan November</td><td class="d-none d-sm-table-cell">5 Desember 2024</td><td><span class="badge bg-secondary">Draft</span></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  `;

  // Render chart
  const ctx = root.querySelector('#activities-chart');
  if (ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: monthly.map((m) => m.label),
        datasets: [
          {
            label: 'Kegiatan',
            data: monthly.map((m) => m.value),
            backgroundColor:
              getComputedStyle(document.documentElement).getPropertyValue(
                '--primary-purple'
              ) || '#3B1C57',
            borderRadius: 8,
          },
        ],
      },
      options: {
        maintainAspectRatio: false,
        scales: {
          y: { beginAtZero: true },
        },
      },
    });
  }
}

// Init when portal-root exists
document.addEventListener('DOMContentLoaded', () => {
  const root = document.getElementById('portal-root');
  if (!root) return;
  renderDashboard(root);
  console.log('Kemahasiswaan dashboard: initialized');
});
