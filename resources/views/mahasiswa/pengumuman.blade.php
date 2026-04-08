@extends('layouts.mahasiswa')

@section('title', 'Pengumuman - Mahasiswa')

@section('content')
<div class="container py-4 mahasiswa-pengumuman-page">
    <div class="mb-4">
        <h1 class="h2 fw-bold mb-1">Pengumuman Kampus</h1>
        <p class="text-muted mb-0">Berita dan informasi penting untuk mahasiswa UNKLAB.</p>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <div class="btn-group" role="group" id="kategoriTabs">
                        <button class="btn btn-primary" data-tab="semua">Semua</button>
                        <button class="btn btn-outline-primary" data-tab="akademik">Akademik</button>
                        <button class="btn btn-outline-primary" data-tab="organisasi">Organisasi</button>
                        <button class="btn btn-outline-primary" data-tab="event">Event</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <input id="searchInput" type="text" class="form-control" placeholder="Cari pengumuman...">
                </div>
            </div>
        </div>
    </div>

    <div id="pengumuman-list" class="row g-3"></div>

    <div id="pengumuman-empty" class="alert alert-light border d-none text-center mt-3">
        Tidak ada pengumuman yang sesuai.
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailTitle">Detail Pengumuman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailContent"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.pengumumanData = @json($pengumuman);

document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('pengumuman-list');
    const empty = document.getElementById('pengumuman-empty');
    const tabs = Array.from(document.querySelectorAll('#kategoriTabs button'));
    const search = document.getElementById('searchInput');
    const modalEl = document.getElementById('detailModal');
    const bsModal = new bootstrap.Modal(modalEl);

    let activeTab = 'semua';

    function badge(item) {
        const badges = [];
        if (item.is_new) badges.push('<span class="badge text-bg-warning">Baru</span>');
        if (item.is_important) badges.push('<span class="badge text-bg-danger">Penting</span>');
        badges.push(`<span class="badge text-bg-light border">${item.kategori}</span>`);
        return badges.join(' ');
    }

    function render() {
        const q = search.value.trim().toLowerCase();
        const filtered = window.pengumumanData.filter(function (item) {
            const cat = item.kategori.toLowerCase();
            const byTab = activeTab === 'semua' || cat === activeTab;
            const bySearch =
                item.judul.toLowerCase().includes(q) ||
                item.ringkasan.toLowerCase().includes(q) ||
                item.author.toLowerCase().includes(q);
            return byTab && bySearch;
        });

        list.innerHTML = filtered.map(function (item) {
            return `
                <div class="col-md-6 col-lg-4">
                    <button class="card h-100 border-0 shadow-sm text-start w-100 p-0" data-id="${item.id}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex flex-wrap gap-1">${badge(item)}</div>
                                <small class="text-muted">${item.date}</small>
                            </div>
                            <h5 class="card-title">${item.judul}</h5>
                            <p class="card-text text-muted small">${item.ringkasan}</p>
                            <small class="text-muted">${item.author}</small>
                        </div>
                    </button>
                </div>
            `;
        }).join('');

        empty.classList.toggle('d-none', filtered.length > 0);

        Array.from(list.querySelectorAll('button[data-id]')).forEach(function (cardBtn) {
            cardBtn.addEventListener('click', function () {
                const id = Number(cardBtn.getAttribute('data-id'));
                const item = window.pengumumanData.find(function (x) { return x.id === id; });
                if (!item) return;
                document.getElementById('detailTitle').textContent = item.judul;
                document.getElementById('detailContent').innerHTML = `
                    <p class="text-muted mb-2">${item.author} | ${item.date}</p>
                    <p class="mb-3">${item.konten.replace(/\n/g, '<br>')}</p>
                    <div class="d-flex flex-wrap gap-2">${badge(item)}</div>
                `;
                bsModal.show();
            });
        });
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            activeTab = tab.dataset.tab;
            tabs.forEach(function (t) {
                t.classList.remove('btn-primary');
                t.classList.add('btn-outline-primary');
            });
            tab.classList.remove('btn-outline-primary');
            tab.classList.add('btn-primary');
            render();
        });
    });

    search.addEventListener('input', render);
    render();
});
</script>
@endpush
