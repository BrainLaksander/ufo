@extends('layouts.mahasiswa')

@section('title', 'Organisasi - Mahasiswa')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
  <div class="relative w-full h-64 md:h-80 bg-gray-300 rounded-lg overflow-hidden mb-8">
    <div id="carousel" class="relative w-full h-full">
      <img id="carousel-img" src="https://images.unsplash.com/photo-1655472355485-d949925e67bb?w=1200&h=500&fit=crop" alt="Carousel" class="w-full h-full object-cover">
      <button id="prev-slide" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white px-3 py-2 rounded hover:bg-opacity-75 transition z-10">❮</button>
      <button id="next-slide" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white px-3 py-2 rounded hover:bg-opacity-75 transition z-10">❯</button>
      <div id="carousel-dots" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2"></div>
    </div>
  </div>

  <div class="mb-8 space-y-4">
    <input id="search-input" type="text" placeholder="Cari organisasi..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
    
    <div class="flex gap-2 overflow-x-auto pb-2">
      <button class="filter-btn active px-4 py-2 rounded-full bg-purple-700 text-white font-medium whitespace-nowrap transition" data-filter="all">Semua</button>
      <button class="filter-btn px-4 py-2 rounded-full border-2 border-purple-700 text-purple-700 hover:bg-purple-700 hover:text-white whitespace-nowrap transition" data-filter="BEM">BEM</button>
      <button class="filter-btn px-4 py-2 rounded-full border-2 border-purple-700 text-purple-700 hover:bg-purple-700 hover:text-white whitespace-nowrap transition" data-filter="Choir">Choir</button>
      <button class="filter-btn px-4 py-2 rounded-full border-2 border-purple-700 text-purple-700 hover:bg-purple-700 hover:text-white whitespace-nowrap transition" data-filter="Creative">Creative</button>
      <button class="filter-btn px-4 py-2 rounded-full border-2 border-purple-700 text-purple-700 hover:bg-purple-700 hover:text-white whitespace-nowrap transition" data-filter="Ministries">Ministries</button>
      <button class="filter-btn px-4 py-2 rounded-full border-2 border-purple-700 text-purple-700 hover:bg-purple-700 hover:text-white whitespace-nowrap transition" data-filter="Daerah">Daerah</button>
      <button class="filter-btn px-4 py-2 rounded-full border-2 border-purple-700 text-purple-700 hover:bg-purple-700 hover:text-white whitespace-nowrap transition" data-filter="Photography">Photography</button>
      <button class="filter-btn px-4 py-2 rounded-full border-2 border-purple-700 text-purple-700 hover:bg-purple-700 hover:text-white whitespace-nowrap transition" data-filter="Himpunan">Himpunan</button>
      <button class="filter-btn px-4 py-2 rounded-full border-2 border-purple-700 text-purple-700 hover:bg-purple-700 hover:text-white whitespace-nowrap transition" data-filter="English">English</button>
    </div>
  </div>

  <div id="org-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
</div>

<script>
window.orgsData = [
  { id: 1, name: "BEM UNKLAB", tagline: "Bersama Membangun, Bersama Melayani", category: "BEM", emoji: "🏛️" },
  { id: 2, name: "UNKLAB Choir", tagline: "Menyanyikan Kemuliaan Tuhan", category: "Choir", emoji: "🎵" },
  { id: 3, name: "Creative Media Club", tagline: "Kreativitas Tanpa Batas", category: "Creative", emoji: "🎨" },
  { id: 4, name: "PMKO", tagline: "Persekutuan Mahasiswa Kristen Oikumene", category: "Ministries", emoji: "⛪" },
  { id: 5, name: "Ikatan Mahasiswa Manado", tagline: "Persatuan Anak Manado", category: "Daerah", emoji: "🏝️" },
  { id: 6, name: "Photography Club", tagline: "Seni Menangkap Momen", category: "Photography", emoji: "📷" },
  { id: 7, name: "Himpunan Mahasiswa Teknik", tagline: "Teknik Untuk Masa Depan", category: "Himpunan", emoji: "⚙️" },
  { id: 8, name: "English Club", tagline: "Meningkatkan Kemampuan Bahasa Inggris", category: "English", emoji: "🌍" },
];

window.carouselImages = [
  "https://images.unsplash.com/photo-1655472355485-d949925e67bb?w=1200&h=500&fit=crop",
  "https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200&h=500&fit=crop",
  "https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1200&h=500&fit=crop"
];
</script>
@endsection
