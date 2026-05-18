@extends('layouts.profile')

@section('profile-content')
<div>
    <h3 class="font-headline text-3xl font-extrabold tracking-tight text-on-surface mb-8">
        My Orders
    </h3>

    @if($orderGroups->count() > 0)
    <div class="space-y-6">
        @foreach($orderGroups as $groupId => $orders)
            @php
                $orders = is_iterable($orders) ? collect($orders) : collect([$orders]);
                $firstOrder = $orders->first();
                $combinedTotal = $orders->sum('total_amount');
                $shopCount = $orders->count();
            @endphp

            <div class="bg-surface-container overflow-hidden">
                <div class="px-8 py-6 border-b border-outline-variant/10 flex items-start justify-between">
                    <div>
                        <p class="text-[10px] text-on-surface-variant uppercase tracking-[0.2em] font-bold">
                            @if($shopCount > 1)
                                Order &middot; {{ $shopCount }} Shops
                            @else
                                Order #{{ $firstOrder->order_id }}
                            @endif
                        </p>
                        <p class="text-sm text-on-surface-variant mt-1">
                            {{ $orders->sum(fn($o) => $o->items->count()) }} item(s) &middot; {{ $firstOrder->order_date?->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-headline font-extrabold text-on-surface">&pound;{{ number_format($combinedTotal, 2) }}</p>
                    </div>
                </div>

                <div class="divide-y divide-outline-variant/10">
                    @foreach($orders as $order)
                        <div class="px-8 py-6 hover:bg-surface-container-lowest transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-surface-container overflow-hidden shrink-0">
                                        <img
                                            alt="{{ $order->shop->shop_name ?? 'Shop' }}"
                                            class="w-full h-full object-cover"
                                            src="{{ $order->shop->shop_image ?? 'https://via.placeholder.com/40' }}"
                                        />
                                    </div>
                                    <div>
                                        <p class="font-bold text-on-surface text-sm">
                                            {{ $order->shop->shop_name ?? 'Unknown Shop' }}
                                        </p>
                                        <p class="text-xs text-on-surface-variant">
                                            #{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}
                                            @if($order->collectionSlot)
                                                &middot; {{ $order->collectionSlot->slot_day }}
                                                {{ substr($order->collectionSlot->start_time, 0, 5) }}-{{ substr($order->collectionSlot->end_time, 0, 5) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    @php
                                        $statusColors = [
                                            'PENDING' => 'text-orange-600 bg-orange-50',
                                            'IN_PROGRESS' => 'text-blue-600 bg-blue-50',
                                            'READY' => 'text-primary bg-primary/10',
                                            'COMPLETED' => 'text-green-600 bg-green-50',
                                            'CANCELLED' => 'text-error bg-red-50',
                                        ];
                                        $statusColor = $statusColors[$order->order_status] ?? 'text-on-surface-variant bg-surface-container-low';
                                    @endphp
                                    <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                        {{ str_replace('_', ' ', $order->order_status) }}
                                    </span>
                                    <span class="font-bold text-on-surface">&pound;{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                            <div class="mb-4 grid gap-3 md:grid-cols-3">
                                <div class="bg-surface-container-low px-4 py-3">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-on-surface-variant">RFID Pickup Tag</p>
                                    <p class="mt-1 font-mono text-sm font-bold text-on-surface">
                                        {{ $order->rfid_uid ?? 'Not assigned yet' }}
                                    </p>
                                </div>
                                <div class="bg-surface-container-low px-4 py-3">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-on-surface-variant">Collection Method</p>
                                    <p class="mt-1 text-sm font-bold text-on-surface">
                                        RFID counter scan
                                    </p>
                                </div>
                                <div class="bg-surface-container-low px-4 py-3">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-on-surface-variant">Collected At</p>
                                    <p class="mt-1 text-sm font-bold text-on-surface">
                                        {{ $order->collected_at ? $order->collected_at->format('M d, Y H:i') : 'Awaiting collection' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($order->items as $item)
                                    <span class="text-xs text-on-surface-variant bg-surface-container-low px-2 py-1">
                                        {{ $item->quantity }}x {{ $item->product->product_name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="px-8 py-4 bg-surface-container-low border-t border-outline-variant/10">
                    <a href="{{ route('orders.show', $firstOrder) }}" class="text-primary font-bold text-sm hover:opacity-90 transition-opacity">
                        View Full Details &rarr;
                    </a>
                </div>
            </div>
        @endforeach
    </div>
    @else
    <div class="bg-surface-container p-12 text-center">
        <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-4 block">shopping_bag</span>
        <p class="text-on-surface-variant">No orders yet.</p>
        <a href="{{ route('home') }}" class="mt-4 inline-block px-6 py-2 bg-primary text-on-primary font-bold text-sm hover:opacity-90 transition-opacity">
            Start Shopping
        </a>
    </div>
    @endif
</div>
@endsection
