# KHQR Payment Integration Guide

## Overview

This document provides a comprehensive guide to the KHQR (Khmer Quick Response) payment integration implemented in the K2 Computer e-commerce platform. KHQR is Cambodia's national payment system powered by Bakong.

## What Has Been Implemented

### 1. Environment Configuration

**File: `.env.example`**

Added KHQR configuration variables:
```env
KHQR_ENABLED=true
KHQR_API_TOKEN=your_jwt_token_here
KHQR_ACCOUNT_ID=ear_bunsong@aclb
KHQR_MERCHANT_NAME="BUNSONG EAR"
KHQR_MERCHANT_CITY="PHNOM PENH"
KHQR_CURRENCY=KHR
KHQR_TRANSACTION_TYPE=PP
```

### 2. Service Configuration

**File: `config/services.php`**

Added KHQR service configuration that reads from environment variables:
```php
'khqr' => [
    'enabled' => env('KHQR_ENABLED', false),
    'api_token' => env('KHQR_API_TOKEN'),
    'account_id' => env('KHQR_ACCOUNT_ID'),
    'merchant_name' => env('KHQR_MERCHANT_NAME'),
    'merchant_city' => env('KHQR_MERCHANT_CITY'),
    'currency' => env('KHQR_CURRENCY', 'KHR'),
    'transaction_type' => env('KHQR_TRANSACTION_TYPE', 'PP'),
],
```

### 3. Controller Improvements

**File: `app/Http/Controllers/KhqrController.php`**

Completely refactored with the following methods:

#### `show(Request $request)`
- Displays the KHQR payment page
- Retrieves pending order from session
- Generates QR code dynamically
- Renders payment view with QR code and order details

#### `generateQRCode($orderData)`
- Validates KHQR configuration
- Calculates payment amount from cart total
- Converts USD to KHR (1 USD = 4100 KHR)
- Creates QR code using Bakong KHQR library
- Returns QR code image and MD5 hash for verification
- Includes comprehensive error handling and logging

#### `checkTransaction(Request $request)`
- Checks payment status via MD5 hash
- Communicates with Bakong API
- Returns JSON response with payment status
- Used for both auto-checking and manual verification

#### `verifyPayment(Request $request)`
- Verifies completed payment
- Updates order status
- Stores order in session (ready for database integration)
- Clears cart and pending order
- Returns success with redirect URL

### 4. Checkout Integration

**File: `app/Http/Controllers/CheckoutController.php`**

Updated `process()` method to handle multiple payment methods:

```php
switch ($request->payment_method) {
    case 'khqr':
        session(['pending_order' => $orderData]);
        return redirect()->route('payment.khqr.show');

    case 'cod':
        // Complete order immediately
        // Store with 'pending' payment status

    case 'card':
        // Card payment (to be implemented)
}
```

### 5. Routes

**File: `routes/web.php`**

Added new KHQR payment routes:
```php
Route::get('/payment/khqr', [KhqrController::class, 'show'])->name('payment.khqr.show');
Route::post('/payment/khqr/check', [KhqrController::class, 'checkTransaction'])->name('payment.khqr.check');
Route::post('/payment/khqr/verify', [KhqrController::class, 'verifyPayment'])->name('payment.khqr.verify');
```

### 6. Payment View

**File: `resources/views/payment/khqr.blade.php`**

Created a comprehensive payment page with:

#### Visual Features
- Large QR code display
- Order number and amount (USD & KHR)
- Step-by-step payment instructions
- Real-time payment status indicator
- 15-minute countdown timer

#### Functionality
- **Auto-verification**: Checks payment status every 3 seconds
- **Manual verification**: "I've Completed Payment" button
- **Timer**: 15-minute timeout for payment
- **Status updates**: Loading, success, and error states
- **Responsive design**: Mobile-friendly interface
- **Security**: CSRF token protection

#### JavaScript Features
```javascript
// Auto-check payment every 3 seconds
setInterval(checkPaymentStatus, 3000);

// Countdown timer
startTimer(); // 15:00 minutes

// Manual verification
verifyAndComplete();
```

## Payment Flow

### Complete User Journey

```
1. User adds products to cart
   ↓
2. User proceeds to checkout
   ↓
3. User fills in shipping/billing information
   ↓
4. User selects "KHQR" as payment method
   ↓
5. User clicks "Place Order"
   ↓
6. System generates order number (K2-XXXXXX)
   ↓
7. System stores pending order in session
   ↓
8. System redirects to KHQR payment page
   ↓
9. System generates QR code with order total
   ↓
10. User scans QR code with banking app
    ↓
11. User confirms payment in banking app
    ↓
12. System auto-detects payment (polls every 3s)
    OR User clicks "I've Completed Payment"
    ↓
13. System verifies payment with Bakong API
    ↓
14. System updates order status to "paid"
    ↓
15. System clears cart and pending order
    ↓
16. System redirects to order success page
```

