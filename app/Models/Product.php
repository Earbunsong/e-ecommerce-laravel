<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'original_price',
        'discount',
        'image',
        'category_id',
        'brand',
        'sku',
        'stock_quantity',
        'in_stock',
        'rating',
        'review_count',
        'specs',
        'colors',
        'storage_options',
        'is_featured',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'in_stock' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'specs' => 'array',
        'colors' => 'array',
        'storage_options' => 'array',
    ];

    /**
     * Relationship: Product belongs to a Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope: Filter active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter in-stock products
     */
    public function scopeInStock($query)
    {
        return $query->where('in_stock', true)->where('stock_quantity', '>', 0);
    }

    /**
     * Scope: Filter by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope: Filter by brand
     */
    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', $brand);
    }

    /**
     * Scope: Filter by price range
     */
    public function scopePriceRange($query, $min, $max = null)
    {
        if ($max) {
            return $query->whereBetween('price', [$min, $max]);
        }
        return $query->where('price', '>=', $min);
    }

    /**
     * Scope: Featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: Search products
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('brand', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%");
        });
    }

    /**
     * Accessor: Get category name
     */
    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : null;
    }

    /**
     * Accessor: Check if product has discount
     */
    public function getHasDiscountAttribute()
    {
        return $this->original_price && $this->original_price > $this->price;
    }

    /**
     * Accessor: Calculate discount percentage
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->has_discount) {
            return 0;
        }
        return round((($this->original_price - $this->price) / $this->original_price) * 100);
    }

    /**
     * Accessor: Get stock status text
     */
    public function getStockStatusAttribute()
    {
        if (!$this->in_stock || $this->stock_quantity <= 0) {
            return 'Out of Stock';
        }
        if ($this->stock_quantity <= 5) {
            return 'Low Stock';
        }
        return 'In Stock';
    }

    /**
     * Accessor: Get image URL (works for both local and S3 storage)
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // Use Storage::url() which automatically handles both local and S3
        return Storage::disk('public')->url($this->image);
    }

    /**
     * Mutator: Auto-calculate discount when prices are set
     */
    public function setOriginalPriceAttribute($value)
    {
        $this->attributes['original_price'] = $value;

        if ($value && isset($this->attributes['price']) && $this->attributes['price'] > 0) {
            $discount = round((($value - $this->attributes['price']) / $value) * 100);
            $this->attributes['discount'] = $discount;
        }
    }

    /**
     * Mutator: Auto-update in_stock based on stock_quantity
     */
    public function setStockQuantityAttribute($value)
    {
        $this->attributes['stock_quantity'] = $value;
        $this->attributes['in_stock'] = $value > 0;
    }
}