<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;

class ExportDataToSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:export {--type=all : Export type (categories, products, or all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export current database data to PHP arrays for seeding production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');

        if ($type === 'all' || $type === 'categories') {
            $this->exportCategories();
        }

        if ($type === 'all' || $type === 'products') {
            $this->exportProducts();
        }

        $this->info('Export completed!');
    }

    private function exportCategories()
    {
        $this->info('Exporting categories...');

        $categories = Category::all();
        $output = "<?php\n\n// Categories data - Copy this into CategorySeeder.php\n";
        $output .= "\$categories = [\n";

        foreach ($categories as $category) {
            $output .= "    [\n";
            $output .= "        'name' => " . var_export($category->name, true) . ",\n";
            $output .= "        'slug' => " . var_export($category->slug, true) . ",\n";
            $output .= "        'description' => " . var_export($category->description, true) . ",\n";
            $output .= "        'image' => " . var_export($category->image, true) . ",\n";
            $output .= "        'is_active' => " . var_export($category->is_active, true) . ",\n";
            $output .= "        'display_order' => " . var_export($category->display_order, true) . ",\n";
            $output .= "    ],\n";
        }

        $output .= "];\n\n";
        $output .= "foreach (\$categories as \$category) {\n";
        $output .= "    Category::updateOrCreate(\n";
        $output .= "        ['slug' => \$category['slug']],\n";
        $output .= "        \$category\n";
        $output .= "    );\n";
        $output .= "}\n";

        file_put_contents(storage_path('app/categories_export.php'), $output);
        $this->info("✓ Categories exported to: storage/app/categories_export.php");
        $this->info("  Total: {$categories->count()} categories");
    }

    private function exportProducts()
    {
        $this->info('Exporting products...');

        $products = Product::with('category')->get();
        $output = "<?php\n\n// Products data - Copy this into ProductSeeder.php\n\n";
        $output .= "// Get categories by slug first\n";

        // Get unique categories
        $categoryMap = [];
        foreach ($products as $product) {
            $slug = $product->category->slug;
            if (!isset($categoryMap[$slug])) {
                $varName = '$' . str_replace('-', '_', $slug);
                $categoryMap[$slug] = $varName;
                $output .= "{$varName} = Category::where('slug', '{$slug}')->first();\n";
            }
        }

        $output .= "\n\$products = [\n";

        foreach ($products as $product) {
            $categoryVar = $categoryMap[$product->category->slug];

            $output .= "    [\n";
            $output .= "        'name' => " . var_export($product->name, true) . ",\n";
            $output .= "        'description' => " . var_export($product->description, true) . ",\n";
            $output .= "        'price' => " . var_export($product->price, true) . ",\n";
            $output .= "        'original_price' => " . var_export($product->original_price, true) . ",\n";
            $output .= "        'discount' => " . var_export($product->discount, true) . ",\n";
            $output .= "        'image' => " . var_export($product->image, true) . ",\n";
            $output .= "        'category_id' => {$categoryVar}->id,\n";
            $output .= "        'brand' => " . var_export($product->brand, true) . ",\n";
            $output .= "        'sku' => " . var_export($product->sku, true) . ",\n";
            $output .= "        'stock_quantity' => " . var_export($product->stock_quantity, true) . ",\n";
            $output .= "        'in_stock' => " . var_export($product->in_stock, true) . ",\n";
            $output .= "        'rating' => " . var_export($product->rating, true) . ",\n";
            $output .= "        'review_count' => " . var_export($product->review_count, true) . ",\n";

            if ($product->specs) {
                $output .= "        'specs' => " . $this->arrayExport($product->specs) . ",\n";
            } else {
                $output .= "        'specs' => null,\n";
            }

            if ($product->colors) {
                $output .= "        'colors' => " . $this->arrayExport($product->colors) . ",\n";
            } else {
                $output .= "        'colors' => null,\n";
            }

            if ($product->storage_options) {
                $output .= "        'storage_options' => " . $this->arrayExport($product->storage_options) . ",\n";
            } else {
                $output .= "        'storage_options' => null,\n";
            }

            $output .= "        'is_featured' => " . var_export($product->is_featured, true) . ",\n";
            $output .= "        'is_active' => " . var_export($product->is_active, true) . ",\n";
            $output .= "    ],\n";
        }

        $output .= "];\n\n";
        $output .= "foreach (\$products as \$productData) {\n";
        $output .= "    Product::updateOrCreate(\n";
        $output .= "        ['sku' => \$productData['sku']],\n";
        $output .= "        \$productData\n";
        $output .= "    );\n";
        $output .= "}\n";

        file_put_contents(storage_path('app/products_export.php'), $output);
        $this->info("✓ Products exported to: storage/app/products_export.php");
        $this->info("  Total: {$products->count()} products");
    }

    private function arrayExport($array)
    {
        if (empty($array)) {
            return '[]';
        }

        $items = array_map(function($item) {
            return var_export($item, true);
        }, $array);

        return '[' . implode(', ', $items) . ']';
    }
}
