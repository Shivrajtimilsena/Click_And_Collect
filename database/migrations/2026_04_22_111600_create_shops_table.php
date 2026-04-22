<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->bigIncrements('shop_id');
            $table->unsignedBigInteger('trader_id')->unique();
            $table->string('shop_name', 255)->unique();
            $table->text('description')->nullable();
            $table->date('register_date')->nullable();
            $table->string('is_active', 1)->default('Y');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('trader_id', 'fk_shops_trader')
                  ->references('trader_id')
                  ->on('traders')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};