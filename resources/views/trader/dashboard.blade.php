@extends('layouts.trader')

@section('title', 'Trader Dashboard | Click&Collect')

@section('header-title', 'Overview / Dashboard')

@section('content')
<section class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-surface-container-lowest p-8 border border-surface-container-high relative overflow-hidden group">
        <div class="flex flex-col gap-2 relative z-10">
            <span class="text-xs font-bold text-secondary uppercase tracking-widest">Active Orders</span>
            <span class="text-4xl font-headline font-extrabold text-on-surface">{{ $activeOrders }}</span>
            <span class="text-xs text-green-600 font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">trending_up</span>
                @if($activeOrders > 0) Orders pending @else No orders @endif
            </span>
        </div>
        <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-8xl text-surface-container-high opacity-30 group-hover:opacity-50 transition-opacity">shopping_cart</span>
    </div>

    <div class="bg-surface-container-lowest p-8 border border-surface-container-high relative overflow-hidden group">
        <div class="flex flex-col gap-2 relative z-10">
            <span class="text-xs font-bold text-secondary uppercase tracking-widest">Total Revenue</span>
            <span class="text-4xl font-headline font-extrabold text-on-surface">&pound;{{ number_format($totalRevenue, 2) }}</span>
            <span class="text-xs text-primary font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">payments</span>
                All time earnings
            </span>
        </div>
        <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-8xl text-surface-container-high opacity-30 group-hover:opacity-50 transition-opacity">account_balance_wallet</span>
    </div>

    <div class="bg-surface-container-lowest p-8 border border-surface-container-high relative overflow-hidden group">
        <div class="flex flex-col gap-2 relative z-10">
            <span class="text-xs font-bold text-secondary uppercase tracking-widest">Low Stock Items</span>
            <span class="text-4xl font-headline font-extrabold text-error">{{ str_pad($lowStockItems, 2, '0', STR_PAD_LEFT) }}</span>
            <span class="text-xs text-error-dim font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-xs">warning</span>
                @if($lowStockItems > 0) Requires attention @else Stock OK @endif
            </span>
        </div>
        <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-8xl text-surface-container-high opacity-30 group-hover:opacity-50 transition-opacity">inventory_2</span>
    </div>

    <div class="bg-on-background p-8 border border-inverse-surface relative overflow-hidden group">
        <div class="flex flex-col gap-2 relative z-10">
            <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Customer Satisfaction</span>
            <span class="text-4xl font-headline font-extrabold text-white">{{ number_format($avgRating, 1) }}<span class="text-lg text-zinc-500">/5</span></span>
            <div class="flex gap-1">
                @for($i = 1; $i <= 5; $i++)
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' {{ $i <= round($avgRating) ? 1 : 0 }}, 'wght' 400, 'GRAD' 0, 'opsz' 24;">star</span>
                @endfor
            </div>
        </div>
    </div>
</section>

