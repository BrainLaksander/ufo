@extends('layouts.app')

@section('title', 'Pengumuman')

@section('content')
    <div class="content">
        <div style="padding: 36px 90px;">
            <h1 style="color: #5e3191; font-size: 36px; margin: 0 0 8px;">Pengumuman</h1>
            <p style="color:#6b7280; margin: 6px 0 18px;">Informasi terbaru seputar kampus dan organisasi</p>

            <div class="search-wrap" style="margin: 26px 0 0;">
                <input class="search" id="announcementSearchInput" type="text" placeholder="Cari pengumuman..." aria-label="Cari pengumuman" style="padding-left: 16px;">
            </div>

            <div class="chips" id="categoryChips" style="margin: 18px 0 0;">
                <button class="chip active" data-category="">Semua</button>
                @php
                    $categories = isset($announcements) ? $announcements->pluck('category')->unique()->filter() : collect();
                @endphp
                @foreach($categories as $category)
                    <button class="chip" data-category="{{ strtolower($category) }}">{{ $category }}</button>
                @endforeach
            </div>



            <div class="cards" id="announcementCards" style="margin: 28px 0;">
                @forelse($announcements as $announcement)
                    <article class="card announcement-card" data-title="{{ $announcement->title }}" data-category="{{ $announcement->category }}" data-org="{{ $announcement->organization ? $announcement->organization->name : 'Kemahasiswaan' }}" style="display: flex; flex-direction: column; align-items: flex-start; text-align: left; padding: 24px;">
                        <div style="display: flex; flex-direction: column; width: 100%; margin-bottom: 12px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <span style="display: inline-block; padding: 4px 10px; background: #f3e8ff; color: #6b21a8; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap;">{{ $announcement->category }}</span>
                                <span style="color: #6b7280; font-size: 12px;">{{ $announcement->published_at ? $announcement->published_at->format('d M Y') : '' }}</span>
                            </div>
                            <h2 style="margin: 0 0 12px 0; font-size: 18px; font-weight: 800; color: #111827;">{{ $announcement->title }}</h2>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                            <div style="width: 24px; height: 24px; border-radius: 50%; background: linear-gradient(135deg, #6f3ba7, #a476d1); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 10px; font-weight: bold; flex-shrink: 0;">
                                {{ substr($announcement->organization ? $announcement->organization->name : 'K', 0, 1) }}
                            </div>
                            <span style="font-size: 13px; font-weight: 600; color: #4b5563;">
                                {{ $announcement->organization ? $announcement->organization->name : 'Kemahasiswaan' }}
                            </span>
                        </div>

                        <div style="color: #4b5563; font-size: 14px; line-height: 1.6; margin-bottom: 24px; flex-grow: 1;">
                            {{ Str::limit(strip_tags($announcement->content), 120) }}
                        </div>

                        <button type="button" class="btn" onclick="openAnnouncementModal({{ json_encode($announcement->id) }}, {{ json_encode($announcement->title) }}, {{ json_encode($announcement->content) }}, {{ json_encode($announcement->organization ? $announcement->organization->name : 'Kemahasiswaan') }}, {{ json_encode($announcement->published_at ? $announcement->published_at->format('d M Y') : '') }})" style="width: 100%; box-sizing: border-box; text-align: center;">
                            Baca Selengkapnya
                        </button>
                    </article>
                @empty
                    <div style="text-align: center; padding: 40px; background: #f9fafb; border-radius: 16px; color: #6b7280;">
                        Belum ada pengumuman saat ini.
                    </div>
                @endforelse
            </div>

            <div class="pagination-wrap" id="announcementPagination"></div>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div id="announcementModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 100; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(4px);">
        <div style="background: #fff; width: 100%; max-width: 600px; border-radius: 20px; padding: 32px; position: relative; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto;">
            <button type="button" onclick="document.getElementById('announcementModal').style.display='none'" style="position: absolute; top: 16px; right: 16px; background: #f3f4f6; color: #4b5563; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 20px; cursor: pointer; display: grid; place-items: center; transition: background 0.2s;">&times;</button>
            
            <h2 id="modalTitle" style="margin: 0 0 12px 0; font-size: 24px; font-weight: 800; color: #111827; padding-right: 20px;"></h2>
            
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb;">
                <div id="modalAvatar" style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #6f3ba7, #a476d1); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 14px; font-weight: bold;"></div>
                <div>
                    <div id="modalOrg" style="font-size: 14px; font-weight: 700; color: #111827;"></div>
                    <div id="modalDate" style="font-size: 12px; color: #6b7280;"></div>
                </div>
            </div>

            <div id="modalContent" style="color: #4b5563; font-size: 16px; line-height: 1.7;"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Search & Filter functionality
    const searchInput = document.getElementById('announcementSearchInput');
    const cardsContainer = document.getElementById('announcementCards');
    const countEl = document.getElementById('announcementCount');
    const chips = document.querySelectorAll('.chip');
    let currentFilter = '';
    let currentPage = 1;
    const perPage = 9;

    function filterAnnouncements() {
        if (!cardsContainer) return;
        const query = searchInput.value.toLowerCase().trim();
        const cards = Array.from(cardsContainer.querySelectorAll('.announcement-card'));
        
        cards.forEach(card => {
            const title = (card.dataset.title || '').toLowerCase();
            const cat = (card.dataset.category || '').toLowerCase();
            const org = (card.dataset.org || '').toLowerCase();
            
            const matchesSearch = !query || title.includes(query) || cat.includes(query) || org.includes(query);
            const matchesFilter = !currentFilter || cat === currentFilter;
            card._matches = matchesSearch && matchesFilter;
        });

        const matched = cards.filter(c => c._matches);
        const unmatched = cards.filter(c => !c._matches);

        matched.forEach(card => cardsContainer.appendChild(card));
        unmatched.forEach(card => cardsContainer.appendChild(card));
        
        if (countEl) countEl.textContent = matched.length + ' pengumuman ditemukan';

        // Pagination
        const totalPages = Math.max(1, Math.ceil(matched.length / perPage));
        if (currentPage > totalPages) currentPage = totalPages;

        const startIdx = (currentPage - 1) * perPage;
        const endIdx = startIdx + perPage;

        matched.forEach((card, i) => {
            card.style.display = (i >= startIdx && i < endIdx) ? 'flex' : 'none';
        });
        unmatched.forEach(card => { card.style.display = 'none'; });

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        const container = document.getElementById('announcementPagination');
        container.innerHTML = '';
        if (totalPages <= 1) return;

        const prevBtn = document.createElement('button');
        prevBtn.className = 'page-btn';
        prevBtn.textContent = '<';
        prevBtn.disabled = currentPage === 1;
        prevBtn.onclick = () => { currentPage--; filterAnnouncements(); window.scrollTo({top: 0, behavior: 'smooth'}); };
        container.appendChild(prevBtn);

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.className = 'page-btn' + (i === currentPage ? ' active' : '');
            btn.textContent = i;
            btn.onclick = () => { currentPage = i; filterAnnouncements(); window.scrollTo({top: 0, behavior: 'smooth'}); };
            container.appendChild(btn);
        }

        const nextBtn = document.createElement('button');
        nextBtn.className = 'page-btn';
        nextBtn.textContent = '>';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.onclick = () => { currentPage++; filterAnnouncements(); window.scrollTo({top: 0, behavior: 'smooth'}); };
        container.appendChild(nextBtn);
    }

    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            chips.forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
            currentFilter = chip.dataset.category;
            currentPage = 1;
            filterAnnouncements();
        });
    });

    if (searchInput) searchInput.addEventListener('input', () => { currentPage = 1; filterAnnouncements(); });

    filterAnnouncements();

    // Modal
    function openAnnouncementModal(id, title, content, org, date) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalContent').innerHTML = content;
        document.getElementById('modalOrg').textContent = org;
        document.getElementById('modalDate').textContent = date;
        document.getElementById('modalAvatar').textContent = org.charAt(0).toUpperCase();
        document.getElementById('announcementModal').style.display = 'flex';
    }
</script>
@endpush
