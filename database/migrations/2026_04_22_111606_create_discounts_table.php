<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->bigIncrements('discount_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('discount_percentage', 5, 2);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('is_active', 1)->default('Y');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('product_id', 'fk_discounts_product')
                  ->references('product_id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};