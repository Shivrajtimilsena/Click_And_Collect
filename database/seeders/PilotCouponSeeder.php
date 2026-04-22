<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PilotCouponSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('coupons')->updateOrInsert(
            ['coupon_code' => 'WELCOME10'],
            [
                'amount'           => null,
                'discount_percent' => 10,
                'start_date'       => now()->toDateString(),
                'end_date'         => now()->addMonth()->toDateString(),
                'description'      => '10% welcome discount',
                'is_active'        => 'Y',
                'created_at'       => $now,
                'updated_at'       => $now,
            ]
        );
    }
}