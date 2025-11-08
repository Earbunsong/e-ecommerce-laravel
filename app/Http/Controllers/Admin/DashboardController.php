<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_categories' => Category::count(),
            'active_categories' => Category::where('is_active', true)->count(),
            'out_of_stock' => Product::where('in_stock', false)->count(),
            'featured_products' => Product::where('is_featured', true)->count(),
        ];

        $recent_products = Product::with('category')
            ->latest()
            ->take(5)
            ->get();

        $categories_with_products = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(5)
            ->get();

        $low_stock_products = Product::where('stock_quantity', '<', 10)
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent_products',
            'categories_with_products',
            'low_stock_products'
        ));
    }
}