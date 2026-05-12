<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('collection_slot', function (Blueprint $table) {
            $table->unsignedBigInteger('shop_id')->after('collection_slot_id');
            
            $table->foreign('shop_id', 'fk_collection_slots_shop')
                  ->references('shop_id')
                  ->on('shop');
        });
    }

    public function down(): void
    {
        Schema::table('collection_slot', function (Blueprint $table) {
            $table->dropForeignKey('fk_collection_slots_shop');
            $table->dropColumn('shop_id');
        });
    }
};
