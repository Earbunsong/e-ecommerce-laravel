<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SetupProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:product-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy seeded product images from public/images to storage for production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up product images...');

        // Source and destination paths
        $sourcePath = public_path('images/products');
        $destinationPath = storage_path('app/public/products');

        // Check if source directory exists
        if (!File::exists($sourcePath)) {
            $this->error('Source directory does not exist: ' . $sourcePath);
            return 1;
        }

        // Create destination directory if it doesn't exist
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
            $this->info('Created destination directory: ' . $destinationPath);
        }

        // Get all image files from source
        $files = File::files($sourcePath);
        $copiedCount = 0;
        $skippedCount = 0;

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $destination = $destinationPath . '/' . $filename;

            // Only copy if file doesn't exist in destination
            if (!File::exists($destination)) {
                File::copy($file->getPathname(), $destination);
                $this->line('Copied: ' . $filename);
                $copiedCount++;
            } else {
                $this->line('Skipped (already exists): ' . $filename);
                $skippedCount++;
            }
        }

        $this->newLine();
        $this->info("Setup complete!");
        $this->info("Copied: {$copiedCount} files");
        $this->info("Skipped: {$skippedCount} files");

        $this->newLine();
        $this->warn('Remember to run: php artisan storage:link');

        return 0;
    }
}
