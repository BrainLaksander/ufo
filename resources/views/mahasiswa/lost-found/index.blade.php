@extends('layouts.app')

@section('title', 'Lost & Found')

@section('content')
    <div class="content">
        <div style="padding: 36px 90px;">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 8px;">
                <div>
                    <h1 style="color: #5e3191; font-size: 36px; margin: 0;">Lost & Found</h1>
                    <p style="color:#6b7280; margin: 6px 0 0;">Barang hilang atau temuan di kampus</p>
                </div>
                <button type="button" onclick="document.getElementById('reportModal').style.display='flex'" style="background: #6f3ba7; color: #fff; border: none; padding: 12px 24px; border-radius: 12px; font-size: 15px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Laporkan Barang
                </button>
            </div>

            @if(session('success'))
                <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; font-weight: 600; font-size: 14px;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="search-wrap" style="margin: 26px 0 0;">
                <input class="search" id="lfSearchInput" type="text" placeholder="Cari barang..." aria-label="Cari barang" style="padding-left: 16px;">
            </div>

            <div class="chips" id="lfTypeChips" style="margin: 18px 0 0;">
                <button class="chip active" data-type="">Semua</button>
                <button class="chip" data-type="lost">Hilang (Lost)</button>
                <button class="chip" data-type="found">Ditemukan (Found)</button>
                <button class="chip" data-type="riwayat">Riwayat (Selesai)</button>
            </div>



            <div class="cards" id="lfCards" style="margin: 28px 0;">
                @forelse($items as $item)
                    <article class="card lf-card" data-title="{{ $item->title }}" data-type="{{ $item->type }}" data-status="{{ $item->status }}" data-desc="{{ $item->description }}" style="text-align: left; padding: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                            <span style="display: inline-block; padding: 4px 10px; background: {{ $item->type === 'lost' ? '#fee2e2' : '#dcfce7' }}; color: {{ $item->type === 'lost' ? '#dc2626' : '#166534' }}; border-radius: 999px; font-size: 12px; font-weight: 700; text-transform: uppercase;">
                                {{ $item->type === 'lost' ? 'HILANG (LOST)' : 'DITEMUKAN (FOUND)' }}
                            </span>
                            <span style="color: #6b7280; font-size: 13px;">{{ $item->date ? $item->date->format('d M Y') : '' }}</span>
                        </div>
                        
                        <h3 style="margin: 0 0 8px; font-size: 20px; font-weight: 800; color: #111827;">{{ $item->title }}</h3>
                        
                        <div style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px;">
                            <div style="display: flex; align-items: center; gap: 8px; color: #4b5563; font-size: 14px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $item->location }}
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px; color: #4b5563; font-size: 14px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                Dilaporkan oleh: {{ $item->contact_person }} {{ $item->organization ? '(' . $item->organization->name . ')' : '(Kemahasiswaan)' }}
                            </div>
                        </div>

                        @if($item->image_path)
                        <div style="margin-bottom: 12px; border-radius: 10px; overflow: hidden; max-height: 160px;">
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" style="width: 100%; height: 160px; object-fit: cover; display: block;">
                        </div>
                        @endif

                        <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin: 0 0 20px;">
                            {{ Str::limit($item->description, 100) }}
                        </p>

                        <button type="button" onclick="openLfModal({{ json_encode($item) }})" class="btn" style="display: block; width: 100%; text-align: center; background: #6f3ba7; color: #fff;">Lihat Detail</button>
                    </article>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: #f9fafb; border-radius: 16px; color: #6b7280;">
                        Belum ada laporan barang hilang/temuan saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div id="lfDetailModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 100; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(4px);">
        <div style="background: #fff; width: 100%; max-width: 500px; border-radius: 20px; padding: 32px; position: relative; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
            <button type="button" onclick="document.getElementById('lfDetailModal').style.display='none'" style="position: absolute; top: 16px; right: 16px; background: #f3f4f6; color: #4b5563; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 20px; cursor: pointer; display: grid; place-items: center; transition: background 0.2s;">&times;</button>
            
            <div id="modalTypeBadge" style="display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 12px;"></div>
            
            <h2 id="modalTitle" style="margin: 0 0 16px 0; font-size: 24px; font-weight: 800; color: #111827;"></h2>
            
            <div style="display: grid; gap: 12px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb;">
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <svg style="color: #6f3ba7; flex: 0 0 20px; margin-top: 2px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <div>
                        <div style="font-size: 13px; color: #6b7280; font-weight: 600;">Lokasi</div>
                        <div id="modalLocation" style="color: #111827; font-size: 15px;"></div>
                    </div>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <svg style="color: #6f3ba7; flex: 0 0 20px; margin-top: 2px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <div>
                        <div style="font-size: 13px; color: #6b7280; font-weight: 600;">Tanggal</div>
                        <div id="modalDate" style="color: #111827; font-size: 15px;"></div>
                    </div>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <svg style="color: #6f3ba7; flex: 0 0 20px; margin-top: 2px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <div>
                        <div style="font-size: 13px; color: #6b7280; font-weight: 600;">Dilaporkan Oleh</div>
                        <div id="modalContactPerson" style="color: #111827; font-size: 15px;"></div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <h4 style="margin: 0 0 8px 0; font-size: 15px; color: #111827;">Deskripsi:</h4>
                <p id="modalDesc" style="margin: 0; color: #4b5563; font-size: 15px; line-height: 1.6;"></p>
            </div>

            <a id="modalContactBtn" href="#" onclick="return confirmBemContact(this.href)" target="_blank" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: #25d366; color: #fff; padding: 14px 20px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 16px; transition: background 0.2s;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                Hubungi BEM
            </a>
        </div>
    </div>

    {{-- Report Modal --}}
    <div id="reportModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 100; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(4px);">
        <div style="background: #fff; width: 100%; max-width: 520px; border-radius: 20px; padding: 32px; position: relative; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
            <button type="button" onclick="document.getElementById('reportModal').style.display='none'" style="position: absolute; top: 16px; right: 16px; background: #f3f4f6; color: #4b5563; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 20px; cursor: pointer; display: grid; place-items: center;">&times;</button>
            
            <h2 style="margin: 0 0 8px; font-size: 22px; font-weight: 800; color: #111827;">Laporkan Barang</h2>
            <p style="margin: 0 0 24px; color: #6b7280; font-size: 14px;">Laporan akan direview oleh BEM Universitas sebelum dipublikasikan.</p>
            
            <form action="{{ route('lost-found.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 6px;">Jenis Laporan *</label>
                        <select name="type" required style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; outline: none;">
                            <option value="lost">Barang Hilang (Lost)</option>
                            <option value="found">Barang Ditemukan (Found)</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 6px;">Nama Barang *</label>
                        <input type="text" name="title" required placeholder="Contoh: Dompet Hitam" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 6px;">Deskripsi *</label>
                        <textarea name="description" required rows="3" placeholder="Ciri-ciri barang..." style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; outline: none; resize: vertical;"></textarea>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 6px;">Foto Barang *</label>
                        <input type="file" name="image" accept="image/png,image/jpeg,image/jpg" id="mhsLfImage" required style="display: none;">
                        <label for="mhsLfImage" id="mhsLfImageLabel" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 14px; border: 2px dashed #d1d5db; border-radius: 10px; cursor: pointer; color: #6b7280; font-size: 14px; transition: border-color 0.2s;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                            <span id="mhsLfImageText">Klik untuk upload foto (maks. 5MB)</span>
                        </label>
                        <img id="mhsLfImagePreview" src="" alt="Preview" style="display: none; margin-top: 8px; max-height: 120px; border-radius: 8px; object-fit: cover;">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 6px;">Tanggal *</label>
                            <input type="date" name="date" required max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ date('Y-m-d') }}" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; outline: none;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 6px;">Lokasi *</label>
                            <input type="text" name="location" required placeholder="Contoh: Gedung A Lt. 2" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; outline: none;">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 6px;">Nama Pelapor *</label>
                            <input type="text" name="contact_person" required placeholder="Nama lengkap" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; outline: none;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 6px;">No. HP *</label>
                            <input type="tel" name="contact_phone" required placeholder="08xxxxxxxxxx" pattern="[0-9]{10,15}" title="Hanya angka (10-15 digit)" oninput="this.value = this.value.replace(/[^0-9]/g, '')" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; outline: none;">
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" style="flex: 1; background: #6f3ba7; color: #fff; border: none; padding: 14px; border-radius: 12px; font-size: 15px; font-weight: 700; cursor: pointer;">Kirim Laporan</button>
                    <button type="button" onclick="document.getElementById('reportModal').style.display='none'" style="padding: 14px 24px; background: #f3f4f6; color: #374151; border: none; border-radius: 12px; font-size: 15px; font-weight: 600; cursor: pointer;">Batal</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Image upload preview
        var mhsLfImageInput = document.getElementById('mhsLfImage');
        if (mhsLfImageInput) {
            mhsLfImageInput.addEventListener('change', function() {
                var file = this.files[0];
                var textEl = document.getElementById('mhsLfImageText');
                var previewEl = document.getElementById('mhsLfImagePreview');
                if (file) {
                    textEl.textContent = file.name;
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        previewEl.src = e.target.result;
                        previewEl.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    textEl.textContent = 'Klik untuk upload foto (maks. 5MB)';
                    previewEl.style.display = 'none';
                }
            });
        }

        // Search & Filter Logic
        const searchInput = document.getElementById('lfSearchInput');
        const cardsContainer = document.getElementById('lfCards');
        const typeChips = document.querySelectorAll('#lfTypeChips .chip');
        let currentType = '';

        function filterItems() {
            if (!cardsContainer) return;
            const query = searchInput ? searchInput.value.toLowerCase() : '';
            const cards = document.querySelectorAll('.lf-card');
            let visible = 0;
            
            cards.forEach(card => {
                const title = card.dataset.title.toLowerCase();
                const desc = card.dataset.desc.toLowerCase();
                const type = card.dataset.type;
                const status = card.dataset.status;
                
                const matchesSearch = !query || title.includes(query) || desc.includes(query);
                let matchesType = false;
                
                if (currentType === 'riwayat') {
                    matchesType = status === 'resolved';
                } else {
                    if (status === 'resolved') {
                        matchesType = false;
                    } else {
                        matchesType = !currentType || type === currentType;
                    }
                }
                
                if (matchesSearch && matchesType) {
                    card.style.display = 'block';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Type chips click handler
        typeChips.forEach(chip => {
            chip.addEventListener('click', () => {
                typeChips.forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                currentType = chip.dataset.type;
                filterItems();
            });
        });

        if (searchInput) searchInput.addEventListener('input', filterItems);

        // Run filter on page load to hide resolved items from default view
        filterItems();

        // Detail Modal Logic
        function openLfModal(item) {
            const badge = document.getElementById('modalTypeBadge');
            if (item.type === 'lost') {
                badge.style.background = '#fee2e2';
                badge.style.color = '#dc2626';
                badge.textContent = 'HILANG (LOST)';
            } else {
                badge.style.background = '#dcfce7';
                badge.style.color = '#166534';
                badge.textContent = 'DITEMUKAN (FOUND)';
            }

            document.getElementById('modalTitle').textContent = item.title;
            document.getElementById('modalLocation').textContent = item.location;
            
            // Format date basic
            let dateStr = item.date;
            if (dateStr && dateStr.indexOf('T') !== -1) {
                const d = new Date(dateStr);
                dateStr = d.toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'});
            }
            document.getElementById('modalDate').textContent = dateStr;
            
            const orgName = item.organization ? item.organization.name : 'Kemahasiswaan';
            document.getElementById('modalContactPerson').textContent = item.contact_person + ' (' + orgName + ')';
            document.getElementById('modalDesc').innerText = item.description;

            const btn = document.getElementById('modalContactBtn');
            btn.style.display = 'flex';
            
            // Arahkan ke kontak BEM (Misal: +6281234567890 BEM UNKLAB)
            const bemPhone = '6281234567890'; // Hardcoded BEM Contact for Lost & Found
            btn.href = 'https://wa.me/' + bemPhone + '?text=Halo BEM UNKLAB, saya ingin melaporkan/mengkonfirmasi terkait barang ' + (item.type === 'lost' ? 'hilang' : 'temuan') + ' (' + encodeURIComponent(item.title) + ') yang diposting di UFO.';

            document.getElementById('lfDetailModal').style.display = 'flex';
        }

        function confirmBemContact(url) {
            if (confirm("Anda akan diarahkan ke kontak BEM (WhatsApp) untuk melaporkan atau mengkonfirmasi barang ini. Lanjutkan?")) {
                return true;
            }
            return false;
        }
    </script>
    @endpush
@endsection
