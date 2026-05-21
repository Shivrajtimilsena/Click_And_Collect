@extends('app')

@section('title', 'All Products | Click&Collect')

@section('content')
<div class="max-w-6xl mx-auto py-12">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-headline font-bold text-on-surface">All Products</h1>
            <p class="text-sm text-secondary">Manage and approve products across all shops</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-primary font-bold hover:underline">Back to Dashboard</a>
    </div>

    <div class="bg-surface-container-lowest border border-surface-container-high overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Product</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Shop</th>
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
                                <div class="font-bold text-on-surface">{{ $product->product_name }}</div>
                                <div class="text-xs text-secondary">#PRD-{{ $product->product_id }}</div>
                            </td>
                            <td class="px-8 py-6 text-sm">{{ $product->shop->shop_name ?? 'N/A' }}</td>
                            <td class="px-8 py-6 text-sm">&pound;{{ number_format($product->price, 2) }}</td>
                            <td class="px-8 py-6 text-sm">{{ $product->stock }}</td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 text-[10px] font-bold uppercase {{ $product->approval_status === 'APPROVED' ? 'bg-green-100 text-green-700' : ($product->approval_status === 'PENDING' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $product->approval_status }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                @if($product->approval_status === 'PENDING')
                                <div class="flex items-center justify-end gap-3">
                                    <form method="POST" action="{{ route('admin.products.approve', $product->product_id) }}">
                                        @csrf
                                        <button type="submit" class="text-green-600 text-sm font-bold hover:underline">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.products.reject', $product->product_id) }}" onsubmit="return confirm('Reject this product?');">
                                        @csrf
                                        <button type="submit" class="text-error text-sm font-bold hover:underline">Reject</button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-10 text-center text-secondary">No products found.</td>
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
</div>
@endsection
