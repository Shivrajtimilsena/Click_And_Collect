<?php

namespace App\Console\Commands;

use App\Services\PayPalService;
use Illuminate\Console\Command;

class TestPayPalConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paypal:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test PayPal API connection and credentials';

    /**
     * Execute the console command.
     */
    public function handle(PayPalService $paypalService): int
    {
        $this->info('Testing PayPal connection...');
        $this->newLine();

        $result = $paypalService->testConnection();

        if ($result['status'] === 'success') {
            $this->info('✓ PayPal Connection Successful');
            $this->line('Mode: '.$result['mode']);
            $this->line('Base URL: '.$result['base_url']);
            
            return self::SUCCESS;
        } else {
            $this->error('✗ PayPal Connection Failed');
            $this->line('Error: '.$result['message']);
            $this->line('Mode: '.$result['mode']);
            $this->line('Base URL: '.$result['base_url']);
            
            return self::FAILURE;
        }
    }
}
