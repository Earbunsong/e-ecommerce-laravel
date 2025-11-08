@extends('layouts.app')

@section('title', 'Checkout - K2 Computer')

@section('content')
<!-- Modern Checkout Page -->
<div class="checkout-page">
    <!-- Progress Steps -->
    <div class="container py-4">
        <div class="checkout-progress">
            <div class="progress-step completed">
                <div class="step-icon">
                    <i class="bi bi-cart-check-fill"></i>
                </div>
                <div class="step-label">Cart</div>
            </div>
            <div class="progress-line completed"></div>
            <div class="progress-step active">
                <div class="step-icon">
                    <i class="bi bi-file-text-fill"></i>
                </div>
                <div class="step-label">Checkout</div>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <div class="step-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="step-label">Complete</div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row g-4">
            <!-- Left Column - Checkout Form -->
            <div class="col-lg-8">
                <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                    @csrf

                    <!-- Customer Information Card -->
                    <div class="checkout-card">
                        <div class="card-header-modern">
                            <div class="header-icon">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <h5 class="mb-0">Contact Information</h5>
                        </div>
                        <div class="card-body-modern">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="first_name" class="form-label-modern">
                                            First Name <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-person"></i>
                                            <input type="text"
                                                   class="form-control-modern @error('first_name') is-invalid @enderror"
                                                   id="first_name"
                                                   name="first_name"
                                                   value="{{ old('first_name', auth()->user()->name ?? '') }}"
                                                   placeholder="Enter first name"
                                                   required>
                                        </div>
                                        @error('first_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="last_name" class="form-label-modern">
                                            Last Name <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-person"></i>
                                            <input type="text"
                                                   class="form-control-modern @error('last_name') is-invalid @enderror"
                                                   id="last_name"
                                                   name="last_name"
                                                   value="{{ old('last_name') }}"
                                                   placeholder="Enter last name"
                                                   required>
                                        </div>
                                        @error('last_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="email" class="form-label-modern">
                                            Email Address <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-envelope"></i>
                                            <input type="email"
                                                   class="form-control-modern @error('email') is-invalid @enderror"
                                                   id="email"
                                                   name="email"
                                                   value="{{ old('email', auth()->user()->email ?? '') }}"
                                                   placeholder="your@email.com"
                                                   required>
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="phone" class="form-label-modern">
                                            Phone Number <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-telephone"></i>
                                            <input type="tel"
                                                   class="form-control-modern @error('phone') is-invalid @enderror"
                                                   id="phone"
                                                   name="phone"
                                                   value="{{ old('phone') }}"
                                                   placeholder="+855 12 345 678"
                                                   required>
                                        </div>
                                        @error('phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address Card -->
                    <div class="checkout-card">
                        <div class="card-header-modern">
                            <div class="header-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <h5 class="mb-0">Shipping Address</h5>
                        </div>
                        <div class="card-body-modern">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-group-modern">
                                        <label for="address" class="form-label-modern">
                                            Street Address <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-house-door"></i>
                                            <input type="text"
                                                   class="form-control-modern @error('address') is-invalid @enderror"
                                                   id="address"
                                                   name="address"
                                                   value="{{ old('address') }}"
                                                   placeholder="House number and street name"
                                                   required>
                                        </div>
                                        @error('address')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label for="city" class="form-label-modern">
                                            City <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-building"></i>
                                            <input type="text"
                                                   class="form-control-modern @error('city') is-invalid @enderror"
                                                   id="city"
                                                   name="city"
                                                   value="{{ old('city') }}"
                                                   placeholder="City"
                                                   required>
                                        </div>
                                        @error('city')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label for="state" class="form-label-modern">
                                            State/Province <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-map"></i>
                                            <input type="text"
                                                   class="form-control-modern @error('state') is-invalid @enderror"
                                                   id="state"
                                                   name="state"
                                                   value="{{ old('state') }}"
                                                   placeholder="State"
                                                   required>
                                        </div>
                                        @error('state')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-modern">
                                        <label for="zip_code" class="form-label-modern">
                                            ZIP Code <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-mailbox"></i>
                                            <input type="text"
                                                   class="form-control-modern @error('zip_code') is-invalid @enderror"
                                                   id="zip_code"
                                                   name="zip_code"
                                                   value="{{ old('zip_code') }}"
                                                   placeholder="12000"
                                                   required>
                                        </div>
                                        @error('zip_code')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Card -->
                    <div class="checkout-card">
                        <div class="card-header-modern">
                            <div class="header-icon">
                                <i class="bi bi-credit-card-fill"></i>
                            </div>
                            <h5 class="mb-0">Payment Method</h5>
                        </div>
                        <div class="card-body-modern">
                            <div class="payment-methods-modern">
                                <!-- KHQR Payment -->
                                <label class="payment-option" for="khqr">
                                    <input type="radio"
                                           name="payment_method"
                                           id="khqr"
                                           value="khqr"
                                           {{ old('payment_method') == 'khqr' ? 'checked' : '' }}
                                           required>
                                    <div class="payment-card">
                                        <div class="payment-icon khqr">
                                            <i class="bi bi-qr-code"></i>
                                        </div>
                                        <div class="payment-details">
                                            <h6>KHQR Payment</h6>
                                            <p>Bakong QR Code - Fast & Secure</p>
                                        </div>
                                        <div class="payment-badge">
                                            <span class="badge-modern recommended">Recommended</span>
                                        </div>
                                        <div class="check-icon">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </div>
                                    </div>
                                </label>

                                <!-- Cash on Delivery -->
                                <label class="payment-option" for="cod">
                                    <input type="radio"
                                           name="payment_method"
                                           id="cod"
                                           value="cod"
                                           {{ old('payment_method') == 'cod' ? 'checked' : '' }}
                                           required>
                                    <div class="payment-card">
                                        <div class="payment-icon cod">
                                            <i class="bi bi-cash-coin"></i>
                                        </div>
                                        <div class="payment-details">
                                            <h6>Cash on Delivery</h6>
                                            <p>Pay when you receive your order</p>
                                        </div>
                                        <div class="check-icon">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </div>
                                    </div>
                                </label>

                                <!-- Credit Card (Coming Soon) -->
                                <label class="payment-option disabled" for="card">
                                    <input type="radio"
                                           name="payment_method"
                                           id="card"
                                           value="card"
                                           disabled>
                                    <div class="payment-card">
                                        <div class="payment-icon card">
                                            <i class="bi bi-credit-card-2-front"></i>
                                        </div>
                                        <div class="payment-details">
                                            <h6>Credit/Debit Card</h6>
                                            <p>Pay with Visa, Mastercard, or Amex</p>
                                        </div>
                                        <div class="payment-badge">
                                            <span class="badge-modern coming-soon">Coming Soon</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('payment_method')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="checkout-actions">
                        <button type="submit" class="btn-checkout-primary">
                            <i class="bi bi-lock-fill me-2"></i>
                            <span>Place Order Securely</span>
                            <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn-checkout-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Back to Cart
                        </a>
                    </div>
                </form>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary-card sticky-summary">
                    <div class="summary-header">
                        <h5 class="mb-0">Order Summary</h5>
                        <span class="items-count">{{ count($cartItems) }} Items</span>
                    </div>

                    <!-- Cart Items -->
                    <div class="summary-items">
                        @foreach($cartItems as $item)
                        <div class="summary-item">
                            <div class="item-image">
                                <img src="{{ asset('images/' . $item['image']) }}"
                                     alt="{{ $item['name'] }}">
                                <span class="item-qty">{{ $item['quantity'] }}</span>
                            </div>
                            <div class="item-info">
                                <h6>{{ $item['name'] }}</h6>
                                <p class="item-sku">SKU: {{ $item['sku'] ?? 'N/A' }}</p>
                            </div>
                            <div class="item-price">
                                ${{ number_format($item['price'] * $item['quantity'], 2) }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Price Breakdown -->
                    <div class="price-breakdown">
                        <div class="price-row">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="price-row">
                            <span>Shipping</span>
                            <span class="{{ $shipping == 0 ? 'text-success' : '' }}">
                                {{ $shipping > 0 ? '$' . number_format($shipping, 2) : 'Free' }}
                            </span>
                        </div>
                        <div class="price-row">
                            <span>Tax (8%)</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>
                        @if($shipping == 0)
                        <div class="free-shipping-badge">
                            <i class="bi bi-truck me-2"></i>
                            Free shipping applied!
                        </div>
                        @else
                        <div class="shipping-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Free shipping on orders over $100
                        </div>
                        @endif
                    </div>

                    <!-- Total -->
                    <div class="summary-total">
                        <div class="total-row">
                            <span>Total</span>
                            <span class="total-amount">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="total-khr">
                            ≈ {{ number_format($total * 4100, 0) }} KHR
                        </div>
                    </div>

                    <!-- Security Badge -->
                    <div class="security-badge">
                        <i class="bi bi-shield-check me-2"></i>
                        Secure checkout · SSL encrypted
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Modern Checkout Styles */
    .checkout-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding-bottom: 50px;
    }

    /* Progress Steps */
    .checkout-progress {
        display: flex;
        align-items: center;
        justify-content: center;
        max-width: 600px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .step-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #cbd5e0;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .progress-step.active .step-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: scale(1.1);
    }

    .progress-step.completed .step-icon {
        background: #48bb78;
        color: white;
    }

    .step-label {
        margin-top: 10px;
        font-size: 14px;
        font-weight: 600;
        color: white;
    }

    .progress-line {
        flex: 1;
        height: 3px;
        background: rgba(255,255,255,0.3);
        margin: 0 15px;
        position: relative;
        top: -15px;
    }

    .progress-line.completed {
        background: #48bb78;
    }

    /* Checkout Cards */
    .checkout-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        margin-bottom: 24px;
        overflow: hidden;
        animation: slideUp 0.5s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card-header-modern {
        padding: 24px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header-icon {
        width: 45px;
        height: 45px;
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .card-body-modern {
        padding: 30px;
    }

    /* Modern Form Elements */
    .form-group-modern {
        margin-bottom: 20px;
    }

    .form-label-modern {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        font-size: 14px;
        display: block;
    }

    .required {
        color: #e53e3e;
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
        font-size: 18px;
        pointer-events: none;
    }

    .form-control-modern {
        width: 100%;
        padding: 14px 16px 14px 48px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #f7fafc;
    }

    .form-control-modern:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Payment Methods */
    .payment-methods-modern {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .payment-option {
        cursor: pointer;
        display: block;
    }

    .payment-option.disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }

    .payment-option input[type="radio"] {
        display: none;
    }

    .payment-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: #f7fafc;
        transition: all 0.3s ease;
        position: relative;
    }

    .payment-option:not(.disabled):hover .payment-card {
        border-color: #667eea;
        background: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
    }

    .payment-option input:checked + .payment-card {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .payment-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .payment-icon.khqr {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .payment-icon.cod {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
    }

    .payment-icon.card {
        background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
        color: white;
    }

    .payment-details h6 {
        margin: 0 0 4px 0;
        font-weight: 600;
        color: #2d3748;
        font-size: 15px;
    }

    .payment-details p {
        margin: 0;
        color: #718096;
        font-size: 13px;
    }

    .check-icon {
        margin-left: auto;
        font-size: 24px;
        color: #667eea;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .payment-option input:checked ~ .payment-card .check-icon {
        opacity: 1;
    }

    .badge-modern {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-modern.recommended {
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        color: #744210;
    }

    .badge-modern.coming-soon {
        background: #edf2f7;
        color: #718096;
    }

    /* Checkout Actions */
    .checkout-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-checkout-primary {
        width: 100%;
        padding: 18px 32px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    .btn-checkout-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
    }

    .btn-checkout-secondary {
        width: 100%;
        padding: 14px 32px;
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-checkout-secondary:hover {
        background: #f7fafc;
        transform: translateX(-5px);
    }

    /* Order Summary Card */
    .order-summary-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .sticky-summary {
        position: sticky;
        top: 90px;
    }

    .summary-header {
        padding: 24px 30px;
        background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .items-count {
        background: rgba(255,255,255,0.2);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 13px;
    }

    .summary-items {
        max-height: 350px;
        overflow-y: auto;
        padding: 20px 30px;
    }

    .summary-item {
        display: flex;
        gap: 15px;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .item-image {
        position: relative;
        width: 70px;
        height: 70px;
        flex-shrink: 0;
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    .item-qty {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #667eea;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
    }

    .item-info {
        flex: 1;
    }

    .item-info h6 {
        margin: 0 0 4px 0;
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
    }

    .item-sku {
        margin: 0;
        font-size: 12px;
        color: #a0aec0;
    }

    .item-price {
        font-weight: 600;
        color: #2d3748;
        font-size: 15px;
    }

    .price-breakdown {
        padding: 20px 30px;
        background: #f7fafc;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        color: #4a5568;
        font-size: 14px;
    }

    .free-shipping-badge {
        margin-top: 12px;
        padding: 10px 16px;
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
    }

    .shipping-info {
        margin-top: 12px;
        padding: 10px 16px;
        background: #fff5f5;
        color: #c53030;
        border-radius: 8px;
        font-size: 13px;
        text-align: center;
    }

    .summary-total {
        padding: 20px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .total-row span:first-child {
        font-size: 16px;
        font-weight: 600;
    }

    .total-amount {
        font-size: 28px;
        font-weight: 700;
    }

    .total-khr {
        text-align: right;
        opacity: 0.9;
        font-size: 14px;
    }

    .security-badge {
        padding: 16px 30px;
        background: #f7fafc;
        text-align: center;
        color: #48bb78;
        font-size: 13px;
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .sticky-summary {
            position: relative;
            top: 0;
        }

        .checkout-progress {
            padding: 20px 10px;
        }

        .step-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .step-label {
            font-size: 12px;
        }

        .progress-line {
            margin: 0 8px;
        }
    }

    @media (max-width: 576px) {
        .card-body-modern {
            padding: 20px;
        }

        .payment-card {
            flex-direction: column;
            text-align: center;
        }

        .check-icon {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    }

    /* Custom Scrollbar */
    .summary-items::-webkit-scrollbar {
        width: 6px;
    }

    .summary-items::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .summary-items::-webkit-scrollbar-thumb {
        background: #667eea;
        border-radius: 10px;
    }
</style>
@endpush

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
        const submitBtn = this.querySelector('.btn-checkout-primary');
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i><span>Processing...</span>';
        submitBtn.disabled = true;
    });

    // Auto-fill logged in user data
    @if(auth()->check())
    document.addEventListener('DOMContentLoaded', function() {
        const firstName = document.getElementById('first_name');
        const email = document.getElementById('email');

        if (!firstName.value) {
            const fullName = "{{ auth()->user()->name ?? '' }}";
            const nameParts = fullName.split(' ');
            firstName.value = nameParts[0] || '';
            if (nameParts.length > 1) {
                document.getElementById('last_name').value = nameParts.slice(1).join(' ');
            }
        }
    });
    @endif
</script>
@endpush
@endsection