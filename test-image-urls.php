<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "=================================================\n";
echo "Testing Image URLs\n";
echo "=================================================\n\n";

// Test Product Images
echo "🖼️  Product Image URLs:\n";
echo "─────────────────────────────────────────\n";

$products = Product::whereNotNull('image')->limit(3)->get();
foreach ($products as $product) {
    echo "Product: {$product->name}\n";
    echo "  Database path: {$product->image}\n";
    echo "  Generated URL: {$product->image_url}\n";

    // Test if URL is accessible
    $ch = curl_init($product->image_url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        echo "  ✅ Image is accessible (HTTP {$httpCode})\n";
    } else {
        echo "  ❌ Image not accessible (HTTP {$httpCode})\n";
    }
    echo "\n";
}

// Test Category Images
echo "📁 Category Image URLs:\n";
echo "─────────────────────────────────────────\n";

$categories = Category::whereNotNull('image')->get();
foreach ($categories as $category) {
    echo "Category: {$category->name}\n";
    echo "  Database path: {$category->image}\n";
    echo "  Generated URL: {$category->image_url}\n";

    // Test if URL is accessible
    $ch = curl_init($category->image_url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        echo "  ✅ Image is accessible (HTTP {$httpCode})\n";
    } else {
        echo "  ❌ Image not accessible (HTTP {$httpCode})\n";
    }
    echo "\n";
}

echo "=================================================\n";
echo "✅ URL Testing Complete!\n";
echo "=================================================\n";
