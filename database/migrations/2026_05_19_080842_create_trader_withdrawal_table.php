<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trader_withdrawal', function (Blueprint $table) {
            $table->bigIncrements('withdrawal_id');
            $table->unsignedBigInteger('trader_id');
            $table->decimal('amount', 12, 2);
            $table->string('paypal_email', 255);
            $table->string('status', 20)->default('PENDING');
            $table->string('paypal_batch_id', 255)->nullable();
            $table->text('admin_notes')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->foreign('trader_id', 'fk_withdrawal_trader')
                ->references('trader_id')->on('trader');

            $table->foreign('processed_by', 'fk_withdrawal_processor')
                ->references('user_id')->on('CC_USER');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trader_withdrawal');
    }
};
