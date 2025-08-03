{{--

dynamic content

@extends('layouts.app')

@section('content')
    --}}
{{-- Categories Section --}}{{--

    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold">Popular Categories</h2>
                <a href="{{ url('/products') }}" class="text-amber-600 hover:text-amber-700">View All →</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($categories as $category)
                    <a href="{{ url('/products?category='.$category->id) }}" class="group">
                        <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                            <img src="{{ asset('storage/'.$category->image) }}" class="h-36 w-full object-cover group-hover:scale-105 transition-transform">
                            <div class="p-4 text-center">
                                <h3 class="font-medium">{{ $category->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $category->products_count }} products</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    --}}
{{-- Featured Products --}}{{--

    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold">Featured Products</h2>
                <a href="{{ url('/products') }}" class="text-amber-600 hover:text-amber-700">View All →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>
@endsection
--}}




{{--Static--}}



@extends('layouts.app')

@section('content')
    @php
        $categories = [
            ['id' => 1, 'name' => 'Laptops', 'count' => 12, 'image' => asset('images/categories/laptops.png')],
            ['id' => 2, 'name' => 'Desktops', 'count' => 8, 'image' => asset('images/categories/desktops.png')],
            ['id' => 3, 'name' => 'Accessories', 'count' => 15, 'image' => asset('images/categories/accessories.png')],
            ['id' => 4, 'name' => 'Monitors', 'count' => 7, 'image' => asset('images/categories/monitors.png')],
            ['id' => 5, 'name' => 'Printers', 'count' => 5, 'image' => asset('images/categories/printers.png')],
            ['id' => 6, 'name' => 'Networking', 'count' => 6, 'image' => asset('images/categories/networking.png')],
        ];

        $products = [
            ['id' => 1, 'name' => 'Dell Inspiron 15', 'price' => 699, 'discount' => 10, 'image' => asset('images/products/laptop1.jpg'), 'category' => 'Laptops', 'rating' => 4, 'reviewCount' => 32],
            ['id' => 2, 'name' => 'HP Pavilion Desktop', 'price' => 799, 'discount' => 0, 'image' => asset('images/products/desktop1.jpg'), 'category' => 'Desktops', 'rating' => 5, 'reviewCount' => 18],
            ['id' => 3, 'name' => 'Logitech Wireless Mouse', 'price' => 25, 'discount' => 15, 'image' => asset('images/products/accessory1.jpg'), 'category' => 'Accessories', 'rating' => 4, 'reviewCount' => 45],
            ['id' => 4, 'name' => 'Samsung 24\" Monitor', 'price' => 149, 'discount' => 5, 'image' => asset('images/products/monitor1.jpg'), 'category' => 'Monitors', 'rating' => 3, 'reviewCount' => 12],
            ['id' => 5, 'name' => 'Canon Office Printer', 'price' => 199, 'discount' => 0, 'image' => asset('images/products/printer1.jpg'), 'category' => 'Printers', 'rating' => 4, 'reviewCount' => 22],
            ['id' => 6, 'name' => 'TP-Link Wi-Fi Router', 'price' => 59, 'discount' => 20, 'image' => asset('images/products/router1.jpg'), 'category' => 'Networking', 'rating' => 5, 'reviewCount' => 30],
        ];
    @endphp

    {{-- Categories --}}
    <section class="py-12 bg-gray-50">
        <div class="container max-w-screen-xl mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold">Popular Categories</h2>
                <a href="{{ url('/products') }}" class="text-amber-600 hover:text-amber-700">View All →</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 justify-items-center">
                @foreach($categories as $cat)
                    <a href="{{ url('/products?category='.$cat['id']) }}" class="group w-full max-w-[180px]">
                        <div class="bg-white rounded-lg shadow hover:shadow-md overflow-hidden text-center">
                            <img src="{{ $cat['image'] }}" class="h-36 w-full object-cover group-hover:scale-105 transition">
                            <div class="p-4">
                                <h3 class="font-medium">{{ $cat['name'] }}</h3>
                                <p class="text-sm text-gray-500">{{ $cat['count'] }} products</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured Products --}}
    {{--<section class="py-12 bg-white">
        <div class="container max-w-screen-xl mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold">Featured Products</h2>
                <a href="{{ url('/products') }}" class="text-amber-600 hover:text-amber-700">View All →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 justify-items-center">
                @foreach($products as $prod)
                    <div class="w-full max-w-[240px]">
                        @include('components.product-card', ['product' => $prod])
                    </div>
                @endforeach
            </div>
        </div>
    </section>--}}

@endsection

