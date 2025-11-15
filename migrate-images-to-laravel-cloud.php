<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "=================================================\n";
echo "Migrating Images to Laravel Cloud Storage\n";
echo "=================================================\n\n";

$migratedCount = 0;
$skippedCount = 0;
$errorCount = 0;

try {
    // Check current storage configuration
    echo "📝 Storage Configuration:\n";
    echo "   Default Disk: " . config('filesystems.default') . "\n";
    echo "   Target URL: " . config('filesystems.disks.s3.url') . "\n\n";

    if (config('filesystems.default') !== 's3') {
        echo "⚠️  Warning: FILESYSTEM_DISK is not set to 's3'\n";
        echo "Please set FILESYSTEM_DISK=s3 in your .env file\n\n";
    }

    // Migrate Product Images
    echo "🖼️  Migrating Product Images...\n";
    echo "─────────────────────────────────────────\n";

    $products = Product::whereNotNull('image')->get();
    echo "Found {$products->count()} products with images\n\n";

    foreach ($products as $product) {
        echo "Processing: {$product->name}\n";
        echo "  Current path: {$product->image}\n";

        try {
            // Check if image starts with 'products/' (already in correct format)
            if (str_starts_with($product->image, 'products/')) {
                // Check if file exists in S3
                if (Storage::disk('s3')->exists($product->image)) {
                    echo "  ✅ Already in Laravel Cloud storage\n";
                    echo "  URL: " . Storage::disk('s3')->url($product->image) . "\n";
                    $skippedCount++;
                } else {
                    // Try to find in local storage and upload
                    $localPath = storage_path('app/public/' . $product->image);
                    if (file_exists($localPath)) {
                        echo "  📤 Uploading to Laravel Cloud...\n";
                        $contents = file_get_contents($localPath);
                        Storage::disk('s3')->put($product->image, $contents, 'public');
                        echo "  ✅ Uploaded successfully!\n";
                        echo "  URL: " . Storage::disk('s3')->url($product->image) . "\n";
                        $migratedCount++;
                    } else {
                        echo "  ⚠️  Local file not found, skipping...\n";
                        $skippedCount++;
                    }
                }
            } else {
                echo "  ℹ️  Non-standard path format, skipping...\n";
                $skippedCount++;
            }
        } catch (Exception $e) {
            echo "  ❌ Error: " . $e->getMessage() . "\n";
            $errorCount++;
        }

        echo "\n";
    }

    // Migrate Category Images
    echo "📁 Migrating Category Images...\n";
    echo "─────────────────────────────────────────\n";

    $categories = Category::whereNotNull('image')->get();
    echo "Found {$categories->count()} categories with images\n\n";

    foreach ($categories as $category) {
        echo "Processing: {$category->name}\n";
        echo "  Current path: {$category->image}\n";

        try {
            // Check if image starts with 'categories/' (already in correct format)
            if (str_starts_with($category->image, 'categories/')) {
                // Check if file exists in S3
                if (Storage::disk('s3')->exists($category->image)) {
                    echo "  ✅ Already in Laravel Cloud storage\n";
                    echo "  URL: " . Storage::disk('s3')->url($category->image) . "\n";
                    $skippedCount++;
                } else {
                    // Try to find in local storage and upload
                    $localPath = storage_path('app/public/' . $category->image);
                    if (file_exists($localPath)) {
                        echo "  📤 Uploading to Laravel Cloud...\n";
                        $contents = file_get_contents($localPath);
                        Storage::disk('s3')->put($category->image, $contents, 'public');
                        echo "  ✅ Uploaded successfully!\n";
                        echo "  URL: " . Storage::disk('s3')->url($category->image) . "\n";
                        $migratedCount++;
                    } else {
                        echo "  ⚠️  Local file not found, skipping...\n";
                        $skippedCount++;
                    }
                }
            } else {
                echo "  ℹ️  Non-standard path format, skipping...\n";
                $skippedCount++;
            }
        } catch (Exception $e) {
            echo "  ❌ Error: " . $e->getMessage() . "\n";
            $errorCount++;
        }

        echo "\n";
    }

    // Summary
    echo "=================================================\n";
    echo "Migration Summary\n";
    echo "=================================================\n";
    echo "✅ Migrated: {$migratedCount}\n";
    echo "⏭️  Skipped: {$skippedCount}\n";
    echo "❌ Errors: {$errorCount}\n";
    echo "=================================================\n\n";

    if ($migratedCount > 0) {
        echo "🎉 Successfully migrated {$migratedCount} images to Laravel Cloud!\n";
    }

    if ($skippedCount > 0) {
        echo "ℹ️  {$skippedCount} images were already in cloud storage or skipped\n";
    }

    echo "\n✨ All images are now ready to display on your website!\n";

} catch (Exception $e) {
    echo "\n❌ FATAL ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
