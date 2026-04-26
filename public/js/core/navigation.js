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

  // Kemahasiswaan sidebar toggle:
  // - desktop: hide/show sidebar without dark overlay
  // - mobile: open/close drawer with overlay
  (function wireKemahasiswaanSidebarToggle() {
    var sidebarToggle = document.getElementById("sidebar-toggle");
    var sidebarClose = document.getElementById("sidebar-close");
    var sidebar = document.getElementById("sidebar");
    var sidebarOverlay = document.getElementById("sidebar-overlay");
    var sidebarCloseLinks = Array.prototype.slice.call(
      document.querySelectorAll("#sidebar [data-close]")
    );

    if (!sidebarToggle || !sidebar || !sidebarOverlay) {
      return;
    }

    if (!sidebar.classList.contains("kemahasiswaan-sidebar")) {
      return;
    }

    function isMobileViewport() {
      return window.matchMedia("(max-width: 1024px)").matches;
    }

    function updateToggleA11y(isOpen) {
      sidebarToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
      sidebarToggle.setAttribute("aria-label", isOpen ? "Sembunyikan sidebar" : "Tampilkan sidebar");
    }

    function setMobileSidebarState(isOpen) {
      sidebar.setAttribute("aria-hidden", isOpen ? "false" : "true");
      sidebarOverlay.classList.toggle("hidden", !isOpen);
      updateToggleA11y(isOpen);
    }

    function setDesktopSidebarState(isHidden) {
      document.body.classList.toggle("kmh-sidebar-hidden", isHidden);
      sidebar.setAttribute("aria-hidden", isHidden ? "true" : "false");
      sidebarOverlay.classList.add("hidden");
      updateToggleA11y(!isHidden);
    }

    utils.on(sidebarToggle, "click", function (event) {
      if (event) {
        event.preventDefault();
      }

      if (isMobileViewport()) {
        var mobileShouldOpen = sidebar.getAttribute("aria-hidden") !== "false";
        setMobileSidebarState(mobileShouldOpen);
        return;
      }

      var desktopShouldHide = !document.body.classList.contains("kmh-sidebar-hidden");
      setDesktopSidebarState(desktopShouldHide);
    });

    utils.on(sidebarOverlay, "click", function () {
      if (isMobileViewport()) {
        setMobileSidebarState(false);
      }
    });

    utils.on(sidebarClose, "click", function (event) {
      if (event) {
        event.preventDefault();
      }

      if (isMobileViewport()) {
        setMobileSidebarState(false);
      } else {
        setDesktopSidebarState(true);
      }
    });

    sidebarCloseLinks.forEach(function (link) {
      utils.on(link, "click", function () {
        if (isMobileViewport()) {
          setMobileSidebarState(false);
        }
      });
    });

    window.addEventListener("resize", function () {
      if (!isMobileViewport()) {
        // Desktop mode should never keep mobile overlay state.
        sidebarOverlay.classList.add("hidden");
        setDesktopSidebarState(document.body.classList.contains("kmh-sidebar-hidden"));
      }
    });

    setDesktopSidebarState(document.body.classList.contains("kmh-sidebar-hidden"));
  })();

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
