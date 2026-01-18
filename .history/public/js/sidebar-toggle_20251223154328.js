// sidebar-toggle.js - Handle burger menu and sidebar toggling
(function(){
    var burgerBtn = document.getElementById('burger-btn');
    var sidebarToggle = document.getElementById('sidebar-toggle');
    var sidebar = document.getElementById('sidebar');
    var sidebarOverlay = document.getElementById('sidebar-overlay');
    var burgerMenu = document.getElementById('burger-menu');
    var burgerOverlay = document.getElementById('burger-overlay');
    
    // Burger menu toggle for mahasiswa pages
    if(burgerBtn && burgerMenu && burgerOverlay) {
        burgerBtn.addEventListener('click', function(e) {
            e.preventDefault();
            burgerMenu.classList.toggle('active');
            burgerOverlay.classList.toggle('active');
        });
        
        burgerOverlay.addEventListener('click', function() {
            burgerMenu.classList.remove('active');
            burgerOverlay.classList.remove('active');
        });
        
        // Close menu when clicking menu items with data-close attribute
        var closeLinks = burgerMenu.querySelectorAll('[data-close]');
        closeLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                burgerMenu.classList.remove('active');
                burgerOverlay.classList.remove('active');
            });
        });
    }
    
    // Sidebar toggle for portal pages
    if(sidebarToggle && sidebar && sidebarOverlay) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });
        
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
        
        // Close sidebar when clicking menu items with data-close attribute
        var closeLinks = sidebar.querySelectorAll('[data-close]');
        closeLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });
        });
    }
})();
