<?php
namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

        $cartItems = $this->getCartItemsWithDetails($cart);
        $totals = $this->calculateTotals($cartItems);

        // Create order in database within transaction
        $order = DB::transaction(function () use ($request, $cartItems, $totals) {
            // Generate unique order number
            $orderNumber = 'K2-' . strtoupper(str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT));

            // Create the order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => $orderNumber,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'subtotal' => $totals['subtotal'],
                'tax' => $totals['tax'],
                'shipping' => $totals['shipping'],
                'total' => $totals['total'],
                'customer_info' => [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ],
                'shipping_address' => [
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip_code' => $request->zip_code,
                ],
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'product_sku' => $item['sku'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            return $order;
        });

        // Handle different payment methods
        switch ($request->payment_method) {
            case 'khqr':
                // Store order ID for KHQR payment completion
                session(['pending_order_id' => $order->id]);

                // Redirect to KHQR payment page
                return redirect()->route('payment.khqr.show');

            case 'card':
                // TODO: Implement card payment gateway integration
                session(['pending_order_id' => $order->id]);
                return redirect()->route('checkout.index')
                    ->with('info', 'Card payment integration coming soon. Please use KHQR or COD.');

            case 'cod':
                // For Cash on Delivery, order is already created
                // Mark as confirmed and clear cart
                $order->update(['status' => 'processing']);

                // Send order confirmation email
                try {
                    $customerEmail = $order->customer_info['email'] ?? null;
                    if ($customerEmail) {
                        Mail::to($customerEmail)->send(new OrderConfirmation($order));
                    }
                } catch (\Exception $e) {
                    // Log error but don't stop the checkout process
                    \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
                }

                // Clear cart
                session()->forget('cart');

                return redirect()->route('checkout.success', ['order' => $order->order_number])
                    ->with('success', 'Order placed successfully! Pay cash upon delivery.');

            default:
                return redirect()->route('checkout.index')
                    ->with('error', 'Invalid payment method selected.');
        }
    }

    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items.product')
            ->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found');
        }

        // Clear the cart after successful order
        session()->forget('cart');

        return view('checkout.success', compact('order'));
    }

    private function getCartItemsWithDetails($cart)
    {
        $productIds = array_column($cart, 'id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        return collect($cart)->map(function ($item) use ($products) {
            $product = $products->get($item['id']);
            if ($product) {
                return array_merge($item, [
                    'name' => $product->name,
                    'image' => $product->image,
                    'price' => (float) $product->price,
                    'sku' => $product->sku
                ]);
            }
            return $item;
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
}

