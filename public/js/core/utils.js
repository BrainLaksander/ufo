// core/utils.js - DOM helpers and reusable menu utilities
(function () {
  if (typeof window === 'undefined' || typeof document === 'undefined') {
    return;
  }

  var utils = window.UFOUtils || {
    qs: function (selector, root) {
      return (root || document).querySelector(selector);
    },
    qsa: function (selector, root) {
      return Array.prototype.slice.call((root || document).querySelectorAll(selector));
    },
    on: function (el, eventName, handler) {
      if (el) {
        el.addEventListener(eventName, handler);
      }
    },
    setMenuState: function (menuEl, overlayEl, isOpen) {
      if (menuEl) {
        menuEl.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
      }
      if (overlayEl) {
        overlayEl.classList.toggle('hidden', !isOpen);
      }
    },
    wireDisclosureMenu: function (config) {
      if (!config || !config.menu || !config.overlay) {
        return;
      }

      var toggleBtn = config.toggleBtn;
      var menu = config.menu;
      var overlay = config.overlay;
      var closeSelector = config.closeSelector || '[data-close]';

      function openMenu() {
        utils.setMenuState(menu, overlay, true);
      }

      function closeMenu() {
        utils.setMenuState(menu, overlay, false);
      }

      utils.on(toggleBtn, 'click', function (e) {
        if (e) {
          e.preventDefault();
        }
        var isHidden = menu.getAttribute('aria-hidden') !== 'false';
        utils.setMenuState(menu, overlay, isHidden);
      });

      utils.on(overlay, 'click', closeMenu);

      utils.qsa(closeSelector, menu).forEach(function (link) {
        utils.on(link, 'click', closeMenu);
      });

      return {
        open: openMenu,
        close: closeMenu,
      };
    },
  };

  window.UFOUtils = utils;

  document.addEventListener('DOMContentLoaded', function () {
    // Generic fallback only if the legacy sidebar pattern exists.
    var toggles = utils.qsa('[data-toggle="sidebar"]');
    var layoutRoot = utils.qs('.layout');

    if (!toggles.length || !layoutRoot) {
      return;
    }

    toggles.forEach(function (toggle) {
      utils.on(toggle, 'click', function (e) {
        e.preventDefault();
        layoutRoot.classList.toggle('sidebar-hidden');
      });
    });
  });
})();
