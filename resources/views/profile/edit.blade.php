@extends('app')

@section('title', 'Customer Profile | Click and Collect')

@section('content')
<main class="pt-32 pb-24 px-6 md:px-12 max-w-screen-2xl mx-auto">
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Left Sidebar: Personal Info & Navigation -->
        <aside class="w-full lg:w-80 flex flex-col space-y-8">
            <!-- Profile Card -->
            <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_10px_30px_rgba(45,47,47,0.04)] relative overflow-hidden">
                <!-- Subtle Gradient Accent -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-linear-to-br from-primary/10 to-transparent rounded-bl-full"></div>
                <div class="relative z-10">
                    <!-- Profile Avatar -->
                    <div class="w-24 h-24 rounded-full overflow-hidden mb-6 ring-4 ring-surface-container">
                        <img 
                            alt="User avatar" 
                            class="w-full h-full object-cover" 
                            src="{{ Auth::user()->avatar_url ?? 'https://via.placeholder.com/96' }}"
                        />
                    </div>

                    <!-- Profile Info -->
                    <h2 class="font-headline text-2xl font-bold text-on-surface mb-1">
                        {{ Auth::user()->full_name }}
                    </h2>
                    <p class="text-on-surface-variant text-sm mb-6">
                        {{ Auth::user()->role === 'customer' ? 'Valued Customer' : Auth::user()->role }} since {{ Auth::user()->created_at->format('Y') }}
                    </p>

                    <!-- Contact Info -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-primary text-xl">mail</span>
                            <span class="text-sm">{{ Auth::user()->email }}</span>
                        </div>
                        @if(Auth::user()->customer && Auth::user()->customer->city)
                        <div class="flex items-center space-x-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-primary text-xl">location_on</span>
                            <span class="text-sm">{{ Auth::user()->customer->city }}, {{ Auth::user()->customer->address }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Edit Profile Button -->
                    <a 
                        href="{{ route('profile.edit') }}"
                        class="mt-8 w-full py-3 px-6 bg-on-background text-surface rounded-full font-bold text-sm tracking-wide hover:opacity-90 transition-opacity active:scale-95 inline-block text-center"
                    >
                        Edit Profile
                    </a>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex flex-col space-y-2">
                <a 
                    href="{{ route('profile.edit') }}"
                    class="flex items-center space-x-4 p-4 bg-white shadow-sm rounded-xl text-primary font-bold hover:shadow-md transition-all"
                >
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">grid_view</span>
                    <span class="text-sm uppercase tracking-widest">Dashboard</span>
                </a>
                <a 
                    href="{{ route('orders.index') }}"
                    class="flex items-center space-x-4 p-4 text-on-surface-variant hover:bg-surface-container-low rounded-xl transition-colors group"
                >
                    <span class="material-symbols-outlined group-hover:text-primary transition-colors">shopping_bag</span>
                    <span class="text-sm uppercase tracking-widest">My Orders</span>
                </a>
                <a 
                    href="{{ route('wishlist.index') }}"
                    class="flex items-center space-x-4 p-4 text-on-surface-variant hover:bg-surface-container-low rounded-xl transition-colors group"
                >
                    <span class="material-symbols-outlined group-hover:text-primary transition-colors">favorite</span>
                    <span class="text-sm uppercase tracking-widest">Saved Shops</span>
                </a>
                <a 
                    href="{{ route('profile.edit') }}"
                    class="flex items-center space-x-4 p-4 text-on-surface-variant hover:bg-surface-container-low rounded-xl transition-colors group"
                >
                    <span class="material-symbols-outlined group-hover:text-primary transition-colors">settings</span>
                    <span class="text-sm uppercase tracking-widest">Settings</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <section class="flex-1 space-y-12">
            <!-- Section 1: Upcoming Collections (Bento Style) -->
            @if($upcomingCollections->count() > 0)
            <div>
                <div class="flex justify-between items-end mb-8">
                    <div>
                        <h3 class="font-headline text-3xl font-extrabold tracking-tight text-on-surface">
                            Upcoming Collections
                        </h3>
                        <p class="text-on-surface-variant mt-1">Don't forget to bring your QR code.</p>
                    </div>
                    <a 
                        href="{{ route('orders.index') }}"
                        class="text-primary font-bold text-sm uppercase tracking-widest border-b-2 border-primary/20 hover:border-primary transition-all pb-1"
                    >
                        View All
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($upcomingCollections as $order)
                    <!-- Collection Card -->
                    <div class="bg-surface-container-low rounded-lg p-8 flex flex-col justify-between group relative overflow-hidden min-h-[280px]">
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-6">
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
                                <div class="bg-white p-3 rounded-2xl shadow-sm">
                                    <span class="material-symbols-outlined text-on-surface @if($order->order_status !== 'READY') text-zinc-300 @endif">
                                        qr_code_2
                                    </span>
                                </div>
                            </div>

                            <!-- Shop Name & Order Details -->
                            <h4 class="font-headline text-2xl font-bold mb-2">
                                {{ $order->collectionSlot->shop->shop_name ?? 'Unknown Shop' }}
                            </h4>
                            <p class="text-on-surface-variant text-sm mb-4">
                                Order #{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }} • {{ $order->items->count() }} Items
                            </p>

                            <!-- Collection Time -->
                            <div class="flex items-center space-x-2 @if($order->order_status === 'READY') text-primary @else text-on-surface-variant @endif font-bold">
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
                            class="absolute -right-16 -bottom-16 w-64 h-64 object-cover opacity-10 group-hover:scale-110 transition-transform duration-500 rounded-full"
                            src="{{ $order->collectionSlot->shop->banner_image ?? 'https://via.placeholder.com/256' }}"
                        />
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Section 2: Order History -->
            <div>
                <h3 class="font-headline text-3xl font-extrabold tracking-tight text-on-surface mb-8">
                    Recent History
                </h3>

                @if($recentOrders->count() > 0)
                <div class="bg-surface-container rounded-lg overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-on-surface-variant border-b border-outline-variant/10">
                                <th class="px-8 py-6 text-[10px] uppercase tracking-[0.2em] font-bold">Order Details</th>
                                <th class="px-8 py-6 text-[10px] uppercase tracking-[0.2em] font-bold">Status</th>
                                <th class="px-8 py-6 text-[10px] uppercase tracking-[0.2em] font-bold text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/10">
                            @foreach($recentOrders as $order)
                            <tr class="bg-white hover:bg-surface-container-lowest transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-4">
                                        <!-- Shop Image -->
                                        <div class="w-12 h-12 bg-surface-container rounded-lg overflow-hidden flex-shrink-0">
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
                                    <span class="inline-flex items-center space-x-1.5 @if($order->order_status === 'COMPLETED') text-zinc-600 bg-zinc-100 @elseif($order->order_status === 'READY') text-green-600 bg-green-100 @else text-amber-600 bg-amber-100 @endif px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                                        <span class="w-1.5 h-1.5 rounded-full @if($order->order_status === 'COMPLETED') bg-zinc-400 @elseif($order->order_status === 'READY') @else @endif"></span>
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

                    <!-- Load More or Pagination -->
                    @if($recentOrders->hasPages())
                    <div class="bg-white p-6 text-center border-t border-outline-variant/10">
                        {{ $recentOrders->links() }}
                    </div>
                    @endif
                </div>
                @else
                <div class="bg-surface-container rounded-lg p-12 text-center">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-4 block">shopping_bag</span>
                    <p class="text-on-surface-variant">No orders yet. Start shopping!</p>
                    <a href="{{ route('home') }}" class="mt-4 inline-block px-6 py-2 bg-primary text-on-primary rounded-full font-bold text-sm hover:opacity-90 transition-opacity">
                        Browse Shops
                    </a>
                </div>
                @endif
            </div>
        </section>
    </div>
</main>
@endsection