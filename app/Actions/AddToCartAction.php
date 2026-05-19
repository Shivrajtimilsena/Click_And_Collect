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
        if ($product->product_status !== 'ACTIVE') {
            throw new \Exception('This product is not available.');
        }

        if ($quantity > $product->stock) {
            throw new \Exception("Only {$product->stock} units of {$product->product_name} are available.");
        }

        if ($product->max_order && $quantity > $product->max_order) {
            throw new \Exception("Maximum {$product->max_order} units of {$product->product_name} per order.");
        }

        if ($product->min_order && $quantity < $product->min_order) {
            throw new \Exception("Minimum {$product->min_order} units of {$product->product_name} per order.");
        }

        $cart = $customer->getOrCreateCart();

        $cartItem = $cart->products()->where('product_id', $product->product_id)->first();
        $existingQty = $cartItem ? $cartItem->quantity : 0;
        $totalQty = $existingQty + $quantity;

        if ($totalQty > $product->stock) {
            throw new \Exception("Only {$product->stock} units of {$product->product_name} are available. You already have {$existingQty} in your cart.");
        }

        if ($product->max_order && $totalQty > $product->max_order) {
            throw new \Exception("Maximum {$product->max_order} units of {$product->product_name} per order. You already have {$existingQty} in your cart.");
        }

        $currentTotal = $cart->products()->sum('quantity');
        $newTotal = $currentTotal - $existingQty + $totalQty;

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
