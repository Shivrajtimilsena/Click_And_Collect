# Fix: Trader Order Status Update Not Persisting

## Problem
When a trader changes an order status via the dropdown on the orders page, the PATCH fetch request silently fails. The page shows the change (CSS updates) but the database is not updated, so on reload the status reverts to the original.

## Root Cause
The JS `fetch()` PATCH approach has reliability issues:
- CSRF token mismatch between the rendered page and the fetch header
- Silent failure in the ownership check returning 403 without the user noticing
- The controller always returns 200 JSON even if `$order->update()` returns false

## Fix

### 1. `resources/views/trader/orders.blade.php`
**Replace** the standalone `<select>` + JS `updateOrderStatus()` approach with a form-based submission:

Before (line 151):
```html
<select class="px-3 py-2 text-xs font-bold uppercase border border-surface-container-high bg-white {{ $statusColor }}" data-url="{{ route('trader.orders.status', $order) }}" onchange="updateOrderStatus(this)">
    ...
</select>
```

After:
```html
<form method="POST" action="{{ route('trader.orders.status', $order) }}">
    @csrf
    @method('PATCH')
    <select name="status" class="px-3 py-2 text-xs font-bold uppercase border border-surface-container-high bg-white {{ $statusColor }}" onchange="this.form.submit()">
        ...
    </select>
</form>
```

**Remove** the entire `@section('scripts')` block (lines 182-221) since `updateOrderStatus()` is no longer needed.

### 2. `app/Http/Controllers/TraderController.php`
**Change `updateStatus()`** from `JsonResponse` to `RedirectResponse`:

Before:
```php
public function updateStatus(Request $request, Order $order): \Illuminate\Http\JsonResponse
{
    ...
    if (! $orderBelongsToTrader) {
        return response()->json(['error' => 'You do not have permission to update this order.'], 403);
    }
    $order->update(['order_status' => $validated['status']]);
    return response()->json(['success' => true, ...]);
}
```

After:
```php
public function updateStatus(Request $request, Order $order): \Illuminate\Http\RedirectResponse
{
    $validated = $request->validate([
        'status' => 'required|string|in:PENDING,IN_PROGRESS,READY,COMPLETED,CANCELLED',
    ]);

    $user = Auth::user();
    $trader = $user->trader;
    $shopIds = $trader->shops()->pluck('shop_id')->toArray();

    $orderBelongsToTrader = Order::where('order_id', $order->order_id)
        ->whereHas('items.product.shop', function ($query) use ($shopIds) {
            $query->whereIn('shop_id', $shopIds);
        })
        ->exists();

    if (! $orderBelongsToTrader) {
        return back()->withErrors(['status' => 'You do not have permission to update this order.']);
    }

    $updates = ['order_status' => $validated['status']];

    if ($validated['status'] === 'COMPLETED' && ! $order->collected_at) {
        $updates['collected_at'] = now();
    }

    $order->update($updates);

    return back()->with('success', "Order #ORD-{$order->order_id} status updated to " . str_replace('_', ' ', $validated['status']) . ".");
}
```

### 3. No route changes needed
The route `PATCH trader/orders/{order}/status` stays the same — form method spoofing handles the PATCH via `@method('PATCH')`.

## Verification
1. Log in as a trader
2. Go to Orders page
3. Change an order status to READY
4. Confirm the dialog? (No — form submits immediately without confirm — this is intentional since it's easy to change back)
5. Page reloads with flash message showing the new status
6. Check database to confirm `order_status` was updated
