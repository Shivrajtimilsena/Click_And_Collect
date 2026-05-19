@extends('layouts.customer')

@section('title', 'Dashboard | My Account')

@section('header-title', 'Overview / Dashboard')

@section('content')
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
    <div class="bg-surface-container-lowest p-5 md:p-8 border border-surface-container-high relative overflow-hidden group">
        <div class="flex flex-col gap-1.5 md:gap-2 relative z-10">
            <span class="text-[10px] md:text-xs font-bold text-secondary uppercase tracking-widest">Active Orders</span>
            <span class="text-2xl md:text-4xl font-headline font-extrabold text-on-surface">{{ $activeOrdersCount }}</span>
            <span class="text-[10px] md:text-xs text-green-600 font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">trending_up</span>
                @if($activeOrdersCount > 0) Awaiting collection @else No active orders @endif
            </span>
        </div>
        <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-6xl md:text-8xl text-surface-container-high opacity-30 group-hover:opacity-50 transition-opacity">shopping_cart</span>
    </div>

    <div class="bg-surface-container-lowest p-5 md:p-8 border border-surface-container-high relative overflow-hidden group">
        <div class="flex flex-col gap-1.5 md:gap-2 relative z-10">
            <span class="text-[10px] md:text-xs font-bold text-secondary uppercase tracking-widest">Total Spent</span>
            <span class="text-2xl md:text-4xl font-headline font-extrabold text-primary">&pound;{{ number_format($totalSpent, 2) }}</span>
            <span class="text-[10px] md:text-xs text-secondary font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">payments</span>
                Lifetime orders
            </span>
        </div>
        <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-6xl md:text-8xl text-surface-container-high opacity-30 group-hover:opacity-50 transition-opacity">account_balance_wallet</span>
    </div>

    <div class="bg-surface-container-lowest p-5 md:p-8 border border-surface-container-high relative overflow-hidden group sm:col-span-2 lg:col-span-1">
        <div class="flex flex-col gap-1.5 md:gap-2 relative z-10">
            <span class="text-[10px] md:text-xs font-bold text-secondary uppercase tracking-widest">Saved Shops</span>
            <span class="text-2xl md:text-4xl font-headline font-extrabold text-on-surface">{{ str_pad($savedShopsCount, 2, '0', STR_PAD_LEFT) }}</span>
            <span class="text-[10px] md:text-xs text-primary-dim font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">favorite</span>
                @if($savedShopsCount > 0) Shops you love @else Browse and save @endif
            </span>
        </div>
        <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-6xl md:text-8xl text-surface-container-high opacity-30 group-hover:opacity-50 transition-opacity">favorite</span>
    </div>
</section>

