@extends('layouts.mahasiswa')

@section('title', 'Organisasi - Mahasiswa')

@section('content')
<!-- Main Container -->
<div class="w-full bg-gradient-to-b from-blue-50 to-white min-h-screen">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    
    <!-- Page Header -->
    <div class="mb-8">
      <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">Daftar Organisasi</h1>
      <p class="text-gray-600 text-base sm:text-lg">Temukan dan bergabung dengan organisasi yang sesuai dengan minat Anda</p>
    </div>

    <!-- Carousel Section -->
    <div class="relative w-full mb-10 overflow-hidden rounded-xl shadow-lg">
      <div id="carousel" class="relative w-full h-48 sm:h-64 md:h-80 bg-gradient-to-r from-purple-600 to-purple-800">
        <img id="carousel-img" src="https://images.unsplash.com/photo-1655472355485-d949925e67bb?w=1200&h=500&fit=crop" alt="Carousel" class="w-full h-full object-cover">
        
        <!-- Navigation Buttons -->
        <button id="prev-slide" class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-black/80 text-white px-2 sm:px-3 py-2 rounded-lg transition-all duration-200 z-10 focus:outline-none focus:ring-2 focus:ring-yellow-400" aria-label="Previous slide">
          <span class="text-lg sm:text-xl">❮</span>
        </button>
        <button id="next-slide" class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-black/80 text-white px-2 sm:px-3 py-2 rounded-lg transition-all duration-200 z-10 focus:outline-none focus:ring-2 focus:ring-yellow-400" aria-label="Next slide">
          <span class="text-lg sm:text-xl">❯</span>
        </button>
        
        <!-- Carousel Dots -->
        <div id="carousel-dots" class="absolute bottom-3 sm:bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10"></div>
      </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="mb-10">
      <!-- Search Bar -->
      <div class="mb-6">
        <div class="relative">
          <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 text-lg">🔍</span>
          <input 
            id="search-input" 
            type="text" 
            placeholder="Cari organisasi..." 
            class="w-full pl-12 pr-4 py-3 sm:py-3.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition-all duration-200 text-gray-900 placeholder-gray-500"
          >
        </div>
      </div>
      
      <!-- Category Filter Chips - Horizontal Scroll on Mobile -->
      <div class="flex gap-2 overflow-x-auto pb-2 sm:pb-0 -mx-4 px-4 sm:mx-0 sm:px-0 snap-x snap-mandatory">
        <button class="filter-btn active flex-shrink-0 px-4 py-2.5 rounded-full bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold whitespace-nowrap shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400" data-filter="all">
          Semua
        </button>
        <button class="filter-btn flex-shrink-0 px-4 py-2.5 rounded-full border-2 border-purple-600 text-purple-600 font-medium whitespace-nowrap hover:bg-purple-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400" data-filter="BEM">BEM</button>
        <button class="filter-btn flex-shrink-0 px-4 py-2.5 rounded-full border-2 border-purple-600 text-purple-600 font-medium whitespace-nowrap hover:bg-purple-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400" data-filter="Choir">Choir</button>
        <button class="filter-btn flex-shrink-0 px-4 py-2.5 rounded-full border-2 border-purple-600 text-purple-600 font-medium whitespace-nowrap hover:bg-purple-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400" data-filter="Creative">Creative</button>
        <button class="filter-btn flex-shrink-0 px-4 py-2.5 rounded-full border-2 border-purple-600 text-purple-600 font-medium whitespace-nowrap hover:bg-purple-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400" data-filter="Ministries">Ministries</button>
        <button class="filter-btn flex-shrink-0 px-4 py-2.5 rounded-full border-2 border-purple-600 text-purple-600 font-medium whitespace-nowrap hover:bg-purple-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400" data-filter="Daerah">Daerah</button>
        <button class="filter-btn flex-shrink-0 px-4 py-2.5 rounded-full border-2 border-purple-600 text-purple-600 font-medium whitespace-nowrap hover:bg-purple-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400" data-filter="Photography">Photography</button>
        <button class="filter-btn flex-shrink-0 px-4 py-2.5 rounded-full border-2 border-purple-600 text-purple-600 font-medium whitespace-nowrap hover:bg-purple-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400" data-filter="Himpunan">Himpunan</button>
        <button class="filter-btn flex-shrink-0 px-4 py-2.5 rounded-full border-2 border-purple-600 text-purple-600 font-medium whitespace-nowrap hover:bg-purple-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400" data-filter="English">English</button>
      </div>
    </div>

    <!-- Organization Cards Grid -->
    <div id="org-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8"></div>
  </div>
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
