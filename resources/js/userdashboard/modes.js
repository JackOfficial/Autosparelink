document.addEventListener('DOMContentLoaded', () => {
    const themeToggler = document.getElementById('themeToggler');
    const themeIcon = document.getElementById('themeIcon');
    const htmlElement = document.documentElement;

    // Set initial icon based on current theme
    const updateIcon = (theme) => {
        if (theme === 'dark') {
            themeIcon.classList.replace('ti-sun', 'ti-moon');
        } else {
            themeIcon.classList.replace('ti-moon', 'ti-sun');
        }
    };

    // Initialize icon on load
    updateIcon(htmlElement.getAttribute('data-bs-theme'));

    themeToggler.addEventListener('click', () => {
        const currentTheme = htmlElement.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
        // Update DOM
        htmlElement.setAttribute('data-bs-theme', newTheme);
        
        // Save to LocalStorage
        localStorage.setItem('theme', newTheme);
        
        // Update Icon
        updateIcon(newTheme);
    });
});