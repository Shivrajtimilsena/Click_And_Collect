<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlist_products', function (Blueprint $table) {
            $table->unsignedBigInteger('wishlist_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamp('added_at')->nullable();

            $table->primary(['wishlist_id', 'product_id'], 'pk_wishlist_products');

            $table->foreign('wishlist_id', 'fk_wlp_wishlist')
                  ->references('wishlist_id')
                  ->on('wishlists')
                  ->onDelete('cascade');

            $table->foreign('product_id', 'fk_wlp_product')
                  ->references('product_id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlist_products');
    }
};