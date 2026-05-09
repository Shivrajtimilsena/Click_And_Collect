<?php

namespace App\Console\Commands;

use Database\Seeders\PilotCollectionSlotSeeder;
use Illuminate\Console\Command;

class GenerateCollectionSlots extends Command
{
    protected $signature = 'slots:generate';

    protected $description = 'Generate fixed collection slots for Wed/Thu/Fri for the next 56 days';

    public function handle(): void
    {
        $this->call(PilotCollectionSlotSeeder::class);
        $this->info('Collection slots generated successfully.');
    }
}
