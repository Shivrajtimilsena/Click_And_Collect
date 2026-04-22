<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('payment_id');
            $table->unsignedBigInteger('order_id')->unique();
            $table->timestamp('payment_date')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('payment_method', 50)->nullable(); // PAYPAL, CASH, etc.
            $table->string('payment_status', 30)->default('PENDING');
            $table->string('paypal_txn_id', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('order_id', 'fk_payments_order')
                  ->references('order_id')
                  ->on('orders')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};