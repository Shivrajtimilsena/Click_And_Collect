<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PilotProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $categories = [
            ['category_name' => 'Artisan Bakery'],
            ['category_name' => 'Local Butcher'],
            ['category_name' => 'Daily Greens'],
            ['category_name' => 'Dairy & Eggs'],
            ['category_name' => 'Fresh Catch'],
            ['category_name' => 'Coffee & Tea'],
            ['category_name' => 'Wine & Spirits'],
            ['category_name' => 'Meat'],
            ['category_name' => 'Vegetables'],
            ['category_name' => 'Seafood'],
            ['category_name' => 'Bakery'],
            ['category_name' => 'Delicatessen'],
        ];

        foreach ($categories as $category) {
            DB::table('product_categories')->updateOrInsert(
                ['category_name' => $category['category_name']],
                [
                    'description' => $category['category_name'] . ' products',
                    'is_active'   => 'Y',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]
            );
        }
    }
}