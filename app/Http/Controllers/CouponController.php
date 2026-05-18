<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'coupon_code' => 'required|string|max:100',
        ]);

        $coupon = Coupon::where('coupon_code', $request->coupon_code)->first();

        if (! $coupon || ! $coupon->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired coupon code.',
            ], 422);
        }

        $total = (float) $request->get('total', 0);
        $discountAmount = $this->calculateDiscount($coupon, $total);

        return response()->json([
            'valid' => true,
            'coupon_code' => $coupon->coupon_code,
            'discount_percent' => $coupon->discount_percent,
            'discount_amount' => $coupon->amount,
            'calculated_discount' => $discountAmount,
            'description' => $coupon->description,
        ]);
    }

    public static function calculateDiscount(Coupon $coupon, float $total): float
    {
        if ($coupon->discount_percent && $coupon->discount_percent > 0) {
            return round($total * $coupon->discount_percent / 100, 2);
        }

        if ($coupon->amount && $coupon->amount > 0) {
            return min($coupon->amount, $total);
        }

        return 0;
    }
}
