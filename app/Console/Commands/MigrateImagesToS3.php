<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;

class MigrateImagesToS3 extends Command
{
    protected $signature = 'storage:migrate-to-s3 {--dry-run : Show what would be migrated without actually doing it}';
    protected $description = 'Migrate product and category images from local storage to S3';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No files will actually be transferred');
        }

        $this->info('Starting migration of images to S3...');
        $this->newLine();

        // Check if S3 disk is configured
        $s3Config = config('filesystems.disks.s3');

        if (!$s3Config || !isset($s3Config['bucket'])) {
            $this->error('S3 disk is not properly configured!');
            $this->line('Make sure you have AWS_BUCKET and other S3 credentials set in .env');
            return 1;
        }

        $this->info('S3 Configuration:');
        $this->line('  Bucket: ' . ($s3Config['bucket'] ?? 'not set'));
        $this->line('  Region: ' . ($s3Config['region'] ?? 'not set'));
        $this->line('  Endpoint: ' . ($s3Config['endpoint'] ?? 'not set'));
        $this->newLine();

        // Test S3 connection
        try {
            Storage::disk('s3')->exists('test');
            $this->info('✓ S3 connection successful');
        } catch (\Exception $e) {
            $this->error('✗ Cannot connect to S3: ' . $e->getMessage());
            $this->line('Please check your AWS credentials in .env');
            return 1;
        }
        $this->newLine();

        $productsMigrated = 0;
        $categoriesMigrated = 0;
        $errors = [];

        // Migrate product images
        $this->info('Migrating product images...');
        $products = Product::whereNotNull('image')->get();

        $bar = $this->output->createProgressBar(count($products));
        $bar->start();

        foreach ($products as $product) {
            try {
                if ($this->migrateFile($product->image, $isDryRun)) {
                    $productsMigrated++;
                }
            } catch (\Exception $e) {
                $errors[] = "Product {$product->id}: {$e->getMessage()}";
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Migrate category images
        $this->info('Migrating category images...');
        $categories = Category::whereNotNull('image')->get();

        $bar = $this->output->createProgressBar(count($categories));
        $bar->start();

        foreach ($categories as $category) {
            try {
                if ($this->migrateFile($category->image, $isDryRun)) {
                    $categoriesMigrated++;
                }
            } catch (\Exception $e) {
                $errors[] = "Category {$category->id}: {$e->getMessage()}";
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('=== Migration Summary ===');
        $this->line("Products migrated: {$productsMigrated}");
        $this->line("Categories migrated: {$categoriesMigrated}");

        if (count($errors) > 0) {
            $this->newLine();
            $this->error('Errors encountered:');
            foreach ($errors as $error) {
                $this->line('  ' . $error);
            }
        }

        if ($isDryRun) {
            $this->newLine();
            $this->info('This was a dry run. Run without --dry-run to actually migrate files.');
        }

        return 0;
    }

    private function migrateFile($imagePath, $isDryRun = false)
    {
        // Check if file exists in local storage
        $localPath = storage_path('app/public/' . $imagePath);

        if (!file_exists($localPath)) {
            throw new \Exception("Local file not found: {$localPath}");
        }

        // Check if already exists in S3
        if (Storage::disk('s3')->exists($imagePath)) {
            $this->line("  Already exists in S3: {$imagePath}", 'vvv');
            return false;
        }

        if ($isDryRun) {
            $this->line("  Would migrate: {$imagePath}", 'vvv');
            return true;
        }

        // Upload to S3
        $fileContents = file_get_contents($localPath);
        Storage::disk('s3')->put($imagePath, $fileContents, 'public');

        return true;
    }
}
