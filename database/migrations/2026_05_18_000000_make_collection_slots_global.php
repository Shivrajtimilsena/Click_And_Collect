<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('collection_slot', function ($table) {
            $table->dropForeign('fk_collection_slots_shop');
        });

        Schema::table('collection_slot', function ($table) {
            $table->dropUnique('uq_slots_shop_date_label');
            $table->dropColumn('shop_id');
            $table->unique(['slot_date', 'slot_label'], 'uq_slots_date_label');
        });
    }

    public function down(): void
    {
        Schema::table('collection_slot', function ($table) {
            $table->dropUnique('uq_slots_date_label');
            $table->unsignedBigInteger('shop_id')->after('collection_slot_id');
            $table->foreign('shop_id', 'fk_collection_slots_shop')
                  ->references('shop_id')
                  ->on('shop');
            $table->unique(['shop_id', 'slot_date', 'slot_label'], 'uq_slots_shop_date_label');
        });
    }
};
