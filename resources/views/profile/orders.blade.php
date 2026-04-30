@extends('layouts.profile')

@section('profile-content')
<div>
    <h3 class="font-headline text-3xl font-extrabold tracking-tight text-on-surface mb-8">
        My Orders
    </h3>

    @if($orders->count() > 0)
    <div class="bg-surface-container overflow-hidden rounded-lg">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-on-surface-variant border-b border-outline-variant/10">
                    <th class="px-8 py-6 text-[10px] uppercase tracking-[0.2em] font-bold">Order Details</th>
                    <th class="px-8 py-6 text-[10px] uppercase tracking-[0.2em] font-bold">Status</th>
                    <th class="px-8 py-6 text-[10px] uppercase tracking-[0.2em] font-bold">Date</th>
                    <th class="px-8 py-6 text-[10px] uppercase tracking-[0.2em] font-bold text-right">Amount</th>
                    <th class="px-8 py-6 text-[10px] uppercase tracking-[0.2em] font-bold text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10">
                @foreach($orders as $order)
                <tr class="bg-white hover:bg-surface-container-lowest transition-colors">
                    <td class="px-8 py-6">
                        <div class="flex items-center space-x-4">
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
                                    #{{ str_pad($order->order_id, 5, '0', STR_PAD_LEFT) }} • {{ $order->items->count() }} Items
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="inline-flex items-center space-x-1.5 rounded-full @if($order->order_status === 'COMPLETED') text-on-surface bg-surface-container-low @elseif($order->order_status === 'READY') text-primary bg-primary/10 @else text-on-surface-variant bg-surface-container-low @endif px-3 py-1 text-xs font-bold uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full @if($order->order_status === 'COMPLETED') bg-on-surface @elseif($order->order_status === 'READY') bg-primary @else bg-on-surface-variant @endif"></span>
                            <span>{{ ucfirst(strtolower($order->order_status)) }}</span>
                        </span>
                    </td>
                    <td class="px-8 py-6 text-on-surface-variant text-sm">
                        {{ $order->order_date?->format('M d, Y') }}
                    </td>
                    <td class="px-8 py-6 text-right font-bold text-on-surface">
                        ${{ number_format($order->total_amount, 2) }}
                    </td>
                    <td class="px-8 py-6 text-center">
                        <a href="{{ route('orders.show', $order->order_id) }}" class="text-primary font-bold text-sm hover:opacity-90 transition-opacity">
                            View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="bg-white p-6 border-t border-outline-variant/10">
            {{ $orders->links() }}
        </div>
        @endif
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
