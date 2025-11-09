<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\TelegramNotificationService;
use Illuminate\Console\Command;

class TestOrderNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:test-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Telegram order notification with a real order from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Looking for a recent order with items...');

        // Find the most recent order with items
        $order = Order::with('items')
            ->has('items')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$order) {
            $this->error('No orders found in the database.');
            $this->info('Please create an order first, then run this command again.');
            return Command::FAILURE;
        }

        $this->info("Found order: #{$order->order_number}");
        $this->info("Customer: {$order->customer_name}");
        $this->info("Items: {$order->items->count()}");
        $this->info("Total: \${$order->total}");
        $this->newLine();

        // Send notification
        try {
            $this->info('Sending Telegram notification...');
            $telegramService = new TelegramNotificationService();
            $result = $telegramService->sendNewOrderNotification($order);

            if ($result) {
                $this->info('✓ Order notification sent successfully!');
                $this->info('Check your Telegram channel to see the formatted message with order items.');
                return Command::SUCCESS;
            } else {
                $this->error('✗ Failed to send notification.');
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('✗ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
