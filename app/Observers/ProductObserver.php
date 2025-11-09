<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\TelegramNotificationService;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    // Low stock threshold - can be configured
    protected $lowStockThreshold = 5;

    /**
     * Handle the Product "updated" event.
     * Check for low stock after product updates
     */
    public function updated(Product $product)
    {
        // Check if stock quantity was changed
        if ($product->wasChanged('stock_quantity')) {
            $this->checkLowStock($product);
        }
    }

    /**
     * Handle the Product "created" event.
     * Check for low stock when product is created
     */
    public function created(Product $product)
    {
        $this->checkLowStock($product);
    }

    /**
     * Check if product stock is low and send notification
     */
    protected function checkLowStock(Product $product)
    {
        // Only check active products
        if (!$product->is_active) {
            return;
        }

        // Check if stock is at or below threshold
        if ($product->stock_quantity <= $this->lowStockThreshold && $product->stock_quantity >= 0) {
            // Check if we already sent a notification recently (cache for 24 hours)
            $cacheKey = "low_stock_notified_{$product->id}";

            if (cache()->has($cacheKey)) {
                // Already notified recently, skip
                return;
            }

            try {
                $telegramService = new TelegramNotificationService();
                $telegramService->sendLowStockAlert($product);

                // Cache the notification to avoid spam (24 hours)
                cache()->put($cacheKey, true, now()->addHours(24));

                Log::info('Low stock alert sent for product', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'stock_quantity' => $product->stock_quantity
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send low stock Telegram notification: ' . $e->getMessage(), [
                    'product_id' => $product->id
                ]);
            }
        }
    }
}
