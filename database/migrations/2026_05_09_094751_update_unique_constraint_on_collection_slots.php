<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('collection_slot', function (Blueprint $table) {
            $table->dropUnique('uq_slots_date_label');
            $table->unique(['shop_id', 'slot_date', 'slot_label'], 'uq_slots_shop_date_label');
        });
    }

    public function down(): void
    {
        Schema::table('collection_slot', function (Blueprint $table) {
            $table->dropUnique('uq_slots_shop_date_label');
            $table->unique(['slot_date', 'slot_label'], 'uq_slots_date_label');
        });
    }
};
