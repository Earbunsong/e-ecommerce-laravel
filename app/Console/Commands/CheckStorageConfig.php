<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class CheckStorageConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check storage configuration and debug S3 setup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Storage Configuration Debug ===');
        $this->newLine();

        // 1. Check environment variables
        $this->info('1. Environment Configuration:');
        $this->line('   FILESYSTEM_DISK: ' . (env('FILESYSTEM_DISK') ?: 'not set (defaults to local)'));
        $this->line('   APP_URL: ' . env('APP_URL'));
        $this->line('   AWS_BUCKET: ' . (env('AWS_BUCKET') ? '✓ Set' : '✗ Not set'));
        $this->line('   AWS_REGION: ' . (env('AWS_DEFAULT_REGION') ?: '✗ Not set'));
        $this->line('   AWS_URL: ' . (env('AWS_URL') ?: '✗ Not set'));
        $this->line('   AWS_ACCESS_KEY_ID: ' . (env('AWS_ACCESS_KEY_ID') ? '✓ Set' : '✗ Not set'));
        $this->line('   AWS_SECRET_ACCESS_KEY: ' . (env('AWS_SECRET_ACCESS_KEY') ? '✓ Set (hidden)' : '✗ Not set'));
        $this->newLine();

        // 2. Check disk configuration
        $this->info('2. Disk Configuration:');
        $defaultDisk = config('filesystems.default');
        $publicDiskConfig = config('filesystems.disks.public');

        $this->line('   Default disk: ' . $defaultDisk);
        $this->line('   Public disk driver: ' . $publicDiskConfig['driver']);

        if ($publicDiskConfig['driver'] === 's3') {
            $this->line('   ✓ Public disk is using S3');
            $this->line('   S3 URL: ' . ($publicDiskConfig['url'] ?? 'not set'));
        } else {
            $this->line('   ✓ Public disk is using LOCAL storage');
            $this->line('   Local URL: ' . ($publicDiskConfig['url'] ?? 'not set'));
        }
        $this->newLine();

        // 3. Test Storage::url() generation
        $this->info('3. URL Generation Test:');
        $testPath = 'products/test-image.jpg';
        try {
            $url = Storage::disk('public')->url($testPath);
            $this->line('   Test path: ' . $testPath);
            $this->line('   Generated URL: ' . $url);

            if (str_contains($url, 's3') || str_contains($url, 'amazonaws')) {
                $this->line('   ✓ URLs are generating for S3');
            } else if (str_contains($url, '/storage/')) {
                $this->line('   ⚠ URLs are generating for LOCAL storage');
            }
        } catch (\Exception $e) {
            $this->error('   ✗ Error generating URL: ' . $e->getMessage());
        }
        $this->newLine();

        // 4. Test with actual product
        $this->info('4. Real Product Image URL:');
        $product = Product::whereNotNull('image')->first();

        if ($product) {
            $this->line('   Product: ' . $product->name);
            $this->line('   Image path in DB: ' . $product->image);
            $this->line('   Generated image_url: ' . $product->image_url);

            if (str_contains($product->image_url, 's3') || str_contains($product->image_url, 'amazonaws')) {
                $this->info('   ✓ Product images are using S3 URLs');
            } else {
                $this->warn('   ⚠ Product images are still using local URLs');
            }
        } else {
            $this->warn('   No products found with images');
        }
        $this->newLine();

        // 5. Recommendations
        $this->info('5. Recommendations:');
        if (env('FILESYSTEM_DISK') !== 's3') {
            $this->warn('   → Set FILESYSTEM_DISK=s3 in your Laravel Cloud environment');
        }
        if (!env('AWS_BUCKET')) {
            $this->warn('   → Configure AWS_BUCKET in your Laravel Cloud environment');
        }
        if (!env('AWS_URL')) {
            $this->warn('   → Configure AWS_URL in your Laravel Cloud environment');
        }
        if (!env('AWS_ACCESS_KEY_ID') || !env('AWS_SECRET_ACCESS_KEY')) {
            $this->warn('   → Configure AWS credentials in your Laravel Cloud environment');
        }

        if (env('FILESYSTEM_DISK') === 's3' && env('AWS_BUCKET') && env('AWS_URL')) {
            $this->info('   ✓ S3 configuration looks good!');
        }

        return 0;
    }
}
