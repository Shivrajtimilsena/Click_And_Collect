<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::connection('oracle')->statement("
            UPDATE CC_USER
            SET CREATED_AT = SYSDATE,
                UPDATED_AT = SYSDATE
            WHERE CREATED_AT IS NULL
        ");
    }

    public function down(): void
    {
    }
};
