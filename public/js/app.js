(function () {
    if (typeof window === "undefined" || typeof document === "undefined") {
        return;
    }

    document.addEventListener("DOMContentLoaded", function () {
        var toggles = Array.prototype.slice.call(
            document.querySelectorAll('[data-toggle="sidebar"]')
        );
        var layoutRoot = document.querySelector(".layout");

        if (!toggles.length || !layoutRoot) {
            return;
        }

        toggles.forEach(function (toggle) {
            toggle.addEventListener("click", function (event) {
                event.preventDefault();
                layoutRoot.classList.toggle("sidebar-hidden");
            });
        });
    });
})();
