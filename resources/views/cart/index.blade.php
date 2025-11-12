
@extends('layouts.app')

@section('title', 'Shopping Cart - K2 Computer')

@section('content')
    <div class="bg-stone-800 text-white py-8">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold">Shopping Cart</h1>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        @if(empty($cartItems))
            <div class="text-center py-12">
                <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-600 mb-4">Your cart is empty</h2>
                <a href="{{ route('products.index') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-lg inline-block transition-colors">
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-6">Cart Items ({{ count($cartItems) }})</h2>

                        @foreach($cartItems as $item)
                            <div class="flex items-center gap-4 py-4 border-b last:border-b-0">
                                <div class="w-20 h-20 bg-gray-100 rounded overflow-hidden flex-shrink-0">
                                    @if(isset($item['image']) && $item['image'])
                                        <img src="{{ $item['image'] }}"
                                             alt="{{ $item['name'] }}"
                                             class="w-full h-full object-cover"
                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-gray-200\'><i class=\'fas fa-box text-gray-400\'></i></div>';">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                            <i class="fas fa-box text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <h3 class="font-semibold">{{ $item['name'] }}</h3>
                                    <p class="text-gray-600">${{ number_format($item['price'], 2) }} each</p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button onclick="updateCartQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})"
                                            class="w-8 h-8 flex items-center justify-center border rounded hover:bg-gray-100 {{ $item['quantity'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <span class="w-12 text-center font-medium">{{ $item['quantity'] }}</span>
                                    <button onclick="updateCartQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})"
                                            class="w-8 h-8 flex items-center justify-center border rounded hover:bg-gray-100">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>

                                <div class="text-right">
                                    <p class="font-bold">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                    <button onclick="removeFromCart({{ $item['id'] }})" class="text-red-500 hover:text-red-700 text-sm">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-bold mb-4">Order Summary</h3>

                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between">
                                <span>Subtotal:</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping:</span>
                                <span>{{ $shipping === 0 ? 'Free' : '$' . number_format($shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tax:</span>
                                <span>${{ number_format($tax, 2) }}</span>
                            </div>
                            <hr>
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total:</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        @if($shipping > 0)
                            <p class="text-sm text-gray-600 mb-4">
                                Add ${{ number_format(100 - $subtotal, 2) }} more for free shipping!
                            </p>
                        @endif

                        <a href="{{ route('checkout.index') }}"
                           class="w-full bg-amber-500 hover:bg-amber-600 text-white py-3 px-4 rounded-lg font-semibold block text-center mb-3 transition-colors">
                            Proceed to Checkout
                        </a>

                        <a href="{{ route('products.index') }}"
                           class="w-full border border-gray-300 hover:bg-gray-50 py-3 px-4 rounded-lg block text-center transition-colors">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
