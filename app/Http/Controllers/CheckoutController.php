<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{

    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $cartItems = $this->getCartItemsWithDetails($cart);
        $calculations = $this->calculateTotals($cartItems);

        return view('checkout.index', array_merge(compact('cartItems'), $calculations));
    }

    public function process(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'payment_method' => 'required|in:card,khqr,cod',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Generate order number
        $orderNumber = 'K2-' . strtoupper(str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT));

        $cartItems = $this->getCartItemsWithDetails($cart);
        $totals = $this->calculateTotals($cartItems);

        // Prepare order data
        $orderData = [
            'order_number' => $orderNumber,
            'customer' => $request->only(['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'state', 'zip_code']),
            'items' => $cartItems,
            'totals' => $totals,
            'payment_method' => $request->payment_method,
            'created_at' => now()
        ];

        // Handle different payment methods
        switch ($request->payment_method) {
            case 'khqr':
                // Store pending order for KHQR payment
                session(['pending_order' => $orderData]);

                // Redirect to KHQR payment page
                return redirect()->route('payment.khqr.show');

            case 'card':
                // TODO: Implement card payment gateway integration
                // For now, redirect to a card payment page (to be created)
                session(['pending_order' => $orderData]);
                return redirect()->route('checkout.index')
                    ->with('info', 'Card payment integration coming soon. Please use KHQR or COD.');

            case 'cod':
                // For Cash on Delivery, directly complete the order
                // In a real application, you would:
                // 1. Save order to database with 'pending' payment status
                // 2. Send email confirmation
                // 3. Update inventory

                session([
                    'last_order' => array_merge($orderData, [
                        'payment_status' => 'pending',
                        'payment_note' => 'Cash on Delivery'
                    ])
                ]);

                // Clear cart
                session()->forget('cart');

                return redirect()->route('checkout.success', ['order' => $orderNumber])
                    ->with('success', 'Order placed successfully! Pay cash upon delivery.');

            default:
                return redirect()->route('checkout.index')
                    ->with('error', 'Invalid payment method selected.');
        }
    }

    public function success($orderNumber)
    {
        $order = session('last_order');

        if (!$order || $order['order_number'] !== $orderNumber) {
            return redirect()->route('home')->with('error', 'Order not found');
        }

        return view('checkout.success', compact('order'));
    }

    private function getCartItemsWithDetails($cart)
    {
        $products = collect($this->getAllProducts())->keyBy('id');

        return collect($cart)->map(function ($item) use ($products) {
            $product = $products->get($item['id']);
            return array_merge($item, [
                'name' => $product['name'] ?? $item['name'],
                'image' => $product['image'] ?? $item['image'],
                'price' => $product['price'] ?? $item['price']
            ]);
        })->values()->toArray();
    }

    private function calculateTotals($cartItems)
    {
        $subtotal = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $shipping = $subtotal >= 100 ? 0 : 15;
        $tax = $subtotal * 0.08;
        $total = $subtotal + $shipping + $tax;

        return compact('subtotal', 'shipping', 'tax', 'total');
    }

    private function getAllProducts()
    {
        // Same products array
        return [
            [
                'id' => 1,
                'name' => 'Dell Inspiron 15 3000 Laptop',
                'price' => 699.99,
                'image' => 'products/dell-inspiron-15.jpg',
                'sku' => 'DELL-INSP15-001'
            ],
            // Add other products...
        ];
    }
}

