<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use App\Models\Shop;
use App\Models\Trader;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $categoriesData = [
            ['category_name' => 'Artisan Bakery', 'description' => 'Freshly baked bread and pastries', 'is_active' => 'Y'],
            ['category_name' => 'Local Butcher', 'description' => 'Premium quality meat', 'is_active' => 'Y'],
            ['category_name' => 'Daily Greens', 'description' => 'Fresh vegetables and greens', 'is_active' => 'Y'],
            ['category_name' => 'Dairy & Eggs', 'description' => 'Dairy products and eggs', 'is_active' => 'Y'],
            ['category_name' => 'Fresh Catch', 'description' => 'Fresh seafood', 'is_active' => 'Y'],
            ['category_name' => 'Coffee & Tea', 'description' => 'Quality beverages', 'is_active' => 'Y'],
            ['category_name' => 'Wine & Spirits', 'description' => 'Fine wines and spirits', 'is_active' => 'Y'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[] = ProductCategory::firstOrCreate(['category_name' => $cat['category_name']], $cat);
        }

        // Create test trader user
        $traderUser = User::firstOrCreate(
            ['email' => 'trader@example.com'],
            [
                'full_name' => 'Test Trader',
                'phone_no' => '555-0000',
                'password' => bcrypt('password'),
                'role' => 'TRADER',
                'status' => 'ACTIVE',
            ]
        );

        $trader = Trader::firstOrCreate(
            ['user_id' => $traderUser->user_id],
            [
                'shop_type' => 'Multi-Vendor',
                'is_active' => 'Y',
            ]
        );

        // Create shops
        $shopNames = ['Green Root', 'Flour & Salt', 'Old Town Butcher', 'Pure Farm', 'Bean Craft', 'Ocean Fresh'];
        $createdShops = [];

        foreach ($shopNames as $idx => $name) {
            $createdShops[] = Shop::firstOrCreate(
                ['shop_name' => $name],
                [
                    'trader_id' => $trader->trader_id,
                    'description' => "$name - Quality local products",
                    'is_active' => 'Y',
                ]
            );
        }

        // Create products
        $productsData = [
            ['shop' => 0, 'category' => 0, 'product_name' => 'Artisan Sourdough Loaf', 'price' => 6.00, 'stock' => 8],
            ['shop' => 0, 'category' => 2, 'product_name' => 'Organic Bunch Carrots', 'price' => 3.00, 'stock' => 12],
            ['shop' => 1, 'category' => 0, 'product_name' => 'Handmade Double Choc Cookies', 'price' => 3.00, 'stock' => 30],
            ['shop' => 1, 'category' => 0, 'product_name' => 'Croissant Pack (4)', 'price' => 8.00, 'stock' => 20],
            ['shop' => 2, 'category' => 1, 'product_name' => 'Premium Grass-Fed Beef Mince', 'price' => 7.50, 'stock' => 15],
            ['shop' => 2, 'category' => 1, 'product_name' => 'Aged Ribeye Steak', 'price' => 22.00, 'stock' => 4],
            ['shop' => 3, 'category' => 3, 'product_name' => 'Pasture-Raised Whole Milk', 'price' => 4.00, 'stock' => 21],
            ['shop' => 3, 'category' => 3, 'product_name' => 'Aged Farmhouse Cheddar', 'price' => 5.80, 'stock' => 12],
            ['shop' => 4, 'category' => 5, 'product_name' => 'Ethical Medium Roast Coffee', 'price' => 14.00, 'stock' => 25],
            ['shop' => 5, 'category' => 4, 'product_name' => 'Fresh Scottish Salmon Fillet', 'price' => 12.50, 'stock' => 8],
        ];

        foreach ($productsData as $product) {
            Product::firstOrCreate(
                ['product_name' => $product['product_name']],
                [
                    'shop_id' => $createdShops[$product['shop']]->shop_id,
                    'product_category_id' => $categories[$product['category']]->product_category_id,
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'product_status' => 'ACTIVE',
                    'image_url' => 'https://via.placeholder.com/300?text=' . urlencode($product['product_name']),
                    'description' => $product['product_name'] . ' - Fresh and quality guaranteed',
                ]
            );
        }

        // Create test customer
        $customerUser = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'full_name' => 'Test Customer',
                'phone_no' => '555-0123',
                'password' => bcrypt('password'),
                'role' => 'CUSTOMER',
                'status' => 'ACTIVE',
            ]
        );
    }
}