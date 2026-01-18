// mahasiswa-ui.js — Vanilla JS to control drawer, carousel, search, filters, sort
(function(){
  // Drawer toggle
  var drawer = document.getElementById('drawer');
  var overlay = document.getElementById('drawer-overlay');
  function openDrawer(){ drawer.classList.add('open'); overlay.classList.add('open'); drawer.setAttribute('aria-hidden','false'); overlay.setAttribute('aria-hidden','false'); }
  function closeDrawer(){ drawer.classList.remove('open'); overlay.classList.remove('open'); drawer.setAttribute('aria-hidden','true'); overlay.setAttribute('aria-hidden','true'); }
  document.querySelectorAll('[data-toggle="sidebar"]').forEach(function(b){ b.addEventListener('click', function(e){ e.preventDefault(); openDrawer(); }); });
  overlay.addEventListener('click', closeDrawer);

  // Carousel simple
  var track = document.querySelector('.carousel-track');
  if(track){
    var slides = track.querySelectorAll('.slide');
    var idx = 0;
    function showSlide(i){ slides.forEach(function(s,si){ s.style.display = si===i? 'block':'none'; }); }
    showSlide(idx);
    setInterval(function(){ idx = (idx+1) % slides.length; showSlide(idx); }, 3000);
  }

  // Filtering and search
  var search = document.getElementById('mh-search');
  var filterButtons = document.querySelectorAll('.filter-btn');
  var sortSelect = document.getElementById('mh-sort');
  var grid = document.getElementById('org-grid');
  function applyFilters(){
    var q = search ? search.value.trim().toLowerCase() : '';
    var activeCat = 'all';
    document.querySelectorAll('.filter-btn').forEach(function(b){ if(b.classList.contains('active')) activeCat = b.getAttribute('data-category'); });
    var cards = grid.querySelectorAll('.org-card');
    cards.forEach(function(card){
      var name = card.getAttribute('data-name') || '';
      var cat = card.getAttribute('data-category') || '';
      var matchesQ = q === '' || name.indexOf(q) !== -1;
      var matchesCat = activeCat === 'all' || cat === activeCat;
      card.style.display = (matchesQ && matchesCat) ? 'flex' : 'none';
    });
    applySort();
  }
  function applySort(){
    var val = sortSelect ? sortSelect.value : 'name_asc';
    var cards = Array.prototype.slice.call(grid.querySelectorAll('.org-card')).filter(function(c){ return c.style.display !== 'none'; });
    cards.sort(function(a,b){
      var an = a.querySelector('.org-name').textContent.trim().toLowerCase();
      var bn = b.querySelector('.org-name').textContent.trim().toLowerCase();
      if(val === 'name_asc') return an.localeCompare(bn);
      return bn.localeCompare(an);
    });
    // append in order
    cards.forEach(function(c){ grid.appendChild(c); });
  }
  if(search){ search.addEventListener('input', applyFilters); }
  filterButtons.forEach(function(b){ b.addEventListener('click', function(){ filterButtons.forEach(function(bb){ bb.classList.remove('active'); }); b.classList.add('active'); applyFilters(); }); });
  if(sortSelect){ sortSelect.addEventListener('change', applySort); }

  // initial
  applyFilters();
})();
