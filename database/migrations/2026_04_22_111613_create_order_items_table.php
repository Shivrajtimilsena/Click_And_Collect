<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('line_total', 10, 2);

            $table->primary(['order_id', 'product_id'], 'pk_order_items');

            $table->foreign('order_id', 'fk_oi_order')
                  ->references('order_id')
                  ->on('orders')
                  ->onDelete('cascade');

            // No onDelete('restrict') here — Oracle default behavior is enough.
            $table->foreign('product_id', 'fk_oi_product')
                  ->references('product_id')
                  ->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};