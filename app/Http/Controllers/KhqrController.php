<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\Payment;
use App\Services\TelegramNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;

class KhqrController extends Controller
{
    /**
     * Display the KHQR payment page with QR code
     */
    public function show(Request $request)
    {
        try {
            // Get order ID from session
            $orderId = session('pending_order_id');

            if (!$orderId) {
                return redirect()->route('cart.index')
                    ->with('error', 'Order not found. Please try again.');
            }

            // Load order from database
            $order = Order::with('items')->find($orderId);

            if (!$order) {
                return redirect()->route('cart.index')
                    ->with('error', 'Order not found. Please try again.');
            }

            // Prepare order data for QR generation
            $orderData = [
                'order_number' => $order->order_number,
                'totals' => [
                    'total' => $order->total
                ]
            ];

            // Generate QR code
            $qrData = $this->generateQRCode($orderData);

            if (!$qrData['success']) {
                return redirect()->route('checkout.index')
                    ->with('error', $qrData['message']);
            }

            return view('payment.khqr', [
                'qrCode' => $qrData['qr_code'],
                'md5' => $qrData['md5'],
                'order' => $order,
                'amount' => $order->total
            ]);

        } catch (\Exception $e) {
            Log::error('KHQR payment page error: ' . $e->getMessage(), [
                'exception' => $e,
                'order_id' => session('pending_order_id')
            ]);

            return redirect()->route('checkout.index')
                ->with('error', 'Unable to process KHQR payment. Please try another payment method.');
        }
    }

    /**
     * Generate KHQR QR code
     */
    public function generateQRCode($orderData)
    {
        try {
            // Check if KHQR is enabled
            if (!config('services.khqr.enabled', false)) {
                return [
                    'success' => false,
                    'message' => 'KHQR payment is currently disabled.'
                ];
            }

            // Validate required configuration
            $requiredConfigs = ['account_id', 'merchant_name', 'merchant_city'];
            foreach ($requiredConfigs as $config) {
                if (empty(config("services.khqr.{$config}"))) {
                    Log::error("KHQR configuration missing: {$config}");
                    return [
                        'success' => false,
                        'message' => 'KHQR payment is not properly configured.'
                    ];
                }
            }

            // Get amount from order data
            $amount = $orderData['totals']['total'];

            // Convert USD to KHR if needed (1 USD = ~4100 KHR)
            // You can adjust this conversion rate or make it dynamic
            // Always use KHR currency for Cambodia
            $currency = KHQRData::CURRENCY_KHR;
            // Convert USD to KHR and round to integer (KHR doesn't use decimals)
            $amount = (int) round($amount * 4100);

            // Create individual info for KHQR
            $individualInfo = new IndividualInfo(
                bakongAccountID: config('services.khqr.account_id'),
                merchantName: config('services.khqr.merchant_name'),
                merchantCity: config('services.khqr.merchant_city'),
                currency: $currency,
                amount: $amount
            );

            // Generate QR code
            $qrData = BakongKHQR::generateIndividual($individualInfo);

            // Check if QR generation was successful
            if ($qrData->status['code'] !== 0) {
                Log::error('KHQR generation failed with error code', [
                    'status' => $qrData->status,
                    'data' => $qrData->data
                ]);
                return [
                    'success' => false,
                    'message' => $qrData->status['message'] ?? 'Failed to generate QR code.'
                ];
            }

            // Validate QR data
            if (empty($qrData->data['qr']) || empty($qrData->data['md5'])) {
                Log::error('KHQR data is incomplete', [
                    'data' => $qrData->data
                ]);
                return [
                    'success' => false,
                    'message' => 'QR code data is incomplete.'
                ];
            }

            // Log successful QR generation
            Log::info('KHQR QR Code generated successfully', [
                'order_number' => $orderData['order_number'],
                'amount' => $amount,
                'currency' => $currency,
                'md5' => $qrData->data['md5']
            ]);

            // Return the QR data
            return [
                'success' => true,
                'qr_code' => $qrData->data['qr'],
                'md5' => $qrData->data['md5'],
                'amount' => $amount,
                'currency' => $currency
            ];

        } catch (\Exception $e) {
            Log::error('KHQR QR Code generation failed: ' . $e->getMessage(), [
                'exception' => $e,
                'order_data' => $orderData
            ]);

            return [
                'success' => false,
                'message' => 'Failed to generate QR code. Please try again.'
            ];
        }
    }

    /**
     * Check transaction status by MD5 hash
     */
    public function checkTransaction(Request $request)

