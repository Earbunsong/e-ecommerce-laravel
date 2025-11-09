<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Start with active products
        $query = Product::with('category')->active();

        // Apply category filter
        if ($request->category && $request->category !== 'all') {
            // Check if category is a slug or an ID
            if (is_numeric($request->category)) {
                $query->where('category_id', $request->category);
            } else {
                // Look up category by slug
                $category = Category::where('slug', $request->category)->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            }
        }

        // Apply brand filter
        if ($request->brand && !empty($request->brand)) {
            $selectedBrands = is_array($request->brand) ? $request->brand : [$request->brand];
            $query->whereIn('brand', $selectedBrands);
        }

        // Apply price range filter
        if ($request->price_range) {
            $this->applyPriceRangeFilter($query, $request->price_range);
        }

        // Apply search
        if ($request->q) {
            $query->search($request->q);
        }

        // Apply sorting
        $this->applySorting($query, $request->sort);

        // Get products
        $products = $query->get();

        // Get all categories for filter
        $categories = Category::withCount('products')->get();

        // Get unique brands for filter
        $brands = Product::active()->distinct()->pluck('brand')->sort()->values();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function show($id)
    {
        $product = Product::with('category')->active()->findOrFail($id);

        // Get related products from same category
        $relatedProducts = Product::with('category')
            ->active()
            ->inStock()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // Check if product is in wishlist
        $isWishlisted = in_array($id, session('wishlist', []));

        return view('products.show', compact('product', 'relatedProducts', 'isWishlisted'));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Apply price range filter to query
     */
    private function applyPriceRangeFilter($query, $range)
    {
        switch ($range) {
            case '0-100':
                $query->where('price', '<=', 100);
                break;
            case '100-500':
                $query->where('price', '>', 100)->where('price', '<=', 500);
                break;
            case '500-1000':
                $query->where('price', '>', 500)->where('price', '<=', 1000);
                break;
            case '1000+':
                $query->where('price', '>', 1000);
                break;
        }
    }

    /**
     * Apply sorting to query
     */
    private function applySorting($query, $sort)
    {
        switch ($sort) {
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }
    }

    /**
     * API: Search products (returns JSON)
     */
    public function searchApi(Request $request)
    {
        $query = Product::with('category')->active();

        // Apply search query
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%")
                  ->orWhere('brand', 'like', "%{$searchTerm}%");
            });
        }

        // Apply category filter
        if ($request->filled('category')) {
            if (is_numeric($request->category)) {
                $query->where('category_id', $request->category);
            } else {
                $category = Category::where('slug', $request->category)->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            }
        }

        // Apply price filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply sorting
        $this->applySorting($query, $request->get('sort', 'newest'));

        // Pagination
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ]
        ]);
    }
}