<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('APP_ORDER', function (Blueprint $table) {
            // Make shop_id nullable to support orders from multiple shops
            $table->unsignedBigInteger('shop_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('APP_ORDER', function (Blueprint $table) {
            $table->unsignedBigInteger('shop_id')->nullable(false)->change();
        });
    }
};
