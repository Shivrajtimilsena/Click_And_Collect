<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_saved_shop', function (Blueprint $table) {
            $table->bigIncrements('saved_shop_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('shop_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unique(['customer_id', 'shop_id']);

            $table->foreign('customer_id', 'fk_saved_shop_customer')
                ->references('customer_id')
                ->on('customer')
                ->onDelete('cascade');

            $table->foreign('shop_id', 'fk_saved_shop_shop')
                ->references('shop_id')
                ->on('shop')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_saved_shop');
    }
};
