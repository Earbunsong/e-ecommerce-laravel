@extends('layouts.app')
@section('title', $product['name'])

@section('content')
    <div class="container max-w-screen-xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Product Image --}}
            <div>
                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-96 object-contain rounded shadow">
            </div>

            {{-- Product Info --}}
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $product['name'] }}</h1>
                <p class="text-gray-500 mb-4">{{ $product['category'] }}</p>

                {{-- Price --}}
                <div class="mb-6">
                    @if($product['discount'] > 0)
                        <span class="text-3xl font-bold text-amber-600">${{ number_format($product['price'] * (1 - $product['discount']/100), 2) }}</span>
                        <span class="ml-2 text-lg text-gray-400 line-through">${{ number_format($product['price'], 2) }}</span>
                    @else
                        <span class="text-3xl font-bold text-amber-600">${{ number_format($product['price'], 2) }}</span>
                    @endif
                </div>

                {{-- Description --}}
                <p class="text-gray-700 mb-6">{{ $product['description'] }}</p>

                {{-- Add to Cart Form --}}
                <form action="{{ url('/cart/add') }}" method="POST" class="flex items-center gap-4">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product['id'] }}">
                    <input type="hidden" name="name" value="{{ $product['name'] }}">
                    <input type="hidden" name="price" value="{{ $product['price'] }}">
                    <input type="hidden" name="image" value="{{ $product['image'] }}">
                    <input type="number" name="quantity" value="1" min="1" class="w-20 border rounded px-2 py-1">
                    <button type="submit" class="bg-amber-600 text-white px-6 py-3 rounded hover:bg-amber-700">
                        Add to Cart
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
