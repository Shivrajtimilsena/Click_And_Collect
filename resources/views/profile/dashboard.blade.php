@extends('layouts.profile')

@section('profile-content')
<!-- Section 1: Upcoming Collections -->
@if($upcomingCollections->count() > 0)
<section class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h3 class="font-headline text-3xl font-extrabold tracking-tight text-on-surface">
                Upcoming Collections
            </h3>
            <p class="text-on-surface-variant mt-1">Don't forget to bring your QR code.</p>
        </div>
        <a 
            href="{{ route('profile.orders') }}"
            class="text-primary font-bold text-sm uppercase tracking-widest border-b-2 border-primary/20 hover:border-primary transition-all pb-1"
        >
            View All
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @foreach($upcomingCollections as $order)
        <!-- Collection Card -->
        <div class="bg-surface-container-low rounded-2xl p-7 sm:p-8 flex flex-col justify-between group relative overflow-hidden min-h-[260px] shadow-sm ring-1 ring-outline-variant/10">
            <div class="relative z-10">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <!-- Status Badge -->
                    <span class="@if($order->order_status === 'READY') bg-primary/10 text-primary @else bg-secondary-container text-on-secondary-container @endif px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">
                        @if($order->order_status === 'READY')
                            Ready for Collection
                        @elseif($order->order_status === 'PENDING')
                            Processing
                        @else
                            {{ ucfirst(strtolower($order->order_status)) }}
                        @endif
                    </span>
                    <div class="bg-white p-3 rounded-2xl shadow-sm ring-1 ring-outline-variant/10">
                        <span class="material-symbols-outlined text-on-surface @if($order->order_status !== 'READY') text-zinc-300 @endif">
                            qr_code_2
                        </span>
                    </div>
                </div>

                <!-- Shop Name & Order Details -->
                <h4 class="font-headline text-2xl font-bold mb-2 text-on-surface">
                    {{ $order->collectionSlot->shop->shop_name ?? 'Unknown Shop' }}
                </h4>
                <p class="text-on-surface-variant text-sm mb-4">
                    Order #{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }} • {{ $order->items->count() }} Items
                </p>

                <!-- Collection Time -->
                <div class="flex items-center gap-2 @if($order->order_status === 'READY') text-primary @else text-on-surface-variant @endif font-bold">
                    <span class="material-symbols-outlined text-lg">
                        @if($order->order_status === 'READY')
                            schedule
                        @else
                            calendar_today
                        @endif
                    </span>
                    <span class="text-sm">
                        {{ $order->collectionSlot->slot_date->format('l') }}, {{ $order->collectionSlot->start_time }} - {{ $order->collectionSlot->end_time }}
                    </span>
                </div>
            </div>

            <!-- Background Image -->
            <img 
                alt="Shop background" 
                class="absolute -right-16 -bottom-16 w-64 h-64 object-cover opacity-10 group-hover:scale-110 transition-transform duration-500"
                src="{{ $order->collectionSlot->shop->banner_image ?? 'https://via.placeholder.com/256' }}"
            />
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Section 2: Order History -->
<section class="space-y-6">
    <h3 class="font-headline text-3xl font-extrabold tracking-tight text-on-surface">
        Recent History
    </h3>

    @if($recentOrders->count() > 0)
    <div class="bg-surface-container overflow-hidden rounded-2xl shadow-sm ring-1 ring-outline-variant/10">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-on-surface-variant border-b border-outline-variant/10 bg-surface-container-low">
                    <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-bold">Order Details</th>
                    <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-bold">Status</th>
                    <th class="px-8 py-5 text-[10px] uppercase tracking-[0.2em] font-bold text-right">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10">
                @foreach($recentOrders as $order)
                <tr class="bg-white hover:bg-surface-container-lowest transition-colors">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <!-- Shop Image -->
                            <div class="w-12 h-12 bg-surface-container overflow-hidden rounded-lg shrink-0">
                                <img 
                                    alt="Shop" 
                                    class="w-full h-full object-cover"
                                    src="{{ $order->collectionSlot->shop->shop_image ?? 'https://via.placeholder.com/48' }}"
                                />
                            </div>
                            <div>
                                <p class="font-bold text-on-surface">
                                    {{ $order->collectionSlot->shop->shop_name ?? 'Unknown Shop' }}
                                </p>
                                <p class="text-xs text-on-surface-variant">
                                    {{ $order->order_date?->format('M d, Y') }} • #{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <!-- Status Badge -->
                        <span class="inline-flex items-center space-x-1.5 rounded-full @if($order->order_status === 'COMPLETED') text-on-surface bg-surface-container-low @elseif($order->order_status === 'READY') text-primary bg-primary/10 @else text-on-surface-variant bg-surface-container-low @endif px-3 py-1 text-xs font-bold uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full @if($order->order_status === 'COMPLETED') bg-on-surface @elseif($order->order_status === 'READY') bg-primary @else bg-on-surface-variant @endif"></span>
                            <span>{{ ucfirst(strtolower($order->order_status)) }}</span>
                        </span>
                    </td>
                    <td class="px-8 py-6 text-right font-bold text-on-surface">
                        ${{ number_format($order->total_amount, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        @if($recentOrders->hasPages())
        <div class="bg-white p-6 border-t border-outline-variant/10">
            {{ $recentOrders->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="bg-surface-container p-12 text-center rounded-2xl shadow-sm ring-1 ring-outline-variant/10">
        <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-4 block">shopping_bag</span>
        <p class="text-on-surface-variant">No orders yet. Start shopping!</p>
        <a href="{{ route('home') }}" class="mt-4 inline-block px-6 py-2 bg-primary text-on-primary font-bold text-sm hover:opacity-90 transition-opacity">
            Browse Shops
        </a>
    </div>
    @endif
</section>
@endsection
