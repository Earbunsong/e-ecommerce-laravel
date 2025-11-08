@extends('layouts.app')

@section('title', 'K2 Computer - Best Computer Deals in Cambodia')

@section('content')
    {{-- Hero Banner --}}
    <section class="hero-banner py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">Best Computer Deals</h1>
            <p class="text-xl mb-8">Discover amazing tech products at unbeatable prices</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-3 rounded-lg text-lg font-semibold transition-colors inline-block">
                    Shop Now
                </a>
                <a href="#categories" class="border-2 border-white hover:bg-white hover:text-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold transition-colors inline-block">
                    Browse Categories
                </a>
            </div>
        </div>
    </section>

    {{-- Categories --}}
    <section id="categories" class="py-12 bg-gray-50">
        <div class="container max-w-screen-xl mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold">Popular Categories</h2>
                <a href="{{ route('products.index') }}" class="text-amber-600 hover:text-amber-700 transition-colors">View All →</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 justify-items-center">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->name]) }}" class="group w-full max-w-[180px]">
                        <div class="bg-white rounded-lg shadow hover:shadow-md overflow-hidden text-center category-card">
                            <div class="h-36 w-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                <i class="fas fa-{{ $category->name === 'Laptops' ? 'laptop' : ($category->name === 'Desktops' ? 'desktop' : ($category->name === 'Accessories' ? 'mouse' : ($category->name === 'Monitors' ? 'tv' : ($category->name === 'Printers' ? 'print' : 'wifi')))) }} text-4xl text-blue-600"></i>
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium">{{ $category->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $category->products_count }} products</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured Products --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-8 text-center">Featured Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('products.index') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-3 rounded-lg font-semibold transition-colors inline-block">
                    View All Products
                </a>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-8 text-center">Why Choose K2 Computer?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shipping-fast text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Fast Delivery</h3>
                    <p class="text-gray-600">Delivery within 2 hours across 25 provinces in Cambodia</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Quality Guarantee</h3>
                    <p class="text-gray-600">100% authentic products with manufacturer warranty</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-2xl text-amber-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">24/7 Support</h3>
                    <p class="text-gray-600">Expert customer support whenever you need help</p>
                </div>
            </div>
        </div>
    </section>
@endsection
