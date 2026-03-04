@extends('layouts.mahasiswa')

@section('title', 'Lost & Found - Mahasiswa')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">🔍 Lost & Found Kampus</h1>
        <p class="text-gray-600 text-lg">Lapor barang hilang & temukan barang Anda. Hubungi pelapor jika menemukan barang mereka.</p>
    </div>

    <!-- Priority Section -->
    @php
        $priorityItems = collect($items)->filter(fn($item) => $item['priority'] && $item['itemStatus'] === 'hilang');
    @endphp
    @if($priorityItems->isNotEmpty())
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-red-600 mb-4">🔴 Barang Penting yang Hilang</h3>
        <p class="text-gray-600 mb-4">Barang-barang di bawah memiliki nilai penting. Jika Anda menemukan, segera hubungi pelapor.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            @foreach($priorityItems as $item)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow cursor-pointer border-2 border-red-300 p-5" onclick="openDetail({{ $item['id'] }})">
                <div class="flex items-start justify-between mb-3">
                    <span class="inline-block px-3 py-1 text-xs font-bold bg-red-100 text-red-600 rounded-full">⭐ Penting</span>
                    <span class="inline-block px-3 py-1 text-xs font-bold bg-red-100 text-red-600 rounded-full">✗ Hilang</span>
                </div>
                <h4 class="text-lg font-bold text-gray-800 mb-2">{{ $item['name'] }}</h4>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $item['description'] }}</p>
                <div class="text-xs text-gray-500 space-y-1">
                    <p>📍 {{ $item['location'] }}</p>
                    <p>📅 {{ \Carbon\Carbon::parse($item['date'])->format('d M Y') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tabs -->
    <div class="flex gap-4 border-b-2 border-gray-200 mb-8">
        <button class="tab-btn active px-4 py-3 font-semibold text-gray-800 border-b-2 border-blue-600 -mb-0.5" data-tab="hilang">✗ Barang Hilang</button>
        <button class="tab-btn px-4 py-3 font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-700" data-tab="ditemukan">✓ Barang Ditemukan</button>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6">
        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="searchInput" placeholder="🔍 Cari nama barang, lokasi, atau deskripsi..." />
    </div>

    <div class="mb-8">
        <label class="block text-sm font-bold text-gray-700 mb-3">Kategori:</label>
        <div id="categoryFilter" class="flex flex-wrap gap-2">
            <button class="filter-btn active px-4 py-2 rounded-full bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors" data-category="all">Semua</button>
            <button class="filter-btn px-4 py-2 rounded-full border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-colors" data-category="Dompet">💼 Dompet</button>
            <button class="filter-btn px-4 py-2 rounded-full border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-colors" data-category="Kunci">🔑 Kunci</button>
            <button class="filter-btn px-4 py-2 rounded-full border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-colors" data-category="Buku">📖 Buku</button>
            <button class="filter-btn px-4 py-2 rounded-full border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-colors" data-category="Elektronik">💻 Elektronik</button>
            <button class="filter-btn px-4 py-2 rounded-full border-2 border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-colors" data-category="Kartu Identitas">🆔 Kartu</button>
        </div>
    </div>

    <!-- Items Grid -->
    <div id="hilanTab" class="tab-content">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="hilanList">
            @forelse(collect($items)->where('itemStatus', 'hilang') as $item)
            <div class="item-card bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow cursor-pointer p-5" data-category="{{ $item['category'] }}" onclick="openDetail({{ $item['id'] }})">
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-block px-3 py-1 text-xs font-bold bg-red-100 text-red-600 rounded-full">✗ Hilang</span>
                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-gray-200 text-gray-700 rounded">{{ $item['category'] }}</span>
                </div>
                <h4 class="text-lg font-bold text-gray-800 mb-2">{{ $item['name'] }}</h4>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $item['description'] }}</p>
                <div class="text-xs text-gray-500 space-y-1">
                    <p>📍 {{ $item['location'] }}</p>
                    <p>📅 {{ \Carbon\Carbon::parse($item['date'])->format('d M Y') }}</p>
                </div>
            </div>
            @empty
            <p class="col-span-full text-gray-500 text-center py-8">Tidak ada barang hilang</p>
            @endforelse
        </div>
    </div>

    <div id="ditemukanTab" class="tab-content hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="ditemukanList">
            @forelse(collect($items)->where('itemStatus', 'ditemukan') as $item)
            <div class="item-card bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow cursor-pointer p-5" data-category="{{ $item['category'] }}" onclick="openDetail({{ $item['id'] }})">
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-block px-3 py-1 text-xs font-bold bg-green-100 text-green-600 rounded-full">✓ Ditemukan</span>
                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-gray-200 text-gray-700 rounded">{{ $item['category'] }}</span>
                </div>
                <h4 class="text-lg font-bold text-gray-800 mb-2">{{ $item['name'] }}</h4>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $item['description'] }}</p>
                <div class="text-xs text-gray-500 space-y-1">
                    <p>📍 {{ $item['location'] }}</p>
                    <p>📅 {{ \Carbon\Carbon::parse($item['date'])->format('d M Y') }}</p>
                </div>
            </div>
            @empty
            <p class="col-span-full text-gray-500 text-center py-8">Tidak ada barang ditemukan</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between">
            <h3 class="text-2xl font-bold text-gray-800" id="detailTitle">Detail Barang</h3>
            <button onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <div class="p-6" id="detailContent">
            <!-- Konten akan diisi oleh JavaScript -->
        </div>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .tab-content {
        animation: fadeIn 0.3s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

<script>
    const items = @json($items);

    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('text-gray-800', 'border-blue-600');
                b.classList.add('text-gray-500', 'border-transparent');
            });
            
            this.classList.remove('text-gray-500', 'border-transparent');
            this.classList.add('text-gray-800', 'border-blue-600');
            
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            document.getElementById(tabName + 'Tab').classList.remove('hidden');
        });
    });

    function openDetail(id) {
        const item = items.find(i => i.id === id);
        if (item) {
            document.getElementById('detailTitle').textContent = item.name;
            const waLink = `https://wa.me/62${item.phone.substring(1)}`;
            const emailLink = `mailto:${item.contact}`;
            
            const statusBadge = item.itemStatus === 'ditemukan' 
                ? '<span class="inline-block px-3 py-1 text-xs font-bold bg-green-100 text-green-600 rounded-full">✓ Ditemukan</span>'
                : '<span class="inline-block px-3 py-1 text-xs font-bold bg-red-100 text-red-600 rounded-full">✗ Hilang</span>';
            
            document.getElementById('detailContent').innerHTML = `
                <div>
                    <div class="flex flex-wrap gap-2 mb-4">
                        ${statusBadge}
                        <span class="inline-block px-3 py-1 text-xs font-semibold bg-gray-200 text-gray-700 rounded">${item.category}</span>
                        ${item.priority ? '<span class="inline-block px-3 py-1 text-xs font-bold bg-yellow-100 text-yellow-600 rounded-full">⭐ Penting</span>' : ''}
                    </div>
                    
                    <div class="border-t border-b border-gray-200 py-4 mb-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">📍 Lokasi</p>
                                <p class="font-semibold text-gray-800">${item.location}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">📅 Tanggal</p>
                                <p class="font-semibold text-gray-800">${new Date(item.date).toLocaleDateString('id-ID', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' })}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="font-bold text-gray-800 mb-2">📝 Deskripsi Lengkap</h4>
                        <p class="text-gray-700 whitespace-pre-line leading-relaxed">${item.description}</p>
                    </div>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-yellow-800"><strong>⚠️ Keamanan:</strong> Jangan bertemu sendirian. Bertemu di tempat umum dan ramai.</p>
                    </div>
                    
                    <div>
                        <p class="font-bold text-gray-800 mb-3">📞 Hubungi Pelapor</p>
                        <a href="${emailLink}" class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors mb-2">✉️ Email</a>
                        <a href="${waLink}" target="_blank" class="block w-full bg-green-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">💬 WhatsApp</a>
                    </div>
                </div>
            `;
            document.getElementById('detailModal').classList.remove('hidden');
        }
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    // Search
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll('.item-card').forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(query) ? '' : 'none';
        });
    });

    // Category filter
    document.getElementById('categoryFilter').addEventListener('click', function(e) {
        if (e.target.tagName === 'BUTTON') {
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('border-2', 'border-gray-300', 'text-gray-700');
            });
            
            e.target.classList.remove('border-2', 'border-gray-300', 'text-gray-700');
            e.target.classList.add('bg-blue-600', 'text-white');
            
            const category = e.target.getAttribute('data-category');
            document.querySelectorAll('.item-card').forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                card.style.display = category === 'all' || cardCategory === category ? '' : 'none';
            });
        }
    });

    // Close modal when clicking outside
    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });
</script>
@endsection
