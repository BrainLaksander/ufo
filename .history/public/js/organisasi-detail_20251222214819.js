document.addEventListener("DOMContentLoaded", function () {
    // Simple image lightbox for event images
    document.querySelectorAll(".org-event-image").forEach((img) => {
        img.addEventListener("click", function () {
            const src = this.getAttribute("src");
            const overlay = document.createElement("div");
            overlay.style =
                "position:fixed;inset:0;background:rgba(0,0,0,0.8);display:flex;align-items:center;justify-content:center;z-index:9999;";
            const im = document.createElement("img");
            im.src = src;
            im.style = "max-width:90%;max-height:90%;border-radius:8px;";
            overlay.appendChild(im);
            overlay.addEventListener("click", () =>
                document.body.removeChild(overlay)
            );
            document.body.appendChild(overlay);
        });
    });

    // Toggle long descriptions
    document.querySelectorAll(".org-show-more").forEach((btn) => {
        btn.addEventListener("click", function () {
            const target = document.querySelector(this.dataset.target);
            if (!target) return;
            target.classList.toggle("hidden");
            this.textContent = target.classList.contains("hidden")
                ? "Tampilkan lebih"
                : "Sembunyikan";
        });
    });
});
