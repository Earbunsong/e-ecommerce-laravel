@extends('layouts.app')

@section('title', 'Checkout - K2 Computer')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center max-w-2xl mx-auto">
                <div class="flex items-center">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center text-white">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 mt-2">Cart</span>
                    </div>
                    <div class="w-24 h-1 bg-green-500 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span class="text-sm font-medium text-blue-600 mt-2">Checkout</span>
                    </div>
                    <div class="w-24 h-1 bg-gray-300 mx-4"></div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-500 mt-2">Complete</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column - Checkout Form -->
            <div class="lg:col-span-2 space-y-6">
                <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                    @csrf

                    <!-- Contact Information -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <h2 class="text-xl font-semibold text-white flex items-center">
                                <i class="fas fa-user-circle mr-3 text-2xl"></i>
                                Contact Information
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        First Name <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-400">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text"
                                               id="first_name"
                                               name="first_name"
                                               value="{{ old('first_name', auth()->user()->name ?? '') }}"
                                               placeholder="Enter first name"
                                               required
                                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('first_name') border-red-500 @enderror">
                                    </div>
                                    @error('first_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-400">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text"
                                               id="last_name"
                                               name="last_name"
                                               value="{{ old('last_name') }}"
                                               placeholder="Enter last name"
                                               required
                                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('last_name') border-red-500 @enderror">
                                    </div>
                                    @error('last_name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-400">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <input type="email"
                                               id="email"
                                               name="email"
                                               value="{{ old('email', auth()->user()->email ?? '') }}"
                                               placeholder="your@email.com"
                                               required
                                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('email') border-red-500 @enderror">
                                    </div>
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-400">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input type="tel"
                                               id="phone"
                                               name="phone"
                                               value="{{ old('phone') }}"
                                               placeholder="+855 12 345 678"
                                               required
                                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('phone') border-red-500 @enderror">
                                    </div>
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <h2 class="text-xl font-semibold text-white flex items-center">
                                <i class="fas fa-map-marker-alt mr-3 text-2xl"></i>
                                Shipping Address
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                        Street Address <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-3 text-gray-400">
                                            <i class="fas fa-home"></i>
                                        </span>
                                        <input type="text"
                                               id="address"
                                               name="address"
                                               value="{{ old('address') }}"
                                               placeholder="House number and street name"
                                               required
                                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('address') border-red-500 @enderror">
                                    </div>
                                    @error('address')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                            City <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-3 text-gray-400">
                                                <i class="fas fa-building"></i>
                                            </span>
                                            <input type="text"
                                                   id="city"
                                                   name="city"
                                                   value="{{ old('city') }}"
                                                   placeholder="City"
                                                   required
                                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('city') border-red-500 @enderror">
                                        </div>
                                        @error('city')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                                            State/Province <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-3 text-gray-400">
                                                <i class="fas fa-map"></i>
                                            </span>
                                            <input type="text"
                                                   id="state"
                                                   name="state"
                                                   value="{{ old('state') }}"
                                                   placeholder="State"
                                                   required
                                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('state') border-red-500 @enderror">
                                        </div>
                                        @error('state')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">
                                            ZIP Code <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-3 text-gray-400">
                                                <i class="fas fa-mail-bulk"></i>
                                            </span>
                                            <input type="text"
                                                   id="zip_code"
                                                   name="zip_code"
                                                   value="{{ old('zip_code') }}"
                                                   placeholder="12000"
                                                   required
                                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('zip_code') border-red-500 @enderror">
                                        </div>
                                        @error('zip_code')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <h2 class="text-xl font-semibold text-white flex items-center">
                                <i class="fas fa-credit-card mr-3 text-2xl"></i>
                                Payment Method
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- KHQR Payment -->
                            <label for="khqr" class="block cursor-pointer">
                                <input type="radio" name="payment_method" id="khqr" value="khqr" {{ old('payment_method') == 'khqr' ? 'checked' : '' }} required class="sr-only peer">
                                <div class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:border-blue-400 transition">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-white text-2xl">
                                            <i class="fas fa-qrcode"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-semibold text-gray-900">KHQR Payment</h6>
                                            <p class="text-sm text-gray-500">Bakong QR Code - Fast & Secure</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">RECOMMENDED</span>
                                        <i class="fas fa-check-circle text-blue-600 text-2xl hidden peer-checked:block"></i>
                                    </div>
                                </div>
                            </label>

                            <!-- Cash on Delivery -->
                            <label for="cod" class="block cursor-pointer">
                                <input type="radio" name="payment_method" id="cod" value="cod" {{ old('payment_method') == 'cod' ? 'checked' : '' }} required class="sr-only peer">
                                <div class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:border-blue-400 transition">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-green-500 flex items-center justify-center text-white text-2xl">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div>
                                            <h6 class="font-semibold text-gray-900">Cash on Delivery</h6>
                                            <p class="text-sm text-gray-500">Pay when you receive your order</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-check-circle text-blue-600 text-2xl hidden peer-checked:block"></i>
                                </div>
                            </label>

                            <!-- Credit Card (Coming Soon) -->
                            <div class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg opacity-60 cursor-not-allowed">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-400 flex items-center justify-center text-white text-2xl">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-semibold text-gray-900">Credit/Debit Card</h6>
                                        <p class="text-sm text-gray-500">Pay with Visa, Mastercard, or Amex</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-gray-200 text-gray-600 text-xs font-semibold rounded-full">COMING SOON</span>
                            </div>

                            @error('payment_method')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-6 rounded-lg transition transform hover:scale-105 flex items-center justify-center shadow-lg">
                            <i class="fas fa-lock mr-2"></i>
                            <span>Place Order Securely</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        <a href="{{ route('cart.index') }}" class="w-full bg-white hover:bg-gray-50 text-blue-600 font-semibold py-3 px-6 rounded-lg border-2 border-blue-600 transition flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Cart
                        </a>
                    </div>
                </form>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden sticky top-24">
                    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-white">Order Summary</h2>
                            <span class="bg-white bg-opacity-20 text-white text-sm font-semibold px-3 py-1 rounded-full">{{ count($cartItems) }} Items</span>
                        </div>
                    </div>

                    <!-- Cart Items -->
                    <div class="p-6 max-h-96 overflow-y-auto">
                        @foreach($cartItems as $item)
                        <div class="flex items-center gap-3 pb-4 mb-4 border-b last:border-b-0">
                            <div class="relative w-16 h-16 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                                @if(isset($item['image']) && $item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}"
                                         alt="{{ $item['name'] }}"
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-gray-200\'><i class=\'fas fa-box text-gray-400\'></i></div>';">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                @endif
                                <span class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center shadow-lg">{{ $item['quantity'] }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h6 class="font-semibold text-sm text-gray-900 truncate">{{ $item['name'] }}</h6>
                                <p class="text-xs text-gray-500">SKU: {{ $item['sku'] ?? 'N/A' }}</p>
                            </div>
                            <div class="font-semibold text-gray-900">${{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Price Breakdown -->
                    <div class="px-6 py-4 bg-gray-50 space-y-2">
                        <div class="flex justify-between text-gray-700">
                            <span>Subtotal</span>
                            <span class="font-semibold">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Shipping</span>
                            <span class="font-semibold {{ $shipping == 0 ? 'text-green-600' : '' }}">
                                {{ $shipping > 0 ? '$' . number_format($shipping, 2) : 'Free' }}
                            </span>
                        </div>
                        <div class="flex justify-between text-gray-700">
                            <span>Tax (8%)</span>
                            <span class="font-semibold">${{ number_format($tax, 2) }}</span>
                        </div>

                        @if($shipping == 0)
                        <div class="mt-3 p-3 bg-green-100 text-green-800 rounded-lg text-sm font-medium flex items-center">
                            <i class="fas fa-truck mr-2"></i>
                            Free shipping applied!
                        </div>
                        @else
                        <div class="mt-3 p-3 bg-red-50 text-red-700 rounded-lg text-sm flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Free shipping on orders over $100
                        </div>
                        @endif
                    </div>

                    <!-- Total -->
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-lg font-semibold">Total</span>
                            <span class="text-3xl font-bold">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="text-right text-sm opacity-90">
                            ≈ {{ number_format($total * 4100, 0) }} KHR
                        </div>
                    </div>

                    <!-- Security Badge -->
                    <div class="px-6 py-4 bg-gray-50 text-center">
                        <p class="text-sm text-green-600 font-semibold flex items-center justify-center">
                            <i class="fas fa-shield-alt mr-2"></i>
                            Secure checkout · SSL encrypted
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Form validation
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!paymentMethod) {
            e.preventDefault();
            alert('Please select a payment method');
            return false;
        }

        // Add loading state to button
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i><span>Processing...</span>';
        submitBtn.disabled = true;
    });
</script>
@endpush
@endsection
