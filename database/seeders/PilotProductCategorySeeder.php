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
            ['category_name' => 'Meat',         'description' => 'Butcher product type'],
            ['category_name' => 'Vegetables',   'description' => 'Greengrocer product type'],
            ['category_name' => 'Seafood',      'description' => 'Fishmonger product type'],
            ['category_name' => 'Bakery',       'description' => 'Bakery product type'],
            ['category_name' => 'Delicatessen', 'description' => 'Deli product type'],
        ];

        foreach ($categories as $category) {
            DB::table('product_categories')->updateOrInsert(
                ['category_name' => $category['category_name']],
                [
                    'description' => $category['description'],
                    'is_active'   => 'Y',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]
            );
        }
    }
}