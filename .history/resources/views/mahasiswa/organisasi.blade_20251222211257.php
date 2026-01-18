@extends('layouts.mahasiswa')

<!-- USING_MAHASISWA_LAYOUT -->

@section('title', 'Organisasi - Mahasiswa')

@section('content')
<section class="mh-page">
  {{-- Carousel --}}
  <div class="carousel" id="mh-carousel">
    <div class="carousel-track">
      <div class="slide"> <div class="slide-inner">Image 1</div> </div>
      <div class="slide"> <div class="slide-inner">Image 2</div> </div>
      <div class="slide"> <div class="slide-inner">Image 3</div> </div>
    </div>
  </div>

  {{-- Controls: search, filters, sort --}}
  <div class="mh-controls">
    <input id="mh-search" class="mh-search" placeholder="Cari organisasi..." />

    <div class="mh-filters" id="mh-filters">
      <button class="filter-btn active" data-category="all">All</button>
      <button class="filter-btn" data-category="BEM">BEM</button>
      <button class="filter-btn" data-category="Choir">Choir</button>
      <button class="filter-btn" data-category="Creative Club">Creative Club</button>
      <button class="filter-btn" data-category="Ministries">Ministries</button>
      <button class="filter-btn" data-category="Ikatan Daerah">Ikatan Daerah</button>
    </div>

    <select id="mh-sort" class="mh-sort">
      <option value="name_asc">Name A→Z</option>
      <option value="name_desc">Name Z→A</option>
    </select>
  </div>

  {{-- Organization grid (hardcoded data) --}}
  @php
    $orgs = [
      ["id"=>1, "name"=>"BEM UNKLAB", "tagline"=>"Bersama Membangun, Bersama Melayani", "category"=>"BEM", "logo"=>"🏛️"],
      ["id"=>2, "name"=>"UNKLAB Choir", "tagline"=>"Menyanyikan Kemuliaan Tuhan", "category"=>"Choir", "logo"=>"🎵"],
      ["id"=>3, "name"=>"Creative Media Club", "tagline"=>"Kreativitas Tanpa Batas", "category"=>"Creative Club", "logo"=>"🎨"],
      ["id"=>4, "name"=>"PMKO", "tagline"=>"Persekutuan Mahasiswa Kristen Oikumene", "category"=>"Ministries", "logo"=>"⛪"],
      ["id"=>5, "name"=>"Ikatan Mahasiswa Manado", "tagline"=>"Persatuan Anak Manado", "category"=>"Ikatan Daerah", "logo"=>"🏝️"],
    ];
  @endphp

  <div class="org-grid" id="org-grid">
    @foreach($orgs as $o)
      <article class="org-card" data-name="{{ strtolower($o['name']) }}" data-category="{{ $o['category'] }}">
        <div class="org-logo">{{ $o['logo'] }}</div>
        <div class="org-body">
          <h3 class="org-name">{{ $o['name'] }}</h3>
          <p class="org-tagline">{{ $o['tagline'] }}</p>
        </div>
        <div class="org-action">
          <button class="view-btn">Lihat Detail</button>
        </div>
      </article>
    @endforeach
  </div>
</section>
@endsection
