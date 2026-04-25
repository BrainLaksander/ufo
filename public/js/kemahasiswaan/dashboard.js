(function () {
  if (typeof window === 'undefined') {
    return;
  }

  // Optional legacy chart initializer. Data must be injected by server-side code.
  function normalizeMonthlyData(source) {
    if (!Array.isArray(source)) {
      return [];
    }

    return source
      .map(function (item) {
        return {
          label: String(item && item.label ? item.label : ''),
          value: Number(item && item.value ? item.value : 0),
        };
      })
      .filter(function (item) {
        return item.label.length > 0;
      });
  }

  document.addEventListener('DOMContentLoaded', function () {
    var canvas = document.querySelector('#activities-chart');
    if (!canvas || !window.Chart) {
      return;
    }

    var monthly = normalizeMonthlyData(window.kemahasiswaanMonthlyActivity || []);
    if (!monthly.length) {
      return;
    }

    var barColor = (
      getComputedStyle(document.documentElement).getPropertyValue('--km-primary') ||
      '#3B1C57'
    ).trim();

    new window.Chart(canvas.getContext('2d'), {
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
            ticks: { color: '#666' },
            grid: { color: 'rgba(0,0,0,0.04)', borderDash: [4, 4] },
          },
        },
      },
    });
  });
})();