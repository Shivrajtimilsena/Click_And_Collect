<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product', function (Blueprint $table) {
            $table->string('approval_status', 30)->default('APPROVED');
        });

        DB::table('product')
            ->whereNull('approval_status')
            ->update(['approval_status' => 'APPROVED']);
    }

    public function down(): void
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn('approval_status');
        });
    }
};
