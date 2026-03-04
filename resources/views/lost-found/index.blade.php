@extends('layouts.mahasiswa')

@section('title', 'Lost & Found')

@section('content')
<div class="min-h-screen pb-20 bg-[#f5f6f8]">
  <div class="max-w-[1200px] mx-auto px-4 sm:px-6 py-8">

  <!-- 1️⃣ PAGE HEADER -->
  <section class="mb-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-3 mb-2">
          <span class="text-3xl">🔔</span>
          <h1 class="text-3xl sm:text-4xl font-bold text-gray-900">Lost & Found</h1>
        </div>
        <p class="text-gray-600 text-sm sm:text-base">Laporkan barang hilang atau temuan Anda di sini</p>
      </div>
      <button data-tab="hilang" type="button" class="flex-shrink-0 flex items-center gap-2 px-6 py-3 bg-[#663399] text-white rounded-full hover:bg-[#663399]/90 transition-colors font-medium">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Laporkan</span>
      </button>
    </div>
  </section>

  <!-- 3️⃣ TABS & FILTERS -->
  <section class="mb-8 space-y-6">
    <!-- Tabs -->
    <div class="flex gap-3">
      <button data-tab="hilang" type="button" class="px-6 py-2.5 rounded-full font-medium transition-all bg-red-600 text-white shadow-sm hover:shadow-md">Barang Hilang</button>
      <button data-tab="ditemukan" type="button" class="px-6 py-2.5 rounded-full font-medium transition-all bg-white text-gray-700 border border-gray-300 hover:border-gray-400">Barang Ditemukan</button>
    </div>

    <!-- Search Bar -->
    <div class="relative">
      <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </span>
      <input id="lf-search" type="text" placeholder="Cari barang..." class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-full focus:outline-none focus:border-[#663399] focus:ring-1 focus:ring-[#663399] bg-white" />
    </div>

    <!-- Category Filter -->
    <div id="lf-categories" class="flex gap-2 overflow-x-auto pb-2">
      <button class="lf-category px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all bg-[#663399] text-white shadow-sm" data-category="all">Semua</button>
      <button class="lf-category px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all bg-white border border-gray-300 text-gray-700 hover:border-gray-400" data-category="Elektronik">Elektronik</button>
      <button class="lf-category px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all bg-white border border-gray-300 text-gray-700 hover:border-gray-400" data-category="Dompet">Dompet</button>
      <button class="lf-category px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all bg-white border border-gray-300 text-gray-700 hover:border-gray-400" data-category="Tas">Tas</button>
      <button class="lf-category px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all bg-white border border-gray-300 text-gray-700 hover:border-gray-400" data-category="Alat Tulis">Alat Tulis</button>
      <button class="lf-category px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all bg-white border border-gray-300 text-gray-700 hover:border-gray-400" data-category="Botol Minum">Botol Minum</button>
      <button class="lf-category px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all bg-white border border-gray-300 text-gray-700 hover:border-gray-400" data-category="Kunci">Kunci</button>
      <button class="lf-category px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all bg-white border border-gray-300 text-gray-700 hover:border-gray-400" data-category="Lainnya">Lainnya</button>
    </div>
  </section>

  <!-- 4️⃣ JUMLAH DATA & GRID KARTU BARANG -->
  <div class="mb-4">
    <p id="lf-count" class="text-sm text-gray-600 font-medium">24 barang ditemukan</p>
  </div>

  <section class="mb-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Card 1 -->
      <article class="lf-card bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 overflow-hidden transition-shadow" data-type="hilang" data-category="Kunci" data-title="Kunci Motor - Hitam" data-location="Parkiran Utama" data-reporter="D. Rahman" data-date="2026-01-20">
        <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
          <img src="/images/dummy/keys.jpg" alt="Kunci" class="w-full h-full object-cover">
          <div class="absolute top-3 right-3 px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded-full">Belum ditemukan</div>
        </div>
        <div class="p-5">
          <h3 class="font-bold text-gray-900 text-lg mb-2">Kunci Motor - Hitam</h3>
          <div class="space-y-2.5 mb-4">
            <div class="flex items-center gap-2 text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a8 8 0 10-11.314-11.314l4.243 4.243"/></svg>
              <span class="text-sm">Parkiran Utama</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M6 21h12"/></svg>
              <span class="text-sm">2026-01-20</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <span class="text-sm">D. Rahman</span>
            </div>
          </div>
          <button class="w-full py-2.5 bg-[#663399] text-white font-medium rounded-lg hover:bg-[#663399]/90 transition-colors">Hubungi Pelapor</button>
        </div>
      </article>

      <!-- Card 2 -->
      <article class="lf-card bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 overflow-hidden transition-shadow" data-type="ditemukan" data-category="Elektronik" data-title="Handphone — Hitam" data-location="Cafetaria" data-reporter="L. Sari" data-date="2026-01-29">
        <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
          <img src="/images/dummy/phone2.jpg" alt="Handphone" class="w-full h-full object-cover">
          <div class="absolute top-3 right-3 px-3 py-1 text-xs font-semibold text-gray-900 bg-[#FFCC00] rounded-full">Ditemukan</div>
        </div>
        <div class="p-5">
          <h3 class="font-bold text-gray-900 text-lg mb-2">Handphone — Hitam</h3>
          <div class="space-y-2.5 mb-4">
            <div class="flex items-center gap-2 text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a8 8 0 10-11.314-11.314l4.243 4.243"/></svg>
              <span class="text-sm">Cafetaria</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M6 21h12"/></svg>
              <span class="text-sm">2026-01-29</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <span class="text-sm">L. Sari</span>
            </div>
          </div>
          <button class="w-full py-2.5 bg-[#663399] text-white font-medium rounded-lg hover:bg-[#663399]/90 transition-colors">Hubungi Pelapor</button>
        </div>
      </article>

      <!-- Card 3 -->
      <article class="lf-card bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 overflow-hidden transition-shadow" data-type="hilang" data-category="Elektronik" data-title="Earbuds — Putih" data-location="Aula" data-reporter="M. Nabil" data-date="2026-01-25">
        <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
          <img src="/images/dummy/earbuds.jpg" alt="Earbuds" class="w-full h-full object-cover">
          <div class="absolute top-3 right-3 px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded-full">Belum ditemukan</div>
        </div>
        <div class="p-5">
          <h3 class="font-bold text-gray-900 text-lg mb-2">Earbuds — Putih</h3>
          <div class="space-y-2.5 mb-4">
            <div class="flex items-center gap-2 text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a8 8 0 10-11.314-11.314l4.243 4.243"/></svg>
              <span class="text-sm">Aula</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M6 21h12"/></svg>
              <span class="text-sm">2026-01-25</span>
            </div>
            <div class="flex items-center gap-2 text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <span class="text-sm">M. Nabil</span>
            </div>
          </div>
          <button class="w-full py-2.5 bg-[#663399] text-white font-medium rounded-lg hover:bg-[#663399]/90 transition-colors">Hubungi Pelapor</button>
        </div>
      </article>
    </div>
  </section>

  <!-- Empty state (hidden until no results) -->
  <div id="lf-empty" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
    <div class="text-6xl mb-4">📭</div>
    <h3 class="text-lg font-bold text-gray-900 mb-2">Tidak ada laporan yang sesuai</h3>
    <p class="text-gray-600">Coba ubah kata kunci atau filter pencarian Anda.</p>
  </div>

  </div> <!-- /.max-w container -->
  <script src="/js/lost-found.js"></script>
</div>
@endsection