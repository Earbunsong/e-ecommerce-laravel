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
                <h2 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-th-large mr-2 text-blue-600"></i>Popular Categories
                </h2>
                <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold transition-colors flex items-center">
                    View All <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @forelse($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                       class="group bg-white rounded-xl shadow-sm hover:shadow-lg overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
                        <div class="aspect-square relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                            @if($category->image)
                                <img src="{{ $category->image_url }}"
                                     alt="{{ $category->name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='{{ asset('images/placeholder-category.png') }}';">
                            @else
                                {{-- Fallback icon based on category name --}}
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-{{
                                        Str::contains(Str::lower($category->name), 'laptop') ? 'laptop' :
                                        (Str::contains(Str::lower($category->name), ['desktop', 'pc']) ? 'desktop' :
                                        (Str::contains(Str::lower($category->name), ['mouse', 'keyboard', 'accessory', 'accessories']) ? 'mouse' :
                                        (Str::contains(Str::lower($category->name), 'monitor') ? 'tv' :
                                        (Str::contains(Str::lower($category->name), 'print') ? 'print' :
                                        (Str::contains(Str::lower($category->name), ['network', 'wifi', 'router']) ? 'wifi' :
                                        (Str::contains(Str::lower($category->name), 'storage') ? 'hdd' :
                                        (Str::contains(Str::lower($category->name), 'headphone') ? 'headphones' :
                                        'microchip')))))))
                                    }} text-5xl text-blue-600 group-hover:text-blue-700 transition-colors"></i>
                                </div>
                            @endif

                            {{-- Product count badge --}}
                            @if($category->products_count > 0)
                                <div class="absolute top-2 right-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md">
                                    {{ $category->products_count }}
                                </div>
                            @endif
                        </div>
                        <div class="p-3 text-center">
                            <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-1">
                                {{ $category->name }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $category->products_count }} {{ Str::plural('item', $category->products_count) }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">No categories available yet</p>
                    </div>
                @endforelse
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
