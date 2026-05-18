@extends('layouts.trader')

@section('title', 'Orders | Trader Portal')

@section('header-title', 'Orders')

@section('content')
<div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_360px]">
    <div class="bg-surface-container-lowest border border-surface-container-high p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold font-headline">RFID Collection</h3>
                <p class="text-sm text-secondary mt-1">Scan an assigned RFID tag to complete an order that is marked ready.</p>
            </div>
            <span class="material-symbols-outlined text-primary">contactless</span>
        </div>
        <form method="POST" action="{{ route('trader.rfid.scan') }}" class="mt-5 flex flex-col gap-3 sm:flex-row">
            @csrf
            <input
                list="known-rfid-tags"
                name="rfid_uid"
                required
                class="min-w-0 flex-1 border border-surface-container-high bg-white px-4 py-3 text-sm font-bold uppercase tracking-wider focus:border-primary focus:outline-none"
                placeholder="Scan or enter RFID UID"
            >
            <button class="bg-primary text-on-primary px-5 py-3 text-xs font-bold uppercase tracking-widest hover:opacity-90">
                Complete Collection
            </button>
        </form>
        <datalist id="known-rfid-tags">
            <option value="53687F13"></option>
            <option value="7369771A"></option>
        </datalist>
    </div>

    <div class="bg-surface-container-lowest border border-surface-container-high p-6">
        <h3 class="text-lg font-bold font-headline">Registered Test Tags</h3>
        <div class="mt-4 space-y-3">
            <div class="flex items-center justify-between border border-surface-container-high bg-surface-container-low px-4 py-3">
                <span class="text-sm text-secondary">Tag 1</span>
                <span class="font-mono text-sm font-bold">53687F13</span>
            </div>
            <div class="flex items-center justify-between border border-surface-container-high bg-surface-container-low px-4 py-3">
                <span class="text-sm text-secondary">Tag 2</span>
                <span class="font-mono text-sm font-bold">7369771A</span>
            </div>
        </div>
    </div>
</div>

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
                    <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">RFID</th>
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
                        <td class="px-8 py-6 min-w-64">
                            <form method="POST" action="{{ route('trader.orders.rfid.assign', $order) }}" class="space-y-2">
                                @csrf
                                <div class="flex">
                                    <input
                                        list="known-rfid-tags"
                                        name="rfid_uid"
                                        value="{{ $order->rfid_uid }}"
                                        placeholder="RFID UID"
                                        class="w-36 border border-surface-container-high bg-white px-3 py-2 font-mono text-xs uppercase focus:border-primary focus:outline-none"
                                    >
                                    <button class="border border-l-0 border-surface-container-high bg-surface-container-low px-3 text-[10px] font-bold uppercase tracking-wider hover:bg-surface-container-high">
                                        Save
                                    </button>
                                </div>
                                @if($order->rfid_assigned_at)
                                    <p class="text-[10px] uppercase tracking-wider text-secondary">Assigned {{ $order->rfid_assigned_at->format('M d, H:i') }}</p>
                                @else
                                    <p class="text-[10px] uppercase tracking-wider text-secondary">No tag assigned</p>
                                @endif
                            </form>
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
                            <form method="POST" action="{{ route('trader.orders.status.update', $order) }}">
                                @csrf
                                @method('PATCH')
                                <select name="order_status" class="px-3 py-2 text-xs font-bold uppercase border border-surface-container-high bg-white {{ $statusColor }}" onchange="this.form.submit()">
                                    <option value="PENDING" {{ $order->order_status == 'PENDING' ? 'selected' : '' }}>Pending</option>
                                    <option value="IN_PROGRESS" {{ $order->order_status == 'IN_PROGRESS' ? 'selected' : '' }}>In Progress</option>
                                    <option value="READY" {{ $order->order_status == 'READY' ? 'selected' : '' }}>Ready</option>
                                    <option value="COMPLETED" {{ $order->order_status == 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                                    <option value="CANCELLED" {{ $order->order_status == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-8 py-6 text-right font-bold text-sm">&pound;{{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-8 py-6 text-right">
                            <a href="#" class="text-primary text-sm font-bold hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-8 py-12 text-center text-secondary">
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

@section('scripts')
<script>
function updateOrderStatus(select) {
    var newStatus = select.value;

    if (!confirm('Are you sure you want to change this order status to ' + newStatus.replace(/_/g, ' ') + '?')) {
        return;
    }

    fetch(select.dataset.url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ status: newStatus }),
    })
    .then(function(res) {
        if (!res.ok) {
            return res.json().then(function(data) {
                throw new Error(data.error || 'Failed to update status');
            });
        }
        return res.json();
    })
    .then(function() {
        var statusColors = {
            'PENDING': 'bg-zinc-100 text-zinc-500',
            'IN_PROGRESS': 'bg-blue-100 text-blue-700',
            'READY': 'bg-orange-100 text-orange-700',
            'COMPLETED': 'bg-green-100 text-green-700',
            'CANCELLED': 'bg-red-100 text-red-700',
        };
        select.className = 'px-3 py-2 text-xs font-bold uppercase border border-surface-container-high bg-white ' + (statusColors[newStatus] || 'bg-zinc-100 text-zinc-500');
    })
    .catch(function(err) {
        alert('Error: ' + err.message);
    });
}
</script>
@endsection

