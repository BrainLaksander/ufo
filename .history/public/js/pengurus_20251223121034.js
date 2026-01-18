// pengurus.js — vanilla JS for burger menu, edit toggles, program add/remove
(function () {
    // Burger menu
    var burgerBtn = document.getElementById("burger-btn");
    var burger = document.getElementById("pengurus-burger");
    var overlay = document.getElementById("pengurus-overlay");
    var burgerClose = document.getElementById("burger-close");

    function openBurger() {
        if (burger) {
            burger.setAttribute("aria-hidden", "false");
            burger.style.transform = "translateX(0)";
        }
        if (overlay) overlay.classList.remove("hidden");
    }
    function closeBurger() {
        if (burger) {
            burger.setAttribute("aria-hidden", "true");
            burger.style.transform = "translateX(-110%)";
        }
        if (overlay) overlay.classList.add("hidden");
    }

    if (burgerBtn) burgerBtn.addEventListener("click", openBurger);
    if (burgerClose) burgerClose.addEventListener("click", closeBurger);
    if (overlay) overlay.addEventListener("click", closeBurger);

    // close when clicking any menu link with data-close
    document.addEventListener("click", function (e) {
        var el = e.target.closest("[data-close]");
        if (el) {
            closeBurger();
        }
    });

    // Logout dummy
    var logout = document.getElementById("pengurus-logout");
    if (logout)
        logout.addEventListener("click", function (e) {
            e.preventDefault();
            window.location.href = "/pengurus/login";
        });

    // Editable blocks (visi & misi)
    document.querySelectorAll("[data-editable]").forEach(function (root) {
        var toggle = root.querySelector("[data-toggle-edit]");
        var view = root.querySelector(".view-mode");
        var edit = root.querySelector(".edit-mode");
        var save = root.querySelector("[data-save]");
        var cancel = root.querySelector("[data-cancel]");
        var display = root.querySelector("#visi-misi");
        var input = root.querySelector("#visi-misi-input");

        if (toggle) {
            toggle.addEventListener("click", function () {
                view.classList.add("hidden");
                edit.classList.remove("hidden");
            });
        }
        if (cancel) {
            cancel.addEventListener("click", function () {
                edit.classList.add("hidden");
                view.classList.remove("hidden");
            });
        }
        if (save) {
            save.addEventListener("click", function () {
                display.textContent = input.value;
                edit.classList.add("hidden");
                view.classList.remove("hidden");
            });
        }
    });

    // Program list add/remove/expand
    var programList = document.getElementById("program-list");
    var addBtn = document.getElementById("add-program");
    var newProgram = document.getElementById("new-program");
    if (addBtn && newProgram && programList) {
        addBtn.addEventListener("click", function () {
            var val = newProgram.value && newProgram.value.trim();
            if (!val) return;
            var id = Date.now();
            var li = document.createElement("li");
            li.setAttribute("data-id", id);
            li.innerHTML =
                '<div class="prog-row"><div class="prog-title">' +
                escapeHtml(val) +
                '</div><div class="prog-actions"><button class="btn-sm" data-toggle-expand>Detail</button> <button class="btn-sm btn-danger" data-remove>Hapus</button></div></div><div class="prog-detail hidden">Detail untuk ' +
                escapeHtml(val) +
                "</div>";
            programList.appendChild(li);
            newProgram.value = "";
        });
    }

    document.addEventListener("click", function (e) {
        var t;
        // remove
        if ((t = e.target.closest("[data-remove]"))) {
            var li = t.closest("li");
            if (li) li.remove();
        }
        // expand
        if ((t = e.target.closest("[data-toggle-expand]"))) {
            var li = t.closest("li");
            if (!li) return;
            var detail = li.querySelector(".prog-detail");
            if (detail) detail.classList.toggle("hidden");
        }
    });

    function escapeHtml(s) {
        return String(s)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
    }
})();
