<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_products', function (Blueprint $table) {
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1);
            $table->decimal('price_at_add', 10, 2)->nullable();

            $table->primary(['cart_id', 'product_id'], 'pk_cart_products');

            $table->foreign('cart_id', 'fk_cp_cart')
                  ->references('cart_id')
                  ->on('carts')
                  ->onDelete('cascade');

            $table->foreign('product_id', 'fk_cp_product')
                  ->references('product_id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_products');
    }
};