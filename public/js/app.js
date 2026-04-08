(function () {
    if (typeof window === "undefined" || typeof document === "undefined") {
        return;
    }

    var utils = window.UFOUtils || {
        qs: function (selector, root) {
            return (root || document).querySelector(selector);
        },
        qsa: function (selector, root) {
            return Array.prototype.slice.call(
                (root || document).querySelectorAll(selector)
            );
        },
        on: function (el, eventName, handler) {
            if (el) {
                el.addEventListener(eventName, handler);
            }
        },
    };

    window.UFOUtils = utils;

    document.addEventListener("DOMContentLoaded", function () {
        var toggles = utils.qsa('[data-toggle="sidebar"]');
        var layoutRoot = utils.qs(".layout");

        if (!toggles.length || !layoutRoot) {
            return;
        }

        toggles.forEach(function (toggle) {
            utils.on(toggle, "click", function (e) {
                e.preventDefault();
                layoutRoot.classList.toggle("sidebar-hidden");
            });
        });
    });
})();
