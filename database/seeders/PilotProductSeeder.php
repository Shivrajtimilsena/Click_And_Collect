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

        DB::table('product')
            ->whereIn('shop_id', array_values($shops))
            ->delete();

        if ($legacyDailyLoafId) {
            DB::table('product')->where('shop_id', $legacyDailyLoafId)->delete();
        }

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
                'product_name' => 'whole_chicken',
                'description' => 'Corn-fed free-range whole chicken from a local farm. Supplied fresh, never frozen.',
                'price' => 9.75,
                'quantity_per_item' => 'approx. 1.5kg',
                'allergy_information' => 'None',
                'stock' => 15,
                'min_order' => 1,
                'max_order' => 4,
                'image_url' => '/images/products/whole_chicken.webp',
            ],

            // Golden Poultry — 2 products
            [
                'shop_id' => $shops['Golden Poultry'],
                'product_category_id' => $categories['Dairy & Eggs'],
                'product_name' => 'range eggs',
                'description' => 'Pasture-raised free-range eggs from a local smallholding. Deep orange yolks, excellent flavour.',
                'price' => 3.20,
                'quantity_per_item' => 'box of 6',
                'allergy_information' => 'Egg',
                'stock' => 40,
                'min_order' => 1,
                'max_order' => 10,
                'image_url' => '/images/products/range eggs.png',
            ],
            [
                'shop_id' => $shops['Golden Poultry'],
                'product_category_id' => $categories['Dairy & Eggs'],
                'product_name' => 'osttich_egg',
                'description' => 'Large ostrich egg, equivalent to approximately 24 chicken eggs. A unique centrepiece.',
                'price' => 15.00,
                'quantity_per_item' => '1 egg',
                'allergy_information' => 'Egg',
                'stock' => 10,
                'min_order' => 1,
                'max_order' => 3,
                'image_url' => '/images/products/osttich_egg.jpg',
            ],

            // Old Orchard Produce — 2 products
            [
                'shop_id' => $shops['Old Orchard Produce'],
                'product_category_id' => $categories['Vegetables'],
                'product_name' => 'tomatoes',
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
                'shop_id' => $shops['Old Orchard Produce'],
                'product_category_id' => $categories['Fruit'],
                'product_name' => 'strawberries',
                'description' => 'Sweet and fragrant British strawberries, hand-picked at the peak of ripeness.',
                'price' => 3.80,
                'quantity_per_item' => '400g punnet',
                'allergy_information' => 'None',
                'stock' => 40,
                'min_order' => 1,
                'max_order' => 6,
                'image_url' => '/images/products/strawberries.png',
            ],

            // Green Valley — 2 products
            [
                'shop_id' => $shops['Green Valley'],
                'product_category_id' => $categories['Vegetables'],
                'product_name' => 'spinach',
                'description' => 'Tender washed baby spinach leaves, perfect for salads or sauteing.',
                'price' => 2.80,
                'quantity_per_item' => '250g bag',
                'allergy_information' => 'None',
                'stock' => 35,
                'min_order' => 1,
                'max_order' => 8,
                'image_url' => '/images/products/spinach.png',
            ],
            [
                'shop_id' => $shops['Green Valley'],
                'product_category_id' => $categories['Vegetables'],
                'product_name' => 'spinach (1)',
                'description' => 'Extra tender young spinach leaves, mild and sweet. Ideal for fresh salads.',
                'price' => 3.20,
                'quantity_per_item' => '150g bag',
                'allergy_information' => 'None',
                'stock' => 25,
                'min_order' => 1,
                'max_order' => 8,
                'image_url' => '/images/products/spinach (1).png',
            ],

            // Heritage Catch — 2 products
            [
                'shop_id' => $shops['Heritage Catch'],
                'product_category_id' => $categories['Seafood'],
                'product_name' => 'scottish fillet',
                'description' => 'Sustainably caught wild Scottish salmon fillets, rich in omega-3.',
                'price' => 12.90,
                'quantity_per_item' => '200g fillet',
                'allergy_information' => 'Fish',
                'stock' => 20,
                'min_order' => 1,
                'max_order' => 6,
                'image_url' => '/images/products/scottish fillet.png',
            ],
            [
                'shop_id' => $shops['Heritage Catch'],
                'product_category_id' => $categories['Seafood'],
                'product_name' => 'king prawn',
                'description' => 'Large uncooked king prawns, perfect for curries, stir-fries, or barbecues.',
                'price' => 8.20,
                'quantity_per_item' => '350g pack',
                'allergy_information' => 'Crustaceans',
                'stock' => 25,
                'min_order' => 1,
                'max_order' => 5,
                'image_url' => '/images/products/king prawn.png',
            ],

            // The Fish Market — 2 products
            [
                'shop_id' => $shops['The Fish Market'],
                'product_category_id' => $categories['Seafood'],
                'product_name' => 'Crab',
                'description' => 'Freshly caught whole crab, cooked and ready to dress. Sweet, delicate meat.',
                'price' => 8.50,
                'quantity_per_item' => '1 whole crab (approx. 500g)',
                'allergy_information' => 'Crustaceans',
                'stock' => 15,
                'min_order' => 1,
                'max_order' => 4,
                'image_url' => '/images/products/Crab.png',
            ],
            [
                'shop_id' => $shops['The Fish Market'],
                'product_category_id' => $categories['Seafood'],
                'product_name' => 'rohu_fish',
                'description' => 'Freshwater Rohu fish, sustainably farmed. Firm flesh perfect for curries and frying.',
                'price' => 6.50,
                'quantity_per_item' => '1 whole fish (approx. 800g)',
                'allergy_information' => 'Fish',
                'stock' => 20,
                'min_order' => 1,
                'max_order' => 4,
                'image_url' => '/images/products/rohu_fish.jpg',
            ],

            // Stoneground Flour & Grain — 2 products
            [
                'shop_id' => $shops['Stoneground Flour & Grain'],
                'product_category_id' => $categories['Bakery'],
                'product_name' => 'Sourdough',
                'description' => 'Slow-fermented sourdough made with heritage stoneground flour. Crisp crust, open crumb.',
                'price' => 4.50,
                'quantity_per_item' => '1 large loaf',
                'allergy_information' => 'Contains gluten',
                'stock' => 30,
                'min_order' => 1,
                'max_order' => 6,
                'image_url' => '/images/products/Sourdough.jpg',
            ],
            [
                'shop_id' => $shops['Stoneground Flour & Grain'],
                'product_category_id' => $categories['Bakery'],
                'product_name' => 'cro',
                'description' => 'All-butter croissants, laminated and baked fresh each morning. Golden, flaky, and indulgent.',
                'price' => 6.80,
                'quantity_per_item' => 'pack of 4',
                'allergy_information' => 'Contains gluten, milk, egg',
                'stock' => 25,
                'min_order' => 1,
                'max_order' => 8,
                'image_url' => '/images/products/cro.png',
            ],

            // fresh fruti center — 2 products
            [
                'shop_id' => $shops['fresh fruti center'],
                'product_category_id' => $categories['Fruit'],
                'product_name' => 'mango',
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
                    'image_url' => $product['image_url'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
