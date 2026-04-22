<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('full_name', 255);
            $table->string('phone_no', 30)->nullable();
            $table->string('email', 255)->unique();
            $table->integer('age')->nullable();
            $table->string('password', 255);
            $table->date('dob')->nullable();
            $table->string('verification_code', 100)->nullable();
            $table->string('status', 30)->default('PENDING'); // PENDING, ACTIVE, BLOCKED
            $table->string('role', 30)->default('CUSTOMER');  // CUSTOMER, TRADER, ADMIN
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};