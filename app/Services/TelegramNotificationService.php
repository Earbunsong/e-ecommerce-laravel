<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotificationService
{
    protected $botToken;
    protected $channelId;
    protected $enabled;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->channelId = config('services.telegram.channel_id');
        $this->enabled = config('services.telegram.enabled', false);
    }

    /**
     * Send a message to the Telegram channel
     */
    protected function sendMessage(string $message, string $parseMode = 'HTML')
    {
        if (!$this->enabled) {
            Log::info('Telegram notifications are disabled');
            return false;
        }

        if (!$this->botToken || !$this->channelId) {
            Log::error('Telegram bot token or channel ID not configured');
            return false;
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $this->channelId,
                'text' => $message,
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => true,
            ]);

            if ($response->successful()) {
                Log::info('Telegram notification sent successfully');
                return true;
            } else {
                Log::error('Telegram API error: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Failed to send Telegram notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send new order notification
     */
    public function sendNewOrderNotification(Order $order)
    {
        $itemsCount = $order->items->count();
        $totalItems = $order->items->sum('quantity');

        // Get items list
        $itemsList = $order->items->map(function ($item) {
            return "  • {$item->product_name} x{$item->quantity} - \${$item->subtotal}";
        })->implode("\n");

        $customerName = $order->customer_name ?? 'N/A';
        $customerPhone = $order->customer_info['phone'] ?? 'N/A';
        $customerEmail = $order->customer_info['email'] ?? 'N/A';

        $message = "🛍️ <b>NEW ORDER RECEIVED!</b>\n\n";
        $message .= "📦 Order: <b>#{$order->order_number}</b>\n";
        $message .= "👤 Customer: {$customerName}\n";
        $message .= "📱 Phone: {$customerPhone}\n";
        $message .= "📧 Email: {$customerEmail}\n";
        $message .= "💳 Payment: " . strtoupper($order->payment_method) . "\n";
        $message .= "📊 Status: " . ucfirst($order->status) . "\n\n";

        $message .= "<b>Order Items ({$totalItems} items):</b>\n";
        $message .= $itemsList . "\n\n";

        $message .= "💰 Subtotal: \${$order->subtotal}\n";
        if ($order->tax > 0) {
            $message .= "🏦 Tax: \${$order->tax}\n";
        }
        if ($order->shipping > 0) {
            $message .= "🚚 Shipping: \${$order->shipping}\n";
        }
        $message .= "💵 <b>Total: \${$order->total}</b>\n\n";

        $message .= "🕒 " . $order->created_at->format('d M Y, h:i A');

        return $this->sendMessage($message);
    }

    /**
     * Send payment received notification
     */
    public function sendPaymentReceivedNotification(Payment $payment)
    {
        $order = $payment->order;
        $customerName = $order->customer_name ?? 'N/A';

        $message = "✅ <b>PAYMENT RECEIVED!</b>\n\n";
        $message .= "📦 Order: <b>#{$order->order_number}</b>\n";
        $message .= "👤 Customer: {$customerName}\n";
        $message .= "💳 Method: " . strtoupper($payment->payment_method) . "\n";
        $message .= "💵 Amount: \${$payment->amount}\n";

        if ($payment->transaction_id) {
            $message .= "🔑 Transaction ID: {$payment->transaction_id}\n";
        }

        $message .= "📊 Status: " . ucfirst($payment->status) . "\n\n";
        $message .= "🕒 " . $payment->created_at->format('d M Y, h:i A');

        return $this->sendMessage($message);
    }

    /**
     * Send low stock alert notification
     */
    public function sendLowStockAlert(Product $product)
    {
        $message = "⚠️ <b>LOW STOCK ALERT!</b>\n\n";
        $message .= "📦 Product: <b>{$product->name}</b>\n";
        $message .= "🔖 SKU: {$product->sku}\n";
        $message .= "📊 Current Stock: <b>{$product->stock_quantity} units</b>\n";
        $message .= "🏷️ Category: {$product->category_name}\n";

        if ($product->brand) {
            $message .= "🏢 Brand: {$product->brand}\n";
        }

        $message .= "💰 Price: \${$product->price}\n\n";
        $message .= "⚡ Action Required: Please restock this item!";

        return $this->sendMessage($message);
    }

    /**
     * Send order status change notification
     */
    public function sendOrderStatusChange(Order $order, string $oldStatus, string $newStatus)
    {
        $statusEmoji = [
            'pending' => '⏳',
            'processing' => '🔄',
            'shipped' => '📦',
            'delivered' => '✅',
            'cancelled' => '❌',
        ];

        $emoji = $statusEmoji[$newStatus] ?? '📋';
        $customerName = $order->customer_name ?? 'N/A';

        $message = "{$emoji} <b>ORDER STATUS UPDATED</b>\n\n";
        $message .= "📦 Order: <b>#{$order->order_number}</b>\n";
        $message .= "👤 Customer: {$customerName}\n";
        $message .= "📊 Status: " . ucfirst($oldStatus) . " → <b>" . ucfirst($newStatus) . "</b>\n";
        $message .= "💵 Total: \${$order->total}\n\n";
        $message .= "🕒 " . now()->format('d M Y, h:i A');

        return $this->sendMessage($message);
    }

    /**
     * Send payment failed notification
     */
    public function sendPaymentFailedNotification(Order $order, string $reason = '')
    {
        $customerName = $order->customer_name ?? 'N/A';

        $message = "❌ <b>PAYMENT FAILED!</b>\n\n";
        $message .= "📦 Order: <b>#{$order->order_number}</b>\n";
        $message .= "👤 Customer: {$customerName}\n";
        $message .= "💳 Method: " . strtoupper($order->payment_method) . "\n";
        $message .= "💵 Amount: \${$order->total}\n";

        if ($reason) {
            $message .= "⚠️ Reason: {$reason}\n";
        }

        $message .= "\n🕒 " . now()->format('d M Y, h:i A');

        return $this->sendMessage($message);
    }

    /**
     * Send daily summary notification
     */
    public function sendDailySummary(array $data)
    {
        $message = "📊 <b>DAILY SUMMARY</b>\n";
        $message .= "📅 " . now()->format('d M Y') . "\n\n";

        $message .= "🛍️ Total Orders: <b>{$data['total_orders']}</b>\n";
        $message .= "💰 Total Revenue: <b>\${$data['total_revenue']}</b>\n";
        $message .= "✅ Completed: {$data['completed_orders']}\n";
        $message .= "⏳ Pending: {$data['pending_orders']}\n";

        if (isset($data['pending_payments']) && $data['pending_payments'] > 0) {
            $message .= "💳 Pending Payments: {$data['pending_payments']}\n";
        }

        if (isset($data['low_stock_count']) && $data['low_stock_count'] > 0) {
            $message .= "⚠️ Low Stock Items: {$data['low_stock_count']}\n";
        }

        return $this->sendMessage($message);
    }

    /**
     * Send test notification
     */
    public function sendTestNotification()
    {
        $message = "🤖 <b>K2 Computer - Telegram Bot Test</b>\n\n";
        $message .= "✅ Your Telegram notification system is working correctly!\n\n";
        $message .= "📱 Bot: @st10_notify_k2_bot\n";
        $message .= "📢 Channel: @st10_k2_channel\n\n";
        $message .= "🕒 " . now()->format('d M Y, h:i A');

        return $this->sendMessage($message);
    }
}
