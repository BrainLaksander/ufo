// mahasiswa.js - Vanilla JS for mahasiswa public pages
(function(){
    // Burger menu toggle
    var burgerBtn = document.getElementById('burger-btn-mahasiswa');
    var burgerMenu = document.getElementById('burger-mahasiswa');
    var overlay = document.getElementById('overlay-mahasiswa');
    var burgerClose = burgerMenu ? burgerMenu.querySelector('.burger-close') : null;

    function openBurger() {
        if(burgerMenu) {
            burgerMenu.setAttribute('aria-hidden', 'false');
        }
        if(overlay) {
            overlay.classList.remove('hidden');
        }
    }

    function closeBurger() {
        if(burgerMenu) {
            burgerMenu.setAttribute('aria-hidden', 'true');
        }
        if(overlay) {
            overlay.classList.add('hidden');
        }
    }

    if(burgerBtn) {
        burgerBtn.addEventListener('click', openBurger);
    }

    if(burgerClose) {
        burgerClose.addEventListener('click', closeBurger);
    }

    if(overlay) {
        overlay.addEventListener('click', closeBurger);
    }

    // Close menu when clicking links with data-close
    document.addEventListener('click', function(e){
        var link = e.target.closest('[data-close]');
        if(link) {
            closeBurger();
        }
    });

    // Filter functionality (if needed on organisasi page)
    var filterInput = document.getElementById('filter-kategori');
    if(filterInput) {
        filterInput.addEventListener('change', function(e){
            var category = e.target.value;
            var items = document.querySelectorAll('.org-item');
            items.forEach(function(item){
                if(!category || item.getAttribute('data-category') === category) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
})();

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
        const btn = card.querySelector("button");
        if (btn) {
            btn.addEventListener("click", () => {
                window.location.href = "/organisasi/" + org.id;
            });
        }
    });
}
