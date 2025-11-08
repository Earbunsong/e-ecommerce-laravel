@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            <i class="fas fa-shopping-cart mr-3 text-blue-600"></i>Checkout
        </h1>
        <nav class="flex text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('cart.index') }}" class="hover:text-blue-600">Cart</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">Checkout</span>
        </nav>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Checkout Form (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Contact Information -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-user-circle mr-3"></i>Contact Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="firstName" class="block text-sm font-medium text-gray-700 mb-2">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="firstName" name="first_name" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <p class="text-red-500 text-sm mt-1 hidden" id="firstName-error">Please enter your first name</p>
                            </div>
                            <div>
                                <label for="lastName" class="block text-sm font-medium text-gray-700 mb-2">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="lastName" name="last_name" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <p class="text-red-500 text-sm mt-1 hidden" id="lastName-error">Please enter your last name</p>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-400">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" id="email" name="email" required
                                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                </div>
                                <p class="text-red-500 text-sm mt-1 hidden" id="email-error">Please enter a valid email</p>
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-3 text-gray-400">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="tel" id="phone" name="phone" required
                                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                </div>
                                <p class="text-red-500 text-sm mt-1 hidden" id="phone-error">Please enter your phone number</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-map-marker-alt mr-3"></i>Shipping Address
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Street Address <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="address" name="address" placeholder="123 Main St" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label for="address2" class="block text-sm font-medium text-gray-700 mb-2">
                                    Apartment, suite, etc. <span class="text-gray-400">(Optional)</span>
                                </label>
                                <input type="text" id="address2" name="address2" placeholder="Apt 4B"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                        City <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="city" name="city" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                </div>
                                <div>
                                    <label for="zip" class="block text-sm font-medium text-gray-700 mb-2">
                                        ZIP Code <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="zip" name="zip" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                                        State/Province <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="state" name="state" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                </div>
                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                        Country <span class="text-red-500">*</span>
                                    </label>
                                    <select id="country" name="country" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                        <option value="">Choose...</option>
                                        <option value="US">United States</option>
                                        <option value="CA">Canada</option>
                                        <option value="UK">United Kingdom</option>
                                        <option value="AU">Australia</option>
                                        <option value="KH" selected>Cambodia</option>
                                        <option value="OTHER">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Method -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-truck mr-3"></i>Shipping Method
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <label class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                            <div class="flex items-center">
                                <input type="radio" name="shipping_method" value="standard" checked
                                       class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-900">Standard Shipping</p>
                                    <p class="text-sm text-gray-500">5-7 business days</p>
                                </div>
                            </div>
                            <span class="font-bold text-gray-900">$5.00</span>
                        </label>
                        <label class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                            <div class="flex items-center">
                                <input type="radio" name="shipping_method" value="express"
                                       class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-900">Express Shipping</p>
                                    <p class="text-sm text-gray-500">2-3 business days</p>
                                </div>
                            </div>
                            <span class="font-bold text-gray-900">$15.00</span>
                        </label>
                        <label class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                            <div class="flex items-center">
                                <input type="radio" name="shipping_method" value="overnight"
                                       class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-900">Overnight Shipping</p>
                                    <p class="text-sm text-gray-500">Next business day</p>
                                </div>
                            </div>
                            <span class="font-bold text-gray-900">$25.00</span>
                        </label>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-credit-card mr-3"></i>Payment Method
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="payment_method" value="credit_card" checked
                                   class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <i class="fas fa-credit-card ml-4 text-lg text-gray-600"></i>
                            <span class="ml-3 font-semibold text-gray-900">Credit Card</span>
                        </label>

                        <div id="creditCardFields" class="ml-8 space-y-3 bg-gray-50 p-4 rounded-lg">
                            <div>
                                <label for="cardNumber" class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                                <input type="text" id="cardNumber" name="card_number" placeholder="1234 5678 9012 3456"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="cardExpiry" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                                    <input type="text" id="cardExpiry" name="card_expiry" placeholder="MM/YY"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                </div>
                                <div>
                                    <label for="cardCvv" class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                    <input type="text" id="cardCvv" name="card_cvv" placeholder="123"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                </div>
                            </div>
                        </div>

                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="payment_method" value="paypal"
                                   class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <i class="fab fa-paypal ml-4 text-lg text-blue-600"></i>
                            <span class="ml-3 font-semibold text-gray-900">PayPal</span>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="payment_method" value="cash_on_delivery"
                                   class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <i class="fas fa-money-bill-wave ml-4 text-lg text-green-600"></i>
                            <span class="ml-3 font-semibold text-gray-900">Cash on Delivery</span>
                        </label>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-comment-alt mr-3"></i>Additional Notes
                        </h2>
                    </div>
                    <div class="p-6">
                        <label for="orderNotes" class="block text-sm font-medium text-gray-700 mb-2">
                            Order Notes <span class="text-gray-400">(Optional)</span>
                        </label>
                        <textarea id="orderNotes" name="order_notes" rows="3"
                                  placeholder="Any special instructions for your order..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"></textarea>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary (1/3 width) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden sticky top-24">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-shopping-bag mr-3"></i>Order Summary
                        </h2>
                    </div>
                    <div class="p-6">
                        <!-- Cart Items -->
                        <div class="mb-4 max-h-80 overflow-y-auto">
                            <h3 class="font-semibold text-gray-900 mb-3">Items in Cart</h3>

                            <!-- Sample Cart Items -->
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 pb-3 border-b">
                                    <img src="https://via.placeholder.com/60" alt="Product" class="w-16 h-16 rounded object-cover">
                                    <div class="flex-1">
                                        <p class="font-medium text-sm">Product Name 1</p>
                                        <p class="text-xs text-gray-500">Qty: 2</p>
                                    </div>
                                    <span class="font-semibold text-gray-900">$50.00</span>
                                </div>
                                <div class="flex items-center gap-3 pb-3 border-b">
                                    <img src="https://via.placeholder.com/60" alt="Product" class="w-16 h-16 rounded object-cover">
                                    <div class="flex-1">
                                        <p class="font-medium text-sm">Product Name 2</p>
                                        <p class="text-xs text-gray-500">Qty: 1</p>
                                    </div>
                                    <span class="font-semibold text-gray-900">$30.00</span>
                                </div>
                                <div class="flex items-center gap-3 pb-3 border-b">
                                    <img src="https://via.placeholder.com/60" alt="Product" class="w-16 h-16 rounded object-cover">
                                    <div class="flex-1">
                                        <p class="font-medium text-sm">Product Name 3</p>
                                        <p class="text-xs text-gray-500">Qty: 1</p>
                                    </div>
                                    <span class="font-semibold text-gray-900">$20.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Coupon Code -->
                        <div class="mb-4 pb-4 border-b">
                            <label for="couponCode" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag mr-2"></i>Coupon Code
                            </label>
                            <div class="flex gap-2">
                                <input type="text" id="couponCode" name="coupon_code" placeholder="Enter code"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium">
                                    Apply
                                </button>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal:</span>
                                <span class="font-semibold">$100.00</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping:</span>
                                <span class="font-semibold" id="shippingCost">$5.00</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Tax (10%):</span>
                                <span class="font-semibold">$10.00</span>
                            </div>
                            <div class="flex justify-between text-green-600">
                                <span>Discount:</span>
                                <span class="font-semibold">-$0.00</span>
                            </div>
                        </div>

                        <div class="border-t-2 border-gray-200 pt-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-gray-900">Total:</span>
                                <span class="text-2xl font-bold text-green-600" id="totalAmount">$115.00</span>
                            </div>
                        </div>

                        <!-- Place Order Button -->
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-3 px-6 rounded-lg transition transform hover:scale-105 flex items-center justify-center mb-3">
                            <i class="fas fa-lock mr-2"></i>
                            Place Order
                        </button>
                        <p class="text-center text-sm text-gray-500">
                            <i class="fas fa-shield-alt mr-1"></i>Secure checkout
                        </p>

                        <!-- Payment Icons -->
                        <div class="mt-4 pt-4 border-t text-center">
                            <p class="text-xs text-gray-500 mb-2">We accept:</p>
                            <div class="flex justify-center gap-3 text-2xl text-gray-600">
                                <i class="fab fa-cc-visa"></i>
                                <i class="fab fa-cc-mastercard"></i>
                                <i class="fab fa-cc-paypal"></i>
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Form validation
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        let isValid = true;

        // Validate required fields
        const requiredFields = ['firstName', 'lastName', 'email', 'phone', 'address', 'city', 'state', 'zip', 'country'];

        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            const errorElement = document.getElementById(fieldId + '-error');

            if (field && !field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
                if (errorElement) errorElement.classList.remove('hidden');
            } else {
                field.classList.remove('border-red-500');
                if (errorElement) errorElement.classList.add('hidden');
            }
        });

        // Email validation
        const emailField = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailField && !emailRegex.test(emailField.value)) {
            isValid = false;
            emailField.classList.add('border-red-500');
            const errorElement = document.getElementById('email-error');
            if (errorElement) {
                errorElement.textContent = 'Please enter a valid email address';
                errorElement.classList.remove('hidden');
            }
        }

        if (!isValid) {
            e.preventDefault();
            showNotification('Please fill in all required fields', 'error');
        }
    });

    // Update shipping cost when shipping method changes
    document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const shippingCosts = {
                'standard': 5.00,
                'express': 15.00,
                'overnight': 25.00
            };
            const subtotal = 100.00;
            const tax = 10.00;
            const discount = 0.00;
            const shipping = shippingCosts[this.value];
            const total = subtotal + shipping + tax - discount;

            document.getElementById('shippingCost').textContent = '$' + shipping.toFixed(2);
            document.getElementById('totalAmount').textContent = '$' + total.toFixed(2);
        });
    });

    // Toggle credit card fields based on payment method
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const creditCardFields = document.getElementById('creditCardFields');
            const cardInputs = creditCardFields.querySelectorAll('input');

            if (this.value === 'credit_card') {
                creditCardFields.classList.remove('hidden');
                cardInputs.forEach(input => input.required = true);
            } else {
                creditCardFields.classList.add('hidden');
                cardInputs.forEach(input => input.required = false);
            }
        });
    });

    // Clear error on input
    document.querySelectorAll('input[required], select[required]').forEach(field => {
        field.addEventListener('input', function() {
            this.classList.remove('border-red-500');
            const errorElement = document.getElementById(this.id + '-error');
            if (errorElement) errorElement.classList.add('hidden');
        });
    });
</script>
@endpush
@endsection