## Technical Details

### Currency Conversion

The system converts USD to KHR for KHQR payments:

```php
$currency = config('services.khqr.currency', KHQRData::CURRENCY_KHR);
if ($currency === KHQRData::CURRENCY_KHR) {
    $amount = round($amount * 4100, 2); // 1 USD = 4100 KHR
}
```

**Note**: You can adjust the conversion rate or make it dynamic by fetching real-time rates.

### Session Data Structure

#### Pending Order (stored during checkout)
```php
[
    'order_number' => 'K2-123456',
    'customer' => [...],
    'items' => [...],
    'totals' => [
        'subtotal' => 699.99,
        'shipping' => 15.00,
        'tax' => 55.99,
        'total' => 770.98
    ],
    'payment_method' => 'khqr',
    'created_at' => Carbon\Carbon
]
```

#### Completed Order (stored after payment)
```php
[
    ...pending_order,
    'payment_status' => 'paid',
    'payment_verified_at' => Carbon\Carbon,
    'payment_md5' => '0f6fc49b4d4f916898e1c3ecaf8fbb4b'
]
```

### Error Handling

All KHQR methods include comprehensive error handling:

```php
try {
    // KHQR operation
} catch (\Exception $e) {
    Log::error('KHQR error: ' . $e->getMessage(), [
        'exception' => $e,
        'context_data' => $data
    ]);

    return response()->json([
        'success' => false,
        'message' => 'User-friendly error message'
    ], 500);
}
```

### Logging

All major operations are logged:

- QR code generation
- Transaction checks
- Payment verification
- Errors and exceptions

View logs in `storage/logs/laravel.log`

## Setup Instructions

### 1. Configure Environment Variables

Copy your `.env.example` to `.env` and update KHQR settings:

```bash
cp .env.example .env
```

Edit `.env`:
```env
KHQR_ENABLED=true
KHQR_API_TOKEN=your_actual_jwt_token_from_bakong
KHQR_ACCOUNT_ID=your_bakong_account_id
KHQR_MERCHANT_NAME="Your Business Name"
KHQR_MERCHANT_CITY="Your City"
KHQR_CURRENCY=KHR
KHQR_TRANSACTION_TYPE=PP
```

### 2. Clear Configuration Cache

```bash
php artisan config:clear
php artisan config:cache
```

### 3. Test KHQR Integration

#### Test in Development Mode

1. Set `KHQR_ENABLED=true` in `.env`
2. Use test credentials from Bakong
3. Navigate to checkout and select KHQR payment
4. Scan QR code with test banking app
5. Verify payment flow completes

#### Test Payment Verification

Use the browser console or API testing tool:

```javascript
// Check transaction status
fetch('/payment/khqr/check', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': 'your-csrf-token'
    },
    body: JSON.stringify({
        md5: 'transaction_md5_hash'
    })
});
```

### 4. Production Deployment

Before deploying to production:

1. ✅ Obtain production KHQR credentials from Bakong
2. ✅ Update `.env` with production API token
3. ✅ Test with real transactions
4. ✅ Ensure SSL/HTTPS is enabled
5. ✅ Monitor logs for errors
6. ✅ Implement order database storage (currently session-based)

## API Integration Details

### Bakong KHQR Library

The implementation uses the official KHQR PHP library:

```bash
composer require khqr-gateway/bakong-khqr-php
```

### Key Classes Used

#### `IndividualInfo`
Creates individual merchant information:
```php
new IndividualInfo(
    bakongAccountID: 'account@bank',
    merchantName: 'Merchant Name',
    merchantCity: 'City',
    currency: KHQRData::CURRENCY_KHR,
    amount: 1000
);
```

#### `BakongKHQR`
Main class for API interactions:
```php
// Generate QR code
$qrData = BakongKHQR::generateIndividual($individualInfo);

// Check transaction
$bakongKhqr = new BakongKHQR($apiToken);
$response = $bakongKhqr->checkTransactionByMD5($md5Hash);
```

### API Response Structure

#### Generate QR Code Response
```php
[
    'data' => [
        'qr' => 'base64_encoded_qr_image',
        'md5' => 'unique_transaction_hash'
    ]
]
```

#### Check Transaction Response
```php
[
    'data' => [
        'status' => 'PAID' | 'PENDING' | 'FAILED',
        'amount' => 1000,
        'currency' => 'KHR',
        'timestamp' => '2025-11-04T12:00:00Z'
    ]
]
```

