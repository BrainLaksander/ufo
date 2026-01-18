// portal.js - Vanilla JS for portal internal pages
(function () {
    // Detect which burger menu to use based on current page
    var role = localStorage.getItem("portal_role") || "pengurus";
    var burgerBtnId = "burger-btn-portal";
    var burgerId = "burger-" + role;
    var overlayId = "overlay-" + role;

    var burgerBtn = document.getElementById(burgerBtnId);
    var burgerMenu = document.getElementById(burgerId);
    var overlay = document.getElementById(overlayId);
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

    // Close menu when clicking links with data-close
    document.addEventListener("click", function (e) {
        var link = e.target.closest("[data-close]");
        if (link) {
            closeBurger();
        }
    });

    // Logout handler
    var logoutBtn =
        document.getElementById("pengurus-logout") ||
        document.querySelector('a[href="/portal/login"]#logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener("click", function (e) {
            e.preventDefault();
            localStorage.removeItem("portal_role");
            window.location.href = "/portal/login";
        });
    }
})();
