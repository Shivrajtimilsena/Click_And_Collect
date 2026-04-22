<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PilotShopsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $shops = [
            [
                'email'       => 'butcher@clickcollect.local',
                'shop_name'   => 'Heritage Butcher',
                'description' => 'Traditional butcher serving fresh meat cuts.',
            ],
            [
                'email'       => 'greengrocer@clickcollect.local',
                'shop_name'   => 'Green Basket',
                'description' => 'Fresh vegetables and seasonal produce.',
            ],
            [
                'email'       => 'fishmonger@clickcollect.local',
                'shop_name'   => 'Sea Fresh Fishmonger',
                'description' => 'Fresh fish and seafood products.',
            ],
            [
                'email'       => 'bakery@clickcollect.local',
                'shop_name'   => 'Old Town Bakery',
                'description' => 'Bread, cakes, pastries, and baked goods.',
            ],
            [
                'email'       => 'deli@clickcollect.local',
                'shop_name'   => 'Local Delicatessen',
                'description' => 'Cheese, cured meats, and specialty delicacies.',
            ],
        ];

        foreach ($shops as $shop) {
            $userId   = DB::table('users')->where('email', $shop['email'])->value('user_id');
            $traderId = DB::table('traders')->where('user_id', $userId)->value('trader_id');

            DB::table('shops')->updateOrInsert(
                ['shop_name' => $shop['shop_name']],
                [
                    'trader_id'     => $traderId,
                    'description'   => $shop['description'],
                    'register_date' => now()->toDateString(),
                    'is_active'     => 'Y',
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]
            );
        }
    }
}