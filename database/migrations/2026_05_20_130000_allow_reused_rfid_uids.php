<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->dropRfidUniqueConstraint();
    }

    public function down(): void
    {
        Schema::table('APP_ORDER', function (Blueprint $table) {
            $table->unique('rfid_uid', 'app_order_rfid_uid_unique');
        });
    }

    private function dropRfidUniqueConstraint(): void
    {
        try {
            Schema::table('APP_ORDER', function (Blueprint $table) {
                $table->dropUnique('app_order_rfid_uid_unique');
            });

            return;
        } catch (\Throwable) {
            // Some Oracle installs expose Laravel unique constraints as indexes.
        }

        try {
            $indexes = DB::select(<<<'SQL'
                SELECT ui.index_name
                FROM user_indexes ui
                JOIN user_ind_columns uic ON ui.index_name = uic.index_name
                WHERE ui.table_name = 'APP_ORDER'
                  AND uic.column_name = 'RFID_UID'
                  AND ui.uniqueness = 'UNIQUE'
            SQL);

            foreach ($indexes as $index) {
                DB::statement('DROP INDEX '.$index->index_name);
            }

            if ($indexes) {
                return;
            }
        } catch (\Throwable) {
            // Fall back to known Laravel-generated names below.
        }

        foreach (['APP_ORDER_RFID_UID_UNIQUE', 'app_order_rfid_uid_unique'] as $indexName) {
            try {
                DB::statement("DROP INDEX {$indexName}");

                return;
            } catch (\Throwable) {
                // Keep trying known generated names.
            }
        }
    }
};
