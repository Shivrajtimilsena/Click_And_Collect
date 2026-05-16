<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::connection('oracle')->statement("CREATE OR REPLACE SYNONYM \"user\" FOR CC_USER");
        DB::connection('oracle')->statement("CREATE OR REPLACE SYNONYM \"order\" FOR CC_ORDER");
    }

    public function down(): void
    {
        DB::connection('oracle')->statement("DROP SYNONYM \"user\"");
        DB::connection('oracle')->statement("DROP SYNONYM \"order\"");
    }
};
