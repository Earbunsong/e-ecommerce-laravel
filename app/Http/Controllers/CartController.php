<?php

// app/Http/Controllers/CartController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $cartItems = $this->getCartItemsWithDetails($cart);
        $calculations = $this->calculateTotals($cartItems);

        return view('cart.index', array_merge(compact('cartItems'), $calculations));
    }

    public function add(Request $request, $id)
    {
        $product = $this->findProduct($id);

        if (!$product) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }
            return redirect()->back()->with('error', 'Product not found');
        }

        $cart = session('cart', []);
        $quantity = $request->input('quantity', 1);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity,
                'sku' => $product['sku'] ?? 'N/A'
            ];
        }

        session(['cart' => $cart]);

        if ($request->ajax()) {
            $cartCount = collect($cart)->sum('quantity');
            return response()->json([
                'success' => true,
                'message' => $product['name'] . ' added to cart!',
                'cart_count' => $cartCount,
                'item' => [
                    'name' => $product['name'],
                    'price' => number_format($product['price'], 2),
                    'quantity' => $quantity
                ]
            ]);
        }

        return redirect()->back()->with('success', $product['name'] . ' added to cart!');
    }

    public function update(Request $request, $id)
    {
        $cart = session('cart', []);
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity <= 0) {
            return $this->remove($request, $id);
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            session(['cart' => $cart]);
        }

        if ($request->ajax()) {
            $cartCount = collect($cart)->sum('quantity');
            $itemTotal = isset($cart[$id]) ? $cart[$id]['price'] * $cart[$id]['quantity'] : 0;

            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'item_total' => number_format($itemTotal, 2)
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully');
    }

    public function remove(Request $request, $id)
    {
        $cart = session('cart', []);
        $productName = isset($cart[$id]) ? $cart[$id]['name'] : 'Item';

        unset($cart[$id]);
        session(['cart' => $cart]);

        if ($request->ajax()) {
            $cartCount = collect($cart)->sum('quantity');
            return response()->json([
                'success' => true,
                'message' => $productName . ' removed from cart',
                'cart_count' => $cartCount
            ]);
        }

        return redirect()->route('cart.index')->with('success', $productName . ' removed from cart');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully');
    }

    private function getCartItemsWithDetails($cart)
    {
        $products = collect($this->getAllProducts())->keyBy('id');

        return collect($cart)->map(function ($item) use ($products) {
            $product = $products->get($item['id']);
            return array_merge($item, [
                'name' => $product['name'] ?? $item['name'],
                'image' => $product['image'] ?? $item['image'],
                'price' => $product['price'] ?? $item['price'],
                'in_stock' => $product['in_stock'] ?? true
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

    private function findProduct($id)
    {
        return collect($this->getAllProducts())->firstWhere('id', (int) $id);
    }

    private function getAllProducts()
    {
        return [
            [
                'id' => 1,
                'name' => 'Dell Inspiron 15 3000 Laptop',
                'price' => 699.99,
                'original_price' => 799.99,
                'image' => 'products/dell-laptop.jpg',
                'category' => 'Laptops',
                'brand' => 'Dell',
                'rating' => 4,
                'review_count' => 128,
                'in_stock' => true,
                'sku' => 'DELL-001'
            ],
            [
                'id' => 2,
                'name' => 'HP Gaming Desktop',
                'price' => 899.99,
                'original_price' => 1099.99,
                'image' => 'products/hp-desktop.jpg',
                'category' => 'Desktops',
                'brand' => 'HP',
                'rating' => 5,
                'review_count' => 89,
                'in_stock' => true,
                'sku' => 'HP-002'
            ],
            [
                'id' => 3,
                'name' => 'Logitech Wireless Mouse',
                'price' => 89.99,
                'original_price' => 109.99,
                'image' => 'products/mouse.jpg',
                'category' => 'Accessories',
                'brand' => 'Logitech',
                'rating' => 5,
                'review_count' => 256,
                'in_stock' => true,
                'sku' => 'LOGI-003'
            ],
            [
                'id' => 4,
                'name' => 'Samsung 27" Monitor',
                'price' => 299.99,
                'original_price' => 379.99,
                'image' => 'products/monitor.jpg',
                'category' => 'Monitors',
                'brand' => 'Samsung',
                'rating' => 4,
                'review_count' => 167,
                'in_stock' => true,
                'sku' => 'SAMS-004'
            ],
            [
                'id' => 5,
                'name' => 'Canon Printer',
                'price' => 179.99,
                'original_price' => 229.99,
                'image' => 'products/printer.jpg',
                'category' => 'Printers',
                'brand' => 'Canon',
                'rating' => 4,
                'review_count' => 94,
                'in_stock' => false,
                'sku' => 'CANON-005'
            ]
        ];
    }
}
