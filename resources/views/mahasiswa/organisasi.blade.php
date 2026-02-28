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
    <div class="relative w-full mb-10 overflow-hidden rounded-xl shadow-lg bg-gradient-to-r from-purple-600 to-yellow-400">
      <div id="carousel" class="relative w-full h-48 sm:h-64 md:h-80">
        <!-- Carousel Image -->
        <img id="carousel-img" src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200&h=500&fit=crop" alt="Carousel" class="w-full h-full object-cover">
        
        <!-- Dark Overlay for Text Readability -->
        <div class="absolute inset-0 bg-black/40"></div>
        
        <!-- Carousel Content Overlay -->
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-6">
          <h2 class="text-2xl sm:text-4xl font-bold mb-3">Jelajahi Organisasi Kami</h2>
          <p class="text-sm sm:text-base opacity-90 max-w-md">Temukan organisasi yang sesuai dengan minat dan bakat Anda</p>
        </div>
        
        <!-- Navigation Buttons -->
        <button id="prev-slide" class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white text-gray-900 px-3 sm:px-4 py-2 rounded-lg transition-all duration-200 z-20 focus:outline-none focus:ring-2 focus:ring-yellow-400" aria-label="Previous slide">
          <span class="text-lg sm:text-xl font-bold">❮</span>
        </button>
        <button id="next-slide" class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white text-gray-900 px-3 sm:px-4 py-2 rounded-lg transition-all duration-200 z-20 focus:outline-none focus:ring-2 focus:ring-yellow-400" aria-label="Next slide">
          <span class="text-lg sm:text-xl font-bold">❯</span>
        </button>
        
        <!-- Carousel Dots -->
        <div id="carousel-dots" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20"></div>
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
        <button class="filter-btn flex-shrink-0 px-4 py-2.5 rounded-full border-2 border-purple-600 text-purple-600 font-medium whitespace-nowrap hover:bg-purple-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400" data-filter="Akademik">Akademik</button>
        <button class="filter-btn flex-shrink-0 px-4 py-2.5 rounded-full border-2 border-purple-600 text-purple-600 font-medium whitespace-nowrap hover:bg-purple-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400" data-filter="Seni">Seni & Olahraga</button>
        <button class="filter-btn flex-shrink-0 px-4 py-2.5 rounded-full border-2 border-purple-600 text-purple-600 font-medium whitespace-nowrap hover:bg-purple-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400" data-filter="Kerohanian">Kerohanian</button>
        <button class="filter-btn flex-shrink-0 px-4 py-2.5 rounded-full border-2 border-purple-600 text-purple-600 font-medium whitespace-nowrap hover:bg-purple-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-400" data-filter="Olahraga">Olahraga</button>
      </div>
    </div>

    <!-- Organization Cards Grid -->
    <div id="org-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8"></div>
  </div>
</div>

<script>
// Data organisasi dari controller
window.orgsData = @json($organisasi);

// Carousel Images
window.carouselImages = [
  "https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200&h=500&fit=crop",
  "https://images.unsplash.com/photo-1655472355485-d949925e67bb?w=1200&h=500&fit=crop",
  "https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1200&h=500&fit=crop"
];

let currentSlide = 0;

// Carousel functionality
function initCarousel() {
  const carousel = document.getElementById('carousel');
  const carouselImg = document.getElementById('carousel-img');
  const dotsContainer = document.getElementById('carousel-dots');
  const prevBtn = document.getElementById('prev-slide');
  const nextBtn = document.getElementById('next-slide');

  // Create dots
  window.carouselImages.forEach((_, index) => {
    const dot = document.createElement('button');
    dot.className = `w-2 h-2 rounded-full transition-all duration-300 ${index === 0 ? 'bg-white w-6' : 'bg-white/50'}`;
    dot.setAttribute('aria-label', `Slide ${index + 1}`);
    dot.onclick = () => goToSlide(index);
    dotsContainer.appendChild(dot);
  });

  window.goToSlide = function(index) {
    currentSlide = index;
    carouselImg.src = window.carouselImages[currentSlide];
    updateDots();
  };

  function updateDots() {
    document.querySelectorAll('#carousel-dots button').forEach((dot, index) => {
      if (index === currentSlide) {
        dot.classList.remove('w-2', 'bg-white/50');
        dot.classList.add('w-6', 'bg-white');
      } else {
        dot.classList.remove('w-6', 'bg-white');
        dot.classList.add('w-2', 'bg-white/50');
      }
    });
  }

  prevBtn.onclick = () => {
    currentSlide = (currentSlide - 1 + window.carouselImages.length) % window.carouselImages.length;
    window.goToSlide(currentSlide);
  };

  nextBtn.onclick = () => {
    currentSlide = (currentSlide + 1) % window.carouselImages.length;
    window.goToSlide(currentSlide);
  };

  // Auto-rotate carousel
  setInterval(() => {
    currentSlide = (currentSlide + 1) % window.carouselImages.length;
    window.goToSlide(currentSlide);
  }, 5000);
}

