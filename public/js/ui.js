// UI helpers: hamburger toggle and sidebar handling
document.addEventListener('DOMContentLoaded', function () {
    var menuBtns = Array.from(document.querySelectorAll('.menu-btn'));
    var sidebar = document.getElementById('ufo-sidebar');
    var closeBtn = document.querySelector('.sidebar-close');
    var backdrop = document.querySelector('[data-sidebar-backdrop]');
    var notificationToggle = document.querySelector('[data-notification-toggle]');
    var notificationPanel = document.getElementById('ufo-notification-panel');
    var notificationClose = document.querySelector('.notification-close');
    var notificationBackdrop = document.querySelector('[data-notification-backdrop]');

    function openSidebar() {
        closeNotifications();
        document.body.classList.add('sidebar-open');
        if (sidebar) { sidebar.classList.add('open'); sidebar.setAttribute('aria-hidden', 'false'); }
        menuBtns.forEach(function (b) { b.setAttribute('aria-expanded', 'true'); });
    }
    function closeSidebar() {
        document.body.classList.remove('sidebar-open');
        if (sidebar) { sidebar.classList.remove('open'); sidebar.setAttribute('aria-hidden', 'true'); }
        menuBtns.forEach(function (b) { b.setAttribute('aria-expanded', 'false'); });
    }

    function openNotifications() {
        closeSidebar();
        document.body.classList.add('notification-open');
        if (notificationPanel) { notificationPanel.classList.add('open'); notificationPanel.setAttribute('aria-hidden', 'false'); }
        if (notificationToggle) notificationToggle.setAttribute('aria-expanded', 'true');
    }

    function closeNotifications() {
        document.body.classList.remove('notification-open');
        if (notificationPanel) { notificationPanel.classList.remove('open'); notificationPanel.setAttribute('aria-hidden', 'true'); }
        if (notificationToggle) notificationToggle.setAttribute('aria-expanded', 'false');
    }

    menuBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (document.body.classList.contains('sidebar-open')) closeSidebar(); else openSidebar();
        });
    });

    if (window.innerWidth >= 1024) {
        if (!document.body.classList.contains('sidebar-collapsed')) {
            document.body.classList.add('sidebar-open');
        }
    }

    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (backdrop) backdrop.addEventListener('click', closeSidebar);
    if (notificationToggle) notificationToggle.addEventListener('click', function () {
        if (document.body.classList.contains('notification-open')) closeNotifications(); else openNotifications();
    });
    if (notificationClose) notificationClose.addEventListener('click', closeNotifications);
    if (notificationBackdrop) notificationBackdrop.addEventListener('click', closeNotifications);

    // close on Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeSidebar();
            closeNotifications();
        }
    });
});
