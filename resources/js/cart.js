// resources/js/cart.js - Add this to your main JavaScript file or include separately

document.addEventListener('DOMContentLoaded', function() {
    // CSRF token for AJAX requests
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Toast notification function
    function showToast(message, type = 'success') {
        // Remove existing toast if any
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 z-50 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white`;

        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;

        // Add to page
        document.body.appendChild(toast);

        // Show toast with animation
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);

        // Hide toast after 3 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 300);
        }, 3000);
    }

    // Update cart count in header
    function updateCartCount(count) {
        const cartCountElements = document.querySelectorAll('.cart-count');
        cartCountElements.forEach(element => {
            element.textContent = count;
            element.style.display = count > 0 ? 'inline' : 'none';
        });

        // Update cart icon badge
        const cartBadges = document.querySelectorAll('.cart-badge');
        cartBadges.forEach(badge => {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        });
    }

    // Show loading state
    function showLoading(button) {
        const originalContent = button.innerHTML;
        button.disabled = true;
        button.innerHTML = `
            <div class="flex items-center justify-center space-x-2">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                <span>Adding...</span>
            </div>
        `;
        return originalContent;
    }

    // Hide loading state
    function hideLoading(button, originalContent) {
        button.disabled = false;
        button.innerHTML = originalContent;
    }

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const button = this.querySelector('.add-to-cart-btn');
            const productId = this.dataset.productId;
            const quantity = this.querySelector('select[name="quantity"]').value;

            // Show loading state
            const originalContent = showLoading(button);

            // Make AJAX request
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: parseInt(quantity)
                })
            })
                .then(response => response.json())
                .then(data => {
                    hideLoading(button, originalContent);

                    if (data.success) {
                        showToast(`${data.product_name} added to cart!`);
                        updateCartCount(data.cart_count);

                        // Optional: Add visual feedback to the button
                        button.innerHTML = `
                        <i class="fas fa-check"></i>
                        <span>Added!</span>
                    `;

                        setTimeout(() => {
                            button.innerHTML = originalContent;
                        }, 2000);
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    hideLoading(button, originalContent);
                    console.error('Error:', error);
                    showToast('Something went wrong. Please try again.', 'error');
                });
        });
    });

    // Quick add to cart (for single click add to cart buttons)
    document.querySelectorAll('.quick-add-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const quantity = this.dataset.quantity || 1;

            const originalContent = showLoading(this);

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: parseInt(quantity)
                })
            })
                .then(response => response.json())
                .then(data => {
                    hideLoading(this, originalContent);

                    if (data.success) {
                        showToast(`${data.product_name} added to cart!`);
                        updateCartCount(data.cart_count);
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    hideLoading(this, originalContent);
                    console.error('Error:', error);
                    showToast('Something went wrong. Please try again.', 'error');
                });
        });
    });

    // Wishlist functionality
    document.querySelectorAll('.wishlist-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const heartIcon = this.querySelector('i');
            const isWishlisted = heartIcon.classList.contains('fas');

            // Toggle heart icon immediately for better UX
            if (isWishlisted) {
                heartIcon.classList.remove('fas');
                heartIcon.classList.add('far');
                this.querySelector('span').textContent = 'Add to Wishlist';
                showToast('Removed from wishlist');
            } else {
                heartIcon.classList.remove('far');
                heartIcon.classList.add('fas');
                this.querySelector('span').textContent = 'Remove from Wishlist';
                showToast('Added to wishlist');
            }

            // You can implement actual wishlist API calls here
            // For now, we'll just toggle the UI
        });
    });

    // Load cart count on page load
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            updateCartCount(data.count);
        })
        .catch(error => {
            console.error('Error loading cart count:', error);
        });

    // Product quantity validation
    document.querySelectorAll('select[name="quantity"]').forEach(select => {
        select.addEventListener('change', function() {
            const quantity = parseInt(this.value);
            const maxStock = parseInt(this.dataset.maxStock);

            if (quantity > maxStock) {
                this.value = maxStock;
                showToast(`Only ${maxStock} items available`, 'error');
            }
        });
    });
});

// Cart Mini Widget (for header dropdown)
function initCartWidget() {
    const cartToggle = document.querySelector('.cart-toggle');
    const cartDropdown = document.querySelector('.cart-dropdown');

    if (cartToggle && cartDropdown) {
        cartToggle.addEventListener('click', function(e) {
            e.preventDefault();
            cartDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!cartToggle.contains(e.target) && !cartDropdown.contains(e.target)) {
                cartDropdown.classList.add('hidden');
            }
        });
    }
}

// Initialize cart widget when DOM is loaded
document.addEventListener('DOMContentLoaded', initCartWidget);

// Smooth scroll to cart section (if on same page)
function scrollToCart() {
    const cartSection = document.getElementById('cart-section');
    if (cartSection) {
        cartSection.scrollIntoView({ behavior: 'smooth' });
    }
}

// Add event listener for mobile quantity buttons
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('mobile-qty-btn')) {
        e.preventDefault();
        const action = e.target.dataset.action;
        const input = e.target.parentNode.querySelector('input[type="number"]');
        const currentValue = parseInt(input.value);
        const maxValue = parseInt(input.max);
        const minValue = parseInt(input.min);

        if (action === 'increase' && currentValue < maxValue) {
            input.value = currentValue + 1;
            input.dispatchEvent(new Event('change'));
        } else if (action === 'decrease' && currentValue > minValue) {
            input.value = currentValue - 1;
            input.dispatchEvent(new Event('change'));
        }
    }
});
