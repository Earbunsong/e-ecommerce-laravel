<?php

// app/Http/Controllers/CartController.php
namespace App\Http\Controllers;

use App\Models\Product;
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
            $productModel = Product::find($product['id']);
            $cart[$id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $productModel ? $productModel->image_url : $product['image'],
                'quantity' => $quantity,
                'sku' => $product['sku'] ?? 'N/A'
            ];
        }

        session(['cart' => $cart]);
        session()->save(); // Force session save for production

        if ($request->ajax()) {
            $cartCount = collect($cart)->sum('quantity');
            return response()->json([
                'success' => true,
                'message' => $product['name'] . ' added to cart!',
                'cart_count' => $cartCount,
                'cart_items' => $cart, // Return full cart for debugging
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
            session()->save(); // Force session save for production
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
        session()->save(); // Force session save for production

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
        $productIds = array_column($cart, 'id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        return collect($cart)->map(function ($item) use ($products) {
            $product = $products->get($item['id']);
            if ($product) {
                return array_merge($item, [
                    'name' => $product->name,
                    'image' => $product->image_url,
                    'price' => (float) $product->price,
                    'in_stock' => $product->in_stock,
                    'stock_quantity' => $product->stock_quantity
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

        $shipping = $subtotal >= 100 ? 0 : 0;
        $tax = $subtotal * 0.08;
        $total = $subtotal + $shipping + $tax;

        return compact('subtotal', 'shipping', 'tax', 'total');
    }

    private function findProduct($id)
    {
        $product = Product::active()->find($id);
        return $product ? $product->toArray() : null;
    }

    // ========================================
    // API Methods for AJAX/JavaScript calls
    // ========================================

    /**
     * API: Add product to cart (returns JSON)
     */
    public function addApi(Request $request, $id)
    {
        // Force session start if not started
        if (!session()->isStarted()) {
            session()->start();
        }

        $product = $this->findProduct($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // Get cart from session
        $cart = session('cart', []);
        $quantity = $request->input('quantity', 1);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $productModel = Product::find($product['id']);
            $cart[$id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => (float) $product['price'],
                'image' => $productModel ? $productModel->image_url : $product['image'],
                'quantity' => (int) $quantity,
                'sku' => $product['sku'] ?? 'N/A'
            ];
        }

        // Save to session and force persistence for production
        session(['cart' => $cart]);
        session()->save(); // Critical for production/HTTPS environments

        $cartCount = collect($cart)->sum('quantity');
        $cartTotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return response()->json([
            'success' => true,
            'message' => $product['name'] . ' added to cart!',
            'cart_count' => $cartCount,
            'cart_total' => number_format($cartTotal, 2),
            'item' => [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => number_format($product['price'], 2),
                'quantity' => $quantity
            ]
        ]);
    }

    /**
     * API: Update cart item quantity
     */
    public function updateApi(Request $request, $id)
    {
        $cart = session('cart', []);
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity <= 0) {
            return $this->removeApi($request, $id);
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            session(['cart' => $cart]);
            session()->save(); // Force session save for production
        }

        $cartCount = collect($cart)->sum('quantity');
        $itemTotal = isset($cart[$id]) ? $cart[$id]['price'] * $cart[$id]['quantity'] : 0;
        $cartTotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return response()->json([
            'success' => true,
            'cart_count' => $cartCount,
            'item_total' => number_format($itemTotal, 2),
            'cart_total' => number_format($cartTotal, 2)
        ]);
    }

    /**
     * API: Remove item from cart
     */
    public function removeApi(Request $request, $id)
    {
        $cart = session('cart', []);
        $productName = isset($cart[$id]) ? $cart[$id]['name'] : 'Item';

        unset($cart[$id]);
        session(['cart' => $cart]);
        session()->save(); // Force session save for production

        $cartCount = collect($cart)->sum('quantity');
        $cartTotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return response()->json([
            'success' => true,
            'message' => $productName . ' removed from cart',
            'cart_count' => $cartCount,
            'cart_total' => number_format($cartTotal, 2)
        ]);
    }

    /**
     * API: Get cart count
     */
    public function count()
    {
        $cart = session('cart', []);
        $cartCount = collect($cart)->sum('quantity');

        return response()->json([
            'success' => true,
            'count' => $cartCount
        ]);
    }
}
