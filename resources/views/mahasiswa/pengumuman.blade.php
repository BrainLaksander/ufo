@extends('layouts.app')

@section('title', 'Pengumuman Mahasiswa - UFO')

@push('styles')
<style>
    .ufo-ann-page {
        padding: 1.25rem 0 2rem;
    }

    .ufo-ann-hero {
        border-radius: 14px;
        background: var(--primary);
        color: #fff;
        padding: 1.1rem 1.2rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 0.95rem;
    }

    .ufo-ann-hero h1 {
        margin: 0 0 0.35rem;
        font-size: 1.55rem;
        font-weight: 700;
    }

    .ufo-ann-hero p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.92rem;
    }

    .ufo-ann-toolbar {
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: #fff;
        padding: 0.85rem;
        margin-bottom: 1rem;
    }

    .ufo-ann-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .ufo-ann-tab {
        border: 1px solid var(--border-color);
        background: #fff;
        color: #333;
        padding: 0.45rem 0.68rem;
        border-radius: 9px;
        font-size: 0.82rem;
        font-weight: 600;
        line-height: 1;
    }

    .ufo-ann-tab.is-active {
        border-color: var(--primary);
        background: rgba(95, 58, 116, 0.1);
        color: var(--primary);
    }

    .ufo-ann-search {
        border-radius: 10px;
        border-color: #d9deea;
    }

    .ufo-ann-search:focus {
        border-color: #7f4da7;
        box-shadow: 0 0 0 0.18rem rgba(127, 77, 167, 0.16);
    }

    .ufo-ann-card-btn {
        position: relative;
        overflow: hidden;
        border: 1px solid #d9deea;
        border-radius: 14px;
        background: linear-gradient(180deg, #ffffff 0%, #fcfbff 100%);
        box-shadow: 0 6px 18px rgba(18, 25, 38, 0.06);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .ufo-ann-card-btn::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        height: 3px;
        background: rgba(95, 58, 116, 0.2);
    }

    .ufo-ann-card-btn.is-new::before {
        background: linear-gradient(90deg, #f59e0b 0%, #fcd34d 100%);
    }

    .ufo-ann-card-btn.is-important::before {
        background: linear-gradient(90deg, #ef4444 0%, #fca5a5 100%);
    }

    .ufo-ann-card-btn:hover {
        transform: translateY(-2px);
        border-color: rgba(95, 58, 116, 0.28);
        box-shadow: 0 12px 26px rgba(18, 25, 38, 0.12);
    }

    .ufo-ann-card-btn:focus-visible {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(95, 58, 116, 0.2), 0 12px 26px rgba(18, 25, 38, 0.12);
    }

    .ufo-ann-card-inner {
        padding: 0.92rem 0.92rem 0.82rem;
    }

    .ufo-ann-meta {
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: 0.5rem;
        margin-bottom: 0.62rem;
    }

    .ufo-ann-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
    }

    .ufo-ann-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        font-size: 0.73rem;
        font-weight: 700;
        padding: 0.2rem 0.52rem;
        border: 1px solid transparent;
        line-height: 1;
    }

    .ufo-ann-pill.new {
        background: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
    }

    .ufo-ann-pill.important {
        background: #fee2e2;
        color: #9f1239;
        border-color: #fca5a5;
    }

    .ufo-ann-pill.category {
        background: rgba(95, 58, 116, 0.1);
        color: var(--primary);
        border-color: rgba(95, 58, 116, 0.18);
    }

    .ufo-ann-date {
        color: #596274;
        font-size: 0.79rem;
        white-space: nowrap;
        border: 1px solid #d7ddea;
        border-radius: 999px;
        background: #f7f8fc;
        padding: 0.12rem 0.48rem;
        line-height: 1.1;
    }

    .ufo-ann-title {
        margin: 0 0 0.38rem;
        font-size: 1.02rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.34;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.7em;
    }

    .ufo-ann-summary {
        margin: 0 0 0.62rem;
        color: #5f697a;
        font-size: 0.86rem;
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 3.9em;
    }

    .ufo-ann-author {
        color: #5f697a;
        font-size: 0.78rem;
        display: inline-flex;
        align-items: center;
        gap: 0.32rem;
        border-top: 1px dashed #dbe2ef;
        padding-top: 0.5rem;
        width: 100%;
    }

    .ufo-ann-author i {
        color: #8b5fb3;
        font-size: 0.85rem;
    }

    .ufo-ann-empty {
        border-radius: 12px;
        border: 1px dashed #ccd4e0;
        background: #fff;
        padding: 0.95rem;
        color: #5f697a;
        text-align: center;
    }

    .ufo-ann-modal-title {
        color: var(--primary);
        font-weight: 700;
    }

    .ufo-ann-modal-meta {
        color: #6b7280;
        font-size: 0.85rem;
        margin-bottom: 0.65rem;
    }

    .ufo-ann-modal-content {
        color: #374151;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .ufo-ann-page {
            padding-top: 1rem;
        }

        .ufo-ann-hero h1 {
            font-size: 1.3rem;
        }

        .ufo-ann-title,
        .ufo-ann-summary {
            min-height: 0;
        }
    }
