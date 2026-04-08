(function () {
  // Ensure Chart.js is available on window.Chart (loaded via CDN in layout)
  if (typeof window === 'undefined') return;

  // Data (sample)
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
    return (
      '<div class="col-12 col-sm-6 col-lg-3">' +
      '  <div class="bg-white p-3 p-md-4 stat-card border">' +
      '    <div class="d-flex align-items-start justify-content-between">' +
      '      <div>' +
      '        <div class="stat-label">' + stat.label + '</div>' +
      '        <div class="stat-value">' + stat.value + '</div>' +
      '      </div>' +
      '      <div class="stat-icon" style="background: var(' + stat.color + ');"></div>' +
      '    </div>' +
      '  </div>' +
      '</div>'
    );
  }

  function renderDashboard(root) {
    root.innerHTML =
      '' +
      '<div class="container-fluid">' +
      '  <div class="page-header">' +
      '    <div>' +
      '      <h1>Dashboard</h1>' +
      '      <p class="page-subtitle">Sistem Administrasi & Kontrol Organisasi Mahasiswa</p>' +
      '    </div>' +
      '    <div class="position-relative">' +
      '      <button class="btn btn-sm btn-link text-white position-relative" aria-label="Notifications">' +
      '        <span class="badge bg-warning text-dark position-absolute" style="top:-6px;right:-6px">5</span>' +
      '        🔔' +
      '      </button>' +
      '    </div>' +
      '  </div>' +
      '  <div class="row mb-4" id="stats-row">' +
      stats.map(renderCard).join('') +
      '  </div>' +
      '  <div class="row gy-4">' +
      '    <div class="col-lg-8">' +
      '      <div class="bg-white p-3 p-md-4 stat-card border chart-card">' +
      '        <h5 class="mb-3">Grafik Kegiatan Bulanan 2024</h5>' +
      '        <canvas id="activities-chart" style="width:100%;height:100%"></canvas>' +
      '      </div>' +
      '    </div>' +
      '    <div class="col-lg-4">' +
      '      <div class="bg-white p-3 p-md-4 stat-card border">' +
      '        <h5>Quick Actions</h5>' +
      '        <div class="d-grid gap-2 mt-3">' +
      '          <button class="btn btn-dark quick-action-btn">Review Kegiatan</button>' +
      '          <button class="btn btn-warning quick-action-btn">Buat Pengumuman</button>' +
      '        </div>' +
      '        <hr />' +
      '        <h6 class="mt-3">Kegiatan Mendatang</h6>' +
      '        <ul class="list-unstyled mt-2">' +
      '          <li class="mb-2"><strong>Seminar Nasional HMTI</strong><div class="small text-muted">20 Desember 2024</div></li>' +
      '          <li class="mb-2"><strong>Festival Seni UKM</strong><div class="small text-muted">22 Desember 2024</div></li>' +
      '          <li class="mb-2"><strong>Rapat Koordinasi BEM</strong><div class="small text-muted">25 Desember 2024</div></li>' +
      '        </ul>' +
      '      </div>' +
      '    </div>' +
      '    <div class="col-12">' +
      '      <div class="bg-white p-3 p-md-4 stat-card border">' +
      '        <div class="d-flex justify-content-between align-items-center mb-3">' +
      '          <h5 class="mb-0">Pengumuman Terbaru</h5>' +
      '          <a href="#" class="small">Lihat Semua</a>' +
      '        </div>' +
      '        <div class="table-responsive">' +
      '          <table class="table table-borderless mb-0">' +
      '            <thead class="small text-muted"><tr><th>Judul Pengumuman</th><th class="d-none d-sm-table-cell">Tanggal</th><th>Status</th></tr></thead>' +
      '            <tbody>' +
      '              <tr><td class="fw-medium">Pendaftaran Kegiatan Semester Genap 2024/2025</td><td class="d-none d-sm-table-cell">15 Desember 2024</td><td><span class="badge bg-success">Terpublikasi</span></td></tr>' +
      '              <tr><td class="fw-medium">Perubahan Format Pengajuan Proposal Kegiatan</td><td class="d-none d-sm-table-cell">10 Desember 2024</td><td><span class="badge bg-success">Terpublikasi</span></td></tr>' +
      '              <tr><td class="fw-medium">Batas Waktu Pengumpulan LPJ Kegiatan November</td><td class="d-none d-sm-table-cell">5 Desember 2024</td><td><span class="badge bg-secondary">Draft</span></td></tr>' +
      '            </tbody>' +
      '          </table>' +
      '        </div>' +
      '      </div>' +
      '    </div>' +
      '  </div>' +
      '</div>';

    // Render chart using global Chart (from CDN)
    var canvas = root.querySelector('#activities-chart');
    if (canvas && window.Chart) {
      const barColor = (
        getComputedStyle(document.documentElement).getPropertyValue(
          '--km-primary'
        ) || '#3B1C57'
      ).trim();
      new Chart(canvas.getContext('2d'), {
        type: 'bar',
        data: {
          labels: monthly.map(function (m) {
            return m.label;
          }),
          datasets: [
            {
              label: 'Kegiatan',
              data: monthly.map(function (m) {
                return m.value;
              }),
              backgroundColor: barColor,
              borderRadius: 12,
              borderSkipped: false,
              barThickness: 36,
              categoryPercentage: 0.7,
              barPercentage: 0.85,
            },
          ],
        },
        options: {
          plugins: { legend: { display: false } },
          maintainAspectRatio: false,
          scales: {
            x: {
              grid: { display: false },
              ticks: { color: '#666' },
            },
            y: {
              beginAtZero: true,
              ticks: { color: '#666', stepSize: 6 },
              grid: { color: 'rgba(0,0,0,0.04)', borderDash: [4, 4] },
            },
          },
        },
      });
    }
  }

  // Init: either render SPA-like root or initialize existing canvas on server-rendered pages
  document.addEventListener('DOMContentLoaded', function () {
    var root = document.getElementById('portal-root');
    if (root) {
      renderDashboard(root);
      return;
    }

    // If page was server-rendered (no portal-root), initialize the chart if canvas exists
    var canvas = document.querySelector('#activities-chart');
    if (canvas && window.Chart) {
      const barColor = (
        getComputedStyle(document.documentElement).getPropertyValue(
          '--km-primary'
        ) || '#3B1C57'
      ).trim();
      new Chart(canvas.getContext('2d'), {
        type: 'bar',
        data: {
          labels: monthly.map(function (m) {
            return m.label;
          }),
          datasets: [
            {
              label: 'Kegiatan',
              data: monthly.map(function (m) {
                return m.value;
              }),
              backgroundColor: barColor,
              borderRadius: 12,
              borderSkipped: false,
              barThickness: 36,
              categoryPercentage: 0.7,
              barPercentage: 0.85,
            },
          ],
        },
        options: {
          plugins: { legend: { display: false } },
          maintainAspectRatio: false,
          scales: {
            x: { grid: { display: false }, ticks: { color: '#666' } },
            y: {
              beginAtZero: true,
              ticks: { color: '#666', stepSize: 6 },
              grid: { color: 'rgba(0,0,0,0.04)', borderDash: [4, 4] },
            },
          },
        },
      });
    }
  });
})();