<!-- Section: Upcoming Collections -->
@if($upcomingCollections->count() > 0)
<section class="space-y-4 md:space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-base md:text-lg font-bold font-headline">Upcoming Collections</h3>
        <a href="{{ route('profile.orders') }}" class="text-[10px] md:text-xs font-bold text-primary uppercase tracking-widest hover:underline">View All</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        @foreach($upcomingCollections as $order)
        <div class="bg-surface-container-lowest border border-surface-container-high p-5 md:p-7 lg:p-8 flex flex-col justify-between group relative overflow-hidden min-h-[200px] md:min-h-[240px]">
            <div class="relative z-10">
                <div class="flex items-start justify-between gap-4 mb-4 md:mb-5">
                    <span class="px-2.5 md:px-3 py-1 text-[10px] font-bold uppercase tracking-wider {{ $order->order_status === 'READY' ? 'bg-primary/10 text-primary' : 'bg-surface-container-high text-secondary' }}">
                        @if($order->order_status === 'READY')
                            Ready for Collection
                        @elseif($order->order_status === 'PENDING')
                            Processing
                        @else
                            {{ ucfirst(strtolower($order->order_status)) }}
                        @endif
                    </span>
                    <span class="material-symbols-outlined text-xl md:text-2xl text-on-surface-muted {{ $order->order_status !== 'READY' ? 'text-zinc-300' : '' }}">qr_code_2</span>
                </div>

                <h4 class="font-headline text-lg md:text-xl font-bold mb-1 text-on-surface">
                    {{ $order->shop->shop_name ?? 'Unknown Shop' }}
                </h4>
                <p class="text-xs md:text-sm text-secondary mb-3 md:mb-4">
                    Order #{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }} &bull; {{ $order->items->count() }} Items
                </p>

                <div class="flex items-center gap-2 {{ $order->order_status === 'READY' ? 'text-primary' : 'text-secondary' }} font-bold">
                    <span class="material-symbols-outlined text-base md:text-lg">
                        {{ $order->order_status === 'READY' ? 'schedule' : 'calendar_today' }}
                    </span>
                    <span class="text-xs md:text-sm">
                        {{ $order->collectionSlot->slot_date?->format('l') ?? 'N/A' }}, {{ $order->collectionSlot->start_time }} - {{ $order->collectionSlot->end_time }}
                    </span>
                </div>
            </div>

            <img
                alt="Shop background"
                class="absolute -right-16 -bottom-16 w-48 md:w-60 h-48 md:h-60 object-cover opacity-10 group-hover:scale-110 transition-transform duration-500"
                src="{{ $order->shop->banner_image ?? 'https://via.placeholder.com/256' }}"
            />
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Section: Recent Orders -->
<section class="space-y-4 md:space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-base md:text-lg font-bold font-headline">Recent Orders</h3>
        <a href="{{ route('profile.orders') }}" class="text-[10px] md:text-xs font-bold text-primary uppercase tracking-widest hover:underline">View All Orders</a>
    </div>

    @if($recentOrders->count() > 0)
    <div class="bg-surface-container-lowest border border-surface-container-high overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[500px] md:min-w-0">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-4 md:px-8 py-3 md:py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Shop</th>
                        <th class="px-4 md:px-8 py-3 md:py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Order Details</th>
                        <th class="px-4 md:px-8 py-3 md:py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Status</th>
                        <th class="px-4 md:px-8 py-3 md:py-4 text-[10px] font-bold text-secondary uppercase tracking-widest text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-low">
                    @foreach($recentOrders as $order)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="px-4 md:px-8 py-4 md:py-6">
                            <div class="flex items-center gap-2 md:gap-3">
                                <div class="w-8 h-8 md:w-10 md:h-10 bg-surface-container overflow-hidden shrink-0 rounded">
                                    <img
                                        alt="{{ $order->shop->shop_name ?? 'Shop' }}"
                                        class="w-full h-full object-cover"
                                        src="{{ $order->shop->shop_image ?? 'https://via.placeholder.com/40' }}"
                                    />
                                </div>
                                <p class="text-xs md:text-sm font-bold text-on-surface truncate max-w-[80px] md:max-w-none">{{ $order->shop->shop_name ?? 'Unknown Shop' }}</p>
                            </div>
                        </td>
                        <td class="px-4 md:px-8 py-4 md:py-6">
                            <p class="text-xs md:text-sm text-on-surface font-medium">#{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-[10px] md:text-xs text-secondary">{{ $order->order_date?->format('M d, Y') }}</p>
                        </td>
                        <td class="px-4 md:px-8 py-4 md:py-6">
                            @php
                                $statusColors = [
                                    'PENDING' => 'bg-zinc-100 text-zinc-500',
                                    'IN_PROGRESS' => 'bg-blue-100 text-blue-700',
                                    'READY' => 'bg-orange-100 text-orange-700',
                                    'COMPLETED' => 'bg-green-100 text-green-700',
                                    'CANCELLED' => 'bg-red-100 text-red-700',
                                ];
                                $statusColor = $statusColors[$order->order_status] ?? 'bg-zinc-100 text-zinc-500';
                            @endphp
                            <span class="px-2 md:px-3 py-0.5 md:py-1 text-[10px] font-bold uppercase whitespace-nowrap {{ $statusColor }}">
                                {{ str_replace('_', ' ', $order->order_status) }}
                            </span>
                        </td>
                        <td class="px-4 md:px-8 py-4 md:py-6 text-right font-bold text-xs md:text-sm">&pound;{{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($recentOrders->hasPages())
        <div class="p-4 md:p-6 border-t border-surface-container-low">
            {{ $recentOrders->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="bg-surface-container-lowest border border-surface-container-high p-8 md:p-12 text-center">
        <span class="material-symbols-outlined text-3xl md:text-4xl text-secondary mb-3 md:mb-4 block">shopping_bag</span>
        <p class="text-sm md:text-base text-secondary">No orders yet. Start shopping!</p>
        <a href="{{ route('home') }}" class="mt-3 md:mt-4 inline-block px-5 md:px-6 py-2 md:py-2.5 bg-primary text-on-primary text-[10px] md:text-xs font-bold hover:opacity-90 transition-opacity uppercase tracking-wider">
            Browse Shops
        </a>
    </div>
    @endif
</section>
@endsection
