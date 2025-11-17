<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'K2 Computer - Electronics Store')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .brand-logo {
            height: 40px;
            object-fit: contain;
            filter: grayscale(1);
            transition: filter 0.3s;
        }
        .brand-logo:hover {
            filter: grayscale(0);
        }
        .product-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .hero-banner {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }
        .category-card {
            transition: all 0.3s ease;
        }
        .category-card:hover {
            transform: scale(1.05);
        }
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }
        .notification.show {
            transform: translateX(0);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
@include('partials.header')

<main>
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-500 text-white px-6 py-3 text-center">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white px-6 py-3 text-center">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

@include('partials.footer')

{{-- Notification Template --}}
<div id="notification-template" class="notification hidden">
    <div class="bg-white border-l-4 border-green-500 rounded-lg shadow-lg p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-lg"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-gray-900" id="notification-message"></p>
            </div>
            <div class="ml-auto pl-3">
                <button onclick="hideNotification()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Set up CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Check for cart added message in localStorage
        const cartAdded = localStorage.getItem('cart_added');
        if (cartAdded) {
            try {
                const data = JSON.parse(cartAdded);
                // Only show if less than 5 seconds old
                if (Date.now() - data.timestamp < 5000) {
                    showNotification(data.message, 'success');
                }
            } catch (e) {
                console.error('Error parsing cart_added:', e);
            }
            localStorage.removeItem('cart_added');
        }

        // Update cart count on page load
        updateCartCount();
        updateWishlistCount();
    });

    // Simple Add to Cart Function - Using API
    function addToCart(productId, quantity = 1) {
        // Show loading state
        const button = $(`button[onclick*="addToCart(${productId})"]`);
        const originalText = button.html();
        button.html('<i class="fas fa-spinner fa-spin mr-2"></i>Adding...').prop('disabled', true);

        // Get CSRF token
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Debug log
        console.log('Adding to cart:', { productId, quantity, csrfToken: csrfToken ? 'present' : 'missing' });

        // Use API endpoint instead of traditional route
        $.ajax({
            url: `/api/cart/add/${productId}`,
            method: 'POST',
            data: {
                product_id: productId,  // Send product_id explicitly
                quantity: quantity,
                _token: csrfToken
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'  // Important for Laravel to recognize AJAX
            },
            xhrFields: {
                withCredentials: true  // Include cookies in request
            },
            cache: false  // Prevent caching issues
        })
            .done(function(response) {
                console.log('Cart success:', response);
                if (response.success) {
                    showNotification(response.message, 'success');
                    updateCartCount(response.cart_count);

                    // Reload page after adding to cart to ensure session sync
                    // This is a temporary fix to ensure cart data persists
                    setTimeout(function() {
                        // Store flash message in localStorage
                        localStorage.setItem('cart_added', JSON.stringify({
                            message: response.message,
                            timestamp: Date.now()
                        }));
                    }, 500);

                    // Show mini cart with animation
                    if (response.item) {
                        showMiniCart(response.item);
                    }
                }
            })
            .fail(function(xhr) {
                console.error('Cart error:', xhr.status, xhr.responseText);
                const response = xhr.responseJSON;

                // Better error messages
                let errorMsg = 'Error adding to cart';
                if (xhr.status === 419) {
                    errorMsg = '⚠️ Session issue detected. Reloading page...';
                    showNotification(errorMsg, 'error');

                    // Try to get fresh CSRF token first
                    fetch('/sanctum/csrf-cookie', { credentials: 'same-origin' })
                        .then(() => {
                            // Get new token
                            return fetch('/api/cart/count', { credentials: 'same-origin' });
                        })
                        .then(() => {
                            // Token refreshed, reload page
                            setTimeout(() => window.location.reload(), 1000);
                        })
                        .catch(() => {
                            // Fallback: just reload
                            setTimeout(() => window.location.reload(), 1000);
                        });
                    return;
                } else if (response && response.message) {
                    errorMsg = response.message;
                } else if (xhr.status === 0) {
                    errorMsg = 'Network error. Please check your connection.';
                } else if (xhr.status === 500) {
                    errorMsg = 'Server error. Please try again.';
                }

                showNotification(errorMsg, 'error');
            })
            .always(function() {
                // Reset button
                button.html(originalText).prop('disabled', false);
            });
    }

    // Enhanced Add to Cart with Quantity
    function addToCartWithQuantity(productId) {
        const quantityInput = document.getElementById(`quantity-${productId}`);
        const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

        addToCart(productId, quantity);
    }

    // Toggle Wishlist
    function toggleWishlist(productId) {
        $.post(`/wishlist/toggle/${productId}`, {
            _token: $('meta[name="csrf-token"]').attr('content')
        })
            .done(function(response) {
                if (response.success) {
                    showNotification(response.message, response.action === 'added' ? 'success' : 'info');
                    updateWishlistCount(response.wishlist_count);

                    // Update heart icon
                    $(`button[onclick*="toggleWishlist(${productId})"] i`).toggleClass('text-red-500 text-gray-400');
                }
            })
            .fail(function() {
                showNotification('Error updating wishlist', 'error');
            });
    }

    // Update Cart Quantity (for cart page)
    function updateCartQuantity(productId, quantity) {
        $.post(`/cart/update/${productId}`, {
            quantity: quantity,
            _token: $('meta[name="csrf-token"]').attr('content')
        })
            .done(function(response) {
                if (response.success) {
                    location.reload();
                }
            })
            .fail(function() {
                showNotification('Error updating cart', 'error');
            });
    }

    // Remove from Cart - Using API
    function removeFromCart(productId) {
        if (confirm('Remove this item from cart?')) {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: `/api/cart/remove/${productId}`,
                method: 'POST',
                data: {
                    _token: csrfToken
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                xhrFields: {
                    withCredentials: true
                }
            })
                .done(function(response) {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        updateCartCount(response.cart_count);
                        location.reload();
                    }
                })
                .fail(function(xhr) {
                    let errorMsg = xhr.status === 419 ? 'Session expired. Please refresh.' : 'Error removing item';
                    showNotification(errorMsg, 'error');
                });
        }
    }

    // Update cart count display
    function updateCartCount(count = null) {
        if (count === null) {
            // Fetch from server to ensure accuracy
            $.get('/api/cart/count', function(response) {
                if (response.success) {
                    updateCartBadge(response.count);
                }
            }).fail(function() {
                // Fallback to page load count
                const fallbackCount = {{ session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }};
                updateCartBadge(fallbackCount);
            });
        } else {
            updateCartBadge(count);
        }
    }

    function updateCartBadge(count) {
        const $cartBadge = $('#cartCount');
        if (count > 0) {
            $cartBadge.text(count).removeClass('hidden');
        } else {
            $cartBadge.addClass('hidden');
        }
    }

    // Update wishlist count display
    function updateWishlistCount(count = null) {
        if (count === null) {
            count = {{ session('wishlist') ? count(session('wishlist')) : 0 }};
        }

        const $wishlistBadge = $('#wishlistCount');
        if (count > 0) {
            $wishlistBadge.text(count).removeClass('hidden');
        } else {
            $wishlistBadge.addClass('hidden');
        }
    }

    // Show notification
    function showNotification(message, type = 'info') {
        const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';

        const notification = $(`
        <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full">
            <div class="flex items-center">
                <i class="fas ${icon} mr-2"></i>
                <span>${message}</span>
                <button onclick="$(this).parent().parent().remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `);

        $('body').append(notification);

        setTimeout(() => notification.removeClass('translate-x-full'), 100);
        setTimeout(() => notification.addClass('translate-x-full'), 3000);
        setTimeout(() => notification.remove(), 3300);
    }

    // Show mini cart
    function showMiniCart(item) {
        const miniCart = $(`
        <div class="fixed top-20 right-4 bg-white border rounded-lg shadow-lg p-4 z-50 w-80" id="mini-cart">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-green-600">
                    <i class="fas fa-check-circle mr-2"></i>Added to Cart
                </h3>
                <button onclick="$('#mini-cart').remove()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                    <i class="fas fa-box text-gray-400"></i>
                </div>
                <div>
                    <p class="font-medium text-sm">${item.name}</p>
                    <p class="text-gray-600 text-sm">Qty: ${item.quantity} × $${item.price}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="/cart" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white py-2 px-3 rounded text-sm text-center transition-colors">
                    View Cart
                </a>
                <button onclick="$('#mini-cart').remove()" class="flex-1 border border-gray-300 hover:bg-gray-50 py-2 px-3 rounded text-sm transition-colors">
                    Continue Shopping
                </button>
            </div>
        </div>
    `);

        $('body').append(miniCart);

        // Auto remove after 5 seconds
        setTimeout(() => {
            $('#mini-cart').fadeOut(() => $('#mini-cart').remove());
        }, 5000);
    }

    // Quantity controls for product cards
    function increaseQuantity(productId) {
        const input = document.getElementById(`quantity-${productId}`);
        if (input) {
            const currentValue = parseInt(input.value);
            const maxValue = parseInt(input.max) || 99;
            if (currentValue < maxValue) {
                input.value = currentValue + 1;
            }
        }
    }

    function decreaseQuantity(productId) {
        const input = document.getElementById(`quantity-${productId}`);
        if (input) {
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }
    }

    // Buy Now function
    function buyNow(productId) {
        addToCart(productId, 1);
        setTimeout(() => {
            window.location.href = '/cart';
        }, 1000);
    }

    // ========================================
    // Live Search Functionality
    // ========================================
    let searchTimeout;
    const searchInput = document.getElementById('live-search-input');
    const searchResults = document.getElementById('search-results');
    const searchResultsContent = document.getElementById('search-results-content');

    if (searchInput) {
        // Listen for input changes
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            // Clear previous timeout
            clearTimeout(searchTimeout);

            // Hide results if query is empty
            if (query.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }

            // Show loading state
            searchResultsContent.innerHTML = `
                <div class="p-4 text-center text-gray-500">
                    <i class="fas fa-spinner fa-spin mr-2"></i>Searching...
                </div>
            `;
            searchResults.classList.remove('hidden');

            // Debounce search (wait 300ms after user stops typing)
            searchTimeout = setTimeout(() => {
                performLiveSearch(query);
            }, 300);
        });

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!document.getElementById('search-container').contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });

        // Prevent form submission when Enter is pressed if results are showing
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchResults.classList.add('hidden');
            }
        });
    }

    function performLiveSearch(query) {
        fetch(`/api/products/search?q=${encodeURIComponent(query)}&per_page=5`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    displaySearchResults(data.data, query);
                } else {
                    searchResultsContent.innerHTML = `
                        <div class="p-4 text-center text-gray-500">
                            <i class="fas fa-search mr-2"></i>No products found for "${query}"
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                searchResultsContent.innerHTML = `
                    <div class="p-4 text-center text-red-500">
                        <i class="fas fa-exclamation-circle mr-2"></i>Error performing search
                    </div>
                `;
            });
    }

    function displaySearchResults(products, query) {
        let html = '<div class="divide-y divide-gray-100">';

        products.forEach(product => {
            const price = parseFloat(product.price).toFixed(2);
            const categoryName = product.category?.name || 'Uncategorized';

            html += `
                <a href="/products/${product.id}" class="flex items-center gap-3 p-3 hover:bg-gray-50 transition-colors">
                    <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-laptop text-gray-400 text-2xl"></i>
                    </div>
                    <div class="flex-grow min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 truncate">${highlightQuery(product.name, query)}</h4>
                        <p class="text-xs text-gray-500">${categoryName}</p>
                        <p class="text-sm font-bold text-amber-600 mt-1">$${price}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </div>
                </a>
            `;
        });

        html += '</div>';

        html += `
            <a href="/products?q=${encodeURIComponent(query)}" class="block p-3 text-center text-amber-600 hover:bg-gray-50 border-t border-gray-200 font-medium">
                View all results for "${query}" <i class="fas fa-arrow-right ml-1"></i>
            </a>
        `;

        searchResultsContent.innerHTML = html;
    }

    function highlightQuery(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<span class="bg-yellow-200">$1</span>');
    }
</script>
@stack('scripts')
</body>
</html>
