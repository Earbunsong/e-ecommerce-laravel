@extends('layouts.app')

@section('title', $product['name'] . ' - K2 Computer')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <nav class="text-sm text-gray-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-amber-600">Home</a> /
            <a href="{{ route('products.index') }}" class="hover:text-amber-600">Products</a> /
            <a href="{{ route('products.index', ['category' => $product['category']]) }}" class="hover:text-amber-600">{{ $product['category'] }}</a> /
            <span>{{ $product['name'] }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <div>
                <img src="{{ asset('images/'.$product['image']) }}" alt="{{ $product['name'] }}" class="w-full rounded-lg shadow-lg">
            </div>
            <div>
                <h1 class="text-3xl font-bold mb-4">{{ $product['name'] }}</h1>

                <div class="flex items-center mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $product['rating'])
                            <i class="fas fa-star text-yellow-400"></i>
                        @else
                            <i class="far fa-star text-gray-300"></i>
                        @endif
                    @endfor
                    <span class="text-gray-500 ml-2">({{ $product['review_count'] }} reviews)</span>
                </div>

                @php
                    $savings = $product['original_price'] ? $product['original_price'] - $product['price'] : 0;
                    $discountPercent = $product['original_price'] ? round(($savings / $product['original_price']) * 100) : 0;
                @endphp

                <div class="mb-6">
                    <span class="text-4xl font-bold text-amber-600">${{ $product['price'] }}</span>
                    @if($product['original_price'])
                        <span class="text-xl text-gray-400 line-through ml-3">${{ $product['original_price'] }}</span>
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm ml-3">Save ${{ $savings }} ({{ $discountPercent }}% off)</span>
                    @endif
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Specifications:</h3>
                    <ul class="list-disc list-inside space-y-1 text-gray-700">
                        @foreach($product['specs'] as $spec)
                            <li>{{ $spec }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="flex gap-4 mb-6">
                    <button onclick="addToCart({{ $product['id'] }})" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white py-3 px-6 rounded-lg text-lg font-semibold transition-colors">
                        Add to Cart - ${{ $product['price'] }}
                    </button>
                    <button onclick="toggleWishlist({{ $product['id'] }})" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        @php
                            $isWishlisted = session('wishlist') && in_array($product['id'], session('wishlist'));
                        @endphp
                        <i class="fas fa-heart {{ $isWishlisted ? 'text-red-500' : 'text-gray-400' }} text-xl"></i>
                    </button>
                </div>

                <div class="border-t pt-6">
                    <p class="text-gray-600"><strong>Category:</strong> {{ $product['category'] }}</p>
                    <p class="text-gray-600"><strong>Brand:</strong> {{ $product['brand'] }}</p>
                    <p class="text-gray-600"><strong>SKU:</strong> {{ strtoupper($product['brand']) }}-{{ str_pad($product['id'], 4, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        @if($relatedProducts->isNotEmpty())
            <div class="mt-12">
                <h2 class="text-2xl font-bold mb-6">Related Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        @include('components.product-card', ['product' => $relatedProduct])
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
