<?php

namespace App\Console\Commands;

use App\Services\TelegramNotificationService;
use Illuminate\Console\Command;

class TestTelegramNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Telegram notification service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Telegram notification service...');

        // Check if Telegram is enabled
        if (!config('services.telegram.enabled')) {
            $this->error('Telegram notifications are disabled in config.');
            $this->info('Please set TELEGRAM_ENABLED=true in your .env file');
            return Command::FAILURE;
        }

        // Check configuration
        if (!config('services.telegram.bot_token')) {
            $this->error('Telegram bot token is not configured.');
            $this->info('Please set TELEGRAM_BOT_TOKEN in your .env file');
            return Command::FAILURE;
        }

        if (!config('services.telegram.channel_id')) {
            $this->error('Telegram channel ID is not configured.');
            $this->info('Please set TELEGRAM_CHANNEL_ID in your .env file');
            return Command::FAILURE;
        }

        $this->info('Configuration looks good!');
        $this->info('Bot Token: ' . substr(config('services.telegram.bot_token'), 0, 20) . '...');
        $this->info('Channel ID: ' . config('services.telegram.channel_id'));
        $this->newLine();

        // Send test notification
        try {
            $telegramService = new TelegramNotificationService();
            $result = $telegramService->sendTestNotification();

            if ($result) {
                $this->info('✓ Test notification sent successfully!');
                $this->info('Check your Telegram channel: ' . config('services.telegram.channel_id'));
                return Command::SUCCESS;
            } else {
                $this->error('✗ Failed to send test notification.');
                $this->warn('Please check your configuration and ensure:');
                $this->line('1. The bot token is valid');
                $this->line('2. The bot has been added to the channel as an administrator');
                $this->line('3. The channel ID is correct (should be @channel_username)');
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('✗ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
