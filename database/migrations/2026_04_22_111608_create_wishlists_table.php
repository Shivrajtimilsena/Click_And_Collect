<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->bigIncrements('wishlist_id');
            $table->unsignedBigInteger('customer_id')->unique();
            $table->integer('no_of_items')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('customer_id', 'fk_wishlists_customer')
                  ->references('customer_id')
                  ->on('customers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};