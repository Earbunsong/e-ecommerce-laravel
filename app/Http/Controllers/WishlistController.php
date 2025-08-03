<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistIds = session('wishlist', []);
        $wishlistItems = collect($this->getAllProducts())
            ->whereIn('id', $wishlistIds)
            ->values();

        return view('wishlist.index', compact('wishlistItems'));
    }

    public function toggle(Request $request, $id)
    {
        $product = collect($this->getAllProducts())->firstWhere('id', (int) $id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $wishlist = session('wishlist', []);

        if (in_array($id, $wishlist)) {
            $wishlist = array_diff($wishlist, [$id]);
            $message = 'Removed from wishlist';
            $action = 'removed';
        } else {
            $wishlist[] = $id;
            $message = $product['name'] . ' added to wishlist!';
            $action = 'added';
        }

        session(['wishlist' => array_values($wishlist)]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'action' => $action,
            'wishlist_count' => count($wishlist)
        ]);
    }

    public function remove($id)
    {
        $wishlist = session('wishlist', []);
        $wishlist = array_diff($wishlist, [$id]);
        session(['wishlist' => array_values($wishlist)]);

        return redirect()->route('wishlist.index')->with('success', 'Item removed from wishlist');
    }

    public function clear()
    {
        session()->forget('wishlist');
        return redirect()->route('wishlist.index')->with('success', 'Wishlist cleared');
    }

    private function getAllProducts()
    {
        return [
            [
                'id' => 1,
                'name' => 'Dell Inspiron 15 3000 Laptop',
                'price' => 699.99,
                'original_price' => 799.99,
                'image' => 'products/dell-inspiron-15.jpg',
                'category' => 'Laptops',
                'brand' => 'Dell',
                'rating' => 4,
                'review_count' => 128,
                'in_stock' => true,
                'stock_quantity' => 15,
                'sku' => 'DELL-INSP15-001'
            ],
            [
                'id' => 2,
                'name' => 'HP Pavilion Gaming Desktop',
                'price' => 899.99,
                'original_price' => 1099.99,
                'image' => 'products/hp-pavilion-desktop.jpg',
                'category' => 'Desktops',
                'brand' => 'HP',
                'rating' => 5,
                'review_count' => 89,
                'in_stock' => true,
                'stock_quantity' => 8,
                'sku' => 'HP-PAV-GAME-002'
            ],
            [
                'id' => 3,
                'name' => 'Logitech MX Master 3 Wireless Mouse',
                'price' => 89.99,
                'original_price' => 109.99,
                'image' => 'products/logitech-mx-master-3.jpg',
                'category' => 'Accessories',
                'brand' => 'Logitech',
                'rating' => 5,
                'review_count' => 256,
                'in_stock' => true,
                'stock_quantity' => 50,
                'sku' => 'LOGI-MXM3-003'
            ],
            [
                'id' => 4,
                'name' => 'Samsung 27" Curved Gaming Monitor',
                'price' => 299.99,
                'original_price' => 379.99,
                'image' => 'products/samsung-27-curved.jpg',
                'category' => 'Monitors',
                'brand' => 'Samsung',
                'rating' => 4,
                'review_count' => 167,
                'in_stock' => true,
                'stock_quantity' => 12,
                'sku' => 'SAMS-C27-004'
            ],
            [
                'id' => 5,
                'name' => 'Canon PIXMA TR8620 All-in-One Printer',
                'price' => 179.99,
                'original_price' => 229.99,
                'image' => 'products/canon-pixma-tr8620.jpg',
                'category' => 'Printers',
                'brand' => 'Canon',
                'rating' => 4,
                'review_count' => 94,
                'in_stock' => false,
                'stock_quantity' => 0,
                'sku' => 'CANON-TR8620-005'
            ],
            // Add more products as needed...
        ];
    }
}
