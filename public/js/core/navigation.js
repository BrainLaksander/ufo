// core/navigation.js - mahasiswa and kemahasiswaan shell interactions
(function () {
  if (typeof window === "undefined" || typeof document === "undefined") {
    return;
  }

  var utils = window.UFOUtils;
  if (!utils) {
    return;
  }

  // Legacy id-based menus that still exist in old shell templates.
  utils.wireDisclosureMenu({
    toggleBtn: document.getElementById("burger-btn"),
    menu: document.getElementById("burger-menu"),
    overlay: document.getElementById("burger-overlay"),
  });

  utils.wireDisclosureMenu({
    toggleBtn: document.getElementById("sidebar-toggle"),
    menu: document.getElementById("sidebar"),
    overlay: document.getElementById("sidebar-overlay"),
  });

  // New mahasiswa drawer.
  utils.wirePanelToggle({
    panel: utils.qs("[data-burger-panel]"),
    overlay: utils.qs("[data-burger-overlay]"),
    openTriggers: utils.qsa("[data-burger-open]"),
    closeTrigger: utils.qs("[data-burger-close]"),
    closeOnSelectors: [".figma-ufo-menu-link"],
    openClass: "is-open",
    overlayMode: "attribute",
  });

  // New mahasiswa notification drawer.
  utils.wirePanelToggle({
    panel: utils.qs("[data-notification-panel]"),
    overlay: utils.qs("[data-notification-overlay]"),
    openTrigger: utils.qs("[data-notification-open]"),
    closeTrigger: utils.qs("[data-notification-close]"),
    openClass: "is-open",
    overlayMode: "attribute",
  });
})();
