@extends('layouts.customer')

@section('title', 'My Orders | Click&Collect')

@section('header-title', 'My Orders')

@section('content')
<div>
    <div class="flex items-center justify-between mb-6 md:mb-8">
        <h3 class="font-headline text-lg md:text-xl font-bold tracking-tight text-on-surface">
            My Orders
        </h3>
        <form method="POST" action="{{ route('profile.orders.clear-history') }}" onsubmit="return confirm('Clear all completed and cancelled orders from your history? This cannot be undone.')">
            @csrf
            <button type="submit" class="text-[10px] md:text-xs font-bold text-error uppercase tracking-widest hover:underline flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">delete_sweep</span>
                <span class="hidden sm:inline">Clear History</span>
            </button>
        </form>
    </div>

    @if($orderGroups->count() > 0)
    <div class="space-y-4 md:space-y-6">
        @foreach($orderGroups as $groupId => $orders)
            @php
                $orders = is_iterable($orders) ? collect($orders) : collect([$orders]);
                $firstOrder = $orders->first();
                $combinedTotal = $orders->sum('total_amount');
                $shopCount = $orders->count();
            @endphp

            <div class="bg-surface-container-lowest border border-surface-container-low overflow-hidden">
                <div class="px-4 md:px-8 py-4 md:py-6 border-b border-surface-container-low flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                    <div>
                        <p class="text-[10px] text-secondary uppercase tracking-[0.2em] font-bold">
                            @if($shopCount > 1)
                                Order &middot; {{ $shopCount }} Shops
                            @else
                                Order #{{ $firstOrder->order_id }}
                            @endif
                        </p>
                        <p class="text-xs md:text-sm text-secondary mt-0.5 md:mt-1">
                            {{ $orders->sum(fn($o) => $o->items->count()) }} item(s) &middot; {{ $firstOrder->order_date?->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl md:text-2xl font-headline font-extrabold text-on-surface">&pound;{{ number_format($combinedTotal, 2) }}</p>
                    </div>
                </div>

                <div class="divide-y divide-surface-container-low">
                    @foreach($orders as $order)
                        <div class="px-4 md:px-8 py-4 md:py-6 hover:bg-surface-container-lowest transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3 md:mb-4">
                                <div class="flex items-center gap-2 md:gap-3">
                                    <div class="w-8 h-8 md:w-10 md:h-10 bg-surface-container overflow-hidden shrink-0 rounded">
                                        <img
                                            alt="{{ $order->shop->shop_name ?? 'Shop' }}"
                                            class="w-full h-full object-cover"
                                            src="{{ $order->shop->shop_image ?? 'https://via.placeholder.com/40' }}"
                                        />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-on-surface text-xs md:text-sm truncate">
                                            {{ $order->shop->shop_name ?? 'Unknown Shop' }}
                                        </p>
                                        <p class="text-[10px] md:text-xs text-secondary truncate">
                                            #{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}
                                            @if($order->collectionSlot)
                                                &middot; {{ $order->collectionSlot->slot_day }}
                                                {{ substr($order->collectionSlot->start_time, 0, 5) }}-{{ substr($order->collectionSlot->end_time, 0, 5) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 md:gap-3 sm:shrink-0">
                                    @php
                                        $statusColors = [
                                            'PENDING' => 'text-orange-600 bg-orange-50',
                                            'IN_PROGRESS' => 'text-blue-600 bg-blue-50',
                                            'READY' => 'text-primary bg-primary/10',
                                            'COMPLETED' => 'text-green-600 bg-green-50',
                                            'CANCELLED' => 'text-error bg-red-50',
                                        ];
                                        $statusColor = $statusColors[$order->order_status] ?? 'text-secondary bg-surface-container-low';
                                    @endphp
                                    <span class="px-2 md:px-3 py-0.5 md:py-1 text-[10px] font-bold uppercase tracking-wider whitespace-nowrap {{ $statusColor }}">
                                        {{ str_replace('_', ' ', $order->order_status) }}
                                    </span>
                                    <span class="font-bold text-on-surface text-xs md:text-sm shrink-0">&pound;{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>

                            <div class="mb-3 md:mb-4 grid grid-cols-1 sm:grid-cols-3 gap-2 md:gap-3">
                                <div class="bg-surface-container-low px-3 md:px-4 py-2 md:py-3">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-secondary">RFID Pickup Tag</p>
                                    <p class="mt-0.5 md:mt-1 font-mono text-xs md:text-sm font-bold text-on-surface truncate">
                                        {{ $order->rfid_uid ?? 'Not assigned yet' }}
                                    </p>
                                </div>
                                <div class="bg-surface-container-low px-3 md:px-4 py-2 md:py-3">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-secondary">Collection Method</p>
                                    <p class="mt-0.5 md:mt-1 text-xs md:text-sm font-bold text-on-surface">
                                        RFID counter scan
                                    </p>
                                </div>
                                <div class="bg-surface-container-low px-3 md:px-4 py-2 md:py-3">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-secondary">Collected At</p>
                                    <p class="mt-0.5 md:mt-1 text-xs md:text-sm font-bold text-on-surface">
                                        {{ $order->collected_at ? $order->collected_at->format('M d, Y H:i') : 'Awaiting collection' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-1.5 md:gap-2">
                                @foreach($order->items as $item)
                                    <span class="text-[10px] md:text-xs text-secondary bg-surface-container-low px-2 md:px-3 py-1 rounded">
                                        {{ $item->quantity }}x {{ $item->product->product_name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="px-4 md:px-8 py-3 md:py-4 bg-surface-container-low border-t border-surface-container-low">
                    <a href="{{ route('orders.show', $firstOrder) }}" class="text-primary font-bold text-xs md:text-sm hover:opacity-90 transition-opacity">
                        View Full Details &rarr;
                    </a>
                </div>
            </div>
        @endforeach
    </div>
    @else
    <div class="bg-surface-container-lowest border border-surface-container-low p-8 md:p-12 text-center">
        <span class="material-symbols-outlined text-3xl md:text-4xl text-secondary mb-3 md:mb-4 block">shopping_bag</span>
        <p class="text-sm md:text-base text-secondary">No orders yet.</p>
        <a href="{{ route('home') }}" class="mt-3 md:mt-4 inline-block px-5 md:px-6 py-2 md:py-2.5 bg-primary text-on-primary font-bold text-xs md:text-sm hover:opacity-90 transition-opacity">
            Start Shopping
        </a>
    </div>
    @endif
</div>
@stop
