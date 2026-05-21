<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PilotProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $shops = [
            'Hearth & Cleaver Traditional Meats' => DB::table('shop')->where('shop_name', 'Hearth & Cleaver Traditional Meats')->value('shop_id'),
            'Golden Poultry' => DB::table('shop')->where('shop_name', 'Golden Poultry')->value('shop_id'),
            'Old Orchard Produce' => DB::table('shop')->where('shop_name', 'Old Orchard Produce')->value('shop_id'),
            'Green Valley' => DB::table('shop')->where('shop_name', 'Green Valley')->value('shop_id'),
            'Heritage Catch' => DB::table('shop')->where('shop_name', 'Heritage Catch')->value('shop_id'),
            'The Fish Market' => DB::table('shop')->where('shop_name', 'The Fish Market')->value('shop_id'),
            'Stoneground Flour & Grain' => DB::table('shop')->where('shop_name', 'Stoneground Flour & Grain')->value('shop_id'),
            'fresh fruti center' => DB::table('shop')->where('shop_name', 'fresh fruti center')->value('shop_id'),
            'The Cleckhuddersfax Larder' => DB::table('shop')->where('shop_name', 'The Cleckhuddersfax Larder')->value('shop_id'),
            'The Cheese Dairy' => DB::table('shop')->where('shop_name', 'The Cheese Dairy')->value('shop_id'),
        ];

        $categories = [
            'Meat' => DB::table('product_category')->where('category_name', 'Meat')->value('product_category_id'),
            'Vegetables' => DB::table('product_category')->where('category_name', 'Vegetables')->value('product_category_id'),
            'Fruit' => DB::table('product_category')->where('category_name', 'Fruit')->value('product_category_id'),
            'Seafood' => DB::table('product_category')->where('category_name', 'Seafood')->value('product_category_id'),
            'Bakery' => DB::table('product_category')->where('category_name', 'Bakery')->value('product_category_id'),
            'Delicatessen' => DB::table('product_category')->where('category_name', 'Delicatessen')->value('product_category_id'),
            'Dairy & Eggs' => DB::table('product_category')->where('category_name', 'Dairy & Eggs')->value('product_category_id'),
            'Pantry' => DB::table('product_category')->where('category_name', 'Pantry')->value('product_category_id'),
        ];

        $legacyDailyLoafId = DB::table('shop')->where('shop_name', 'The Daily Loaf')->value('shop_id');
        $shopIds = array_values($shops);

        if ($legacyDailyLoafId) {
            $shopIds[] = $legacyDailyLoafId;
        }

        $productIds = DB::table('product')
            ->whereIn('shop_id', $shopIds)
            ->pluck('product_id');

        if ($productIds->isNotEmpty()) {
            $orderIds = DB::table('order_item')
                ->whereIn('product_id', $productIds)
                ->pluck('order_id')
                ->unique();

            if ($orderIds->isNotEmpty()) {
                DB::table('payment')->whereIn('order_id', $orderIds)->delete();
                DB::table('order_item')->whereIn('order_id', $orderIds)->delete();
                DB::table('APP_ORDER')->whereIn('order_id', $orderIds)->delete();
            }
        }

        DB::table('product')
            ->whereIn('shop_id', $shopIds)
            ->delete();

        $products = [
            // Hearth & Cleaver Traditional Meats — 2 products
            [
                'shop_id' => $shops['Hearth & Cleaver Traditional Meats'],
                'product_category_id' => $categories['Meat'],
                'product_name' => 'Dry Aged Prime Boneless Ribeye Steak - 2 pcs _ 1 inch thick (1)',
                'description' => '28-day dry-aged ribeye, expertly trimmed and cut to order. Rich marbling for exceptional flavour.',
                'price' => 18.50,
                'quantity_per_item' => 'approx. 300g',
                'allergy_information' => 'None',
                'stock' => 20,
                'min_order' => 1,
                'max_order' => 6,
                'image_url' => '/images/products/Dry Aged Prime Boneless Ribeye Steak - 2 pcs _ 1 inch thick (1).png',
            ],
            [
                'shop_id' => $shops['Hearth & Cleaver Traditional Meats'],
                'product_category_id' => $categories['Meat'],
                'product_name' => 'Whole Chicken',
                'description' => 'Corn-fed free-range whole chicken from a local farm. Supplied fresh, never frozen.',
                'price' => 9.75,
                'quantity_per_item' => 'approx. 1.5kg',
                'allergy_information' => 'None',
                'stock' => 15,
                'min_order' => 1,
                'max_order' => 4,
                'image_url' => '/images/products/whole_chicken.webp',
            ],
            [
                'shop_id' => $shops['Golden Poultry'],
                'product_category_id' => $categories['Meat'],
                'product_name' => 'Tomatoes',
                'description' => 'Vine-ripened tomatoes, juicy and full of flavour. Perfect for salads, sauces, or roasting.',
                'price' => 3.50,
                'quantity_per_item' => '500g pack',
                'allergy_information' => 'None',
                'stock' => 40,
                'min_order' => 1,
                'max_order' => 8,
                'image_url' => '/images/products/tomatoes.jpg',
            ],
            [
                'shop_id' => $shops['Golden Poultry'],
                'product_category_id' => $categories['Meat'],
                'product_name' => 'Mango',
                'description' => 'Sweet and juicy ripe mangoes, perfect for desserts, smoothies, or savoury dishes.',
                'price' => 2.50,
                'quantity_per_item' => '1 fruit',
                'allergy_information' => 'None',
                'stock' => 25,
                'min_order' => 1,
                'max_order' => 6,
                'image_url' => '/images/products/mango.jpeg',
            ],
            [
                'shop_id' => $shops['fresh fruti center'],
                'product_category_id' => $categories['Fruit'],
                'product_name' => 'oranges_1kg',
                'description' => 'Juicy seedless oranges, perfect for fresh juice or snacking.',
                'price' => 2.80,
                'quantity_per_item' => '1kg bag',
                'allergy_information' => 'None',
                'stock' => 35,
                'min_order' => 1,
                'max_order' => 8,
                'image_url' => '/images/products/oranges_1kg.jpg',
            ],

            // The Cleckhuddersfax Larder — 2 products
            [
                'shop_id' => $shops['The Cleckhuddersfax Larder'],
                'product_category_id' => $categories['Delicatessen'],
                'product_name' => 'cheddar',
                'description' => '18-month aged clothbound cheddar from a local artisan dairy. Crumbly, rich, and deeply flavoured.',
                'price' => 5.80,
                'quantity_per_item' => '250g wedge',
                'allergy_information' => 'Milk',
                'stock' => 30,
                'min_order' => 1,
                'max_order' => 6,
                'image_url' => '/images/products/cheddar.png',
            ],
            [
                'shop_id' => $shops['The Cleckhuddersfax Larder'],
                'product_category_id' => $categories['Delicatessen'],
                'product_name' => 'selection board',
                'description' => 'A curated selection of artisan cured meats including chorizo, prosciutto, and salami.',
                'price' => 9.50,
                'quantity_per_item' => '300g mixed pack',
                'allergy_information' => 'None',
                'stock' => 20,
                'min_order' => 1,
                'max_order' => 4,
                'image_url' => '/images/products/selection board.png',
            ],

            // The Cheese Dairy — 2 products
            [
                'shop_id' => $shops['The Cheese Dairy'],
                'product_category_id' => $categories['Dairy & Eggs'],
                'product_name' => 'milk',
                'description' => 'Fresh whole milk from pasture-fed cows, delivered daily. Perfect for your morning coffee or baking.',
                'price' => 2.50,
                'quantity_per_item' => '1 litre bottle',
                'allergy_information' => 'Milk',
                'stock' => 30,
                'min_order' => 1,
                'max_order' => 8,
                'image_url' => '/images/products/milk.png',
            ],
            [
                'shop_id' => $shops['The Cheese Dairy'],
                'product_category_id' => $categories['Dairy & Eggs'],
                'product_name' => 'cheddar',
                'description' => '18-month aged clothbound cheddar from a local artisan dairy. Crumbly, rich, and deeply flavoured.',
                'price' => 5.80,
                'quantity_per_item' => '250g wedge',
                'allergy_information' => 'Milk',
                'stock' => 30,
                'min_order' => 1,
                'max_order' => 6,
                'image_url' => '/images/products/cheddar.png',
            ],
        ];

        foreach ($products as $product) {
            DB::table('product')->updateOrInsert(
                [
                    'shop_id' => $product['shop_id'],
                    'product_name' => $product['product_name'],
                ],
                [
                    'product_category_id' => $product['product_category_id'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'quantity_per_item' => $product['quantity_per_item'],
                    'allergy_information' => $product['allergy_information'],
                    'stock' => $product['stock'],
                    'amount' => $product['price'],
                    'max_order' => $product['max_order'],
                    'min_order' => $product['min_order'],
                    'add_date' => now()->toDateString(),
                    'update_date' => now()->toDateString(),
                    'product_status' => 'ACTIVE',
                    'approval_status' => 'APPROVED',
                    'image_url' => $product['image_url'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