// Render organization cards
function renderOrgs(orgs) {
  const grid = document.getElementById('org-grid');
  grid.innerHTML = '';
  
  orgs.forEach(org => {
    const categoryClass = org.kategori.toLowerCase().replace(/\s+/g, '-');
    const card = document.createElement('div');
    card.className = `org-card group cursor-pointer transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl`;
    card.setAttribute('data-category', categoryClass);
    card.setAttribute('data-name', org.nama.toLowerCase());
    
    card.innerHTML = `
      <a href="/organisasi/${org.id}" class="block bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden h-full">
        <div class="bg-gradient-to-r ${getBgGradient(org.id)} p-6 h-24 flex items-center justify-center">
          <span class="text-5xl">${org.emoji}</span>
        </div>
        <div class="p-5">
          <h3 class="text-lg font-bold text-gray-900 mb-2">${org.nama}</h3>
          <p class="text-sm text-gray-600 mb-3 line-clamp-2">${org.tagline}</p>
          <div class="flex items-center justify-between">
            <span class="inline-block px-3 py-1 text-xs font-semibold bg-purple-100 text-purple-600 rounded-full">${org.kategori}</span>
            <span class="text-sm text-gray-500">👥 ${org.members}</span>
          </div>
        </div>
        <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
          <button class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold py-2 rounded-lg hover:shadow-lg transition-all duration-200">
            ${org.registrationOpen ? '📝 Daftar' : '🔒 Tutup'}
          </button>
        </div>
      </a>
    `;
    
    grid.appendChild(card);
  });
}

function getBgGradient(id) {
  const gradients = [
    'from-purple-400 to-purple-600',
    'from-blue-400 to-blue-600',
    'from-pink-400 to-pink-600',
    'from-green-400 to-green-600',
    'from-yellow-400 to-yellow-600',
    'from-indigo-400 to-indigo-600'
  ];
  return gradients[(id - 1) % gradients.length];
}

// Search functionality
document.getElementById('search-input')?.addEventListener('keyup', (e) => {
  const query = e.target.value.toLowerCase();
  const cards = document.querySelectorAll('.org-card');
  cards.forEach(card => {
    const name = card.getAttribute('data-name');
    card.style.display = name.includes(query) ? '' : 'none';
  });
});

// Filter functionality
document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', (e) => {
    document.querySelectorAll('.filter-btn').forEach(b => {
      b.classList.remove('from-purple-600', 'to-purple-700', 'text-white', 'shadow-md', 'hover:shadow-lg');
      b.classList.add('border-2', 'border-purple-600', 'text-purple-600');
    });
    
    e.target.classList.remove('border-2', 'border-purple-600', 'text-purple-600');
    e.target.classList.add('bg-gradient-to-r', 'from-purple-600', 'to-purple-700', 'text-white', 'shadow-md', 'hover:shadow-lg');
    
    const filter = e.target.getAttribute('data-filter');
    const cards = document.querySelectorAll('.org-card');
    
    cards.forEach(card => {
      const category = card.getAttribute('data-category');
      if (filter === 'all') {
        card.style.display = '';
      } else {
        card.style.display = category.includes(filter.toLowerCase()) ? '' : 'none';
      }
    });
  });
});

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  initCarousel();
  renderOrgs(window.orgsData);
});
</script>
@endsection
