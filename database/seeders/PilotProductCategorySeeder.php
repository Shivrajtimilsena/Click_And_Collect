<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PilotProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $categories = [
            ['category_name' => 'Meat',          'description' => 'Fresh meat and poultry'],
            ['category_name' => 'Vegetables',     'description' => 'Seasonal vegetables and salads'],
            ['category_name' => 'Fruit',          'description' => 'Fresh fruit and berries'],
            ['category_name' => 'Seafood',        'description' => 'Fresh fish and shellfish'],
            ['category_name' => 'Bakery',         'description' => 'Bread, pastries, and cakes'],
            ['category_name' => 'Delicatessen',   'description' => 'Cheese, charcuterie, and speciality foods'],
            ['category_name' => 'Dairy & Eggs',   'description' => 'Milk, eggs, butter, and cream'],
            ['category_name' => 'Pantry',         'description' => 'Oils, preserves, and store-cupboard essentials'],
        ];

        foreach ($categories as $category) {
            DB::table('product_categories')->updateOrInsert(
                ['category_name' => $category['category_name']],
                [
                    'description' => $category['description'],
                    'is_active' => 'Y',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
