<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\TelegramNotificationService;
use Illuminate\Console\Command;

class TestLowStockAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:test-lowstock {product_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Telegram low stock alert notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $productId = $this->argument('product_id');

        if ($productId) {
            // Test with specific product
            $product = Product::find($productId);

            if (!$product) {
                $this->error("Product with ID {$productId} not found.");
                return Command::FAILURE;
            }
        } else {
            // Find any product with low stock (≤5 units) or use first product
            $product = Product::where('stock_quantity', '<=', 5)
                ->where('is_active', true)
                ->first();

            if (!$product) {
                // No low stock products, use first available product
                $product = Product::where('is_active', true)->first();

                if (!$product) {
                    $this->error('No active products found in the database.');
                    return Command::FAILURE;
                }

                $this->warn("No low stock products found. Using product: {$product->name}");
            }
        }

        $this->info("Testing low stock alert for:");
        $this->info("Product: {$product->name}");
        $this->info("SKU: {$product->sku}");
        $this->info("Current Stock: {$product->stock_quantity} units");
        $this->info("Category: {$product->category_name}");
        $this->newLine();

        // Send notification
        try {
            $this->info('Sending Telegram low stock alert...');
            $telegramService = new TelegramNotificationService();
            $result = $telegramService->sendLowStockAlert($product);

            if ($result) {
                $this->info('✓ Low stock alert sent successfully!');
                $this->info('Check your Telegram channel: @st10_k2_channel');
                $this->newLine();
                $this->info('💡 TIP: Low stock alerts are automatically sent when:');
                $this->line('   - Product stock is updated to ≤5 units');
                $this->line('   - Product is created with stock ≤5 units');
                $this->line('   - Only sent once per 24 hours per product (to prevent spam)');
                return Command::SUCCESS;
            } else {
                $this->error('✗ Failed to send notification.');
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('✗ Error: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}