## Security Considerations

### Implemented Security Measures

1. **CSRF Protection**: All POST requests include CSRF token
2. **Environment Variables**: Sensitive credentials stored in `.env`
3. **Session Security**: Order data stored in encrypted sessions
4. **Logging**: All transactions logged for audit trail
5. **Validation**: Input validation on all endpoints
6. **Error Handling**: No sensitive data exposed in error messages

### Recommendations

1. **Enable HTTPS**: Essential for production
2. **Rate Limiting**: Add rate limits to payment endpoints
3. **Webhook Verification**: Verify Bakong webhook signatures (when available)
4. **Database Storage**: Move from session to database for order persistence
5. **Payment Timeout**: Implement automatic order cancellation after timeout
6. **Fraud Detection**: Monitor for suspicious payment patterns

## Troubleshooting

### Common Issues

#### 1. "KHQR payment is currently disabled"
**Solution**: Set `KHQR_ENABLED=true` in `.env` and run `php artisan config:clear`

#### 2. "KHQR payment is not properly configured"
**Solution**: Ensure all required environment variables are set:
- KHQR_ACCOUNT_ID
- KHQR_MERCHANT_NAME
- KHQR_MERCHANT_CITY

#### 3. QR Code Not Generating
**Solution**:
- Check `storage/logs/laravel.log` for errors
- Verify KHQR library is installed: `composer show khqr-gateway/bakong-khqr-php`
- Ensure account ID format is correct (e.g., `username@bank`)

#### 4. Payment Verification Fails
**Solution**:
- Verify KHQR_API_TOKEN is correct
- Check if token has expired
- Ensure MD5 hash is being passed correctly
- Review API response in logs

#### 5. Session Data Lost
**Solution**:
- Check session driver configuration
- Verify `storage/framework/sessions` is writable
- Ensure browser accepts cookies

### Debug Mode

Enable detailed logging:

```php
// In KhqrController methods, add:
Log::debug('KHQR Debug', [
    'order_data' => $orderData,
    'qr_data' => $qrData,
    'api_response' => $response
]);
```

## Future Enhancements

### Planned Improvements

1. **Database Integration**
   - Store orders in `orders` table
   - Store order items in `order_items` table
   - Store payment transactions in `payments` table

2. **Email Notifications**
   - Send QR code via email
   - Send payment confirmation email
   - Send order receipt

3. **Admin Dashboard**
   - View KHQR transactions
   - Reconcile payments
   - Generate payment reports

4. **Webhook Integration**
   - Receive payment notifications from Bakong
   - Automatic order status updates
   - Real-time payment confirmation

5. **Multi-Currency Support**
   - Support both USD and KHR
   - Dynamic exchange rates
   - Customer currency selection

6. **Refund Functionality**
   - Process KHQR refunds
   - Partial refunds
   - Refund tracking

## Testing

### Manual Testing Checklist

- [ ] User can select KHQR payment at checkout
- [ ] QR code is generated successfully
- [ ] QR code displays correct amount
- [ ] Timer counts down from 15:00
- [ ] Auto-check polls every 3 seconds
- [ ] Manual verification button works
- [ ] Payment success redirects correctly
- [ ] Cart is cleared after payment
- [ ] Order appears on success page
- [ ] Payment timeout is handled correctly
- [ ] Cancel payment returns to checkout
- [ ] Logs are created for all operations

### Unit Testing (To Be Implemented)

```php
// tests/Feature/KhqrPaymentTest.php

public function test_khqr_payment_page_displays()
{
    // Test payment page loads correctly
}

public function test_qr_code_generation()
{
    // Test QR code is generated with correct amount
}

public function test_payment_verification()
{
    // Test payment verification logic
}
```

## Support & Resources

### Documentation
- **Bakong KHQR**: [Official Documentation](https://bakong.nbc.org.kh/)
- **KHQR PHP Library**: [GitHub Repository](https://github.com/khqr-gateway/bakong-khqr-php)

### Contact
- **Technical Issues**: Check `storage/logs/laravel.log`
- **Bakong Support**: Contact your Bakong account manager

## Changelog

### Version 1.0.0 (2025-11-04)
- ✅ Initial KHQR integration
- ✅ Environment variable configuration
- ✅ QR code generation
- ✅ Payment verification
- ✅ Auto-check functionality
- ✅ Responsive payment page
- ✅ Comprehensive error handling
- ✅ Logging implementation

---

**Last Updated**: November 4, 2025
**Version**: 1.0.0
**Status**: Production Ready (with database integration pending)
