@extends('layouts.mahasiswa')

@section('title', 'Organisasi - Mahasiswa')

@section('content')
<div class="container py-4 mahasiswa-organisasi-page">
    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold mb-1">Daftar Organisasi</h1>
            <p class="text-muted mb-0">Temukan organisasi yang sesuai dengan minat Anda.</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Landing
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <input id="search-input" type="text" class="form-control" placeholder="Cari organisasi...">
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-wrap gap-2">
                        <button class="filter-btn btn btn-primary btn-sm" data-filter="all">Semua</button>
                        <button class="filter-btn btn btn-outline-primary btn-sm" data-filter="Akademik">Akademik</button>
                        <button class="filter-btn btn btn-outline-primary btn-sm" data-filter="Seni & Olahraga">Seni & Olahraga</button>
                        <button class="filter-btn btn btn-outline-primary btn-sm" data-filter="Kerohanian">Kerohanian</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="org-grid" class="row g-4"></div>

    <div id="org-empty" class="alert alert-light border d-none mt-4 text-center">
        Tidak ada organisasi yang sesuai dengan filter.
    </div>
</div>
@endsection

@push('scripts')
<script>
window.orgsData = @json($organisasi);

document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('org-grid');
    const empty = document.getElementById('org-empty');
    const search = document.getElementById('search-input');
    const filters = Array.from(document.querySelectorAll('.filter-btn'));

    let activeFilter = 'all';

    function render() {
        const q = search.value.trim().toLowerCase();
        const filtered = window.orgsData.filter(function (org) {
            const matchCategory = activeFilter === 'all' || org.kategori === activeFilter;
            const matchSearch =
                org.nama.toLowerCase().includes(q) ||
                org.tagline.toLowerCase().includes(q) ||
                org.deskripsi.toLowerCase().includes(q);
            return matchCategory && matchSearch;
        });

        grid.innerHTML = filtered.map(function (org) {
            const categoryIconMap = {
                'Akademik': 'bi-mortarboard-fill',
                'Seni & Olahraga': 'bi-trophy-fill',
                'Kerohanian': 'bi-heart-fill'
            };
            const orgIcon = categoryIconMap[org.kategori] || 'bi-people-fill';

            return `
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <h5 class="card-title mb-0"><i class="bi ${orgIcon} me-2"></i>${org.nama}</h5>
                                <span class="badge text-bg-light border">${org.kategori}</span>
                            </div>
                            <p class="text-muted small mb-2">${org.tagline}</p>
                            <p class="card-text">${org.deskripsi}</p>
                            <div class="d-flex justify-content-between small text-muted mt-3">
                                <span><i class="bi bi-people-fill me-1"></i>Anggota: ${org.members}</span>
                                <span>${org.registrationOpen ? '<i class="bi bi-unlock-fill me-1"></i>Pendaftaran Buka' : '<i class="bi bi-lock-fill me-1"></i>Pendaftaran Tutup'}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        empty.classList.toggle('d-none', filtered.length > 0);
    }

    filters.forEach(function (btn) {
        btn.addEventListener('click', function () {
            activeFilter = btn.dataset.filter;
            filters.forEach(function (b) {
                b.classList.remove('btn-primary');
                b.classList.add('btn-outline-primary');
            });
            btn.classList.remove('btn-outline-primary');
            btn.classList.add('btn-primary');
            render();
        });
    });

    search.addEventListener('input', render);
    render();
});
</script>
@endpush
