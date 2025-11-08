@extends('layouts.app')

@section('title', 'Checkout - K2 Computer')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Checkout</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                        @csrf

                        <!-- Customer Information -->
                        <h5 class="mb-3">Customer Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Shipping Address -->
                        <h5 class="mb-3">Shipping Address</h5>
                        <div class="mb-3">
                            <label for="address" class="form-label">Street Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror"
                                   id="address" name="address" value="{{ old('address') }}"
                                   placeholder="House number and street name" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                       id="city" name="city" value="{{ old('city') }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State/Province <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror"
                                       id="state" name="state" value="{{ old('state') }}" required>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="zip_code" class="form-label">ZIP Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('zip_code') is-invalid @enderror"
                                       id="zip_code" name="zip_code" value="{{ old('zip_code') }}" required>
                                @error('zip_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Payment Method -->
                        <h5 class="mb-3">Payment Method</h5>
                        <div class="payment-methods">
                            <div class="form-check mb-3 p-3 border rounded @error('payment_method') border-danger @enderror">
                                <input class="form-check-input" type="radio" name="payment_method" id="khqr"
                                       value="khqr" {{ old('payment_method') == 'khqr' ? 'checked' : '' }} required>
                                <label class="form-check-label w-100" for="khqr">
                                    <strong>KHQR (Bakong QR Code)</strong>
                                    <p class="text-muted mb-0 small">Pay securely using Cambodia's national payment system</p>
                                </label>
                            </div>

                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod"
                                       value="cod" {{ old('payment_method') == 'cod' ? 'checked' : '' }} required>
                                <label class="form-check-label w-100" for="cod">
                                    <strong>Cash on Delivery (COD)</strong>
                                    <p class="text-muted mb-0 small">Pay with cash when you receive your order</p>
                                </label>
                            </div>

                            <div class="form-check mb-3 p-3 border rounded bg-light">
                                <input class="form-check-input" type="radio" name="payment_method" id="card"
                                       value="card" {{ old('payment_method') == 'card' ? 'checked' : '' }} disabled>
                                <label class="form-check-label w-100" for="card">
                                    <strong>Credit/Debit Card</strong>
                                    <p class="text-muted mb-0 small">Coming soon...</p>
                                </label>
                            </div>
                        </div>
                        @error('payment_method')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-lock-fill me-2"></i>Place Order
                            </button>
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Cart
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <!-- Cart Items -->
                    <div class="mb-3">
                        <h6 class="mb-2">Items ({{ count($cartItems) }})</h6>
                        <div class="order-items" style="max-height: 300px; overflow-y: auto;">
                            @foreach($cartItems as $item)
                            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                <img src="{{ asset('images/' . $item['image']) }}"
                                     alt="{{ $item['name'] }}"
                                     class="img-thumbnail me-3"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 small">{{ $item['name'] }}</h6>
                                    <small class="text-muted">Qty: {{ $item['quantity'] }}</small>
                                </div>
                                <div class="text-end">
                                    <strong>${{ number_format($item['price'] * $item['quantity'], 2) }}</strong>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Totals -->
                    <div class="order-totals">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong>${{ number_format($subtotal, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <strong>{{ $shipping > 0 ? '$' . number_format($shipping, 2) : 'Free' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (8%):</span>
                            <strong>${{ number_format($tax, 2) }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">Total:</h5>
                            <h5 class="mb-0 text-primary">${{ number_format($total, 2) }}</h5>
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-muted">≈ {{ number_format($total * 4100, 0) }} KHR</small>
                        </div>
                    </div>

                    @if($shipping == 0)
                    <div class="alert alert-success mt-3 mb-0 small">
                        <i class="bi bi-truck me-2"></i>Free shipping on orders over $100!
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .payment-methods .form-check {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .payment-methods .form-check:hover:not(.bg-light) {
        background-color: #f8f9fa !important;
        border-color: #0d6efd !important;
    }

    .payment-methods .form-check-input:checked + .form-check-label {
        color: #0d6efd;
    }

    .sticky-top {
        position: sticky;
    }

    @media (max-width: 991px) {
        .sticky-top {
            position: relative !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!paymentMethod) {
            e.preventDefault();
            alert('Please select a payment method');
            return false;
        }
    });
</script>
@endpush
@endsection
