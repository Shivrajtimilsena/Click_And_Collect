<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('notifications') && ! Schema::hasTable('notification')) {
            Schema::rename('notifications', 'notification');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('notification') && ! Schema::hasTable('notifications')) {
            Schema::rename('notification', 'notifications');
        }
    }
};
