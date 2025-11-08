@extends('layouts.app')

@section('title', 'Products - K2 Computer')

@section('content')
    <div class="bg-stone-800 text-white py-8">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold">Products</h1>
            @if(request('category'))
                <p class="text-gray-300 mt-2">Category: {{ request('category') }}</p>
            @endif
            @if(request('q'))
                <p class="text-gray-300 mt-2">Search results for: "{{ request('q') }}"</p>
            @endif
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 flex gap-6">
        {{-- Sidebar Filters --}}
        <aside class="w-64 hidden lg:block">
            <form id="filterForm" method="GET" action="{{ route('products.index') }}">
                <input type="hidden" name="q" value="{{ request('q') }}">

                {{-- Categories Filter --}}
                <div class="bg-white p-4 rounded-lg shadow mb-6">
                    <h2 class="text-lg font-semibold mb-4">Categories</h2>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('products.index', array_merge(request()->all(), ['category' => null])) }}"
                               class="text-gray-700 hover:text-amber-600 transition-colors {{ !request('category') ? 'text-amber-600 font-semibold' : '' }}">
                                All Categories
                            </a>
                        </li>
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('products.index', array_merge(request()->all(), ['category' => $cat['name']])) }}"
                                   class="text-gray-700 hover:text-amber-600 transition-colors {{ request('category') === $cat['name'] ? 'text-amber-600 font-semibold' : '' }}">
                                    {{ $cat['name'] }} ({{ $cat['count'] }})
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Price Range Filter --}}
                <div class="bg-white p-4 rounded-lg shadow mb-6">
                    <h2 class="text-lg font-semibold mb-4">Price Range</h2>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="price_range" value="" class="mr-2" {{ !request('price_range') ? 'checked' : '' }} onchange="this.form.submit()">
                            All Prices
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price_range" value="0-100" class="mr-2" {{ request('price_range') === '0-100' ? 'checked' : '' }} onchange="this.form.submit()">
                            $0 - $100
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price_range" value="100-500" class="mr-2" {{ request('price_range') === '100-500' ? 'checked' : '' }} onchange="this.form.submit()">
                            $100 - $500
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price_range" value="500-1000" class="mr-2" {{ request('price_range') === '500-1000' ? 'checked' : '' }} onchange="this.form.submit()">
                            $500 - $1,000
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="price_range" value="1000+" class="mr-2" {{ request('price_range') === '1000+' ? 'checked' : '' }} onchange="this.form.submit()">
                            $1,000+
                        </label>
                    </div>
                </div>

                {{-- Brand Filter --}}
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-4">Brands</h2>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($brands as $brand)
                            <label class="flex items-center">
                                <input type="checkbox" name="brand[]" value="{{ $brand }}" class="mr-2"
                                       {{ in_array($brand, (array)request('brand', [])) ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                                {{ $brand }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </form>
        </aside>

        {{-- Products Grid --}}
        <div class="flex-1">
            {{-- Results Header --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <p class="text-gray-600">Showing {{ $products->count() }} products</p>
                <form method="GET" action="{{ route('products.index') }}" class="flex gap-4">
                    @foreach(request()->all() as $key => $value)
                        @if($key !== 'sort')
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endif
                    @endforeach
                    <select name="sort" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500" onchange="this.form.submit()">
                        <option value="">Sort by Default</option>
                        <option value="price-low" {{ request('sort') === 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price-high" {{ request('sort') === 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name: A to Z</option>
                        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Highest Rated</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                    </select>
            </div>

            {{-- Products or No Results --}}
            @if($products->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-600 mb-4">No products found</h2>
                    <p class="text-gray-600 mb-6">Try adjusting your filters or search terms</p>
                    <a href="{{ route('products.index') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-lg transition-colors">
                        View All Products
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        @include('components.product-card', ['product' => $product])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
