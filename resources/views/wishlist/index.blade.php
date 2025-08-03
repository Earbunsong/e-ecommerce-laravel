<?php
@extends('layouts.app')

@section('title', 'My Wishlist - K2 Computer')

@section('content')
    <div class="bg-stone-800 text-white py-8">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold">My Wishlist</h1>
            <p class="text-gray-300 mt-2">Save your favorite items for later</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        @if($wishlistItems->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-heart text-6xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-600 mb-4">Your wishlist is empty</h2>
                <p class="text-gray-600 mb-6">Add some products to your wishlist to save them for later</p>
                <a href="{{ route('products.index') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-lg inline-block transition-colors">
                    Browse Products
                </a>
            </div>
        @else
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-600">{{ $wishlistItems->count() }} item(s) in your wishlist</p>
                <form method="POST" action="{{ route('wishlist.clear') }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Clear entire wishlist?')"
                            class="text-red-600 hover:text-red-800 text-sm">
                        <i class="fas fa-trash mr-1"></i> Clear Wishlist
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($wishlistItems as $product)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-all">
                        <div class="relative">
                            <a href="{{ route('products.show', $product['id']) }}">
                                <div class="h-48 w-full bg-gradient-to-br from-gray-100 to-gray-200 rounded-t-lg flex items-center justify-center">
                                    <i class="fas fa-laptop text-6xl text-gray-400"></i>
                                </div>
                            </a>

                            {{-- Remove from Wishlist Button --}}
                            <form method="POST" action="{{ route('wishlist.remove', $product['id']) }}" class="absolute top-2 right-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Remove from wishlist?')"
                                        class="p-2 bg-white rounded-full shadow hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-times text-red-500"></i>
                                </button>
                            </form>
                        </div>

                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2 line-clamp-2">
                                <a href="{{ route('products.show', $product['id']) }}" class="hover:text-amber-600">
                                    {{ $product['name'] }}
                                </a>
                            </h3>

                            <div class="flex items-center mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $product['rating'] ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="text-gray-500 text-sm ml-2">({{ $product['review_count'] ?? 0 }})</span>
                            </div>

                            <div class="mb-3">
                                <span class="text-2xl font-bold text-amber-600">${{ number_format($product['price'], 2) }}</span>
                                @if(isset($product['original_price']) && $product['original_price'] > $product['price'])
                                    <span class="text-gray-400 line-through ml-2">${{ number_format($product['original_price'], 2) }}</span>
                                @endif
                            </div>

                            @if($product['in_stock'] ?? true)
                                <button onclick="addToCart({{ $product['id'] }})"
                                        class="w-full bg-amber-500 hover:bg-amber-600 text-white py-2 px-4 rounded-lg font-semibold mb-2 transition-colors">
                                    <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                                </button>
                            @else
                                <button disabled class="w-full bg-gray-300 text-gray-500 py-2 px-4 rounded-lg cursor-not-allowed mb-2">
                                    Out of Stock
                                </button>
                            @endif

                            <a href="{{ route('products.show', $product['id']) }}"
                               class="block w-full text-center border border-gray-300 hover:bg-gray-50 py-2 px-4 rounded-lg text-sm transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
