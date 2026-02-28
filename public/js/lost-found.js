// public/js/lost-found.js — lightweight client-side filters & carousel (vanilla JS)
(function () {
  function qs(sel, root = document) {
    return root.querySelector(sel);
  }
  function qsa(sel, root = document) {
    return Array.from(root.querySelectorAll(sel));
  }

  var searchInput = qs('#lf-search');
  var categoryButtons = qsa('.lf-category');
  var tabButtons = qsa('[data-tab]');
  var cards = qsa('.lf-card');
  var countEl = qs('#lf-count');
  var emptyEl = qs('#lf-empty');
  var filterBtn = qs('#lf-filter-btn');
  var resetBtn = qs('#lf-reset-btn');

  function getActiveCategory() {
    var active = categoryButtons.find(function (b) {
      return b.classList.contains('active');
    });
    return active ? active.dataset.category : 'all';
  }
  function getActiveTab() {
    var active = tabButtons.find(function (b) {
      return b.classList.contains('active');
    });
    return active ? active.dataset.tab : 'hilang';
  }

  function matches(card, q, category, tab) {
    q = q || '';
    if (q) {
      var hay = (
        card.dataset.title +
        ' ' +
        card.dataset.location +
        ' ' +
        card.dataset.reporter +
        ' ' +
        card.dataset.date
      ).toLowerCase();
      if (hay.indexOf(q) === -1) return false;
    }
    if (category && category !== 'all' && card.dataset.category !== category)
      return false;
    if (tab && card.dataset.type !== tab) return false;
    return true;
  }

  function update() {
    var q = ((searchInput && searchInput.value) || '').trim().toLowerCase();
    var category = getActiveCategory();
    var tab = getActiveTab();
    var visible = 0;
    cards.forEach(function (c) {
      if (matches(c, q, category, tab)) {
        c.style.display = '';
        visible++;
      } else {
        c.style.display = 'none';
      }
    });
    if (countEl) countEl.textContent = visible + ' barang ditemukan';
    if (emptyEl) {
      if (visible === 0) emptyEl.classList.remove('hidden');
      else emptyEl.classList.add('hidden');
    }
  }

  // wire search live
  if (searchInput)
    searchInput.addEventListener('input', function () {
      update();
    });

  // categories
  categoryButtons.forEach(function (b) {
    b.addEventListener('click', function () {
      categoryButtons.forEach(function (bb) {
        bb.classList.remove(
          'active',
          'bg-[#663399]',
          'text-white',
          'shadow-lg'
        );
        bb.classList.add('bg-gray-100', 'text-gray-700');
        bb.classList.remove('shadow-lg');
      });
      b.classList.add('active', 'bg-[#663399]', 'text-white', 'shadow-lg');
      b.classList.remove('bg-gray-100', 'text-gray-700');
      update();
    });
  });

  // tabs
  tabButtons.forEach(function (t) {
    t.addEventListener('click', function () {
      tabButtons.forEach(function (tt) {
        tt.classList.remove(
          'active',
          'bg-red-600',
          'text-white',
          'bg-[#663399]',
          'bg-white',
          'text-gray-700',
          'shadow-lg'
        );
      });
      if (t.dataset.tab === 'hilang') {
        t.classList.add('active', 'bg-red-600', 'text-white', 'shadow-lg');
      } else {
        t.classList.add('active', 'bg-[#663399]', 'text-white', 'shadow-lg');
      }
      update();
    });
  });

  // filter & reset
  if (filterBtn) filterBtn.addEventListener('click', update);
  if (resetBtn)
    resetBtn.addEventListener('click', function () {
      if (searchInput) searchInput.value = '';
      categoryButtons.forEach(function (bb) {
        bb.classList.remove('active');
        bb.classList.remove('bg-[#663399]', 'text-white');
        bb.classList.add('bg-gray-100', 'text-gray-700');
      });
      var allBtn = document.querySelector('.lf-category[data-category="all"]');
      if (allBtn) {
        allBtn.classList.add('active', 'bg-[#663399]', 'text-white');
        allBtn.classList.remove('bg-gray-100', 'text-gray-700');
      }
      tabButtons.forEach(function (tt) {
        tt.classList.remove('active');
        tt.classList.remove(
          'bg-red-600',
          'text-white',
          'bg-white',
          'text-gray-700'
        );
      });
      var hilang = document.querySelector('[data-tab="hilang"]');
      if (hilang) {
        hilang.classList.add('active', 'bg-red-600', 'text-white');
      }
      update();
    });

  // init state: ensure 'Semua' and 'Barang Hilang' active
  (function init() {
    var allBtn = document.querySelector('.lf-category[data-category="all"]');
    if (allBtn) {
      allBtn.classList.add('active', 'bg-[#663399]', 'text-white', 'shadow-lg');
    }
    var hilang = document.querySelector('[data-tab="hilang"]');
    if (hilang) {
      hilang.classList.add('active', 'bg-red-600', 'text-white', 'shadow-lg');
    }
    update();
  })();

  // Carousel behavior for .snap-x element
  var carousel = document.querySelector('.snap-x');
  var dots = Array.from(document.querySelectorAll('.carousel-dot'));
  if (carousel && dots.length) {
    var idx = 0;
    function go(i) {
      idx = ((i % dots.length) + dots.length) % dots.length;
      var child = carousel.children[idx];
      // center the active child
      var left =
        child.offsetLeft - (carousel.offsetWidth - child.offsetWidth) / 2;
      carousel.scrollTo({ left: left, behavior: 'smooth' });
      dots.forEach(function (d, di) {
        if (di === idx) {
          d.classList.remove('opacity-50');
          d.classList.add('opacity-100');
          d.classList.add('md:w-8', 'md:h-3');
          d.classList.remove('w-3', 'h-3');
        } else {
          d.classList.remove('opacity-100');
          d.classList.add('opacity-50');
          d.classList.add('w-3', 'h-3');
          d.classList.remove('md:w-8', 'md:h-3');
        }
      });
    }
    dots.forEach(function (d, i) {
      d.addEventListener('click', function () {
        go(i);
      });
    });

    // Prev/Next controls
    var prevBtn = qs('.carousel-prev');
    var nextBtn = qs('.carousel-next');
    if (prevBtn)
      prevBtn.addEventListener('click', function () {
        go((idx - 1 + dots.length) % dots.length);
      });
    if (nextBtn)
      nextBtn.addEventListener('click', function () {
        go((idx + 1) % dots.length);
      });

    // initialize to first slide
    go(0);

    var timer = setInterval(function () {
      go((idx + 1) % dots.length);
    }, 5000);
    carousel.addEventListener('mouseenter', function () {
      clearInterval(timer);
    });
    carousel.addEventListener('mouseleave', function () {
      timer = setInterval(function () {
        go((idx + 1) % dots.length);
      }, 5000);
    });
  }
})();
