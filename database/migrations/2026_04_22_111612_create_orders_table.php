<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('collection_slot_id')->nullable();
            $table->unsignedBigInteger('cart_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->timestamp('order_date')->nullable();
            $table->decimal('order_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('order_status', 30)->default('PENDING');
            $table->string('payment_status', 30)->default('UNPAID');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('customer_id', 'fk_orders_customer')
                  ->references('customer_id')
                  ->on('customers')
                  ->onDelete('cascade');

            // No onDelete('restrict') here — Oracle default behavior is enough.
            $table->foreign('shop_id', 'fk_orders_shop')
                  ->references('shop_id')
                  ->on('shops');

            $table->foreign('collection_slot_id', 'fk_orders_slot')
                  ->references('collection_slot_id')
                  ->on('collection_slots')
                  ->onDelete('set null');

            $table->foreign('cart_id', 'fk_orders_cart')
                  ->references('cart_id')
                  ->on('carts')
                  ->onDelete('set null');

            $table->foreign('coupon_id', 'fk_orders_coupon')
                  ->references('coupon_id')
                  ->on('coupons')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};