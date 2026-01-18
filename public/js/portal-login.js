// portal-login.js - Handle role selection and redirect
(function () {
    var roleCards = document.querySelectorAll(".role-card");

    roleCards.forEach(function (card) {
        card.addEventListener("click", function (e) {
            e.preventDefault();
            var role = this.getAttribute("data-role");
            if (role) {
                localStorage.setItem("portal_role", role);
                window.location.href = "/portal/" + role;
            }
        });
    });
})();
