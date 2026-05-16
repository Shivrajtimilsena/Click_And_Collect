<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop', function (Blueprint $table) {
            $table->string('shop_address', 500)->nullable();
            $table->string('shop_image', 500)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('shop', function (Blueprint $table) {
            $table->dropColumn(['shop_address', 'shop_image']);
        });
    }
};