</style>
@endpush

@section('content')
<section class="ufo-ann-page">
    <div class="ufo-ann-hero">
        <h1>Pengumuman Kampus</h1>
        <p>Berita dan informasi penting untuk mahasiswa UNKLAB.</p>
    </div>

    <div class="ufo-ann-toolbar">
        <div class="row g-2 align-items-center">
            <div class="col-md-8">
                <div class="ufo-ann-tabs" id="kategoriTabs">
                    <button type="button" class="ufo-ann-tab is-active" data-tab="semua">Semua</button>
                    <button type="button" class="ufo-ann-tab" data-tab="akademik">Akademik</button>
                    <button type="button" class="ufo-ann-tab" data-tab="organisasi">Organisasi</button>
                    <button type="button" class="ufo-ann-tab" data-tab="event">Event</button>
                </div>
            </div>
            <div class="col-md-4">
                <input id="searchInput" type="text" class="form-control ufo-ann-search" placeholder="Cari pengumuman">
            </div>
        </div>
    </div>

    <div id="pengumuman-list" class="row g-3"></div>

    <div id="pengumuman-empty" class="ufo-ann-empty d-none mt-3">
        Tidak ada pengumuman yang sesuai.
    </div>
</section>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ufo-ann-modal-title" id="detailTitle">Detail Pengumuman</h5>
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
    const tabs = Array.from(document.querySelectorAll('.ufo-ann-tab'));
    const search = document.getElementById('searchInput');
    const modalEl = document.getElementById('detailModal');
    const bsModal = new bootstrap.Modal(modalEl);

    let activeTab = 'semua';

    function badge(item) {
        const badges = [];
        if (item.is_new) badges.push('<span class="ufo-ann-pill new">Baru</span>');
        if (item.is_important) badges.push('<span class="ufo-ann-pill important">Penting</span>');
        badges.push(`<span class="ufo-ann-pill category">${item.kategori}</span>`);
        return badges.join(' ');
    }

    function render() {
        const q = search.value.trim().toLowerCase();
        const filtered = window.pengumumanData.filter(function (item) {
            const cat = (item.kategori || '').toLowerCase();
            const byTab = activeTab === 'semua' || cat === activeTab;
            const bySearch =
                (item.judul || '').toLowerCase().includes(q) ||
                (item.ringkasan || '').toLowerCase().includes(q) ||
                (item.author || '').toLowerCase().includes(q);
            return byTab && bySearch;
        });

        list.innerHTML = filtered.map(function (item) {
            const cardTone = item.is_important ? 'is-important' : (item.is_new ? 'is-new' : '');
            return `
                <div class="col-md-6 col-lg-4">
                    <button type="button" class="ufo-ann-card-btn ${cardTone} h-100 text-start w-100 p-0" data-id="${item.id}">
                        <div class="ufo-ann-card-inner">
                            <div class="ufo-ann-meta">
                                <div class="ufo-ann-badges">${badge(item)}</div>
                                <small class="ufo-ann-date">${item.date}</small>
                            </div>
                            <h5 class="ufo-ann-title">${item.judul}</h5>
                            <p class="ufo-ann-summary">${item.ringkasan}</p>
                            <small class="ufo-ann-author"><i class="bi bi-person-circle"></i>${item.author}</small>
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
                    <p class="ufo-ann-modal-meta">${item.author} | ${item.date}</p>
                    <p class="ufo-ann-modal-content mb-3">${(item.konten || '').replace(/\n/g, '<br>')}</p>
                    <div class="ufo-ann-badges">${badge(item)}</div>
                `;
                bsModal.show();
            });
        });
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            activeTab = tab.dataset.tab;
            tabs.forEach(function (t) {
                t.classList.remove('is-active');
            });
            tab.classList.add('is-active');
            render();
        });
    });

    search.addEventListener('input', render);
    render();
});
</script>
@endpush
