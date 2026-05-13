@extends('layouts.app')

@section('title', 'Mahasiswa - UFO')

@section('content')
    <div class="content">
            <section class="hero">
                <div class="hero-content">
                    @isset($heroImages)
                        @if(count($heroImages) > 0)
                            <div class="hero-slider" id="heroSlider">
                                @foreach($heroImages as $i => $img)
                                    <div class="hero-slide {{ $i === 0 ? 'active' : '' }}" style="background-image: url('{{ $img }}');"></div>
                                @endforeach
                            </div>
                        @else
                            <div class="hero-slider">
                                <div class="hero-slide active" style="background: linear-gradient(135deg, #6f3ba7 0%, #a476d1 50%, #f0b84c 100%);"></div>
                            </div>
                        @endif
                    @else
                        <div class="hero-slider">
                            <div class="hero-slide active" style="background: linear-gradient(135deg, #6f3ba7 0%, #a476d1 50%, #f0b84c 100%);"></div>
                        </div>
                    @endisset
                </div>
                @isset($heroImages)
                    @if(count($heroImages) > 1)
                        <button class="arrow left" type="button" aria-label="Sebelumnya" onclick="prevSlide()">‹</button>
                        <button class="arrow right" type="button" aria-label="Berikutnya" onclick="nextSlide()">›</button>
                        <div class="dots" aria-hidden="true" id="heroDots">
                            @foreach($heroImages as $i => $img)
                                <span class="dot{{ $i === 0 ? ' active' : '' }}" onclick="goToSlide({{ $i }})"></span>
                            @endforeach
                        </div>
                    @endif
                @endisset
            </section>

            <div class="search-wrap">
                <input class="search" id="orgSearchInput" type="text" placeholder="Cari organisasi..." aria-label="Cari organisasi" style="padding-left: 16px;">
            </div>

            <div class="chips" id="categoryChips">
                <button class="chip active" data-category="">Semua</button>
                <button class="chip" data-category="bem">BEM</button>
                <button class="chip" data-category="choir">Choir</button>
                <button class="chip" data-category="creative club">Creative Club</button>
                <button class="chip" data-category="ministries">Ministries</button>
                <button class="chip" data-category="ikatan daerah">Ikatan Daerah</button>
            </div>

            <div class="toolbar">

                <select class="sort" id="orgSort" aria-label="Urutkan organisasi" style="width: auto; min-width: 150px;">
                    <option value="az">A-Z</option>
                    <option value="za">Z-A</option>
                </select>
            </div>

            <div class="cards" id="orgCards">
                @isset($organizations)
                    @foreach($organizations as $org)
                        <article class="card" data-name="{{ $org->name }}" data-category="{{ $org->kategori }}" style="display: flex; flex-direction: column; align-items: center; justify-content: space-between;">
                            <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                                <div class="avatar" style="background: linear-gradient(135deg, #a476d1, #f0b84c); color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: bold; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 16px;">
                                    @if($org->logo_path)
                                        <img src="{{ Storage::url($org->logo_path) }}" alt="{{ $org->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        {{ strtoupper(substr($org->name, 0, 1)) }}
                                    @endif
                                </div>
                                <h3 style="margin: 0 0 6px 0; font-size: 16px; font-weight: 800; color: #111827; text-align: center;">{{ $org->name }}</h3>
                                <p style="font-size: 13px; color: #6b7280; margin: 0; text-align: center; padding: 0 10px;">{{ $org->motto ?? $org->kategori }}</p>
                                @if($org->is_open_recruitment)
                                    <div style="margin-top: 10px; display: inline-flex; align-items: center; gap: 4px; background: #dcfce7; color: #16a34a; padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700;">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #16a34a; display: inline-block;"></span>
                                        Open Recruitment
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('organisasi.show', $org->id) }}" class="btn" style="display: block; text-decoration: none; text-align: center; width: 100%; margin-top: 24px; background: #6f3ba7; color: #fff;">Lihat Detail</a>
                        </article>
                    @endforeach
                @endisset
            </div>

            <div class="pagination-wrap" id="orgPagination"></div>
        </div>
    </div>

    {{-- Pass organization data to JS for search/sort --}}
    <script>
        // Search, Sort & Filter functionality
        const searchInput = document.getElementById('orgSearchInput');
        const sortSelect = document.getElementById('orgSort');
        const cardsContainer = document.getElementById('orgCards');
        const countEl = document.getElementById('orgCount');
        const chips = document.querySelectorAll('.chip');
        let currentFilter = '';
        let currentPage = 1;
        const perPage = 9;

        function filterAndSort() {
            const query = searchInput.value.toLowerCase().trim();
            const cards = Array.from(cardsContainer.querySelectorAll('.card'));
            
            // Determine which cards match
            cards.forEach(card => {
                const name = (card.dataset.name || '').toLowerCase();
                const cat = (card.dataset.category || '').toLowerCase();
                const matchesSearch = !query || name.includes(query) || cat.includes(query);
                const matchesFilter = !currentFilter || cat === currentFilter;
                card._matches = matchesSearch && matchesFilter;
            });

            // Sort matched cards
            const matched = cards.filter(c => c._matches)
                .sort((a, b) => {
                    const nameA = (a.dataset.name || '').toLowerCase();
                    const nameB = (b.dataset.name || '').toLowerCase();
                    return sortSelect.value === 'za' ? nameB.localeCompare(nameA) : nameA.localeCompare(nameB);
                });
            const unmatched = cards.filter(c => !c._matches);

            // Reorder DOM
            matched.forEach(card => cardsContainer.appendChild(card));
            unmatched.forEach(card => cardsContainer.appendChild(card));

            if (countEl) countEl.textContent = matched.length + ' organisasi ditemukan';

            // Pagination
            const totalPages = Math.max(1, Math.ceil(matched.length / perPage));
            if (currentPage > totalPages) currentPage = totalPages;

            const startIdx = (currentPage - 1) * perPage;
            const endIdx = startIdx + perPage;

            matched.forEach((card, i) => {
                card.style.display = (i >= startIdx && i < endIdx) ? 'flex' : 'none';
            });
            unmatched.forEach(card => { card.style.display = 'none'; });

            renderPagination(matched.length, totalPages);
        }

        function renderPagination(totalItems, totalPages) {
            const container = document.getElementById('orgPagination');
            container.innerHTML = '';
            if (totalPages <= 1) return;

            const prevBtn = document.createElement('button');
            prevBtn.className = 'page-btn';
            prevBtn.textContent = '<';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => { currentPage--; filterAndSort(); window.scrollTo({top: 0, behavior: 'smooth'}); };
            container.appendChild(prevBtn);

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.className = 'page-btn' + (i === currentPage ? ' active' : '');
                btn.textContent = i;
                btn.onclick = () => { currentPage = i; filterAndSort(); window.scrollTo({top: 0, behavior: 'smooth'}); };
                container.appendChild(btn);
            }

            const nextBtn = document.createElement('button');
            nextBtn.className = 'page-btn';
            nextBtn.textContent = '>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => { currentPage++; filterAndSort(); window.scrollTo({top: 0, behavior: 'smooth'}); };
            container.appendChild(nextBtn);
        }

        chips.forEach(chip => {
            chip.addEventListener('click', () => {
                chips.forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                currentFilter = chip.dataset.category;
                currentPage = 1;
                filterAndSort();
            });
        });

        if (searchInput) searchInput.addEventListener('input', () => { currentPage = 1; filterAndSort(); });
        if (sortSelect) sortSelect.addEventListener('change', () => { currentPage = 1; filterAndSort(); });

        filterAndSort();

        // Hero Slider Logic
        let currentSlide = 0;
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.dots .dot');
        
        function updateSlider() {
            if (slides.length === 0) return;
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === currentSlide);
            });
            if (dots.length > 0) {
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentSlide);
                });
            }
        }
        
        function nextSlide() {
            if (slides.length <= 1) return;
            currentSlide = (currentSlide + 1) % slides.length;
            updateSlider();
        }
        
        function prevSlide() {
            if (slides.length <= 1) return;
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            updateSlider();
        }
        
        function goToSlide(index) {
            currentSlide = index;
            updateSlider();
        }
        
        // Auto slide every 5 seconds
        if (slides.length > 1) {
            setInterval(nextSlide, 5000);
        }
    </script>
@endsection
