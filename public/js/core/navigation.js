// core/navigation.js - Wire burger and sidebar toggles used by portal layouts
(function () {
  var utils = window.UFOUtils || {};

  function setMenuState(menuEl, overlayEl, isOpen) {
    if (utils.setMenuState) {
      utils.setMenuState(menuEl, overlayEl, isOpen);
      return;
    }

    if (menuEl) {
      menuEl.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
    }

    if (overlayEl) {
      overlayEl.classList.toggle('hidden', !isOpen);
    }
  }

  function wireMenu(toggleBtn, menuEl, overlayEl) {
    if (!toggleBtn || !menuEl || !overlayEl) {
      return;
    }

    toggleBtn.addEventListener('click', function (e) {
      e.preventDefault();
      var shouldOpen = menuEl.getAttribute('aria-hidden') !== 'false';
      setMenuState(menuEl, overlayEl, shouldOpen);
    });

    overlayEl.addEventListener('click', function () {
      setMenuState(menuEl, overlayEl, false);
    });

    menuEl.querySelectorAll('[data-close]').forEach(function (link) {
      link.addEventListener('click', function () {
        setMenuState(menuEl, overlayEl, false);
      });
    });
  }

  wireMenu(
    document.getElementById('burger-btn'),
    document.getElementById('burger-menu'),
    document.getElementById('burger-overlay')
  );

  wireMenu(
    document.getElementById('sidebar-toggle'),
    document.getElementById('sidebar'),
    document.getElementById('sidebar-overlay')
  );
})();