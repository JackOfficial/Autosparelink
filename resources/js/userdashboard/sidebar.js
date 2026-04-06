document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const topbar = document.getElementById('topbar');
    const toggleBtn = document.getElementById('toggleBtn');
    const mobileBtn = document.getElementById('mobileBtn');
    const overlay = document.getElementById('overlay');

    // Desktop collapse
    if (toggleBtn) {
      toggleBtn.addEventListener('click', () => {
        if (sidebar) sidebar.classList.toggle('collapsed');
        if (content) content.classList.toggle('full');
        if (topbar) topbar.classList.toggle('full');
      });
    }

    // Mobile sidebar open
    if (mobileBtn) {
      mobileBtn.addEventListener('click', () => {
        if (sidebar) sidebar.classList.add('mobile-show');
        if (overlay) overlay.classList.add('show');
      });
    }

    // Click outside to close
    if (overlay) {
      overlay.addEventListener('click', () => {
        if (sidebar) sidebar.classList.remove('mobile-show');
        if (overlay) overlay.classList.remove('show');
      });
    }

    // Active Link Logic
    const currentPage = window.location.pathname; 
    const navLinks = document.querySelectorAll('.sidebar .nav-link');

    navLinks.forEach(link => {
        // More robust check: does the current path include the link's href?
        const href = link.getAttribute('href');
        if (href && currentPage.includes(href) && href !== '#') {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
});