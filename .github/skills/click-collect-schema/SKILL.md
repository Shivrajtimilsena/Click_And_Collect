# Click & Collect Schema & Project Context Skill

**Use this skill for ANY code generation, review, or modification in the Click & Collect Laravel/APEX project.**

This skill encodes the non-negotiable project conventions that diverge from Laravel defaults.

---

## Critical Rule: Non-Default Primary Keys

**This project does NOT use default Laravel `id` primary keys.**

Every model, query, relationship, and join MUST use explicit schema keys.

### Primary Keys (Mandatory)

```
users              → user_id
customers          → customer_id
traders            → trader_id
admins             → admin_id
shops              → shop_id
product_categories → product_category_id
products           → product_id
discounts          → discount_id
reviews            → review_id
wishlists          → wishlist_id
carts              → cart_id
orders             → order_id
collection_slots   → collection_slot_id
payments           → payment_id
coupons            → coupon_id
```

### Composite Primary Keys

These tables use **composite keys**, NOT single IDs:

```
wishlist_products  (wishlist_id, product_id)
cart_products      (cart_id, product_id)
order_items        (order_id, product_id)
```

For composite keys:
- Do NOT use `find($id)`
- Use explicit `where()` conditions instead:

```php
CartProduct::where('cart_id', $cartId)
    ->where('product_id', $productId)
    ->first();
```

---

## Model Definition (Mandatory)

Every model MUST explicitly define table, primary key, and key type:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $incrementing = true;
    protected $keyType = 'int';
}
```

**Never** rely on Laravel's default assumptions.

---

## Relationship Definition (Mandatory)

Always explicitly define the related model, foreign key, and local key:

### Wrong
```php
public function shop()
{
    return $this->belongsTo(Shop::class);
}
```

### Correct
```php
public function shop()
{
    return $this->belongsTo(Shop::class, 'shop_id', 'shop_id');
}
```

### Example: Product relationships

```php
public function shop()
{
    return $this->belongsTo(Shop::class, 'shop_id', 'shop_id');
}

public function category()
{
    return $this->belongsTo(ProductCategory::class, 'product_category_id', 'product_category_id');
}

public function reviews()
{
    return $this->hasMany(Review::class, 'product_id', 'product_id');
}

public function discount()
{
    return $this->hasOne(Discount::class, 'product_id', 'product_id');
}
```

### Example: Review relationships

```php
public function product()
{
    return $this->belongsTo(Product::class, 'product_id', 'product_id');
}

public function customer()
{
    return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
}
```

---

## Query Standard (Mandatory)

### Wrong Patterns (NEVER use)
```php
Product::find($id);
Product::where('id', $id)->first();
Review::where('id', $id)->first();
Order::find($orderId);
```

### Correct Patterns (Always use)
```php
Product::where('product_id', $productId)->first();
Review::where('review_id', $reviewId)->first();
Order::where('order_id', $orderId)->first();
```

---

## Join Standard (Mandatory)

### Wrong
```php
->join('reviews', 'products.id', '=', 'reviews.product_id')
->join('shops', 'products.shop_id', '=', 'shops.id')
->join('discounts', 'products.id', '=', 'discounts.product_id')
```

### Correct
```php
->join('reviews', 'products.product_id', '=', 'reviews.product_id')
->join('shops', 'products.shop_id', '=', 'shops.shop_id')
->join('discounts', 'products.product_id', '=', 'discounts.product_id')
```

---

## Discount Rule (Critical)

**`discount_percentage` is NOT a column in `products`.**

It belongs ONLY in the `discounts` table.

### Wrong
```php
Product::where('discount_percentage', '>', 0)
$products->pluck('discount_percentage')
```

### Correct (Join pattern)

```php
Product::query()
    ->join('discounts', 'products.product_id', '=', 'discounts.product_id')
    ->where('discounts.discount_percentage', '>', 0)
    ->select('products.*')
    ->get();
```

### Correct (Relationship pattern)

```php
Product::whereHas('discount', function ($q) {
    $q->where('discount_percentage', '>', 0);
})->get();
```

---

## Foreign Key Map (Reference)

```
customers.user_id                    → users.user_id
traders.user_id                      → users.user_id
admins.user_id                       → users.user_id
shops.trader_id                      → traders.trader_id
products.shop_id                     → shops.shop_id
products.product_category_id         → product_categories.product_category_id
discounts.product_id                 → products.product_id
reviews.customer_id                  → customers.customer_id
reviews.product_id                   → products.product_id
wishlists.customer_id                → customers.customer_id
wishlist_products.wishlist_id        → wishlists.wishlist_id
wishlist_products.product_id         → products.product_id
carts.customer_id                    → customers.customer_id
cart_products.cart_id                → carts.cart_id
cart_products.product_id             → products.product_id
orders.customer_id                   → customers.customer_id
orders.shop_id                       → shops.shop_id
orders.collection_slot_id            → collection_slots.collection_slot_id
orders.cart_id                       → carts.cart_id
orders.coupon_id                     → coupons.coupon_id
order_items.order_id                 → orders.order_id
order_items.product_id               → products.product_id
payments.order_id                    → orders.order_id
```

---

## Project Architecture

### Application Split
- **Laravel 12**: Customer-facing frontend, browsing, checkout, account pages, business logic
- **Oracle APEX**: Trader Dashboard, Admin Dashboard, reporting
- **Shared Database**: Both layers use the same `CC_APP` schema

### Business Context
- **Pilot traders**: butcher, greengrocer, fishmonger, bakery, delicatessen (5 total)
- **Single basket** spanning all traders
- **Collection only** (no delivery pilot)
- **Collection slots**: 10:00-13:00, 13:00-16:00, 16:00-19:00 (Wed/Thu/Fri only)
- **Slot capacity**: max 20 orders per slot
- **Slot timing rule**: minimum 24 hours after order placement
- **Shop limit**: max 10 shops initially
- **Trader permissions**: Access only own shop/products/reports
- **Admin permissions**: Access any trader account and system views

### Shared Database Connection
```
DB_CONNECTION=oracle
DB_HOST=192.168.10.100 (or 127.0.0.1)
DB_PORT=1521
DB_SERVICE_NAME=XEPDB1 (or FREEPDB1)
DB_USERNAME=CC_APP
DB_CHARSET=AL32UTF8
```

---

## Common Mistakes to NEVER Make

❌ Product::where('id', $id)
❌ Review::find($id) without explicit model primary key config
❌ ->join('reviews', 'products.id', '=', 'reviews.product_id')
❌ Product::where('discount_percentage', '>', 0)
❌ return $this->belongsTo(Shop::class); (without explicit keys)
❌ Editing old committed migrations unless in very early local-only development
❌ Assuming Laravel defaults apply to this project

---

## When to Apply This Skill

✅ Creating new models or controllers
✅ Writing database queries
✅ Defining relationships
✅ Writing joins or subqueries
✅ Adding new migrations
✅ Refactoring existing code
✅ Reviewing code for schema compliance
✅ Debugging database-related issues

---

## Quick Checklist for Code Review

- [ ] All models define `$primaryKey`, `$table`, `$keyType`?
- [ ] All relationships use explicit foreign and local keys?
- [ ] All queries use schema keys, not assumed `id`?
- [ ] All joins use correct schema keys?
- [ ] No queries on `discount_percentage` in products table?
- [ ] Composite key queries use where() instead of find()?
- [ ] No Laravel default ID assumptions anywhere?

---

## One-Line Summary

**This project uses schema-specific keys like `user_id`, `product_id`, `order_id` — NEVER default Laravel `id`.**
