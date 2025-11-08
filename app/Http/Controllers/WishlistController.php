<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistIds = session('wishlist', []);
        $wishlistItems = Product::whereIn('id', $wishlistIds)
            ->active()
            ->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    public function toggle(Request $request, $id)
    {
        $product = Product::active()->find($id);

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
            $message = $product->name . ' added to wishlist!';
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
}
