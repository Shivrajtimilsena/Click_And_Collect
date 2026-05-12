@extends('app')

@section('title', 'Profile Settings | Click&Collect')

@section('content')
<div class="pt-8 pb-32 px-6 max-w-7xl mx-auto">
    <!-- Profile Header Bento Style -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-12">
        <!-- User Info Card -->
        <div class="md:col-span-8 bg-surface-container-lowest rounded-lg p-10 flex flex-col md:flex-row items-center md:items-start gap-8 shadow-[0_10px_30px_rgba(45,47,47,0.04)] relative overflow-hidden">
            <div class="w-32 h-32 rounded-full overflow-hidden bg-surface-container-high ring-4 ring-primary-container/20">
                @if(Auth::user()->avatar_url)
                    <img class="w-full h-full object-cover" src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->full_name }}"/>
                @else
                    <div class="w-full h-full bg-gradient-to-br from-primary to-primary-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-5xl">account_circle</span>
                    </div>
                @endif
            </div>
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-on-background mb-2">{{ Auth::user()->full_name ?? 'Customer' }}</h1>
                <p class="text-on-surface-variant font-medium mb-6">Member since {{ Auth::user()->created_at->format('M Y') }}</p>
                <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                    <button onclick="openEditProfileModal()" class="px-8 py-3 rounded-full bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold text-sm tracking-wide active:scale-95 transition-all shadow-lg shadow-primary/20 cursor-pointer">Edit Profile</button>
                </div>
            </div>
            <!-- Abstract visual element -->
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl"></div>
        </div>

        <!-- Stats/Summary Card -->
        <div class="md:col-span-4 bg-surface-container rounded-lg p-8 flex flex-col justify-between">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-on-surface-variant block mb-6">Collection Point</span>
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined text-primary">location_on</span>
                    <span class="font-bold text-lg">Cleckhuddersfax</span>
                </div>
                <p class="text-sm text-on-surface-variant">12–14 High Street, HX1 1AA</p>
            </div>
            <div class="pt-6 mt-6 border-t border-outline-variant/20">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-semibold">Total Orders</span>
                    <span class="text-2xl font-black text-primary">{{ $orders->count() ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Left Column: Upcoming Collection Slots -->
        <div class="lg:col-span-1">
            <div class="sticky top-28">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-extrabold tracking-tight">Collection Slots</h2>
                    <span class="material-symbols-outlined text-on-surface-variant">calendar_today</span>
                </div>
                <div class="space-y-4">
                    @forelse($upcomingSlots ?? [] as $slot)
                        @if($slot)
                            <div class="p-6 rounded-lg bg-surface-container-lowest border-l-4 border-primary shadow-sm hover:translate-x-1 transition-transform">
                                <h3 class="font-bold text-on-background mb-4">{{ $slot->slot_date ? $slot->slot_date->format('l, M d') : 'Date TBA' }}</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between text-sm p-3 rounded-md bg-surface-container-low">
                                        <span class="font-medium">{{ $slot->start_time ?? 'TBA' }} — {{ $slot->end_time ?? 'TBA' }}</span>
                                        <span class="text-[10px] font-bold bg-primary/10 text-primary px-2 py-1 rounded">{{ $slot->capacity - $slot->total_order ?? 0 }} LEFT</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="p-6 rounded-lg bg-surface-container-low text-center">
                            <p class="text-sm text-on-surface-variant">No upcoming collection slots</p>
                        </div>
                    @endforelse
                </div>
                <button class="w-full mt-8 py-4 rounded-full border-2 border-outline/20 text-on-surface font-bold text-sm uppercase tracking-widest hover:bg-surface-container transition-colors">
                    View All Slots
                </button>
            </div>
        </div>

        <!-- Right Column: Recent Order History -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-extrabold tracking-tight">Recent Orders</h2>
                <div class="flex gap-4">
                    <button class="text-xs font-bold uppercase tracking-widest text-primary">All Orders</button>
                    <button class="text-xs font-bold uppercase tracking-widest text-on-surface-variant opacity-40">Returns</button>
                </div>
            </div>

            <div class="space-y-10">
                @forelse($orders ?? [] as $order)
                    @php
                        $firstItem = $order->items?->first();
                        $shopName = $firstItem?->product?->shop?->shop_name ?? 'Order';
                        $traderAvatar = $firstItem?->product?->shop?->trader?->user?->avatar_url;
                    @endphp
                    <!-- Order Group -->
                    <div class="bg-surface-container-lowest overflow-hidden shadow-[0_10px_30px_rgba(45,47,47,0.02)]">
                        <!-- Order Header -->
                        <div class="px-8 py-6 bg-surface-container-low flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full overflow-hidden">
                                    @if($traderAvatar)
                                        <img class="w-full h-full object-cover" src="{{ $traderAvatar }}" alt="{{ $shopName }}"/>
                                    @else
                                        <div class="w-full h-full bg-primary flex items-center justify-center text-white text-sm font-bold">
                                            {{ substr($shopName, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-on-background uppercase tracking-tight text-sm">{{ $shopName }}</h4>
                                    <p class="text-xs text-on-surface-variant">Order #{{ $order->order_id }} — {{ $order->created_at?->format('M d, Y') ?? 'Date TBA' }}</p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-on-surface-variant cursor-pointer">more_vert</span>
                        </div>

                        <!-- Order Items -->
                        <div class="p-8 space-y-6">
                            @forelse($order->items as $item)
                                <div class="flex items-center gap-6">
                                    <div class="w-20 h-20 rounded-lg bg-surface-container-high relative flex-shrink-0">
                                        @if($item->product->image_url)
                                            <img class="w-full h-full object-cover rounded-lg" src="{{ $item->product->image_url }}" alt="{{ $item->product->product_name }}"/>
                                        @else
                                            <div class="w-full h-full bg-surface-container flex items-center justify-center">
                                                <span class="material-symbols-outlined text-gray-400">image</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <h5 class="font-bold">{{ $item->product->product_name }}</h5>
                                            <span class="font-bold">£{{ number_format($item->line_total / $item->quantity, 2) }}</span>
                                        </div>
                                        <p class="text-xs text-on-surface-variant mt-1">Quantity: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-on-surface-variant">No items in this order</p>
                            @endforelse
                        </div>

                    </div>
                @empty
                    <div class="bg-surface-container-lowest rounded-lg p-12 text-center">
                        <span class="material-symbols-outlined text-4xl text-on-surface-variant block mb-4">shopping_bag</span>
                        <p class="text-on-surface-variant font-medium">No orders yet. Start shopping!</p>
                        <a href="{{ route('home') }}" class="text-primary font-bold mt-4 inline-block">Browse Artisans</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

@include('modals.edit-profile-modal')
