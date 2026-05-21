<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PilotShopsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('shop')->where('shop_name', 'The Daily Loaf')->delete();

        $shops = [
            // Butcher — 2 shops
            [
                'email' => 'butcher@clickcollect.local',
                'shop_name' => 'Hearth & Cleaver Traditional Meats',
                'description' => 'Premium quality meats from local farms. Our butchers select the finest cuts of beef, lamb, pork, and poultry, all sourced from trusted local farms that practice ethical and sustainable animal husbandry.',
                'shop_image' => '/images/shopimage/meat shop.png',
            ],
            [
                'email' => 'butcher@clickcollect.local',
                'shop_name' => 'Golden Poultry',
                'description' => 'Free-range and corn-fed poultry, ethically reared on local farms. Our birds are slow-grown for superior flavour and texture.',
                'shop_image' => '/images/shopimage/Golden Poultry.jpg',
            ],

            // Greengrocer — 2 shops
            [
                'email' => 'greengrocer@clickcollect.local',
                'shop_name' => 'Old Orchard Produce',
                'description' => 'Fresh seasonal fruits and vegetables straight from the orchard. We work directly with local growers to bring you the freshest, most flavourful produce at the peak of ripeness.',
                'shop_image' => '/images/shopimage/green grocer.png',
            ],
            [
                'email' => 'greengrocer@clickcollect.local',
                'shop_name' => 'Green Valley',
                'description' => 'Organic and locally grown vegetables, herbs, and salad greens. Cold-pressed juices and seasonal fruit boxes available.',
                'shop_image' => '/images/shopimage/green valley.jpg',
            ],

            // Fishmonger — 2 shops
            [
                'email' => 'fishmonger@clickcollect.local',
                'shop_name' => 'Heritage Catch',
                'description' => 'Sustainably sourced fresh fish and seafood delivered daily. Our catch comes from heritage fishing communities practising traditional methods for the finest quality.',
                'shop_image' => '/images/shopimage/fish shop.png',
            ],
            [
                'email' => 'fishmonger@clickcollect.local',
                'shop_name' => 'The Fish Market',
                'description' => 'A wide selection of fresh and smoked fish, shellfish, and seafood specialities. Daily deliveries from coast to counter.',
                'shop_image' => '/images/shopimage/Fish_M.jpg',
            ],

            // Bakery — 2 shops
            [
                'email' => 'bakery@clickcollect.local',
                'shop_name' => 'Stoneground Flour & Grain',
                'description' => 'Artisan breads, pastries, and cakes baked fresh daily using traditional stoneground flours. Every loaf is hand-crafted and slow-fermented for superior taste and texture.',
                'shop_image' => '/images/shopimage/bakery shop.png',
            ],
            [
                'email' => 'bakery@clickcollect.local',
                'shop_name' => 'fresh fruti center',
                'description' => 'Sourdough specialists crafting organic, naturally leavened bread. Also offering a range of sweet and savoury pastries, cakes, and traybakes.',
                'shop_image' => '/images/shopimage/seafood market.png',
            ],

            // Deli — 2 shops
            [
                'email' => 'deli@clickcollect.local',
                'shop_name' => 'The Cleckhuddersfax Larder',
                'description' => 'Fine cheeses, cured meats, olives, and specialty provisions from across the British Isles and Europe. Our deli counter is stocked with carefully selected artisanal delights.',
                'shop_image' => '/images/shopimage/Dairy Shop.png',
            ],
            [
                'email' => 'deli@clickcollect.local',
                'shop_name' => 'The Cheese Dairy',
                'description' => 'An artisan cheese haven featuring aged cheddars, soft-ripened bries, blue cheeses, and handcrafted dairy products from local creameries.',
                'shop_image' => '/images/shopimage/Best Milk Dairy in Boring Road.png',
            ],
        ];

        foreach ($shops as $shop) {
            $userId = DB::table('CC_USER')->where('email', $shop['email'])->value('user_id');
            $traderId = DB::table('trader')->where('user_id', $userId)->value('trader_id');

            DB::table('shop')->updateOrInsert(
                ['shop_name' => $shop['shop_name']],
                [
                    'trader_id' => $traderId,
                    'description' => $shop['description'],
                    'shop_image' => $shop['shop_image'],
                    'register_date' => now()->toDateString(),
                    'is_active' => 'Y',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
