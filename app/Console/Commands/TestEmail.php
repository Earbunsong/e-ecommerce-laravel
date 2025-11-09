<?php

namespace App\Console\Commands;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test order confirmation email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get email from argument or ask for it
        $email = $this->argument('email') ?? $this->ask('What email address should we send the test to?');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address!');
            return 1;
        }

        $this->info('Testing email configuration...');
        $this->newLine();

        // Show current configuration
        $this->info('Current Mail Configuration:');
        $this->line('Driver: ' . config('mail.default'));
        $this->line('Host: ' . config('mail.mailers.smtp.host'));
        $this->line('Port: ' . config('mail.mailers.smtp.port'));
        $this->line('From: ' . config('mail.from.address'));
        $this->newLine();

        // Check if we have a recent order to use
        $order = Order::with('items')->latest()->first();

        if (!$order) {
            $this->warn('No orders found in database!');
            $this->info('Creating a test order...');

            // You could create a dummy order here if needed
            $this->error('Please create at least one order first, then run this command again.');
            return 1;
        }

        $this->info("Using order: {$order->order_number}");
        $this->newLine();

        try {
            // Send the email
            Mail::to($email)->send(new OrderConfirmation($order));

            $this->newLine();
            $this->info('✓ Email sent successfully!');
            $this->line("Check {$email} for the order confirmation email.");
            $this->newLine();

            $this->info('Email Details:');
            $this->line("To: {$email}");
            $this->line("Subject: Order Confirmation #{$order->order_number} - K2 Computer");
            $this->line("Order Total: \${$order->total}");

            return 0;

        } catch (\Exception $e) {
            $this->newLine();
            $this->error('✗ Failed to send email!');
            $this->newLine();
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();

            $this->warn('Common issues:');
            $this->line('1. Invalid SMTP credentials in .env file');
            $this->line('2. MAIL_USERNAME or MAIL_PASSWORD not set');
            $this->line('3. Gmail App Password not generated (if using Gmail)');
            $this->line('4. Firewall blocking SMTP port 587');
            $this->newLine();

            $this->info('Check your .env file:');
            $this->line('MAIL_MAILER=smtp');
            $this->line('MAIL_HOST=smtp.gmail.com');
            $this->line('MAIL_PORT=587');
            $this->line('MAIL_USERNAME=your-email@gmail.com');
            $this->line('MAIL_PASSWORD=your-app-password');
            $this->line('MAIL_ENCRYPTION=tls');

            return 1;
        }
    }
}
