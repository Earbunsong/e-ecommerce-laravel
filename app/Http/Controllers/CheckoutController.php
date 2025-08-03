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

        // In a real application, you would:
        // 1. Save order to database
        // 2. Process payment
        // 3. Send email confirmation
        // 4. Update inventory

        // Store order details in session temporarily
        session([
            'last_order' => [
                'order_number' => $orderNumber,
                'customer' => $request->only(['first_name', 'last_name', 'email', 'phone']),
                'items' => $this->getCartItemsWithDetails($cart),
                'totals' => $this->calculateTotals($this->getCartItemsWithDetails($cart)),
                'created_at' => now()
            ]
        ]);

        // Clear cart
        session()->forget('cart');

        return redirect()->route('checkout.success', ['order' => $orderNumber])
            ->with('success', 'Order placed successfully!');
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

