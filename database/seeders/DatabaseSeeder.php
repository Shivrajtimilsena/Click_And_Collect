<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PilotUsersAndRolesSeeder::class,
            PilotShopsSeeder::class,
            PilotProductCategorySeeder::class,
            PilotCollectionSlotSeeder::class,
            PilotCouponSeeder::class,
            PilotProductSeeder::class,
        ]);
    }
}