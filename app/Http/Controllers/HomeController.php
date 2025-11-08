<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get active categories with product counts
        $categories = Category::active()
            ->withCount('products')
            ->ordered()
            ->take(8)
            ->get();

        // Get featured products that are active and in stock
        $featuredProducts = Product::with('category')
            ->active()
            ->where('is_featured', true)
            ->inStock()
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // If no featured products, get latest active products
        if ($featuredProducts->count() < 4) {
            $featuredProducts = Product::with('category')
                ->active()
                ->inStock()
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();
        }

        // Get unique brands from active products
        $brands = Product::active()
            ->distinct()
            ->pluck('brand')
            ->filter()
            ->sort()
            ->values();

        return view('home', compact('categories', 'featuredProducts', 'brands'));
    }
}
