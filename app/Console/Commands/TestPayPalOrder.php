<?php

namespace App\Console\Commands;

use App\Services\PayPalService;
use Illuminate\Console\Command;

class TestPayPalOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paypal:test-order {amount=9.99 : The order amount to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test PayPal order creation';

    /**
     * Execute the console command.
     */
    public function handle(PayPalService $paypalService): int
    {
        $amount = (float) $this->argument('amount');

        $this->info("Testing PayPal order creation with amount: £{$amount}");
        $this->newLine();

        try {
            $order = $paypalService->createOrder($amount, config('paypal.currency'));

            $this->info('✓ Order created successfully');
            $this->line('Order ID: '.$order['id']);
            $this->line('Status: '.$order['status']);
            
            if (isset($order['purchase_units'][0]['amount'])) {
                $this->line('Currency: '.$order['purchase_units'][0]['amount']['currency_code']);
                $this->line('Amount: '.$order['purchase_units'][0]['amount']['value']);
            }
            
            $this->newLine();
            $this->info('Full Response:');
            $this->line(json_encode($order, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            $this->newLine();
            $this->info('Test successful! PayPal integration is working properly.');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('✗ Order creation failed');
            $this->line('Error: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
