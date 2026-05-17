<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trader_application', function (Blueprint $table) {
            $table->id('application_id');
            $table->string('shop_name');
            $table->string('email');
            $table->string('location');
            $table->text('speciality');
            $table->text('description');
            $table->string('status', 20)->default('PENDING');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trader_application');
    }
};
