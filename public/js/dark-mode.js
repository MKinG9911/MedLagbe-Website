document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const mobileDarkModeToggle = document.getElementById('mobile-dark-mode-toggle');
    const darkModeStyle = document.getElementById('dark-mode-style');
    
    // Check for saved dark mode preference
    const savedMode = localStorage.getItem('darkMode');
    if (savedMode === 'enabled') {
        enableDarkMode();
    }
    
    // Toggle dark mode
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', toggleDarkMode);
    }
    
    if (mobileDarkModeToggle) {
        mobileDarkModeToggle.addEventListener('click', toggleDarkMode);
    }
    
    function toggleDarkMode() {
        if (document.body.classList.contains('dark-mode')) {
            disableDarkMode();
        } else {
            enableDarkMode();
        }
    }
    
    function enableDarkMode() {
        document.body.classList.add('dark-mode');
        localStorage.setItem('darkMode', 'enabled');
        
        // If the dark mode stylesheet isn't loaded yet, load it
        if (!darkModeStyle) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'assets/css/dark-mode.css';
            link.id = 'dark-mode-style';
            document.head.appendChild(link);
        }
        
        // Send to server if user is logged in
        if (typeof userId !== 'undefined') {
            fetch('update-dark-mode.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ darkMode: true })
            });
        }
    }
    
    function disableDarkMode() {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('darkMode', 'disabled');
        
        // Remove the dark mode stylesheet if it exists
        if (darkModeStyle) {
            darkModeStyle.remove();
        }
        
        // Send to server if user is logged in
        if (typeof userId !== 'undefined') {
            fetch('update-dark-mode.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ darkMode: false })
            });
        }
    }
});