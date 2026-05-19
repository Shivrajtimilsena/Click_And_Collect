<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trader_withdrawal', function (Blueprint $table) {
            $table->string('paypal_batch_status', 50)->nullable()->after('paypal_batch_id');
        });
    }

    public function down(): void
    {
        Schema::table('trader_withdrawal', function (Blueprint $table) {
            $table->dropColumn('paypal_batch_status');
        });
    }
};
