<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .order-details {
            background-color: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .order-number {
            font-size: 24px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 10px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #1f2937;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th {
            background-color: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .total-row {
            font-weight: bold;
            font-size: 16px;
            background-color: #f9fafb;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
            margin-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-processing {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4F46E5;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Order Confirmation</h1>
        <p>Thank you for your purchase!</p>
    </div>

    <div class="content">
        <p>Hi {{ $order->customer_info['first_name'] ?? 'Customer' }},</p>

        <p>Thank you for your order! We've received your order and will process it shortly.</p>

        <div class="order-details">
            <div class="order-number">Order #{{ $order->order_number }}</div>
            <p>
                <strong>Order Date:</strong> {{ $order->created_at->format('F d, Y h:i A') }}<br>
                <strong>Order Status:</strong>
                <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span><br>
                <strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}<br>
                <strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}
            </p>
        </div>

        <div class="section-title">Order Items</div>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th style="text-align: center">Qty</th>
                    <th style="text-align: right">Price</th>
                    <th style="text-align: right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->product_sku }}</td>
                    <td style="text-align: center">{{ $item->quantity }}</td>
                    <td style="text-align: right">${{ number_format($item->price, 2) }}</td>
                    <td style="text-align: right">${{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right"><strong>Subtotal:</strong></td>
                    <td style="text-align: right">${{ number_format($order->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right"><strong>Tax:</strong></td>
                    <td style="text-align: right">${{ number_format($order->tax, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right"><strong>Shipping:</strong></td>
                    <td style="text-align: right">
                        @if($order->shipping == 0)
                            FREE
                        @else
                            ${{ number_format($order->shipping, 2) }}
                        @endif
                    </td>
                </tr>
                <tr class="total-row">
                    <td colspan="4" style="text-align: right"><strong>Total:</strong></td>
                    <td style="text-align: right">${{ number_format($order->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="section-title">Shipping Address</div>
        <p>
            {{ $order->customer_info['first_name'] ?? '' }} {{ $order->customer_info['last_name'] ?? '' }}<br>
            {{ $order->shipping_address['address'] ?? '' }}<br>
            {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['zip_code'] ?? '' }}<br>
            @if(isset($order->customer_info['phone']))
            Phone: {{ $order->customer_info['phone'] }}<br>
            @endif
            @if(isset($order->customer_info['email']))
            Email: {{ $order->customer_info['email'] }}
            @endif
        </p>

        @if($order->payment_method === 'cod')
        <div style="background-color: #fef3c7; padding: 15px; border-radius: 6px; margin: 20px 0;">
            <strong>Payment Method: Cash on Delivery</strong><br>
            Please prepare the exact amount of <strong>${{ number_format($order->total, 2) }}</strong> when the order arrives.
        </div>
        @endif

        <p style="margin-top: 30px;">
            If you have any questions about your order, please contact us at:
        </p>
        <p>
            <strong>Email:</strong> support@k2computer.com<br>
            <strong>Phone:</strong> +855 XX XXX XXX
        </p>
    </div>

    <div class="footer">
        <p>This is an automated message, please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} K2 Computer. All rights reserved.</p>
    </div>
</body>
</html>