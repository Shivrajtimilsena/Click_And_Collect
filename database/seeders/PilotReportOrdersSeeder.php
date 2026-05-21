<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PilotReportOrdersSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $customerIds = DB::table('customer')->pluck('customer_id');
        $shopIds = DB::table('shop')->pluck('shop_id');

        if ($customerIds->isEmpty() || $shopIds->isEmpty()) {
            return;
        }

        DB::table('payment')->delete();
        DB::table('order_item')->delete();
        DB::table('APP_ORDER')->delete();

        $productsByShop = DB::table('product')
            ->where('product_status', 'ACTIVE')
            ->get(['product_id', 'shop_id', 'price'])
            ->groupBy('shop_id');

        if ($productsByShop->isEmpty()) {
            return;
        }

        $findCollectionSlotId = function (Carbon $orderDate): ?int {
            return DB::table('collection_slot')
                ->whereDate('slot_date', '>=', $orderDate->toDateString())
                ->orderBy('slot_date')
                ->orderBy('start_time')
                ->value('collection_slot_id');
        };

        $createCompletedOrder = function (
            int $shopId,
            Carbon $orderDate,
            int $customerId,
            ?int $collectionSlotId = null
        ) use ($productsByShop): void {
            $products = $productsByShop->get($shopId, collect());

            if ($products->isEmpty()) {
                return;
            }

            $items = $products->random(random_int(1, min(3, $products->count())));
            $items = $items instanceof \Illuminate\Support\Collection ? $items : collect([$items]);

            $orderAmount = 0;
            foreach ($items as $item) {
                $quantity = random_int(1, 3);
                $unitPrice = (float) $item->price;
                $lineTotal = $unitPrice * $quantity;
                $orderAmount += $lineTotal;
            }

            $orderId = DB::table('APP_ORDER')->insertGetId(
                [
                    'customer_id' => $customerId,
                    'shop_id' => $shopId,
                    'collection_slot_id' => $collectionSlotId,
                    'cart_id' => null,
                    'coupon_id' => null,
                    'order_date' => $orderDate,
                    'order_amount' => $orderAmount,
                    'discount_amount' => 0,
                    'total_amount' => $orderAmount,
                    'order_status' => 'COMPLETED',
                    'payment_status' => 'COMPLETED',
                    'notes' => null,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ],
                'order_id'
            );

            foreach ($items as $item) {
                $quantity = random_int(1, 3);
                $unitPrice = (float) $item->price;
                $lineTotal = $unitPrice * $quantity;

                DB::table('order_item')->insert([
                    'order_id' => $orderId,
                    'product_id' => $item->product_id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ]);
            }

            DB::table('payment')->insert([
                'order_id' => $orderId,
                'payment_date' => $orderDate->copy()->addMinutes(random_int(1, 120)),
                'amount' => $orderAmount,
                'payment_method' => 'PAYPAL',
                'payment_status' => 'COMPLETED',
                'paypal_txn_id' => 'DUMMY-'.strtoupper(bin2hex(random_bytes(6))),
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);
        };

        foreach ($shopIds as $shopId) {
            $products = $productsByShop->get($shopId, collect());

            if ($products->isEmpty()) {
                continue;
            }

            for ($i = 0; $i < 100; $i++) {
                $customerId = $customerIds->random();
                $orderDate = $now->copy()->subDays(random_int(0, 90));
                $orderDate->setTime(random_int(8, 20), random_int(0, 59), random_int(0, 59));

                $createCompletedOrder($shopId, $orderDate, $customerId);
            }

            for ($i = 0; $i < 5; $i++) {
                $customerId = $customerIds->random();
                $orderDate = $now->copy();
                $orderDate->setTime(random_int(8, 20), random_int(0, 59), random_int(0, 59));
                $collectionSlotId = $findCollectionSlotId($orderDate);

                $createCompletedOrder($shopId, $orderDate, $customerId, $collectionSlotId);
            }
        }

        $shopsByTrader = DB::table('shop')
            ->select(['shop_id', 'trader_id'])
            ->get()
            ->groupBy('trader_id');

        foreach ($shopsByTrader as $traderId => $traderShops) {
            $shopIdsForTrader = $traderShops->pluck('shop_id')->values();

            if ($shopIdsForTrader->isEmpty()) {
                continue;
            }

            for ($i = 0; $i < 10; $i++) {
                $shopId = (int) $shopIdsForTrader->random();
                $customerId = $customerIds->random();
                $orderDate = $now->copy();
                $orderDate->setTime(random_int(8, 20), random_int(0, 59), random_int(0, 59));
                $collectionSlotId = $findCollectionSlotId($orderDate);

                $createCompletedOrder($shopId, $orderDate, $customerId, $collectionSlotId);
            }
        }
    }
}
