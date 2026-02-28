<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UFO - UNKLAB Forum Organization')</title>
    <!-- Tailwind CSS CDN for Organisasi Page -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap 5 CSS for Lost & Found & Pengumuman -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
    <style>
        body {
            padding-top: 64px;
        }
        .cursor-pointer {
            cursor: pointer;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="mahasiswa-layout">
    @include('components.mahasiswa.header')
    
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Content akan diisi oleh JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/global.js"></script>
    <script src="/js/sidebar-toggle.js"></script>
    <script>
        // Global function untuk open detail modal
        function openDetail(id) {
            fetch(`/api/lost-found/${id}`)
                .then(response => response.json())
                .then(data => {
                    showDetailModal(data);
                });
        }

        function showDetailModal(item) {
            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            const modalBody = document.querySelector('.modal-body');
            
            const categoryIcons = {
                'Dompet': '💼',
                'Kunci': '🔑',
                'Buku': '📖',
                'Elektronik': '💻',
                'Kartu Identitas': '🆔'
            };

            const icon = categoryIcons[item.category] || '📦';
            const statusBadge = item.itemStatus === 'ditemukan' 
                ? '<span class="badge bg-success">✓ Ditemukan</span>'
                : '<span class="badge bg-danger">✗ Hilang</span>';

            modalBody.innerHTML = `
                <div class="mb-3">
                    <div style="font-size: 3rem;">${icon}</div>
                    ${statusBadge}
                    ${item.priority ? '<span class="badge bg-warning">⭐ Penting</span>' : ''}
                </div>
                <h5>Kategori: ${item.category}</h5>
                <p><strong>Lokasi:</strong> ${item.location}</p>
                <p><strong>Tanggal:</strong> ${new Date(item.date).toLocaleDateString('id-ID')}</p>
                <h6 class="mt-3">Deskripsi:</h6>
                <p>${item.description.replace(/\n/g, '<br>')}</p>
                <div class="mt-3 border-top pt-3">
                    <h6>Hubungi Pelapor:</h6>
                    <a href="mailto:${item.contact}" class="btn btn-sm btn-primary me-2">✉️ Email</a>
                    <a href="https://wa.me/62${item.phone.substring(1)}" target="_blank" class="btn btn-sm btn-success">💬 WhatsApp</a>
                </div>
            `;
            
            modal.show();
        }
    </script>
</body>
</html>
