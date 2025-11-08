@extends('layouts.app')

@section('title', 'Order Success - K2 Computer')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="text-center mb-5">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                </div>
                <h1 class="display-5 mb-3">Order Placed Successfully!</h1>
                <p class="lead text-muted">Thank you for your purchase. Your order has been received and is being processed.</p>
            </div>

            <!-- Order Details Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Order Number</h6>
                            <h4 class="text-primary">{{ $order['order_number'] }}</h4>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted mb-2">Order Date</h6>
                            <p class="mb-0">{{ $order['created_at']->format('F d, Y g:i A') }}</p>
                        </div>
                    </div>

                    <hr>

                    <!-- Customer Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-3">Customer Information</h6>
                            <p class="mb-1">
                                <strong>{{ $order['customer']['first_name'] }} {{ $order['customer']['last_name'] }}</strong>
                            </p>
                            <p class="mb-1 text-muted">
                                <i class="bi bi-envelope me-2"></i>{{ $order['customer']['email'] }}
                            </p>
                            <p class="mb-0 text-muted">
                                <i class="bi bi-telephone me-2"></i>{{ $order['customer']['phone'] }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Shipping Address</h6>
                            <p class="mb-0 text-muted">
                                {{ $order['customer']['address'] }}<br>
                                {{ $order['customer']['city'] }}, {{ $order['customer']['state'] }} {{ $order['customer']['zip_code'] }}
                            </p>
                        </div>
                    </div>

                    <hr>

                    <!-- Order Items -->
                    <div class="mb-4">
                        <h6 class="mb-3">Order Items</h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order['items'] as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('images/' . $item['image']) }}"
                                                     alt="{{ $item['name'] }}"
                                                     class="img-thumbnail me-3"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                                <span>{{ $item['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item['quantity'] }}</td>
                                        <td class="text-end">${{ number_format($item['price'], 2) }}</td>
                                        <td class="text-end">
                                            <strong>${{ number_format($item['price'] * $item['quantity'], 2) }}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>${{ number_format($order['totals']['subtotal'], 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span>
                                        @if($order['totals']['shipping'] > 0)
                                            ${{ number_format($order['totals']['shipping'], 2) }}
                                        @else
                                            <span class="text-success">Free</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Tax (8%):</span>
                                    <span>${{ number_format($order['totals']['tax'], 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-3">
                                    <h5 class="mb-0">Total:</h5>
                                    <h5 class="mb-0 text-success">${{ number_format($order['totals']['total'], 2) }}</h5>
                                </div>
                                <div class="text-end mt-1">
                                    <small class="text-muted">≈ {{ number_format($order['totals']['total'] * 4100, 0) }} KHR</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="alert alert-info mt-4 mb-0">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Payment Method:</strong>
                                @if($order['payment_method'] == 'khqr')
                                    KHQR (Bakong QR Code)
                                    @if(isset($order['payment_status']) && $order['payment_status'] == 'paid')
                                        <span class="badge bg-success ms-2">Paid</span>
                                    @endif
                                @elseif($order['payment_method'] == 'cod')
                                    Cash on Delivery
                                    <span class="badge bg-warning text-dark ms-2">Pending</span>
                                @elseif($order['payment_method'] == 'card')
                                    Credit/Debit Card
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- What's Next -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">What's Next?</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            You will receive an order confirmation email at <strong>{{ $order['customer']['email'] }}</strong>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            We'll send you shipping updates via email
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Your order will be delivered within 3-5 business days
                        </li>
                        @if($order['payment_method'] == 'cod')
                        <li>
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Please prepare exact cash payment upon delivery
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-shop me-2"></i>Continue Shopping
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-house me-2"></i>Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bi-check-circle-fill {
        animation: scaleIn 0.5s ease-in-out;
    }

    @keyframes scaleIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .card {
        border: none;
    }

    .table td {
        vertical-align: middle;
    }
</style>
@endpush
@endsection