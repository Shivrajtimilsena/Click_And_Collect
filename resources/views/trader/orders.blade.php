@extends('layouts.trader')

@section('title', 'Orders | Trader Portal')

@section('header-title', 'Orders')

@section('content')
<div class="bg-surface-container-lowest border border-surface-container-high overflow-hidden">
    <div class="px-8 py-6 flex justify-between items-center border-b border-surface-container-high">
        <div>
            <h3 class="text-lg font-bold font-headline">All Orders</h3>
            <p class="text-sm text-secondary">Manage customer orders</p>
        </div>
        <div class="flex gap-4">
            <select class="px-4 py-2 bg-surface-container-high border-none text-sm focus:ring-2 focus:ring-primary/20">
                <option>All Status</option>
                <option>Pending</option>
                <option>In Progress</option>
                <option>Ready</option>
                <option>Completed</option>
            </select>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Order ID</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Customer</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Collection Slot</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Items</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Status</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest text-right">Total</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-container-low">
                @forelse($orders as $order)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="px-8 py-6">
                            <span class="text-sm font-bold text-on-surface">#ORD-{{ $order->order_id }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center bg-surface-container-high font-bold text-xs text-secondary">
                                    {{ strtoupper(substr($order->customer->user->full_name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-on-surface">{{ $order->customer->user->full_name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-secondary">{{ $order->customer->user->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-sm">
                            @if($order->collectionSlot)
                                <div>
                                    <p class="font-bold">{{ $order->collectionSlot->slot_day }} {{ $order->collectionSlot->slot_date?->format('M d') }}</p>
                                    <p class="text-xs text-secondary">{{ substr($order->collectionSlot->start_time, 0, 5) }}-{{ substr($order->collectionSlot->end_time, 0, 5) }}</p>
                                </div>
                            @else
                                <span class="text-secondary">No slot</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="text-sm text-secondary">
                                @foreach($order->items->take(2) as $item)
                                    <div>{{ $item->quantity }}x {{ $item->product->product_name ?? 'Unknown' }}</div>
                                @endforeach
                                @if($order->items->count() > 2)
                                    <div class="text-xs text-zinc-400">+{{ $order->items->count() - 2 }} more items</div>
                                @endif
                            </div>
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
                            <select class="px-3 py-2 text-xs font-bold uppercase border border-surface-container-high bg-white {{ $statusColor }}" onchange="updateOrderStatus({{ $order->order_id }}, this.value)">
                                <option value="PENDING" {{ $order->order_status == 'PENDING' ? 'selected' : '' }}>Pending</option>
                                <option value="IN_PROGRESS" {{ $order->order_status == 'IN_PROGRESS' ? 'selected' : '' }}>In Progress</option>
                                <option value="READY" {{ $order->order_status == 'READY' ? 'selected' : '' }}>Ready</option>
                                <option value="COMPLETED" {{ $order->order_status == 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                                <option value="CANCELLED" {{ $order->order_status == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </td>
                        <td class="px-8 py-6 text-right font-bold text-sm">&pound;{{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-8 py-6 text-right">
                            <a href="#" class="text-primary text-sm font-bold hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-8 py-12 text-center text-secondary">
                            No orders found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="px-8 py-4 border-t border-surface-container-high">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
