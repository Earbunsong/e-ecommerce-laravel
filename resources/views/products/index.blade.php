@extends('layouts.app')
@include('data.products')

@section('content')
    <div class="bg-stone-800 text-white py-8">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold">Products</h1>
        </div>
    </div>
    <div class="container mx-auto px-4 py-8 flex gap-6">
        {{-- Sidebar --}}
        <aside class="w-64 hidden lg:block">
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-semibold mb-4">Categories</h2>
                <ul class="space-y-2">
                    @foreach($categories as $cat)
                        <li><a href="{{ url('/products?category='.$cat['id']) }}" class="text-gray-700 hover:text-amber-600">{{ $cat['name'] }} ({{ $cat['count'] }})</a></li>
                    @endforeach
                </ul>
            </div>
        </aside>
        {{-- Products --}}
        <div class="flex-1">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $prod)
                    @include('components.product-card', ['product' => $prod])
                @endforeach
            </div>
        </div>
    </div>
@endsection
