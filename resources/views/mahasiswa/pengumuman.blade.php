@extends('layouts.mahasiswa')

@section('title', 'Pengumuman - Mahasiswa')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">📢 Pengumuman Kampus</h1>
        <p class="text-gray-600 text-lg">Berita dan informasi penting untuk semua mahasiswa UNKLAB</p>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex gap-4 border-b-2 border-gray-200 mb-8">
        <button class="tab-btn active px-4 py-3 font-semibold text-gray-800 border-b-2 border-blue-600 -mb-0.5" data-tab="semua">Semua</button>
        <button class="tab-btn px-4 py-3 font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-700" data-tab="akademik">Akademik</button>
        <button class="tab-btn px-4 py-3 font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-700" data-tab="organisasi">Organisasi</button>
        <button class="tab-btn px-4 py-3 font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-700" data-tab="event">Event</button>
    </div>

    <!-- Search -->
    <div class="mb-8">
        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" id="searchInput" placeholder="🔍 Cari pengumuman..." />
    </div>

    <!-- Tab Contents -->
    <div id="semuaTab" class="tab-content">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="semaList">
            @forelse($pengumuman as $item)
            <div class="pengumuman-item bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow cursor-pointer p-5" data-kategori="{{ strtolower($item['kategori']) }}" onclick="loadDetail({{ $item['id'] }})">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex flex-wrap gap-2">
                        @if($item['is_new'])
                        <span class="inline-block px-3 py-1 text-xs font-bold bg-yellow-100 text-yellow-600 rounded-full">🆕 Baru</span>
                        @endif
                        @if($item['is_important'])
                        <span class="inline-block px-3 py-1 text-xs font-bold bg-red-100 text-red-600 rounded-full">⭐ Penting</span>
                        @endif
                    </div>
                    <small class="text-gray-500 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($item['date'])->format('d M Y') }}</small>
                </div>
                <h4 class="text-lg font-bold text-gray-800 mb-2">{{ $item['judul'] }}</h4>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $item['ringkasan'] }}</p>
                <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-gray-200 text-gray-700 rounded">{{ $item['kategori'] }}</span>
                    <small class="text-gray-500 text-xs">{{ $item['author'] }}</small>
                </div>
            </div>
            @empty
            <p class="col-span-full text-gray-500 text-center py-8">Tidak ada pengumuman</p>
            @endforelse
        </div>
    </div>

    <!-- Kategori Tabs -->
    @foreach(['akademik' => 'Akademik', 'organisasi' => 'Organisasi', 'event' => 'Event'] as $tab => $label)
    <div id="{{ $tab }}Tab" class="tab-content hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $filtered = collect($pengumuman)->filter(fn($item) => strtolower($item['kategori']) === strtolower($label));
            @endphp
            @forelse($filtered as $item)
            <div class="pengumuman-item bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow cursor-pointer p-5" data-kategori="{{ strtolower($item['kategori']) }}" onclick="loadDetail({{ $item['id'] }})">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex flex-wrap gap-2">
                        @if($item['is_new'])
                        <span class="inline-block px-3 py-1 text-xs font-bold bg-yellow-100 text-yellow-600 rounded-full">🆕 Baru</span>
                        @endif
                        @if($item['is_important'])
                        <span class="inline-block px-3 py-1 text-xs font-bold bg-red-100 text-red-600 rounded-full">⭐ Penting</span>
                        @endif
                    </div>
                    <small class="text-gray-500 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($item['date'])->format('d M Y') }}</small>
                </div>
                <h4 class="text-lg font-bold text-gray-800 mb-2">{{ $item['judul'] }}</h4>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $item['ringkasan'] }}</p>
                <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-gray-200 text-gray-700 rounded">{{ $item['kategori'] }}</span>
                    <small class="text-gray-500 text-xs">{{ $item['author'] }}</small>
                </div>
            </div>
            @empty
            <p class="col-span-full text-gray-500 text-center py-8">Tidak ada pengumuman {{ $label }}</p>
            @endforelse
        </div>
    </div>
    @endforeach
</div>

<!-- Detail Modal -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between">
            <h3 class="text-2xl font-bold text-gray-800" id="detailTitle">Detail Pengumuman</h3>
            <button onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <div class="p-6" id="detailContent">
            <!-- Konten akan diisi oleh JavaScript -->
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between">
            <h3 class="text-2xl font-bold text-gray-800" id="detailTitle">Detail Pengumuman</h3>
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
    const pengumuman = @json($pengumuman);

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

    function loadDetail(id) {
        const item = pengumuman.find(p => p.id === id);
        if (item) {
            document.getElementById('detailTitle').textContent = item.judul;
            
            const attachments = Array.isArray(item.lampiran) 
                ? item.lampiran.map(f => `<a href="#" class="inline-block px-4 py-2 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition-colors">📥 ${f}</a>`).join(' ')
                : '';
            
            document.getElementById('detailContent').innerHTML = `
                <div>
                    <div class="flex flex-wrap gap-2 mb-4">
                        ${item.is_new ? '<span class="inline-block px-3 py-1 text-xs font-bold bg-yellow-100 text-yellow-600 rounded-full">🆕 Baru</span>' : ''}
                        ${item.is_important ? '<span class="inline-block px-3 py-1 text-xs font-bold bg-red-100 text-red-600 rounded-full">⭐ Penting</span>' : ''}
                        <span class="inline-block px-3 py-1 text-xs font-semibold bg-gray-200 text-gray-700 rounded">${item.kategori}</span>
                    </div>
                    
                    <div class="border-b border-gray-200 py-3 mb-4">
                        <p class="text-sm text-gray-600">
                            <strong>✍️ Penulis:</strong> ${item.author} | <strong>📅 Tanggal:</strong> ${new Date(item.date).toLocaleDateString('id-ID', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' })}
                        </p>
                    </div>
                    
                    <div class="mb-6 text-gray-700 whitespace-pre-line leading-relaxed">
                        ${item.konten}
                    </div>
                    
                    ${attachments ? `
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="font-bold text-gray-800 mb-3">📎 Lampiran</h4>
                        <div class="flex flex-wrap gap-2">
                            ${attachments}
                        </div>
                    </div>
                    ` : ''}
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
        document.querySelectorAll('.pengumuman-item').forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(query) ? '' : 'none';
        });
    });

    // Close modal when clicking outside
    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });
</script>
@endsection
