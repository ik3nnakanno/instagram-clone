document.addEventListener('DOMContentLoaded', () => {
    const switchAppearanceBtn = document.querySelector('.switch-appearance');
    const settingsDiv = document.getElementById('settings');
    const themeDiv = document.querySelector('.theme');
    const toggleSwitch = document.getElementById('theme-toggle');
    const body = document.body;
    const lightImages = document.querySelectorAll('.dark');
    const darkImages = document.querySelectorAll('.light');
    const moreLabel = document.querySelector('.more-label');
    const elementsToColor = document.querySelectorAll('h1, h2, h3, h4, h5, a, label, small, p, textarea, span, b');
    const searchNav = document.querySelector('.searchnav');
    const navi = document.querySelector('.navi');
    const views = document.querySelector('.views');

    function setTheme(theme) {
        body.classList.remove('light-theme', 'dark-theme');
        body.classList.add(theme === 'light' ? 'light-theme' : 'dark-theme');

        lightImages.forEach(img => img.style.display = theme === 'light' ? 'none' : 'block');
        darkImages.forEach(img => img.style.display = theme === 'light' ? 'block' : 'none');

        elementsToColor.forEach(el => {
            el.style.color = theme === 'light' ? 'black' : 'white';
        });

        if (searchNav) {
            searchNav.style.backgroundColor = theme === 'light' ? 'white' : 'black';
            searchNav.style.color = theme === 'light' ? 'black' : 'white';
        }

        if (navi) {
            navi.style.backgroundColor = theme === 'light' ? 'white' : 'black';
            navi.style.color = theme === 'light' ? 'black' : 'white';
        }

        const views = document.querySelectorAll('.views');
        views.forEach(view => {
            view.style.backgroundColor = theme === 'light' ? 'white' : 'black';
            view.style.color = theme === 'light' ? 'black' : 'white';
        });

        if (toggleSwitch) {
            toggleSwitch.checked = theme === 'light';
        }
    }

    function toggleTheme() {
        if (toggleSwitch) {
            const theme = toggleSwitch.checked ? 'light' : 'dark';
            setTheme(theme);
            localStorage.setItem('theme', theme);
        }
    }

    // Click event for switch appearance button
    switchAppearanceBtn?.addEventListener('click', function () {
        settingsDiv.style.display = 'none';
        themeDiv.style.display = 'block';
    });

    // Click event for the specific label to show the theme settings
    moreLabel?.addEventListener('click', function () {
        settingsDiv.style.display = 'none';
        themeDiv.style.display = 'block';
    });

    const storedTheme = localStorage.getItem('theme');
    if (storedTheme) {
        setTheme(storedTheme);
    } else {
        setTheme('dark');
    }

    // Event listener for theme toggle switch
    if (toggleSwitch) {
        toggleSwitch.addEventListener('change', toggleTheme);
    }

    // Hide .theme when clicking outside of it
    document.addEventListener('click', function (event) {
        if (themeDiv && !themeDiv.contains(event.target) && event.target !== switchAppearanceBtn && event.target !== moreLabel) {
            themeDiv.style.display = 'none';
        }
    });
});
