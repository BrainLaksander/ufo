(function () {
  if (typeof window === "undefined" || typeof document === "undefined") {
    return;
  }

  var utils = window.UFOUtils;
  if (!utils) {
    return;
  }

  utils.wirePanelToggle({
    panel: utils.qs("[data-burger-panel]"),
    overlay: utils.qs("[data-burger-overlay]"),
    openTriggers: utils.qsa("[data-burger-open]"),
    closeTrigger: utils.qs("[data-burger-close]"),
    closeOnSelectors: [".ufo-pengurus-menu-link"],
    openClass: "is-open",
    overlayMode: "attribute",
    bodyNoScrollClass: "ufo-no-scroll",
  });

  utils.wirePanelToggle({
    panel: utils.qs("[data-notification-panel]"),
    overlay: utils.qs("[data-notification-overlay]"),
    openTrigger: utils.qs("[data-notification-open]"),
    closeTrigger: utils.qs("[data-notification-close]"),
    openClass: "is-open",
    overlayMode: "attribute",
    bodyNoScrollClass: "ufo-no-scroll",
  });

  utils.qsa("[data-quick-action='open-pengajuan']").forEach(function (button) {
    utils.on(button, "click", function () {
      window.location.href = "/pengurus/proposals";
    });
  });
})();