    {
        try {
            $md5 = $request->input('md5');

            if (!$md5) {
                return response()->json([
                    'success' => false,
                    'message' => 'MD5 hash is required'
                ], 400);
            }

            // Get API token from config
            $apiToken = config('services.khqr.api_token');

            if (!$apiToken) {
                Log::error('KHQR API token not configured');
                return response()->json([
                    'success' => false,
                    'message' => 'Payment service not configured'
                ], 500);
            }

            // Initialize KHQR with API token
            $bakongKhqr = new BakongKHQR($apiToken);

            // Check transaction status
            $response = $bakongKhqr->checkTransactionByMD5($md5);

            Log::info('KHQR transaction check', [
                'md5' => $md5,
                'response' => $response
            ]);

            // Check if payment is successful
            // Payment is confirmed when acknowledgedDateMs is present (transaction acknowledged by Bakong)
            $isPaid = isset($response['data']) &&
                      isset($response['data']['acknowledgedDateMs']) &&
                      !empty($response['data']['acknowledgedDateMs']);

            if ($isPaid) {
                Log::info('KHQR Payment DETECTED!', [
                    'md5' => $md5,
                    'transaction_id' => $response['data']['externalRef'] ?? null,
                    'amount' => $response['data']['amount'] ?? null,
                    'from' => $response['data']['fromAccountId'] ?? null
                ]);
            }

            return response()->json([
                'success' => true,
                'paid' => $isPaid,
                'data' => $response,
                'transaction' => $isPaid ? [
                    'transaction_id' => $response['data']['externalRef'] ?? null,
                    'amount' => $response['data']['amount'] ?? null,
                    'from' => $response['data']['fromAccountId'] ?? null,
                    'acknowledged_at' => $response['data']['acknowledgedDateMs'] ?? null
                ] : null
            ]);

        } catch (\Exception $e) {
            Log::error('KHQR transaction check failed: ' . $e->getMessage(), [
                'exception' => $e,
                'md5' => $request->input('md5')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to check transaction status'
            ], 500);
        }
    }

    /**
     * Verify payment and complete order
     */
    public function verifyPayment(Request $request)
    {
        try {
            $md5 = $request->input('md5');
            $orderNumber = $request->input('order_number');

            // Check transaction status
            $checkResponse = $this->checkTransaction($request);
            $checkData = json_decode($checkResponse->getContent(), true);

            if (!$checkData['success'] || !$checkData['paid']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not confirmed. Please complete the payment.'
                ]);
            }

            // Find order by order number
            $order = Order::where('order_number', $orderNumber)->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            // Check if payment already exists for this order
            if ($order->payment && $order->payment->status === 'completed') {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment already processed',
                    'redirect_url' => route('checkout.success', ['order' => $orderNumber])
                ]);
            }

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'khqr',
                'transaction_id' => $checkData['transaction']['transaction_id'] ?? $checkData['data']['data']['externalRef'] ?? null,
                'md5_hash' => $md5,
                'amount' => $order->total,
                'currency' => 'USD',
                'status' => 'completed',
                'payment_data' => $checkData['data'] ?? null,
                'paid_at' => now(),
            ]);

            // Update order payment status
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'paid_at' => now(),
            ]);

            // Send Telegram notification for payment received
            try {
                $telegramService = new TelegramNotificationService();
                $telegramService->sendPaymentReceivedNotification($payment->fresh('order'));
            } catch (\Exception $e) {
                // Log error but don't stop the payment process
                Log::error('Failed to send Telegram notification: ' . $e->getMessage());
            }

            // Send order confirmation email
            try {
                $customerEmail = $order->customer_info['email'] ?? null;
                if ($customerEmail) {
                    Mail::to($customerEmail)->send(new OrderConfirmation($order));
                    Log::info('Order confirmation email sent', ['order_number' => $orderNumber]);
                }
            } catch (\Exception $e) {
                // Log error but don't stop the payment process
                Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            }

            // Clear pending order and cart
            session()->forget(['pending_order_id', 'cart']);

            Log::info('KHQR payment verified and order completed', [
                'order_id' => $order->id,
                'order_number' => $orderNumber,
                'md5' => $md5
            ]);

            return response()->json([
                'success' => true,
                'redirect_url' => route('checkout.success', ['order' => $orderNumber])
            ]);

        } catch (\Exception $e) {
            Log::error('KHQR payment verification failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to verify payment'
            ], 500);
        }
    }
}
