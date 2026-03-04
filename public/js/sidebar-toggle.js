// sidebar-toggle.js - Handle burger menu and sidebar toggling
(function () {
  var burgerBtn = document.getElementById('burger-btn');
  var sidebarToggle = document.getElementById('sidebar-toggle');
  var sidebar = document.getElementById('sidebar');
  var sidebarOverlay = document.getElementById('sidebar-overlay');
  var burgerMenu = document.getElementById('burger-menu');
  var burgerOverlay = document.getElementById('burger-overlay');

  // Burger menu toggle for mahasiswa pages
  if (burgerBtn && burgerMenu && burgerOverlay) {
    burgerBtn.addEventListener('click', function (e) {
      e.preventDefault();
      var isHidden = burgerMenu.getAttribute('aria-hidden') === 'true';
      burgerMenu.setAttribute('aria-hidden', !isHidden);
      burgerOverlay.classList.toggle('hidden');
    });

    burgerOverlay.addEventListener('click', function () {
      burgerMenu.setAttribute('aria-hidden', 'true');
      burgerOverlay.classList.add('hidden');
    });

    // Close menu when clicking menu items with data-close attribute
    var closeLinks = burgerMenu.querySelectorAll('[data-close]');
    closeLinks.forEach(function (link) {
      link.addEventListener('click', function () {
        burgerMenu.setAttribute('aria-hidden', 'true');
        burgerOverlay.classList.add('hidden');
      });
    });
  }

  // Sidebar toggle for portal pages
  if (sidebarToggle && sidebar && sidebarOverlay) {
    sidebarToggle.addEventListener('click', function (e) {
      e.preventDefault();
      var isHidden = sidebar.getAttribute('aria-hidden') === 'true';
      // Always set attribute to string 'true'/'false' to be explicit
      sidebar.setAttribute('aria-hidden', (!isHidden).toString());

      // Only show overlay on small screens (matches CSS breakpoint)
      if (window.innerWidth <= 1000) {
        sidebarOverlay.classList.toggle('hidden');
      } else {
        // Ensure overlay hidden on desktop to avoid dimming content
        sidebarOverlay.classList.add('hidden');
      }
    });

    sidebarOverlay.addEventListener('click', function () {
      sidebar.setAttribute('aria-hidden', 'true');
      sidebarOverlay.classList.add('hidden');
    });

    // Close sidebar when clicking menu items with data-close attribute
    var closeLinks = sidebar.querySelectorAll('[data-close]');
    closeLinks.forEach(function (link) {
      link.addEventListener('click', function () {
        sidebar.setAttribute('aria-hidden', 'true');
        sidebarOverlay.classList.add('hidden');
      });
    });

    // Keep overlay hidden if window becomes large while it's visible
    window.addEventListener('resize', function () {
      if (window.innerWidth > 1000) {
        sidebarOverlay.classList.add('hidden');
      }
    });
  }
})();