<section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-surface-container-lowest border border-surface-container-high">
        <div class="px-8 py-6 flex justify-between items-center border-b border-surface-container-high">
            <div>
                <h3 class="text-lg font-bold font-headline">Revenue Performance</h3>
                <p class="text-sm text-secondary" id="revenue-period-label">This week's performance</p>
            </div>
            <div class="flex gap-2" id="revenue-tabs">
                <button data-period="weekly" class="revenue-tab px-4 py-1.5 text-xs font-bold uppercase tracking-wider bg-primary text-on-primary">Weekly</button>
                <button data-period="monthly" class="revenue-tab px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-zinc-500">Monthly</button>
                <button data-period="yearly" class="revenue-tab px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-zinc-500">Yearly</button>
            </div>
        </div>

        {{-- Weekly Chart --}}
        <div id="revenue-chart-weekly" class="revenue-chart">
            @php $amounts = array_column($weeklyRevenue, 'amount'); $maxRevenue = max(max($amounts), 1); @endphp
            <div class="h-64 px-8 flex items-end justify-between gap-4 pt-4 relative">
                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none opacity-20 py-4">
                    <div class="w-full border-t border-zinc-400"></div>
                    <div class="w-full border-t border-zinc-400"></div>
                    <div class="w-full border-t border-zinc-400"></div>
                    <div class="w-full border-t border-zinc-400"></div>
                </div>
                @foreach($weeklyRevenue as $data)
                    @php $amount = (float) $data['amount']; $height = $amount > 0 ? ($amount / $maxRevenue) * 100 : 5; @endphp
                    <div class="flex-1 flex flex-col items-center justify-end group relative">
                        <div class="w-full bg-surface-container-high hover:bg-primary transition-all cursor-pointer relative" style="height: {{ $height }}%;">
                            @if($amount > 0)
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-on-background text-white text-[10px] py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    &pound;{{ number_format($amount, 2) }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between px-8 pb-6 mt-4 text-[10px] font-bold text-zinc-400 uppercase tracking-widest">
                @foreach($weeklyRevenue as $data)
                    <span>{{ $data['label'] }}</span>
                @endforeach
            </div>
        </div>

        {{-- Monthly Chart --}}
        <div id="revenue-chart-monthly" class="revenue-chart hidden">
            @php $amounts = array_column($monthlyRevenue, 'amount'); $maxRevenue = max(max($amounts), 1); @endphp
            <div class="h-64 px-8 flex items-end justify-between gap-4 pt-4 relative">
                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none opacity-20 py-4">
                    <div class="w-full border-t border-zinc-400"></div>
                    <div class="w-full border-t border-zinc-400"></div>
                    <div class="w-full border-t border-zinc-400"></div>
                    <div class="w-full border-t border-zinc-400"></div>
                </div>
                @foreach($monthlyRevenue as $data)
                    @php $amount = (float) $data['amount']; $height = $amount > 0 ? ($amount / $maxRevenue) * 100 : 5; @endphp
                    <div class="flex-1 flex flex-col items-center justify-end group relative">
                        <div class="w-full bg-surface-container-high hover:bg-primary transition-all cursor-pointer relative" style="height: {{ $height }}%;">
                            @if($amount > 0)
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-on-background text-white text-[10px] py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    &pound;{{ number_format($amount, 2) }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between px-8 pb-6 mt-4 text-[10px] font-bold text-zinc-400 uppercase tracking-widest">
                @foreach($monthlyRevenue as $data)
                    <span title="{{ $data['full_label'] }}">{{ $data['label'] }}</span>
                @endforeach
            </div>
        </div>

        {{-- Yearly Chart --}}
        <div id="revenue-chart-yearly" class="revenue-chart hidden">
            @php $amounts = array_column($yearlyRevenue, 'amount'); $maxRevenue = max(max($amounts), 1); @endphp
            <div class="h-64 px-8 flex items-end justify-between gap-4 pt-4 relative">
                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none opacity-20 py-4">
                    <div class="w-full border-t border-zinc-400"></div>
                    <div class="w-full border-t border-zinc-400"></div>
                    <div class="w-full border-t border-zinc-400"></div>
                    <div class="w-full border-t border-zinc-400"></div>
                </div>
                @foreach($yearlyRevenue as $data)
                    @php $amount = (float) $data['amount']; $height = $amount > 0 ? ($amount / $maxRevenue) * 100 : 5; @endphp
                    <div class="flex-1 flex flex-col items-center justify-end group relative">
                        <div class="w-full bg-surface-container-high hover:bg-primary transition-all cursor-pointer relative" style="height: {{ $height }}%;">
                            @if($amount > 0)
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-on-background text-white text-[10px] py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    &pound;{{ number_format($amount, 2) }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between px-8 pb-6 mt-4 text-[10px] font-bold text-zinc-400 uppercase tracking-widest">
                @foreach($yearlyRevenue as $data)
                    <span>{{ $data['label'] }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-surface-container-lowest border border-surface-container-high">
        <div class="px-8 py-6 border-b border-surface-container-high">
            <h3 class="text-lg font-bold font-headline">Quick Stats</h3>
        </div>
        <div class="p-8 space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-surface-container-high">
                        <span class="material-symbols-outlined text-secondary">store</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-on-surface">Your Shops</p>
                        <p class="text-xs text-secondary">{{ $shops->count() }} active</p>
                    </div>
                </div>
                <span class="text-lg font-bold text-on-surface">{{ $shops->count() }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-surface-container-high">
                        <span class="material-symbols-outlined text-secondary">inventory_2</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-on-surface">Total Products</p>
                        <p class="text-xs text-secondary">across all shops</p>
                    </div>
                </div>
                <span class="text-lg font-bold text-on-surface">{{ $shops->sum(fn($s) => $s->products->count()) }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 flex items-center justify-center bg-surface-container-high">
                        <span class="material-symbols-outlined text-secondary">shopping_bag</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-on-surface">Total Orders</p>
                        <p class="text-xs text-secondary">all time</p>
                    </div>
                </div>
                <span class="text-lg font-bold text-on-surface">{{ $recentOrders->count() }}+</span>
            </div>
        </div>
    </div>

    <div class="lg:col-span-3 bg-surface-container-lowest border border-surface-container-high overflow-hidden">
        <div class="px-8 py-6 flex justify-between items-center border-b border-surface-container-high">
            <h3 class="text-lg font-bold font-headline">Recent Orders</h3>
            <a href="{{ route('trader.orders.index') }}" class="text-xs font-bold text-primary uppercase tracking-widest hover:underline">View All Orders</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Customer</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Collection Slot</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Items</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-low">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 flex items-center justify-center bg-surface-container-high font-bold text-xs text-secondary">
                                        {{ strtoupper(substr($order->customer->user->full_name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-on-surface">{{ $order->customer->user->full_name ?? 'Unknown' }}</p>
                                        <p class="text-[10px] text-zinc-400">#ORD-{{ $order->order_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-sm">
                                @if($order->collectionSlot)
                                    {{ $order->collectionSlot->slot_day }} {{ substr($order->collectionSlot->start_time, 0, 5) }}-{{ substr($order->collectionSlot->end_time, 0, 5) }}
                                @else
                                    No slot
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm text-secondary">
                                    @foreach($order->items->take(2) as $item)
                                        {{ $item->quantity }}x {{ $item->product->product_name }}@if(!$loop->last), @endif
                                    @endforeach
                                    @if($order->items->count() > 2), +{{ $order->items->count() - 2 }} more @endif
                                </span>
                            </td>
                            <td class="px-8 py-6">
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
                                <span class="px-3 py-1 text-[10px] font-bold uppercase {{ $statusColor }}">
                                    {{ str_replace('_', ' ', $order->order_status) }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right font-bold text-sm">&pound;{{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-secondary">
                                No orders yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.revenue-tab');
    const charts = {
        weekly: document.getElementById('revenue-chart-weekly'),
        monthly: document.getElementById('revenue-chart-monthly'),
        yearly: document.getElementById('revenue-chart-yearly'),
    };
    const periodLabels = {
        weekly: "This week's performance",
        monthly: 'Last 12 months',
        yearly: 'Last 5 years',
    };

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            const period = this.dataset.period;

            tabs.forEach(function (t) {
                t.classList.remove('bg-primary', 'text-on-primary');
                t.classList.add('text-zinc-500');
            });
            this.classList.add('bg-primary', 'text-on-primary');
            this.classList.remove('text-zinc-500');

            Object.keys(charts).forEach(function (key) {
                charts[key].classList.add('hidden');
            });
            charts[period].classList.remove('hidden');

            document.getElementById('revenue-period-label').textContent = periodLabels[period];
        });
    });
});
</script>
