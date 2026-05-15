<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trader_application', function (Blueprint $table) {
            $table->string('password', 255)->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('trader_application', function (Blueprint $table) {
            $table->dropColumn('password');
        });
    }
};
