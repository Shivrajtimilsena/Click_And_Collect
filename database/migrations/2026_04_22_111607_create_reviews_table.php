<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('review_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('review_rating', 2, 1)->default(0);
            $table->text('review')->nullable();
            $table->date('review_date')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('customer_id', 'fk_reviews_customer')
                  ->references('customer_id')
                  ->on('customers')
                  ->onDelete('cascade');

            $table->foreign('product_id', 'fk_reviews_product')
                  ->references('product_id')
                  ->on('products')
                  ->onDelete('cascade');

            $table->unique(['customer_id', 'product_id'], 'uq_reviews_cust_prod');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};