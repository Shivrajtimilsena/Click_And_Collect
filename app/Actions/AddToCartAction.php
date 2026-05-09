<?php

namespace App\Actions;

use App\Models\CartProduct;
use App\Models\Customer;
use App\Models\Product;

class AddToCartAction
{
    const MAX_ITEMS = 20;

    public function execute(Customer $customer, Product $product, int $quantity): CartProduct
    {
        $cart = $customer->getOrCreateCart();

        $currentTotal = $cart->products()->sum('quantity');
        $cartItem = $cart->products()->where('product_id', $product->product_id)->first();
        $existingQty = $cartItem ? $cartItem->quantity : 0;
        $newTotal = $currentTotal - $existingQty + $quantity;

        if ($newTotal > self::MAX_ITEMS) {
            throw new \Exception('max 20 item allowed to order');
        }

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);

            return $cartItem->refresh();
        }

        return $cart->products()->create([
            'product_id' => $product->product_id,
            'quantity' => $quantity,
        ]);
    }
}
