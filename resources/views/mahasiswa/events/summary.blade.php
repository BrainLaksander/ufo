@extends('layouts.app')

@section('title', 'Event Kampus - Ringkasan Bulanan')

@section('content')
    <div class="content">
        <div style="padding: 36px 90px;">
            <h1 style="color: #5e3191; font-size: 36px; margin: 0 0 8px;">Event Kampus</h1>
            <p style="color:#6b7280; margin: 6px 0 18px;">Ringkasan kegiatan bulanan di UNKLAB</p>

            <div class="search-wrap" style="margin: 26px 0 0;">
                <input class="search" id="eventSearchInput" type="text" placeholder="Cari event..." aria-label="Cari event" style="padding-left: 16px;">
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px; flex-wrap: wrap; gap: 16px;">
                <div class="chips" id="categoryChips" style="margin: 0;">
                    <button class="chip active" data-category="">Semua</button>
                    @foreach(['Pelayanan/Sosial (PKM)', 'Kebudayaan', 'Akademik', 'Keagamaan/Kerohanian', 'Minat/Bakat', 'Kebangsaan'] as $cat)
                        <button class="chip" data-category="{{ strtolower($cat) }}">{{ $cat }}</button>
                    @endforeach
                </div>

                <div style="display: flex; background: #f3f4f6; padding: 4px; border-radius: 8px; flex-shrink: 0;">
                    <a href="{{ route('events.index', ['mode' => 'detail']) }}" style="padding: 6px 12px; border-radius: 6px; text-decoration: none; color: #6b7280; font-size: 14px; font-weight: 600;">
                        Grid Detail
                    </a>
                    <a href="{{ route('events.index', ['mode' => 'summary']) }}" style="padding: 6px 12px; border-radius: 6px; text-decoration: none; color: #111827; font-size: 14px; font-weight: 600; background: #fff; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                        List Ringkasan
                    </a>
                </div>
            </div>

            <div class="summary-container" style="max-width: 800px; margin: 0 auto;">
                @forelse($monthlySummary as $month => $categories)
                    <div class="month-block" style="margin-bottom: 40px;">
                        <h2 style="font-size: 24px; font-weight: 800; color: #111827; border-bottom: 2px solid #e5e7eb; padding-bottom: 12px; margin-bottom: 20px;">{{ $month }}</h2>
                        
                        <div class="category-grid" style="display: flex; flex-direction: column; gap: 16px;">
                            @foreach($categories as $categoryName => $events)
                                <div class="category-box" data-category="{{ strtolower($categoryName) }}" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
                                    <h3 style="font-size: 16px; color: #5e3191; margin: 0 0 16px 0; display: flex; align-items: center; gap: 10px;">
                                        <span style="background: #ede9fe; padding: 4px 12px; border-radius: 999px; font-weight: 700;">{{ $categoryName }}</span>
                                        <span style="font-size: 13px; color: #64748b; font-weight: 500;">({{ $events->count() }} Kegiatan)</span>
                                    </h3>
                                    
                                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;">
                                        @foreach($events as $event)
                                            <li class="event-item" data-title="{{ strtolower($event->title) }}" onclick="openEventDetailModal({{ json_encode([
                                                'title' => $event->title,
                                                'category' => $event->category ?? 'Umum',
                                                'date' => optional($event->start_at)->translatedFormat('d F Y, H:i'),
                                                'end_date' => $event->end_at ? \Carbon\Carbon::parse($event->end_at)->translatedFormat('d F Y, H:i') : null,
                                                'location' => $event->location ?? '-',
                                                'organization' => $event->organization->name ?? '-',
                                                'description' => $event->description ?? 'Tidak ada deskripsi',
                                                'poster_url' => $event->poster_path ? Storage::url($event->poster_path) : null,
                                                'registration_link' => $event->registration_link,
                                                'start_iso' => $event->start_at ? \Carbon\Carbon::parse($event->start_at)->toIso8601String() : null,
                                                'end_iso' => $event->end_at ? \Carbon\Carbon::parse($event->end_at)->toIso8601String() : null
                                            ]) }})" style="display: flex; justify-content: space-between; align-items: center; font-size: 15px; padding: 12px; background: #fff; border-radius: 8px; border: 1px solid #f1f5f9; box-shadow: 0 1px 2px rgba(0,0,0,0.02); cursor: pointer; transition: all 0.2s;">
                                                <span style="color: #334155; font-weight: 600;">{{ $event->title }}</span>
                                                <span style="color: #64748b; font-variant-numeric: tabular-nums; font-size: 14px; background: #f1f5f9; padding: 4px 10px; border-radius: 6px;">
                                                    {{ \Carbon\Carbon::parse($event->start_at)->translatedFormat('d M') }}
                                                    @if($event->end_at && \Carbon\Carbon::parse($event->start_at)->toDateString() !== \Carbon\Carbon::parse($event->end_at)->toDateString())
                                                        - {{ \Carbon\Carbon::parse($event->end_at)->translatedFormat('d M') }}
                                                    @endif
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 60px 20px; background: #f9fafb; border-radius: 16px; border: 2px dashed #e5e7eb;">
                        <h3 style="color: #374151; font-size: 18px; font-weight: 600; margin: 0 0 8px;">Belum Ada Agenda</h3>
                        <p style="color: #6b7280; font-size: 15px; margin: 0;">Tidak ada kegiatan yang dijadwalkan pada kategori/waktu ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

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
    const chips = document.querySelectorAll('#categoryChips .chip');
    const monthBlocks = document.querySelectorAll('.month-block');
    let currentFilter = '';

    function filterEvents() {
        const query = searchInput.value.toLowerCase().trim();
        
        monthBlocks.forEach(month => {
            const catBoxes = month.querySelectorAll('.category-box');
            let monthHasVisible = false;

            catBoxes.forEach(box => {
                const boxCat = box.dataset.category || '';
                const items = box.querySelectorAll('.event-item');
                let boxHasVisible = false;

                // Check category
                const matchesFilter = !currentFilter || boxCat === currentFilter;

                if (matchesFilter) {
                    items.forEach(item => {
                        const title = item.dataset.title || '';
                        const matchesSearch = !query || title.includes(query);
                        
                        item.style.display = matchesSearch ? 'flex' : 'none';
                        if (matchesSearch) boxHasVisible = true;
                    });
                }

                box.style.display = boxHasVisible && matchesFilter ? 'block' : 'none';
                if (boxHasVisible && matchesFilter) monthHasVisible = true;
            });

            month.style.display = monthHasVisible ? 'block' : 'none';
        });
    }

    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            chips.forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
            currentFilter = chip.dataset.category;
            filterEvents();
        });
    });

    if (searchInput) searchInput.addEventListener('input', filterEvents);

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
        document.body.style.overflow = 'hidden'; // Prevent scrolling
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
