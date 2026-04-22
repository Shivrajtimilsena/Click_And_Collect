<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PilotProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $shops = [
            'Heritage Butcher'      => DB::table('shops')->where('shop_name', 'Heritage Butcher')->value('shop_id'),
            'Green Basket'          => DB::table('shops')->where('shop_name', 'Green Basket')->value('shop_id'),
            'Sea Fresh Fishmonger'  => DB::table('shops')->where('shop_name', 'Sea Fresh Fishmonger')->value('shop_id'),
            'Old Town Bakery'       => DB::table('shops')->where('shop_name', 'Old Town Bakery')->value('shop_id'),
            'Local Delicatessen'    => DB::table('shops')->where('shop_name', 'Local Delicatessen')->value('shop_id'),
        ];

        $categories = [
            'Meat'         => DB::table('product_categories')->where('category_name', 'Meat')->value('product_category_id'),
            'Vegetables'   => DB::table('product_categories')->where('category_name', 'Vegetables')->value('product_category_id'),
            'Seafood'      => DB::table('product_categories')->where('category_name', 'Seafood')->value('product_category_id'),
            'Bakery'       => DB::table('product_categories')->where('category_name', 'Bakery')->value('product_category_id'),
            'Delicatessen' => DB::table('product_categories')->where('category_name', 'Delicatessen')->value('product_category_id'),
        ];

        $products = [
            [
                'shop_id'               => $shops['Heritage Butcher'],
                'product_category_id'   => $categories['Meat'],
                'product_name'          => 'Fresh Chicken Breast',
                'description'           => 'Skinless chicken breast portions.',
                'price'                 => 6.50,
                'quantity_per_item'     => '500g pack',
                'allergy_information'   => 'None',
                'stock'                 => 40,
                'min_order'             => 1,
                'max_order'             => 5,
            ],
            [
                'shop_id'               => $shops['Heritage Butcher'],
                'product_category_id'   => $categories['Meat'],
                'product_name'          => 'Lamb Chops',
                'description'           => 'Fresh lamb chops cut to order.',
                'price'                 => 9.75,
                'quantity_per_item'     => '500g pack',
                'allergy_information'   => 'None',
                'stock'                 => 25,
                'min_order'             => 1,
                'max_order'             => 4,
            ],
            [
                'shop_id'               => $shops['Green Basket'],
                'product_category_id'   => $categories['Vegetables'],
                'product_name'          => 'Carrots',
                'description'           => 'Fresh locally sourced carrots.',
                'price'                 => 1.80,
                'quantity_per_item'     => '1kg bag',
                'allergy_information'   => 'None',
                'stock'                 => 60,
                'min_order'             => 1,
                'max_order'             => 10,
            ],
            [
                'shop_id'               => $shops['Green Basket'],
                'product_category_id'   => $categories['Vegetables'],
                'product_name'          => 'Baby Spinach',
                'description'           => 'Fresh washed spinach leaves.',
                'price'                 => 2.30,
                'quantity_per_item'     => '250g pack',
                'allergy_information'   => 'None',
                'stock'                 => 35,
                'min_order'             => 1,
                'max_order'             => 8,
            ],
            [
                'shop_id'               => $shops['Sea Fresh Fishmonger'],
                'product_category_id'   => $categories['Seafood'],
                'product_name'          => 'Salmon Fillet',
                'description'           => 'Fresh salmon fillet portions.',
                'price'                 => 8.90,
                'quantity_per_item'     => '2 fillets pack',
                'allergy_information'   => 'Fish',
                'stock'                 => 30,
                'min_order'             => 1,
                'max_order'             => 5,
            ],
            [
                'shop_id'               => $shops['Sea Fresh Fishmonger'],
                'product_category_id'   => $categories['Seafood'],
                'product_name'          => 'King Prawns',
                'description'           => 'Raw king prawns ready to cook.',
                'price'                 => 7.20,
                'quantity_per_item'     => '300g pack',
                'allergy_information'   => 'Crustaceans',
                'stock'                 => 20,
                'min_order'             => 1,
                'max_order'             => 4,
            ],
            [
                'shop_id'               => $shops['Old Town Bakery'],
                'product_category_id'   => $categories['Bakery'],
                'product_name'          => 'Sourdough Bread',
                'description'           => 'Freshly baked sourdough loaf.',
                'price'                 => 3.50,
                'quantity_per_item'     => '1 loaf',
                'allergy_information'   => 'Contains gluten',
                'stock'                 => 50,
                'min_order'             => 1,
                'max_order'             => 6,
            ],
            [
                'shop_id'               => $shops['Old Town Bakery'],
                'product_category_id'   => $categories['Bakery'],
                'product_name'          => 'Butter Croissant',
                'description'           => 'Flaky butter croissant.',
                'price'                 => 1.60,
                'quantity_per_item'     => '1 piece',
                'allergy_information'   => 'Contains gluten, milk, egg',
                'stock'                 => 80,
                'min_order'             => 1,
                'max_order'             => 12,
            ],
            [
                'shop_id'               => $shops['Local Delicatessen'],
                'product_category_id'   => $categories['Delicatessen'],
                'product_name'          => 'Cheddar Cheese',
                'description'           => 'Mature cheddar block.',
                'price'                 => 4.90,
                'quantity_per_item'     => '250g block',
                'allergy_information'   => 'Milk',
                'stock'                 => 40,
                'min_order'             => 1,
                'max_order'             => 6,
            ],
            [
                'shop_id'               => $shops['Local Delicatessen'],
                'product_category_id'   => $categories['Delicatessen'],
                'product_name'          => 'Olives Mix',
                'description'           => 'Mixed marinated olives.',
                'price'                 => 3.20,
                'quantity_per_item'     => '200g tub',
                'allergy_information'   => 'May contain sulphites',
                'stock'                 => 30,
                'min_order'             => 1,
                'max_order'             => 6,
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->updateOrInsert(
                [
                    'shop_id'      => $product['shop_id'],
                    'product_name' => $product['product_name'],
                ],
                [
                    'product_category_id' => $product['product_category_id'],
                    'description'         => $product['description'],
                    'price'               => $product['price'],
                    'quantity_per_item'   => $product['quantity_per_item'],
                    'allergy_information' => $product['allergy_information'],
                    'stock'               => $product['stock'],
                    'amount'              => $product['price'],
                    'max_order'           => $product['max_order'],
                    'min_order'           => $product['min_order'],
                    'add_date'            => now()->toDateString(),
                    'update_date'         => now()->toDateString(),
                    'product_status'      => 'ACTIVE',
                    'image_url'           => null,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ]
            );
        }
    }
}