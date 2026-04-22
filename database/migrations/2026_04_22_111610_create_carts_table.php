<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('cart_id');
            $table->unsignedBigInteger('customer_id')->unique();
            $table->integer('item_numbers')->default(0);
            $table->date('add_date')->nullable();
            $table->string('cart_status', 20)->default('OPEN');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('customer_id', 'fk_carts_customer')
                  ->references('customer_id')
                  ->on('customers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};