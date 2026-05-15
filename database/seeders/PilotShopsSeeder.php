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

        $shops = [
            [
                'email' => 'butcher@clickcollect.local',
                'shop_name' => 'Hearth & Cleaver Traditional Meats',
                'description' => 'Premium quality meats from local farms. Our butchers select the finest cuts of beef, lamb, pork, and poultry, all sourced from trusted local farms that practice ethical and sustainable animal husbandry.',
            ],
            [
                'email' => 'greengrocer@clickcollect.local',
                'shop_name' => 'Old Orchard Produce',
                'description' => 'Fresh seasonal fruits and vegetables straight from the orchard. We work directly with local growers to bring you the freshest, most flavourful produce at the peak of ripeness.',
            ],
            [
                'email' => 'fishmonger@clickcollect.local',
                'shop_name' => 'Heritage Catch',
                'description' => 'Sustainably sourced fresh fish and seafood delivered daily. Our catch comes from heritage fishing communities practising traditional methods for the finest quality.',
            ],
            [
                'email' => 'bakery@clickcollect.local',
                'shop_name' => 'Stoneground Flour & Grain',
                'description' => 'Artisan breads, pastries, and cakes baked fresh daily using traditional stoneground flours. Every loaf is hand-crafted and slow-fermented for superior taste and texture.',
            ],
            [
                'email' => 'deli@clickcollect.local',
                'shop_name' => 'The Cleckhuddersfax Larder',
                'description' => 'Fine cheeses, cured meats, olives, and specialty provisions from across the British Isles and Europe. Our deli counter is stocked with carefully selected artisanal delights.',
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
                    'register_date' => now()->toDateString(),
                    'is_active' => 'Y',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
