@php
    $discountPercent = isset($product->original_price) && $product->original_price > 0
        ? round((($product->original_price - $product->price) / $product->original_price) * 100)
        : 0;
    $isWishlisted = session('wishlist') && in_array($product->id, session('wishlist'));
    $stockStatus = $product->in_stock ?? true;
@endphp

<div class="bg-white rounded-lg shadow hover:shadow-lg transition-all product-card" data-product-id="{{ $product->id }}">
    <div class="relative">
        <a href="{{ route('products.show', $product->id) }}">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}"
                     alt="{{ $product->name }}"
                     class="h-48 w-full object-cover rounded-t-lg"
                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'h-48 w-full bg-gradient-to-br from-gray-100 to-gray-200 rounded-t-lg flex items-center justify-center\'><i class=\'fas fa-image text-6xl text-gray-400\'></i></div>';">
            @else
                <div class="h-48 w-full bg-gradient-to-br from-gray-100 to-gray-200 rounded-t-lg flex items-center justify-center">
                    <i class="fas fa-image text-6xl text-gray-400"></i>
                </div>
            @endif
        </a>

        @if($discountPercent > 0)
            <span class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-full text-sm font-semibold">
                -{{ $discountPercent }}%
            </span>
        @endif

        @if(!$stockStatus)
            <span class="absolute top-2 right-2 bg-gray-500 text-white px-2 py-1 rounded text-sm">
                Out of Stock
            </span>
        @else
            <button onclick="toggleWishlist({{ $product->id }})" class="absolute top-2 right-2 p-2 bg-white rounded-full shadow hover:bg-gray-50 transition-colors">
                <i class="fas fa-heart {{ $isWishlisted ? 'text-red-500' : 'text-gray-400' }}"></i>
            </button>
        @endif
    </div>

    <div class="p-4">
        <h3 class="font-semibold text-lg mb-2 line-clamp-2">
            <a href="{{ route('products.show', $product->id) }}" class="hover:text-amber-600 transition-colors">
                {{ $product->name }}
            </a>
        </h3>

        <div class="flex items-center mb-2">
            @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star {{ $i <= ($product->rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
            @endfor
            <span class="text-gray-500 text-sm ml-2">({{ $product->review_count ?? 0 }})</span>
        </div>

        <div class="mb-3">
            <span class="text-2xl font-bold text-amber-600">${{ number_format($product->price, 2) }}</span>
            @if(isset($product->original_price) && $product->original_price > $product->price)
                <span class="text-gray-400 line-through ml-2">${{ number_format($product->original_price, 2) }}</span>
            @endif
        </div>

        @if($stockStatus)
            {{-- Quantity Selector --}}
            <div class="flex items-center justify-between mb-3">
                <label class="text-sm font-medium text-gray-700">Qty:</label>
                <div class="flex items-center border border-gray-300 rounded">
                    <button type="button" onclick="decreaseQuantity({{ $product->id }})" class="px-2 py-1 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-minus text-xs"></i>
                    </button>
                    <input type="number" id="quantity-{{ $product->id }}" value="1" min="1" max="99"
                           class="w-12 text-center border-0 focus:ring-0 text-sm">
                    <button type="button" onclick="increaseQuantity({{ $product->id }})" class="px-2 py-1 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                </div>
            </div>

            {{-- Add to Cart Button --}}
            <button onclick="addToCartWithQuantity({{ $product->id }})"
                    class="w-full bg-amber-500 hover:bg-amber-600 text-white py-2 px-4 rounded-lg font-semibold mb-2 transition-colors">
                <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
            </button>

            {{-- Buy Now Button --}}
            <button onclick="buyNow({{ $product->id }})"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-semibold transition-colors">
                Buy Now
            </button>
        @else
            <button disabled class="w-full bg-gray-300 text-gray-500 py-2 px-4 rounded-lg cursor-not-allowed font-semibold">
                Out of Stock
            </button>
        @endif
    </div>
</div>
