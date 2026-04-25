// core/utils.js - shared DOM helpers and menu/panel wiring
(function () {
  if (typeof window === "undefined" || typeof document === "undefined") {
    return;
  }

  var existing = window.UFOUtils || {};

  function toArray(value) {
    if (!value) {
      return [];
    }

    if (Array.isArray(value)) {
      return value.filter(Boolean);
    }

    return [value];
  }

  var utils = {
    qs: existing.qs || function (selector, root) {
      return (root || document).querySelector(selector);
    },
    qsa: existing.qsa || function (selector, root) {
      return Array.prototype.slice.call((root || document).querySelectorAll(selector));
    },
    on: existing.on || function (el, eventName, handler) {
      if (el) {
        el.addEventListener(eventName, handler);
      }
    },
    setMenuState: existing.setMenuState || function (menuEl, overlayEl, isOpen) {
      if (menuEl) {
        menuEl.setAttribute("aria-hidden", isOpen ? "false" : "true");
      }

      if (overlayEl) {
        overlayEl.classList.toggle("hidden", !isOpen);
      }
    },
    setPanelState: existing.setPanelState || function (panelEl, overlayEl, isOpen, options) {
      var config = options || {};
      var openClass = config.openClass || "is-open";
      var overlayMode = config.overlayMode || "attribute";
      var overlayHiddenClass = config.overlayHiddenClass || "hidden";
      var bodyNoScrollClass = config.bodyNoScrollClass || "";

      if (panelEl) {
        panelEl.classList.toggle(openClass, isOpen);
        panelEl.setAttribute("aria-hidden", isOpen ? "false" : "true");
      }

      if (overlayEl) {
        if (overlayMode === "class") {
          overlayEl.classList.toggle(overlayHiddenClass, !isOpen);
        } else if (isOpen) {
          overlayEl.removeAttribute("hidden");
        } else {
          overlayEl.setAttribute("hidden", "hidden");
        }
      }

      if (bodyNoScrollClass && document.body) {
        var hasOpenPanel = false;

        if (panelEl && isOpen) {
          hasOpenPanel = true;
        }

        if (!hasOpenPanel && openClass) {
          hasOpenPanel = !!document.querySelector("." + openClass + "[aria-hidden='false']");
        }

        document.body.classList.toggle(bodyNoScrollClass, hasOpenPanel);
      }
    },
    wireDisclosureMenu: existing.wireDisclosureMenu || function (config) {
      if (!config || !config.menu || !config.overlay) {
        return;
      }

      var toggleBtn = config.toggleBtn;
      var menu = config.menu;
      var overlay = config.overlay;
      var closeSelector = config.closeSelector || "[data-close]";

      function openMenu() {
        utils.setMenuState(menu, overlay, true);
      }

      function closeMenu() {
        utils.setMenuState(menu, overlay, false);
      }

      utils.on(toggleBtn, "click", function (event) {
        if (event) {
          event.preventDefault();
        }

        var shouldOpen = menu.getAttribute("aria-hidden") !== "false";
        utils.setMenuState(menu, overlay, shouldOpen);
      });

      utils.on(overlay, "click", closeMenu);

      utils.qsa(closeSelector, menu).forEach(function (link) {
        utils.on(link, "click", closeMenu);
      });

      return {
        open: openMenu,
        close: closeMenu,
      };
    },
    wirePanelToggle: existing.wirePanelToggle || function (config) {
      if (!config || !config.panel || !config.overlay) {
        return;
      }

      var openTriggers = toArray(config.openTriggers || config.openTrigger);
      var closeTriggers = toArray(config.closeTriggers || config.closeTrigger);
      var closeOnSelectors = toArray(config.closeOnSelectors);

      function openPanel(event) {
        if (event && config.preventDefaultOpen !== false) {
          event.preventDefault();
        }

        utils.setPanelState(config.panel, config.overlay, true, config);
      }

      function closePanel() {
        utils.setPanelState(config.panel, config.overlay, false, config);
      }

      function closePanelWithOptionalPrevent(event) {
        if (event && config.preventDefaultClose === true) {
          event.preventDefault();
        }

        closePanel();
      }

      openTriggers.forEach(function (trigger) {
        utils.on(trigger, "click", openPanel);
      });

      closeTriggers.forEach(function (trigger) {
        utils.on(trigger, "click", closePanelWithOptionalPrevent);
      });

      utils.on(config.overlay, "click", closePanel);

      closeOnSelectors.forEach(function (selector) {
        utils.qsa(selector).forEach(function (el) {
          utils.on(el, "click", closePanel);
        });
      });

      return {
        open: openPanel,
        close: closePanel,
      };
    },
  };

  window.UFOUtils = utils;
})();
