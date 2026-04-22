<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_slots', function (Blueprint $table) {
            $table->bigIncrements('collection_slot_id');
            $table->date('slot_date');
            $table->string('slot_day', 30)->nullable();
            $table->string('slot_label', 100); // e.g. 10:00-11:00
            $table->string('start_time', 20)->nullable();
            $table->string('end_time', 20)->nullable();
            $table->integer('capacity')->default(0);
            $table->integer('total_order')->default(0);
            $table->string('is_active', 1)->default('Y');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->unique(['slot_date', 'slot_label'], 'uq_slots_date_label');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_slots');
    }
};