<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'oracle';

    public function up(): void
    {
        DB::connection('oracle')->statement('ALTER TABLE SHOP DROP CONSTRAINT SHOP_TRADER_ID_UK');
    }

    public function down(): void
    {
        DB::connection('oracle')->statement('ALTER TABLE SHOP ADD CONSTRAINT SHOP_TRADER_ID_UK UNIQUE (TRADER_ID)');
    }
};
