@extends('layouts.app')

@section('title', 'Order Success - K2 Computer')

@section('content')
<div class="success-page-wrapper">
    <!-- Confetti Animation -->
    <div class="confetti-container" id="confetti"></div>

    <div class="max-w-6xl mx-auto px-4 py-12">
        <div class="w-full">
                <!-- Success Animation Card -->
                <div class="success-hero text-center mb-12">
                    <div class="success-icon-wrapper mb-8">
                        <div class="success-circle">
                            <div class="success-checkmark">
                                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                                    <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-extrabold mb-6 success-text animate-bounce-in">Payment Successful!</h1>
                    <p class="text-xl md:text-2xl text-white mb-8 font-medium">Thank you for your purchase! Your order has been confirmed.</p>
                    <div class="order-badge">
                        <span class="badge-label">Order Number</span>
                        <span class="badge-value">{{ $order->order_number }}</span>
                    </div>
                </div>

            <!-- Order Timeline -->
            <div class="timeline-wrapper mb-12">
                <div class="timeline-card modern-card">
                    <div class="timeline-header">
                        <i class="fas fa-clock-rotate-left"></i>
                        <h5 class="mb-0">Order Timeline</h5>
                    </div>
                    <div class="timeline">
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Order Placed</h6>
                                <p class="text-muted mb-0">{{ $order->created_at->format('F d, Y g:i A') }}</p>
                            </div>
                        </div>
                        @if($order->payment_status == 'paid')
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Payment Confirmed</h6>
                                <p class="text-muted mb-0">{{ $order->payment_method == 'khqr' ? 'KHQR Payment' : 'Cash on Delivery' }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="timeline-item {{ $order->status == 'processing' ? 'active' : '' }}">
                            <div class="timeline-marker">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Processing</h6>
                                <p class="text-muted mb-0">Preparing your order</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Shipped</h6>
                                <p class="text-muted mb-0">On the way to you</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="timeline-content">
                                <h6>Delivered</h6>
                                <p class="text-muted mb-0">Arrives in 3-5 days</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer & Shipping Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="info-card modern-card">
                        <div class="info-card-header">
                            <i class="fas fa-user"></i>
                            <h6 class="mb-0">Customer Information</h6>
                        </div>
                        <div class="info-card-body">
                            <div class="info-item">
                                <i class="fas fa-user-circle text-primary"></i>
                                <span>{{ $order->customer_info['first_name'] ?? '' }} {{ $order->customer_info['last_name'] ?? '' }}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-envelope text-primary"></i>
                                <span>{{ $order->customer_info['email'] ?? '' }}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-phone text-primary"></i>
                                <span>{{ $order->customer_info['phone'] ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="info-card modern-card">
                        <div class="info-card-header">
                            <i class="fas fa-location-dot"></i>
                            <h6 class="mb-0">Shipping Address</h6>
                        </div>
                        <div class="info-card-body">
                            <div class="info-item">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                                <span>{{ $order->shipping_address['address'] ?? '' }}</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-city text-primary"></i>
                                <span>{{ $order->shipping_address['city'] ?? '' }}@if(isset($order->shipping_address['state'])), {{ $order->shipping_address['state'] }}@endif</span>
                            </div>
                        </div>
                    </div>
            </div>

            <!-- Order Items -->
            <div class="modern-card mb-8">
                <div class="card-header-modern">
                    <i class="fas fa-shopping-bag"></i>
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body-modern">
                    <div class="order-items-list">
                        @foreach($order->items as $item)
                        <div class="order-item-card">
                            <div class="item-image">
                                @if($item->product && $item->product->image_url)
                                <img src="{{ $item->product->image_url }}"
                                     alt="{{ $item->product_name }}">
                                @else
                                <div class="image-placeholder">
                                    <i class="fas fa-box"></i>
                                </div>
                                @endif
                            </div>
                            <div class="item-details">
                                <h6 class="item-name">{{ $item->product_name }}</h6>
                                <div class="item-meta">
                                    <span class="item-quantity">
                                        <i class="fas fa-boxes"></i> Qty: {{ $item->quantity }}
                                    </span>
                                    <span class="item-price">
                                        <i class="fas fa-tag"></i> ${{ number_format($item->price, 2) }}
                                    </span>
                                </div>
                            </div>
                            <div class="item-total">
                                <span class="total-label">Total</span>
                                <span class="total-value">${{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="modern-card summary-card mb-8">
                <div class="card-header-modern">
                    <i class="fas fa-calculator"></i>
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body-modern">
                    <div class="summary-row">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-value">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Shipping</span>
                        <span class="summary-value">
                            @if($order->shipping > 0)
                                ${{ number_format($order->shipping, 2) }}
                            @else
                                <span class="text-success fw-bold">Free</span>
                            @endif
                        </span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Tax (8%)</span>
                        <span class="summary-value">${{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-row summary-total">
                        <span class="summary-label">Total</span>
                        <span class="summary-value">${{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="summary-currency">
                        <i class="fas fa-money-bill-wave"></i>
                        Approximately {{ number_format($order->total * 4100, 0) }} KHR
                    </div>

                    <!-- Payment Badge -->
                    <div class="payment-badge">
                        @if($order->payment_method == 'khqr')
                            <div class="payment-info">
                                <i class="fas fa-qrcode"></i>
                                <div>
                                    <div class="payment-method">KHQR Payment</div>
                                    <div class="payment-subtext">Bakong QR Code</div>
                                </div>
                            </div>
                            @if($order->payment_status == 'paid')
                                <span class="status-badge paid">
                                    <i class="fas fa-check-circle"></i> Paid
                                </span>
                            @endif
                        @elseif($order->payment_method == 'cod')
                            <div class="payment-info">
                                <i class="fas fa-money-bill-1-wave"></i>
                                <div>
                                    <div class="payment-method">Cash on Delivery</div>
                                    <div class="payment-subtext">Pay when you receive</div>
                                </div>
                            </div>
                            <span class="status-badge pending">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- What's Next -->
            <div class="modern-card whats-next-card mb-12">
                <div class="card-header-modern">
                    <i class="fas fa-lightbulb"></i>
                    <h5 class="mb-0">What's Next?</h5>
                </div>
                <div class="card-body-modern">
                    <div class="next-steps">
                        <div class="next-step">
                            <div class="step-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="step-content">
                                <h6>Email Confirmation</h6>
                                <p>Check <strong>{{ $order->customer_info['email'] ?? '' }}</strong> for your order details</p>
                            </div>
                        </div>
                        <div class="next-step">
                            <div class="step-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="step-content">
                                <h6>Shipping Updates</h6>
                                <p>We'll send you real-time tracking information</p>
                            </div>
                        </div>
                        <div class="next-step">
                            <div class="step-icon">
                                <i class="fas fa-truck-fast"></i>
                            </div>
                            <div class="step-content">
                                <h6>Fast Delivery</h6>
                                <p>Your order arrives within 3-5 business days</p>
                            </div>
                        </div>
                        @if($order->payment_method == 'cod')
                        <div class="next-step">
                            <div class="step-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="step-content">
                                <h6>Payment on Delivery</h6>
                                <p>Prepare exact cash when receiving your order</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('home') }}" class="btn-modern btn-secondary-new">
                    <i class="fas fa-home"></i>
                    <span>Back to Home</span>
                </a>
                <a href="{{ route('products.index') }}" class="btn-modern btn-primary-new">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Continue Shopping</span>
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Success Page Wrapper */
.success-page-wrapper {
    background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
    min-height: 100vh;
    padding: 40px 0;
    position: relative;
    overflow: hidden;
}

.success-page-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,165.3C384,171,480,149,576,133.3C672,117,768,107,864,122.7C960,139,1056,181,1152,181.3C1248,181,1344,139,1392,117.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover;
    pointer-events: none;
}

/* Confetti */
.confetti-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 9999;
}

/* Success Hero */
.success-hero {
    animation: fadeInUp 0.6s ease-out;
}

.success-circle {
    width: 140px;
    height: 140px;
    margin: 0 auto;
    background: linear-gradient(135deg, #10b981, #059669);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 20px 60px rgba(16, 185, 129, 0.4);
    animation: scaleIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    position: relative;
}

.success-circle::before {
    content: '';
    position: absolute;
    inset: -10px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981, #059669);
    opacity: 0.2;
    animation: pulse 2s ease-in-out infinite;
}

.checkmark {
    width: 80px;
    height: 80px;
    stroke-width: 3;
    stroke: white;
    stroke-miterlimit: 10;
    animation: fill 0.4s ease-in-out 0.4s forwards, scale 0.3s ease-in-out 0.9s both;
}

.checkmark-circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: white;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}

.checkmark-check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
}

@keyframes stroke {
    100% { stroke-dashoffset: 0; }
}

@keyframes scale {
    0%, 100% { transform: none; }
    50% { transform: scale3d(1.1, 1.1, 1); }
}

@keyframes fill {
    100% { box-shadow: inset 0 0 0 60px white; }
}

@keyframes scaleIn {
    0% {
        transform: scale(0) rotate(-45deg);
        opacity: 0;
    }
    50% {
        transform: scale(1.2) rotate(10deg);
    }
    100% {
        transform: scale(1) rotate(0deg);
        opacity: 1;
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 0.2;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.3;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.success-text {
    color: #ffffff;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

@keyframes bounce-in {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% { transform: scale(0.9); }
    100% { transform: scale(1); }
}

.animate-bounce-in {
    animation: bounce-in 0.8s ease-out;
}

.order-badge {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    background: white;
    padding: 16px 32px;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.badge-label {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #6b7280;
}

.badge-value {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    font-family: 'Courier New', monospace;
}

/* Modern Cards */
.modern-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    animation: fadeInUp 0.6s ease-out backwards;
}

.modern-card:nth-child(2) { animation-delay: 0.1s; }
.modern-card:nth-child(3) { animation-delay: 0.2s; }
.modern-card:nth-child(4) { animation-delay: 0.3s; }

.modern-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
}

.card-header-modern {
    padding: 24px;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    border-bottom: 2px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 12px;
}

.card-header-modern i {
    font-size: 24px;
    color: #667eea;
}

.card-header-modern h5 {
    margin: 0;
    font-weight: 700;
    color: #1f2937;
}

.card-body-modern {
    padding: 24px;
}

/* Timeline */
.timeline-card {
    background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
}

.timeline-header {
    padding: 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    gap: 12px;
}

.timeline-header i {
    font-size: 24px;
}

.timeline {
    padding: 32px 24px;
    position: relative;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 47px;
    top: 32px;
    bottom: 32px;
    width: 2px;
    background: linear-gradient(180deg, #10b981 0%, #e5e7eb 100%);
}

.timeline-item {
    display: flex;
    gap: 20px;
    margin-bottom: 32px;
    position: relative;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
    transition: all 0.3s ease;
}

.timeline-marker i {
    font-size: 16px;
    color: #9ca3af;
}

.timeline-item.completed .timeline-marker {
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
}

.timeline-item.completed .timeline-marker i {
    color: white;
}

.timeline-item.active .timeline-marker {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.3);
    animation: pulse-marker 2s infinite;
}

.timeline-item.active .timeline-marker i {
    color: white;
}

@keyframes pulse-marker {
    0%, 100% {
        box-shadow: 0 4px 20px rgba(59, 130, 246, 0.3);
    }
    50% {
        box-shadow: 0 4px 30px rgba(59, 130, 246, 0.6);
    }
}

.timeline-content h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
}

.timeline-content p {
    margin: 0;
    font-size: 14px;
}

/* Info Cards */
.info-card {
    height: 100%;
}

.info-card-header {
    padding: 20px;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border-bottom: 2px solid #bae6fd;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-card-header i {
    font-size: 20px;
    color: #0284c7;
}

.info-card-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f9fafb;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: #f3f4f6;
    transform: translateX(5px);
}

.info-item i {
    font-size: 18px;
    width: 24px;
    text-align: center;
}

.info-item span {
    flex: 1;
    color: #374151;
    font-size: 14px;
}

/* Order Items */
.order-items-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.order-item-card {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    transition: all 0.3s ease;
}

.order-item-card:hover {
    border-color: #667eea;
    transform: translateX(5px);
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.1);
}

.item-image {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
    background: #f3f4f6;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #e5e7eb, #d1d5db);
}

.image-placeholder i {
    font-size: 32px;
    color: #9ca3af;
}

.item-details {
    flex: 1;
}

.item-name {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 16px;
}

.item-meta {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.item-quantity, .item-price {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    color: #6b7280;
}

.item-quantity i, .item-price i {
    color: #667eea;
}

.item-total {
    text-align: right;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.total-label {
    font-size: 12px;
    color: #9ca3af;
    font-weight: 500;
}

.total-value {
    font-size: 20px;
    font-weight: 700;
    color: #10b981;
}

/* Summary Card */
.summary-card {
    background: linear-gradient(135deg, #ffffff 0%, #fef3c7 100%);
    border: 2px solid #fbbf24;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #fde68a;
}

.summary-row:last-of-type {
    border-bottom: none;
}

.summary-label {
    font-size: 15px;
    color: #78350f;
    font-weight: 500;
}

.summary-value {
    font-size: 15px;
    color: #92400e;
    font-weight: 600;
}

.summary-divider {
    height: 2px;
    background: linear-gradient(90deg, transparent, #fbbf24, transparent);
    margin: 16px 0;
}

.summary-total {
    padding: 16px 0;
    margin-top: 8px;
}

.summary-total .summary-label {
    font-size: 20px;
    font-weight: 700;
    color: #78350f;
}

.summary-total .summary-value {
    font-size: 28px;
    font-weight: 800;
    background: linear-gradient(135deg, #10b981, #059669);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.summary-currency {
    text-align: center;
    padding: 16px;
    background: #fffbeb;
    border-radius: 12px;
    margin-top: 16px;
    color: #92400e;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.summary-currency i {
    color: #f59e0b;
}

.payment-badge {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    background: white;
    border-radius: 16px;
    margin-top: 20px;
    border: 2px solid #e5e7eb;
}

.payment-info {
    display: flex;
    align-items: center;
    gap: 16px;
}

.payment-info i {
    font-size: 32px;
    color: #667eea;
}

.payment-method {
    font-weight: 700;
    color: #1f2937;
    font-size: 16px;
}

.payment-subtext {
    font-size: 13px;
    color: #6b7280;
    margin-top: 2px;
}

.status-badge {
    padding: 10px 20px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.status-badge.paid {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
}

.status-badge.pending {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    box-shadow: 0 4px 20px rgba(245, 158, 11, 0.3);
}

/* What's Next */
.whats-next-card {
    background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
}

.next-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.next-step {
    display: flex;
    gap: 16px;
    padding: 20px;
    background: white;
    border-radius: 16px;
    transition: all 0.3s ease;
}

.next-step:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
}

.step-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.step-icon i {
    font-size: 22px;
    color: white;
}

.step-content h6 {
    margin: 0 0 8px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 15px;
}

.step-content p {
    margin: 0;
    font-size: 13px;
    color: #6b7280;
    line-height: 1.5;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 40px;
}

.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 16px 32px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.btn-modern i {
    font-size: 18px;
}

.btn-modern.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-modern.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.4);
}

.btn-modern.btn-secondary {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-modern.btn-secondary:hover {
    background: #667eea;
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
}

/* New Modern Button Styles */
.btn-modern.btn-primary-new {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-modern.btn-primary-new:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 12px 40px rgba(245, 158, 11, 0.5);
}

.btn-modern.btn-secondary-new {
    background: white;
    color: #059669;
    border: 3px solid white;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-modern.btn-secondary-new:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border-color: white;
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 12px 40px rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

/* Responsive */
@media (max-width: 768px) {
    .order-item-card {
        flex-direction: column;
        text-align: center;
    }

    .item-total {
        width: 100%;
        text-align: center;
    }

    .timeline::before {
        left: 19px;
    }

    .timeline-marker {
        width: 40px;
        height: 40px;
    }

    .next-steps {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn-modern {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Confetti Animation
document.addEventListener('DOMContentLoaded', function() {
    createConfetti();
});

function createConfetti() {
    const colors = ['#667eea', '#764ba2', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
    const confettiCount = 50;
    const container = document.getElementById('confetti');

    for (let i = 0; i < confettiCount; i++) {
        const confetti = document.createElement('div');
        confetti.style.position = 'absolute';
        confetti.style.width = Math.random() * 10 + 5 + 'px';
        confetti.style.height = Math.random() * 10 + 5 + 'px';
        confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.left = Math.random() * 100 + '%';
        confetti.style.top = '-10px';
        confetti.style.opacity = Math.random();
        confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
        confetti.style.animation = `fall ${Math.random() * 3 + 2}s linear forwards`;
        confetti.style.animationDelay = Math.random() * 2 + 's';

        container.appendChild(confetti);

        setTimeout(() => confetti.remove(), 5000);
    }
}

// Add fall animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fall {
        to {
            transform: translateY(100vh) rotate(${Math.random() * 360}deg);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush
@endsection