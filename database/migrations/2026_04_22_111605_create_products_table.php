<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('product_category_id');
            $table->string('product_name', 255);
            $table->text('description')->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('price', 10, 2);
            $table->integer('amount')->default(0);
            $table->integer('max_order')->nullable();
            $table->integer('min_order')->default(1);
            $table->date('add_date')->nullable();
            $table->date('update_date')->nullable();
            $table->string('product_status', 30)->default('ACTIVE');
            $table->string('image_url', 500)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('shop_id', 'fk_products_shop')
                  ->references('shop_id')
                  ->on('shops')
                  ->onDelete('cascade');

            // No onDelete('restrict') here — Oracle default behavior is enough.
            $table->foreign('product_category_id', 'fk_products_cat')
                  ->references('product_category_id')
                  ->on('product_categories');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};