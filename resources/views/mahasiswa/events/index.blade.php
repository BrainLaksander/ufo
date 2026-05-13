@extends('layouts.app')

@section('title', 'Event Kampus')

@section('content')
    <div class="content">
        <div style="padding: 36px 90px;">
            <h1 style="color: #5e3191; font-size: 36px; margin: 0 0 8px;">Event Kampus</h1>
            <p style="color:#6b7280; margin: 6px 0 18px;">Temukan dan ikuti berbagai event menarik di UNKLAB</p>

            <div class="search-wrap" style="margin: 26px 0 0;">
                <input class="search" id="eventSearchInput" type="text" placeholder="Cari event..." aria-label="Cari event" style="padding-left: 16px;">
            </div>

            {{-- Status Tabs --}}
            <div id="statusTabs" style="display: flex; margin-top: 24px; background: #f3f4f6; border-radius: 12px; padding: 4px; gap: 4px;">
                <button class="event-status-tab active" data-tab="active" style="flex: 1; padding: 12px 20px; border-radius: 10px; border: none; font-size: 15px; font-weight: 700; cursor: pointer; transition: all .2s ease; background: #fff; color: #5e3191; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                    Aktif & Akan Datang
                    <span id="activeCount" style="margin-left: 6px; background: #ede9fe; color: #5b21b6; padding: 2px 8px; border-radius: 999px; font-size: 12px;">0</span>
                </button>
                <button class="event-status-tab" data-tab="finished" style="flex: 1; padding: 12px 20px; border-radius: 10px; border: none; font-size: 15px; font-weight: 700; cursor: pointer; transition: all .2s ease; background: transparent; color: #6b7280;">
                    Selesai
                    <span id="finishedCount" style="margin-left: 6px; background: #f3f4f6; color: #6b7280; padding: 2px 8px; border-radius: 999px; font-size: 12px;">0</span>
                </button>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; flex-wrap: wrap; gap: 16px;">
                <div class="chips" id="categoryChips" style="margin: 0;">
                    <button class="chip active" data-category="">Semua</button>
                    @foreach(['Pelayanan/Sosial (PKM)', 'Kebudayaan', 'Akademik', 'Keagamaan/Kerohanian', 'Minat/Bakat', 'Kebangsaan'] as $cat)
                        <button class="chip" data-category="{{ strtolower($cat) }}">{{ $cat }}</button>
                    @endforeach
                </div>

                <div style="display: flex; background: #f3f4f6; padding: 4px; border-radius: 8px; flex-shrink: 0;">
                    <a href="{{ route('events.index', ['mode' => 'detail']) }}" style="padding: 6px 12px; border-radius: 6px; text-decoration: none; color: #111827; font-size: 14px; font-weight: 600; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                        Grid Detail
                    </a>
                    <a href="{{ route('events.index', ['mode' => 'summary']) }}" style="padding: 6px 12px; border-radius: 6px; text-decoration: none; color: #6b7280; font-size: 14px; font-weight: 600;">
                        List Ringkasan
                    </a>
                </div>
            </div>

            <div class="cards" id="eventCards" style="margin: 28px 0;">
                @forelse($events ?? [] as $event)
                    <article class="card event-card"
                        data-title="{{ strtolower($event->title) }}"
                        data-category="{{ strtolower($event->category ?? 'umum') }}"
                        data-start="{{ $event->start_at ? $event->start_at->toIso8601String() : '' }}"
                        data-end="{{ $event->end_at ? $event->end_at->toIso8601String() : '' }}"
                        style="display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 24px; position: relative;">

                        {{-- Status Badge (injected by JS) --}}
                        <div class="event-status-badge" style="position: absolute; top: 12px; right: 12px;"></div>

                        <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                            <div class="avatar" style="background: linear-gradient(135deg, #a476d1, #f0b84c); color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 16px; border-radius: 50%; width: 80px; height: 80px;">
                                @if($event->poster_path)
                                    <img src="{{ Storage::url($event->poster_path) }}" alt="{{ $event->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    {{ strtoupper(substr($event->title, 0, 1)) }}
                                @endif
                            </div>
                            <h3 style="margin: 0 0 6px 0; font-size: 16px; font-weight: 800; color: #111827; text-align: center;">{{ $event->title }}</h3>
                            <span style="background: #ede9fe; color: #5b21b6; padding: 2px 8px; border-radius: 9999px; font-size: 11px; font-weight: bold; white-space: nowrap; margin-bottom: 12px;">{{ $event->category ?? 'Umum' }}</span>
                            
                            <p style="font-size: 13px; color: #6b7280; margin: 0 0 4px 0; text-align: center;">
                                {{ optional($event->start_at)->translatedFormat('d M Y, H:i') }}
                            </p>
                            @if($event->organization)
                                <p style="font-size: 13px; color: #4b5563; margin: 0 0 16px 0; text-align: center;">
                                    {{ $event->organization->name }}
                                </p>
                            @endif
                        </div>

                        <div style="width: 100%; margin-top: 24px; display: flex; gap: 8px; flex-direction: column;">
                            <button type="button" class="btn" onclick="openEventDetailModal({{ json_encode([
                                'title' => $event->title,
                                'category' => $event->category ?? 'Umum',
                                'date' => optional($event->start_at)->translatedFormat('d F Y, H:i'),
                                'end_date' => $event->end_at ? \Carbon\Carbon::parse($event->end_at)->translatedFormat('d F Y, H:i') : null,
                                'location' => $event->location ?? '-',
                                'organization' => $event->organization->name ?? '-',
                                'description' => $event->description ?? 'Tidak ada deskripsi',
                                'poster_url' => $event->poster_path ? Storage::url($event->poster_path) : null,
                                'registration_link' => $event->registration_link,
                                'start_iso' => $event->start_at ? $event->start_at->toIso8601String() : null,
                                'end_iso' => $event->end_at ? $event->end_at->toIso8601String() : null
                            ]) }})" style="width: 100%; box-sizing: border-box; background: #fff; color: #5e3191; border: 1px solid #5e3191;">Lihat Detail</button>
                            
                            <div class="event-register-btn">
                            @if($event->registration_link)
                                <a href="{{ $event->registration_link }}" target="_blank" class="btn" style="display: block; text-align: center; text-decoration: none; width: 100%; box-sizing: border-box; background: #5e3191; color: #fff;">Daftar Sekarang</a>
                            @else
                                <button type="button" class="btn" style="width: 100%; opacity: 0.7; cursor: not-allowed; background: #9ca3af; color: #fff;" disabled>Pendaftaran Tertutup</button>
                            @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: #f3f4f6; border-radius: 8px;">
                        <h3 style="color: #4b5563; margin-bottom: 8px;">Belum ada Event</h3>
                        <p style="color: #6b7280; font-size: 14px;">Saat ini tidak ada event kampus yang sedang berjalan atau akan datang.</p>
                    </div>
                @endforelse
            </div>

            {{-- Empty state for finished tab --}}
            <div id="emptyFinished" style="display: none; text-align: center; padding: 40px; background: #f9fafb; border-radius: 16px; border: 1px solid #e5e7eb; margin: 28px 0;">
                <p style="margin: 0; color: #6b7280; font-size: 15px;">Belum ada event yang selesai.</p>
            </div>
            <div id="emptyActive" style="display: none; text-align: center; padding: 40px; background: #f9fafb; border-radius: 16px; border: 1px solid #e5e7eb; margin: 28px 0;">
                <p style="margin: 0; color: #6b7280; font-size: 15px;">Tidak ada event aktif atau akan datang saat ini.</p>
            </div>

            <div class="pagination-wrap" id="eventPagination"></div>
        </div>
    </div>

    <style>
        @keyframes pulse-live {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        .event-status-tab:hover {
            color: #5e3191 !important;
        }
    </style>

    {{-- Event Detail Modal --}}
    <div id="eventDetailModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 100; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(4px);">
        <div style="background: #fff; width: 100%; max-width: 600px; border-radius: 20px; position: relative; box-shadow: 0 20px 40px rgba(0,0,0,0.2); max-height: 90vh; display: flex; flex-direction: column; overflow: hidden;">
            <button type="button" onclick="closeEventDetailModal()" style="position: absolute; top: 16px; right: 16px; background: rgba(255,255,255,0.8); color: #4b5563; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 20px; cursor: pointer; display: grid; place-items: center; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">&times;</button>
            
            <div id="modalPosterContainer" style="width: 100%; height: 240px; background: #f3f4f6; display: none;">
                <img id="modalPoster" src="" alt="Poster" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            
            <div style="padding: 32px; overflow-y: auto;">
                <span id="modalCategory" style="background: #ede9fe; color: #5b21b6; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: bold; display: inline-block; margin-bottom: 12px;"></span>
                <h2 id="modalTitle" style="margin: 0 0 16px 0; font-size: 24px; font-weight: 800; color: #1e1b4b;"></h2>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 16px; margin-bottom: 24px;">
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <div>
                            <div style="font-size: 13px; color: #6b7280; margin-bottom: 2px;">Waktu Pelaksanaan</div>
                            <div id="modalDate" style="color: #111827; font-weight: 600; font-size: 14px;"></div>
                            <div id="modalEndDate" style="color: #4b5563; font-size: 13px; margin-top: 2px; display: none;"></div>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><path d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 1 1 18 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <div>
                            <div style="font-size: 13px; color: #6b7280; margin-bottom: 2px;">Lokasi</div>
                            <div id="modalLocation" style="color: #111827; font-weight: 600; font-size: 14px;"></div>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <div>
                            <div style="font-size: 13px; color: #6b7280; margin-bottom: 2px;">Penyelenggara</div>
                            <div id="modalOrganization" style="color: #111827; font-weight: 600; font-size: 14px;"></div>
                        </div>
                    </div>
                </div>
                
                <h3 style="font-size: 16px; font-weight: 700; color: #1e1b4b; margin: 0 0 12px; border-top: 1px solid #e5e7eb; padding-top: 20px;">Tentang Event</h3>
                <div id="modalDescription" style="color: #4b5563; font-size: 14px; line-height: 1.6; white-space: pre-wrap;"></div>
            </div>
            
            <div id="modalActionContainer" style="padding: 20px 32px; border-top: 1px solid #e5e7eb; background: #f9fafb;">
                <a id="modalRegisterBtn" href="#" target="_blank" class="btn" style="display: block; text-align: center; text-decoration: none; width: 100%; box-sizing: border-box; background: #5e3191; color: #fff; padding: 14px; border-radius: 10px; font-weight: 700; font-size: 15px;">Daftar Sekarang</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const searchInput = document.getElementById('eventSearchInput');
    const cardsContainer = document.getElementById('eventCards');
    const chips = document.querySelectorAll('#categoryChips .chip');
    const statusTabs = document.querySelectorAll('.event-status-tab');
    let currentFilter = '';
    let currentTab = 'active';
    let currentPage = 1;
    const perPage = 9;

    // Compute event status from timestamps
    function getEventStatus(card) {
        const now = new Date();
        const startStr = card.dataset.start;
        const endStr = card.dataset.end;
        const start = startStr ? new Date(startStr) : null;
        const end = endStr ? new Date(endStr) : null;

        if (end && now > end) return 'finished';
        if (start && now >= start && (!end || now <= end)) return 'running';
        return 'upcoming';
    }

    // Render status badge on a card and toggle registration button
    function renderStatusBadge(card) {
        const badge = card.querySelector('.event-status-badge');
        const regBtn = card.querySelector('.event-register-btn');
        if (!badge) return;
        const status = getEventStatus(card);

        // Hide registration button for finished events
        if (regBtn) regBtn.style.display = (status === 'finished') ? 'none' : 'block';

        if (status === 'running') {
            badge.innerHTML = '<span style="display: inline-flex; align-items: center; gap: 5px; background: #dcfce7; color: #16a34a; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700;"><span style="width: 6px; height: 6px; border-radius: 50%; background: #16a34a; animation: pulse-live 1.5s ease-in-out infinite;"></span>Sedang Berjalan</span>';
        } else if (status === 'finished') {
            badge.innerHTML = '<span style="display: inline-flex; align-items: center; gap: 5px; background: #f3f4f6; color: #6b7280; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700;">Selesai</span>';
        } else {
            badge.innerHTML = '<span style="display: inline-flex; align-items: center; gap: 5px; background: #ede9fe; color: #7c3aed; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700;">Akan Datang</span>';
        }
    }

    // Update all badges and counts
    function updateAllStatuses() {
        const cards = Array.from(cardsContainer.querySelectorAll('.event-card'));
        let activeCount = 0;
        let finishedCount = 0;

        cards.forEach(card => {
            renderStatusBadge(card);
            const status = getEventStatus(card);
            card.dataset.status = status;
            if (status === 'finished') finishedCount++;
            else activeCount++;
        });

        document.getElementById('activeCount').textContent = activeCount;
        document.getElementById('finishedCount').textContent = finishedCount;
    }

    function filterEvents() {
        if (!cardsContainer) return;
        const query = searchInput.value.toLowerCase().trim();
        const cards = Array.from(cardsContainer.querySelectorAll('.event-card'));

        cards.forEach(card => {
            const title = card.dataset.title || '';
            const cat = card.dataset.category || '';
            const status = getEventStatus(card);

            const matchesSearch = !query || title.includes(query);
            const matchesFilter = !currentFilter || cat === currentFilter;
            const matchesTab = (currentTab === 'active') ? (status !== 'finished') : (status === 'finished');
            card._matches = matchesSearch && matchesFilter && matchesTab;
        });

        const matched = cards.filter(c => c._matches);
        const unmatched = cards.filter(c => !c._matches);

        matched.forEach(card => cardsContainer.appendChild(card));
        unmatched.forEach(card => cardsContainer.appendChild(card));

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

        // Show/hide empty states
        const emptyFinished = document.getElementById('emptyFinished');
        const emptyActive = document.getElementById('emptyActive');
        if (currentTab === 'finished' && matched.length === 0) {
            emptyFinished.style.display = 'block';
        } else {
            emptyFinished.style.display = 'none';
        }
        if (currentTab === 'active' && matched.length === 0) {
            emptyActive.style.display = 'block';
        } else {
            emptyActive.style.display = 'none';
        }
    }

    function renderPagination(totalPages) {
        const container = document.getElementById('eventPagination');
        container.innerHTML = '';
        if (totalPages <= 1) return;

        const prevBtn = document.createElement('button');
        prevBtn.className = 'page-btn';
        prevBtn.textContent = '<';
        prevBtn.disabled = currentPage === 1;
        prevBtn.onclick = () => { currentPage--; filterEvents(); window.scrollTo({top: 0, behavior: 'smooth'}); };
        container.appendChild(prevBtn);

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.className = 'page-btn' + (i === currentPage ? ' active' : '');
            btn.textContent = i;
            btn.onclick = () => { currentPage = i; filterEvents(); window.scrollTo({top: 0, behavior: 'smooth'}); };
            container.appendChild(btn);
        }

        const nextBtn = document.createElement('button');
        nextBtn.className = 'page-btn';
        nextBtn.textContent = '>';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.onclick = () => { currentPage++; filterEvents(); window.scrollTo({top: 0, behavior: 'smooth'}); };
        container.appendChild(nextBtn);
    }

    // Tab switching
    statusTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            statusTabs.forEach(t => {
                t.style.background = 'transparent';
                t.style.color = '#6b7280';
                t.style.boxShadow = 'none';
                t.classList.remove('active');
            });
            tab.style.background = '#fff';
            tab.style.color = '#5e3191';
            tab.style.boxShadow = '0 2px 8px rgba(0,0,0,0.06)';
            tab.classList.add('active');
            currentTab = tab.dataset.tab;
            currentPage = 1;
            filterEvents();
        });
    });

    // Category chips
    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            chips.forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
            currentFilter = chip.dataset.category;
            currentPage = 1;
            filterEvents();
        });
    });

    if (searchInput) searchInput.addEventListener('input', () => { currentPage = 1; filterEvents(); });

    // Initial render
    updateAllStatuses();
    filterEvents();

    // Real-time update every 30 seconds
    setInterval(() => {
        updateAllStatuses();
        filterEvents();
    }, 30000);

    function openEventDetailModal(event) {
        document.getElementById('modalTitle').textContent = event.title;
        document.getElementById('modalCategory').textContent = event.category;
        document.getElementById('modalDate').textContent = event.date;
        
        const endDateEl = document.getElementById('modalEndDate');
        if (event.end_date && event.end_date !== event.date) {
            endDateEl.textContent = 'Hingga: ' + event.end_date;
            endDateEl.style.display = 'block';
        } else {
            endDateEl.style.display = 'none';
        }
        
        document.getElementById('modalLocation').textContent = event.location;
        document.getElementById('modalOrganization').textContent = event.organization;
        document.getElementById('modalDescription').textContent = event.description;
        
        const posterContainer = document.getElementById('modalPosterContainer');
        const posterImg = document.getElementById('modalPoster');
        if (event.poster_url) {
            posterImg.src = event.poster_url;
            posterContainer.style.display = 'block';
        } else {
            posterContainer.style.display = 'none';
        }
        
        const actionContainer = document.getElementById('modalActionContainer');
        const registerBtn = document.getElementById('modalRegisterBtn');
        
        // Determine if event is finished
        const now = new Date();
        const endStr = event.end_iso;
        const end = endStr ? new Date(endStr) : null;
        const isFinished = end && now > end;

        if (event.registration_link && !isFinished) {
            registerBtn.href = event.registration_link;
            actionContainer.style.display = 'block';
        } else {
            actionContainer.style.display = 'none';
        }
        
        const modal = document.getElementById('eventDetailModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeEventDetailModal() {
        document.getElementById('eventDetailModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Close modal on click outside
    document.getElementById('eventDetailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEventDetailModal();
        }
    });
</script>
@endpush
