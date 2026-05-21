<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PilotFlashDealSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $products = DB::table('product')
            ->where('product_status', 'ACTIVE')
            ->inRandomOrder()
            ->limit(5)
            ->get(['product_id']);

        if ($products->isEmpty()) {
            return;
        }

        DB::table('discount')->delete();

        foreach ($products as $product) {
            $discountPercentage = random_int(10, 40);

            DB::table('discount')->updateOrInsert(
                ['product_id' => $product->product_id],
                [
                    'discount_percentage' => $discountPercentage,
                    'start_date' => $now->toDateString(),
                    'end_date' => $now->copy()->addDays(7)->toDateString(),
                    'is_active' => 'Y',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
