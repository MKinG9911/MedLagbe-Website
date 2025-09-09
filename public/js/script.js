// Theme Toggle
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.querySelector('.theme-toggle');
    const htmlElement = document.documentElement;
    
    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-theme', savedTheme);
    
    // Update icon based on theme
    function updateThemeIcon() {
        const isDark = htmlElement.getAttribute('data-theme') === 'dark';
        themeToggle.innerHTML = isDark ? '☀️' : '🌙';
    }
    
    updateThemeIcon();
    
    // Toggle theme
    themeToggle.addEventListener('click', function() {
        const currentTheme = htmlElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        htmlElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon();
    });
});

// Search Suggestions
function initializeSearch() {
    const searchInput = document.querySelector('.search-input');
    const searchContainer = document.querySelector('.search-container');
    
    if (!searchInput) return;
    
    let suggestionsContainer = document.querySelector('.search-suggestions');
    if (!suggestionsContainer) {
        suggestionsContainer = document.createElement('div');
        suggestionsContainer.className = 'search-suggestions';
        searchContainer.appendChild(suggestionsContainer);
    }
    
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            hideSuggestions();
            return;
        }
        
        searchTimeout = setTimeout(() => {
            fetchSuggestions(query);
        }, 300);
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchContainer.contains(e.target)) {
            hideSuggestions();
        }
    });
    
    function fetchSuggestions(query) {
        fetch(`/MedLagbe/product/suggestions?term=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(suggestions => {
                showSuggestions(suggestions);
            })
            .catch(error => {
                console.error('Error fetching suggestions:', error);
            });
    }
    
    function showSuggestions(suggestions) {
        suggestionsContainer.innerHTML = '';
        
        if (suggestions.length === 0) {
            hideSuggestions();
            return;
        }
        
        suggestions.slice(0, 8).forEach(suggestion => {
            const item = document.createElement('div');
            item.className = 'search-suggestion-item';
            item.textContent = suggestion;
            item.addEventListener('click', function() {
                searchInput.value = suggestion;
                hideSuggestions();
                // Trigger search
                const form = searchInput.closest('form');
                if (form) form.submit();
            });
            suggestionsContainer.appendChild(item);
        });
        
        suggestionsContainer.style.display = 'block';
    }
    
    function hideSuggestions() {
        suggestionsContainer.style.display = 'none';
    }
}

// Cart Functions
function addToCart(productId, quantity = 1) {
    const btn = event.target;
    const originalText = btn.textContent;
    
    btn.disabled = true;
    btn.classList.add('loading');
    btn.textContent = 'Adding...';
    
    fetch('/MedLagbe/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Item added to cart!', 'success');
            updateCartCount();
        } else {
            showNotification(data.message || 'Failed to add item to cart', 'error');
        }
    })
    .catch(error => {
        showNotification('Error adding item to cart', 'error');
        console.error('Error:', error);
    })
    .finally(() => {
        btn.disabled = false;
        btn.classList.remove('loading');
        btn.textContent = originalText;
    });
}

function updateCartQuantity(productId, quantity) {
    fetch('/MedLagbe/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Refresh to show updated totals
        } else {
            showNotification('Failed to update quantity', 'error');
        }
    })
    .catch(error => {
        showNotification('Error updating quantity', 'error');
        console.error('Error:', error);
    });
}

function removeFromCart(productId) {
    if (!confirm('Are you sure you want to remove this item?')) {
        return;
    }
    
    fetch('/MedLagbe/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showNotification('Failed to remove item', 'error');
        }
    })
    .catch(error => {
        showNotification('Error removing item', 'error');
        console.error('Error:', error);
    });
}

function updateCartCount() {
    // Update cart count in header
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        const currentCount = parseInt(cartCount.textContent) || 0;
        cartCount.textContent = currentCount + 1;
    }
}

// Notifications
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} notification`;
    notification.textContent = message;
    
    // Style the notification
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        min-width: 300px;
        animation: slideIn 0.3s ease-out;
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);
}

// Form Validation
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        const errorElement = field.parentNode.querySelector('.form-error');
        
        if (!field.value.trim()) {
            if (!errorElement) {
                const error = document.createElement('div');
                error.className = 'form-error';
                error.textContent = 'This field is required';
                field.parentNode.appendChild(error);
            }
            field.style.borderColor = 'var(--danger-color)';
            isValid = false;
        } else {
            if (errorElement) {
                errorElement.remove();
            }
            field.style.borderColor = 'var(--border-color)';
        }
    });
    
    // Email validation
    const emailFields = form.querySelectorAll('input[type="email"]');
    emailFields.forEach(field => {
        if (field.value && !isValidEmail(field.value)) {
            const errorElement = field.parentNode.querySelector('.form-error');
            if (!errorElement) {
                const error = document.createElement('div');
                error.className = 'form-error';
                error.textContent = 'Please enter a valid email address';
                field.parentNode.appendChild(error);
            }
            field.style.borderColor = 'var(--danger-color)';
            isValid = false;
        }
    });
    
    return isValid;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Modal Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

// File Upload Preview
function previewFile(input, previewId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);
    
    if (file && preview) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (file.type.startsWith('image/')) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 200px;">`;
            } else {
                preview.innerHTML = `<p>File selected: ${file.name}</p>`;
            }
        };
        reader.readAsDataURL(file);
    }
}

// Quantity Controls
function updateQuantity(input, change) {
    const currentValue = parseInt(input.value) || 1;
    const newValue = Math.max(1, currentValue + change);
    input.value = newValue;
    
    // Trigger update for cart items
    if (input.dataset.productId) {
        updateCartQuantity(input.dataset.productId, newValue);
    }
}

// Price Formatting
function formatPrice(price) {
    return new Intl.NumberFormat('en-BD', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 0
    }).format(price);
}

// Lazy Loading for Images
function initializeLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}

// Smooth Scrolling
function smoothScrollTo(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeSearch();
    initializeLazyLoading();
    
    // Initialize form validation
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(form)) {
                e.preventDefault();
            }
        });
    });
    
    // Initialize quantity controls
    document.querySelectorAll('.quantity-control').forEach(control => {
        const input = control.querySelector('input');
        const decreaseBtn = control.querySelector('.decrease');
        const increaseBtn = control.querySelector('.increase');
        
        if (decreaseBtn) {
            decreaseBtn.addEventListener('click', () => updateQuantity(input, -1));
        }
        
        if (increaseBtn) {
            increaseBtn.addEventListener('click', () => updateQuantity(input, 1));
        }
    });
    
    // Close modals when clicking outside
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal(modal.id);
            }
        });
    });
});

// Add CSS for animations and search suggestions
const additionalStyles = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .search-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: var(--bg-primary);
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        z-index: 1000;
        display: none;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .search-suggestion-item {
        padding: 0.75rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid var(--border-color);
        transition: var(--transition);
    }
    
    .search-suggestion-item:hover {
        background-color: var(--bg-tertiary);
    }
    
    .search-suggestion-item:last-child {
        border-bottom: none;
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .quantity-control button {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        color: var(--text-primary);
        cursor: pointer;
        border-radius: var(--border-radius);
        transition: var(--transition);
    }
    
    .quantity-control button:hover {
        background: var(--bg-tertiary);
    }
    
    .quantity-control input {
        width: 60px;
        text-align: center;
        border: 1px solid var(--border-color);
        padding: 0.25rem;
        border-radius: var(--border-radius);
    }
`;

// Add the additional styles to the page
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);
