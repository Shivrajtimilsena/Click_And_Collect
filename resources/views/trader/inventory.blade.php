@extends('layouts.trader')

@section('title', 'Inventory | Trader Portal')

@section('header-title', 'Inventory')

@section('content')
<div class="bg-surface-container-lowest border border-surface-container-high overflow-hidden">
    <div class="px-8 py-6 flex justify-between items-center border-b border-surface-container-high">
        <div>
            <h3 class="text-lg font-bold font-headline">Product Inventory</h3>
            <p class="text-sm text-secondary">Manage your products across all shops</p>
        </div>
        <div class="flex gap-4">
            <select class="px-4 py-2 bg-surface-container-high border-none text-sm focus:ring-2 focus:ring-primary/20">
                <option value="">All Shops</option>
                @foreach($shops as $shop)
                    <option value="{{ $shop->shop_id }}">{{ $shop->shop_name }}</option>
                @endforeach
            </select>
            <button class="bg-primary text-on-primary px-4 py-2 text-sm font-bold flex items-center gap-2">
                <a href="{{ route('trader.product.create') }}" class="flex items-center gap-2 text-on-primary hover:opacity-90">
                    <span class="material-symbols-outlined text-sm">add</span>
                    Add Product
                </a>
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Product</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Shop</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Category</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Price</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Stock</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Status</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-container-low">
                @forelse($products as $product)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" class="w-12 h-12 object-cover border border-surface-container-high"/>
                                @else
                                    <div class="w-12 h-12 flex items-center justify-center bg-surface-container-high">
                                        <span class="material-symbols-outlined text-secondary">image</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-bold text-on-surface">{{ $product->product_name }}</p>
                                    <p class="text-xs text-secondary">#PRD-{{ $product->product_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-sm">{{ $product->shop->shop_name ?? 'N/A' }}</td>
                        <td class="px-8 py-6 text-sm">{{ $product->category->category_name ?? 'Uncategorized' }}</td>
                        <td class="px-8 py-6">
                            <div class="font-bold">&pound;{{ number_format($product->price, 2) }}</div>
                            @if($product->discount)
                                <div class="text-xs text-green-600">-{{ $product->discount->discount_percentage }}%</div>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            @if($product->stock < 5)
                                <span class="px-3 py-1 text-xs font-bold uppercase bg-red-100 text-red-700">
                                    {{ $product->stock }} left
                                </span>
                            @elseif($product->stock < 10)
                                <span class="px-3 py-1 text-xs font-bold uppercase bg-orange-100 text-orange-700">
                                    {{ $product->stock }} left
                                </span>
                            @else
                                <span class="text-sm text-secondary">{{ $product->stock }} in stock</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            @php
                                $statusClass = $product->product_status === 'ACTIVE' 
                                    ? 'bg-green-100 text-green-700' 
                                    : 'bg-zinc-100 text-zinc-500';
                            @endphp
                            <span class="px-3 py-1 text-[10px] font-bold uppercase {{ $statusClass }}">
                                {{ $product->product_status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center gap-4 justify-end">
                                <a href="{{ route('trader.product.edit', $product) }}" class="text-primary text-sm font-bold hover:underline whitespace-nowrap">Edit</a>
                                <form action="{{ route('trader.product.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-error text-sm font-bold hover:underline whitespace-nowrap">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-8 py-12 text-center text-secondary">
                            No products found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="px-8 py-4 border-t border-surface-container-high">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
