document.addEventListener("DOMContentLoaded", function () {
    initBurger();
    initCarousel();
    initFilters();
    renderOrgCards();
});

// Burger Menu Toggle
function initBurger() {
    const burgerBtn = document.getElementById("burger-btn");
    const drawer = document.getElementById("drawer");
    const overlay = document.getElementById("drawer-overlay");
    const closeBtn = document.getElementById("close-drawer");

    if (!burgerBtn || !drawer) return;

    burgerBtn.addEventListener("click", function () {
        drawer.classList.toggle("-translate-x-full");
        drawer.classList.toggle("translate-x-0");
        if (overlay) {
            overlay.classList.toggle("hidden");
        }
    });

    if (closeBtn) {
        closeBtn.addEventListener("click", function () {
            drawer.classList.add("-translate-x-full");
            drawer.classList.remove("translate-x-0");
            if (overlay) overlay.classList.add("hidden");
        });
    }

    if (overlay) {
        overlay.addEventListener("click", function () {
            drawer.classList.add("-translate-x-full");
            drawer.classList.remove("translate-x-0");
            overlay.classList.add("hidden");
        });
    }
}

// Carousel
let currentSlide = 0;
let carouselInterval;

function initCarousel() {
    const carouselImg = document.getElementById("carousel-img");
    const prevBtn = document.getElementById("prev-slide");
    const nextBtn = document.getElementById("next-slide");
    const dotsContainer = document.getElementById("carousel-dots");

    if (!carouselImg || !window.carouselImages) return;

    // Create dots
    window.carouselImages.forEach((_, idx) => {
        const dot = document.createElement("button");
        dot.className =
            idx === 0
                ? "w-3 h-3 bg-white rounded-full"
                : "w-3 h-3 bg-gray-400 rounded-full hover:bg-gray-300";
        dot.setAttribute("data-slide", idx);
        dot.addEventListener("click", () => goToSlide(idx));
        dotsContainer.appendChild(dot);
    });

    prevBtn.addEventListener("click", () => prevSlide());
    nextBtn.addEventListener("click", () => nextSlide());

    autoplayCarousel();
}

function updateCarousel() {
    const carouselImg = document.getElementById("carousel-img");
    const dots = document.querySelectorAll("#carousel-dots button");

    if (carouselImg) {
        carouselImg.src = window.carouselImages[currentSlide];
    }

    dots.forEach((dot, idx) => {
        if (idx === currentSlide) {
            dot.className = "w-3 h-3 bg-white rounded-full";
        } else {
            dot.className =
                "w-3 h-3 bg-gray-400 rounded-full hover:bg-gray-300";
        }
    });
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % window.carouselImages.length;
    updateCarousel();
    clearInterval(carouselInterval);
    autoplayCarousel();
}

function prevSlide() {
    currentSlide =
        (currentSlide - 1 + window.carouselImages.length) %
        window.carouselImages.length;
    updateCarousel();
    clearInterval(carouselInterval);
    autoplayCarousel();
}

function goToSlide(idx) {
    currentSlide = idx;
    updateCarousel();
    clearInterval(carouselInterval);
    autoplayCarousel();
}

function autoplayCarousel() {
    carouselInterval = setInterval(() => {
        currentSlide = (currentSlide + 1) % window.carouselImages.length;
        updateCarousel();
    }, 3000);
}

// Filters & Search
let selectedFilter = "all";

function initFilters() {
    const searchInput = document.getElementById("search-input");
    const filterBtns = document.querySelectorAll(".filter-btn");

    searchInput.addEventListener("input", (e) => {
        const query = e.target.value.toLowerCase();
        renderOrgCards(query, selectedFilter);
    });

    filterBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            filterBtns.forEach((b) => {
                b.classList.remove(
                    "bg-purple-700",
                    "text-white",
                    "font-medium"
                );
                b.classList.add(
                    "border-2",
                    "border-purple-700",
                    "text-purple-700"
                );
            });

            e.target.classList.add(
                "bg-purple-700",
                "text-white",
                "font-medium"
            );
            e.target.classList.remove(
                "border-2",
                "border-purple-700",
                "text-purple-700"
            );

            selectedFilter = e.target.getAttribute("data-filter");
            const searchInput = document.getElementById("search-input");
            const query = searchInput.value.toLowerCase();
            renderOrgCards(query, selectedFilter);
        });
    });
}

function renderOrgCards(searchQuery = "", filter = "all") {
    const grid = document.getElementById("org-grid");
    if (!grid || !window.orgsData) return;

    let filtered = window.orgsData.filter((org) => {
        const matchSearch =
            searchQuery === "" || org.name.toLowerCase().includes(searchQuery);
        const matchFilter = filter === "all" || org.category === filter;
        return matchSearch && matchFilter;
    });

    grid.innerHTML = "";

    if (filtered.length === 0) {
        grid.innerHTML =
            '<div class="col-span-full text-center text-gray-500 py-8">Organisasi tidak ditemukan</div>';
        return;
    }

        filtered.forEach((org) => {
        const card = document.createElement("div");
        card.className =
            "bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden org-card";
        card.innerHTML = `
      <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 text-center">
        <div class="text-5xl mb-2">${org.emoji}</div>
      </div>
      <div class="p-4">
        <h3 class="text-lg font-bold text-gray-800 mb-2">${org.name}</h3>
        <p class="text-sm text-gray-600 mb-4">${org.tagline}</p>
        <button class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition font-medium">Lihat Detail</button>
      </div>
    `;
                grid.appendChild(card);
                const btn = card.querySelector('button');
                if(btn){
                        btn.addEventListener('click', ()=>{
                                window.location.href = '/organisasi/' + org.id;
                        });
                }
    });
}
