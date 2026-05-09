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
            PilotProductSeeder::class,
            PilotCouponSeeder::class,
            PilotCollectionSlotSeeder::class,
        ]);
    }
}
