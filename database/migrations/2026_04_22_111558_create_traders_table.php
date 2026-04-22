<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('traders', function (Blueprint $table) {
            $table->bigIncrements('trader_id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('shop_type', 100)->nullable();
            $table->string('logo_url', 500)->nullable();
            $table->string('is_active', 1)->default('Y');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('user_id', 'fk_traders_user')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traders');
    }
};
