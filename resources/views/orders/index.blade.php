@extends('app')

@section('title', 'My Orders | Click&Collect')

@section('content')
<div class="max-w-4xl mx-auto py-12">
    <h1 class="text-3xl font-headline font-bold text-on-surface mb-8">My Orders</h1>

    @if ($orderGroups->count() > 0)
        <div class="space-y-6">
            @foreach ($orderGroups as $groupId => $orders)
                @php
                    $orders = is_iterable($orders) ? collect($orders) : collect([$orders]);
                    $firstOrder = $orders->first();
                    $combinedTotal = $orders->sum('total_amount');
                    $shopCount = $orders->count();
                    $slot = $firstOrder->collectionSlot;
                @endphp

                <div class="bg-surface-container-lowest border border-surface-container-high overflow-hidden">
                    <div class="p-6 border-b border-surface-container-high flex items-start justify-between">
                        <div>
                            <p class="text-xs text-secondary uppercase tracking-widest font-bold">
                                @if($shopCount > 1)
                                    Order Group &middot; {{ $shopCount }} Shops
                                @else
                                    Order #{{ $firstOrder->order_id }}
                                @endif
                            </p>
                            <p class="text-lg font-bold text-on-surface mt-1">
                                {{ $orders->sum(fn($o) => $o->items->count()) }} Item(s) across {{ $shopCount }} shop(s)
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-headline font-extrabold text-on-surface">&pound;{{ number_format($combinedTotal, 2) }}</p>
                            <p class="text-xs text-secondary">{{ $firstOrder->created_at?->format('M d, Y') ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($slot)
                    <div class="px-6 py-4 bg-primary/5 border-b border-surface-container-high">
                        <div class="flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-primary text-lg">schedule</span>
                            <span class="font-bold">Collection:</span>
                            <span>{{ $slot->slot_day }}, {{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="divide-y divide-surface-container-high">
                        @foreach($orders as $order)
                            <div class="p-6 hover:bg-surface-container-low transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 flex items-center justify-center bg-surface-container-high">
                                            <span class="material-symbols-outlined text-secondary text-lg">store</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-on-surface">{{ $order->collectionSlot?->shop->shop_name ?? 'Shop' }}</p>
                                            <p class="text-xs text-secondary">#ORD-{{ $order->order_id }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @php
                                            $statusStyles = [
                                                'PENDING' => 'bg-zinc-100 text-zinc-600',
                                                'IN_PROGRESS' => 'bg-blue-100 text-blue-700',
                                                'READY' => 'bg-orange-100 text-orange-700',
                                                'COMPLETED' => 'bg-green-100 text-green-700',
                                                'CANCELLED' => 'bg-red-100 text-red-700',
                                            ];
                                            $style = $statusStyles[$order->order_status] ?? 'bg-zinc-100 text-zinc-600';
                                        @endphp
                                        <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider {{ $style }}">
                                            {{ str_replace('_', ' ', $order->order_status) }}
                                        </span>
                                        <span class="font-bold text-on-surface">&pound;{{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center gap-2 text-sm">
                                            <span class="text-secondary">{{ $item->quantity }}x</span>
                                            <span class="text-on-surface">{{ $item->product->product_name }}</span>
                                            <span class="text-secondary ml-auto">&pound;{{ number_format($item->line_total, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="px-6 py-4 bg-surface-container-low border-t border-surface-container-high">
                        <a href="{{ route('orders.show', $firstOrder) }}" class="inline-block bg-primary text-on-primary px-6 py-3 font-bold text-sm hover:opacity-90 transition-all">
                            View Full Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-surface-container-lowest border border-surface-container-high p-12 text-center">
            <span class="material-symbols-outlined text-6xl text-surface-variant mb-4 block">receipt_long</span>
            <p class="text-secondary text-lg mb-6">No orders yet</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-primary text-on-primary px-6 py-3 font-bold hover:opacity-90">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection