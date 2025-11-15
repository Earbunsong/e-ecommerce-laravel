<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search
        if ($request->search) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by stock status
        if ($request->stock_status === 'in_stock') {
            $query->inStock();
        } elseif ($request->stock_status === 'out_of_stock') {
            $query->where('in_stock', false);
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in database
     */
    public function store(Request $request)
    {
        // Debug: Log all request data
        \Log::info('Product creation attempt', [
            'all_data' => $request->all(),
            'has_file' => $request->hasFile('image'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string|max:100',
            'sku' => 'required|string|max:50|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rating' => 'nullable|numeric|min:0|max:5',
            'review_count' => 'nullable|integer|min:0',
            'specs' => 'nullable|array',
            'specs.*' => 'nullable|string',
            'colors' => 'nullable|array',
            'colors.*' => 'nullable|string',
            'storage_options' => 'nullable|array',
            'storage_options.*' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean'
        ]);

        \Log::info('Product validation passed', ['validated_data' => $validated]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Use default disk (will use s3 if configured, or public for local)
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            $imagePath = $request->file('image')->store('products', $disk);
            $validated['image'] = $imagePath;
        }

        // Filter out empty array values
        if (isset($validated['specs'])) {
            $validated['specs'] = array_values(array_filter($validated['specs'], fn($v) => !empty($v)));
        }
        if (isset($validated['colors'])) {
            $validated['colors'] = array_values(array_filter($validated['colors'], fn($v) => !empty($v)));
        }
        if (isset($validated['storage_options'])) {
            $validated['storage_options'] = array_values(array_filter($validated['storage_options'], fn($v) => !empty($v)));
        }

        // Calculate discount if original price provided
        if (isset($validated['original_price']) && $validated['original_price'] && $validated['original_price'] > $validated['price']) {
            $validated['discount'] = round((($validated['original_price'] - $validated['price']) / $validated['original_price']) * 100);
        }

        // Auto-set in_stock based on stock_quantity
        $validated['in_stock'] = $validated['stock_quantity'] > 0;

        // Handle checkboxes (they won't be in request if unchecked)
        $validated['is_featured'] = $request->has('is_featured') ? true : false;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product->load('category');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in database
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string|max:100',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rating' => 'nullable|numeric|min:0|max:5',
            'review_count' => 'nullable|integer|min:0',
            'specs' => 'nullable|array',
            'specs.*' => 'nullable|string',
            'colors' => 'nullable|array',
            'colors.*' => 'nullable|string',
            'storage_options' => 'nullable|array',
            'storage_options.*' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Use default disk (will use s3 if configured, or public for local)
            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';

            // Delete old image
            if ($product->image) {
                Storage::disk($disk)->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', $disk);
            $validated['image'] = $imagePath;
        }

        // Filter out empty array values
        if (isset($validated['specs'])) {
            $validated['specs'] = array_values(array_filter($validated['specs'], fn($v) => !empty($v)));
        }
        if (isset($validated['colors'])) {
            $validated['colors'] = array_values(array_filter($validated['colors'], fn($v) => !empty($v)));
        }
        if (isset($validated['storage_options'])) {
            $validated['storage_options'] = array_values(array_filter($validated['storage_options'], fn($v) => !empty($v)));
        }

        // Calculate discount if original price provided
        if (isset($validated['original_price']) && $validated['original_price'] && $validated['original_price'] > $validated['price']) {
            $validated['discount'] = round((($validated['original_price'] - $validated['price']) / $validated['original_price']) * 100);
        } else {
            $validated['discount'] = null;
        }

        // Auto-set in_stock based on stock_quantity
        $validated['in_stock'] = $validated['stock_quantity'] > 0;

        // Handle checkboxes (they won't be in request if unchecked)
        $validated['is_featured'] = $request->has('is_featured') ? true : false;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from database
     */
    public function destroy(Product $product)
    {
        // Delete product image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Toggle product active status
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        return back()->with('success', 'Product status updated successfully!');
    }

    /**
     * Toggle product featured status
     */
    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);

        return back()->with('success', 'Product featured status updated successfully!');
    }
}