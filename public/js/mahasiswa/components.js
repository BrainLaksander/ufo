// mahasiswa/components.js - Vanilla JS for mahasiswa public pages
(function () {
    // Burger menu toggle
    var burgerBtn = document.getElementById("burger-btn-mahasiswa");
    var burgerMenu = document.getElementById("burger-mahasiswa");
    var overlay = document.getElementById("overlay-mahasiswa");
    var burgerClose = burgerMenu
        ? burgerMenu.querySelector(".burger-close")
        : null;

    function openBurger() {
        if (burgerMenu) {
            burgerMenu.setAttribute("aria-hidden", "false");
        }
        if (overlay) {
            overlay.classList.remove("hidden");
        }
    }

    function closeBurger() {
        if (burgerMenu) {
            burgerMenu.setAttribute("aria-hidden", "true");
        }
        if (overlay) {
            overlay.classList.add("hidden");
        }
    }

    if (burgerBtn) {
        burgerBtn.addEventListener("click", openBurger);
    }

    if (burgerClose) {
        burgerClose.addEventListener("click", closeBurger);
    }

    if (overlay) {
        overlay.addEventListener("click", closeBurger);
    }
})();