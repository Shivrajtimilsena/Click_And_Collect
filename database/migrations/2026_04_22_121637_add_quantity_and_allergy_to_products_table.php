<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('quantity_per_item', 100)->nullable()->after('price');
            $table->text('allergy_information')->nullable()->after('quantity_per_item');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['quantity_per_item', 'allergy_information']);
        });
    }
};