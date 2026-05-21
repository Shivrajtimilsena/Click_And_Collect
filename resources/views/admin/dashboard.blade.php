@extends('app')

@section('title', 'Admin Dashboard | Click&Collect')

@section('content')
<div class="max-w-6xl mx-auto py-12">
    <h1 class="text-3xl font-headline font-bold text-on-surface mb-8">Admin Dashboard</h1>

    <div class="grid grid-cols-4 gap-6 mb-10">
        <div class="bg-surface-container-lowest border border-surface-container-high p-6">
            <p class="text-sm text-secondary uppercase tracking-wider">Pending</p>
            <p class="text-4xl font-headline font-extrabold text-primary mt-2">{{ $pendingCount }}</p>
        </div>
        <div class="bg-surface-container-lowest border border-surface-container-high p-6">
            <p class="text-sm text-secondary uppercase tracking-wider">Approved</p>
            <p class="text-4xl font-headline font-extrabold text-green-600 mt-2">{{ $approvedCount }}</p>
        </div>
        <div class="bg-surface-container-lowest border border-surface-container-high p-6">
            <p class="text-sm text-secondary uppercase tracking-wider">Rejected</p>
            <p class="text-4xl font-headline font-extrabold text-error mt-2">{{ $rejectedCount }}</p>
        </div>
        <div class="bg-surface-container-lowest border border-surface-container-high p-6 {{ $pendingWithdrawals > 0 ? 'ring-2 ring-primary' : '' }}">
            <p class="text-sm text-secondary uppercase tracking-wider">Pending Withdrawals</p>
            <p class="text-4xl font-headline font-extrabold {{ $pendingWithdrawals > 0 ? 'text-primary' : 'text-on-surface' }} mt-2">{{ $pendingWithdrawals }}</p>
        </div>
    </div>

    <div class="flex gap-4">
        <a href="{{ route('admin.applications') }}" class="inline-block bg-primary text-on-primary px-8 py-3 font-bold hover:opacity-90 transition-all">
            Manage Applications
        </a>
        <a href="{{ route('admin.withdrawals.index') }}" class="inline-block border border-primary text-primary px-8 py-3 font-bold hover:bg-primary hover:text-on-primary transition-all">
            Manage Withdrawals
        </a>
        <a href="{{ route('admin.products.index') }}" class="inline-block border border-primary text-primary px-8 py-3 font-bold hover:bg-primary hover:text-on-primary transition-all">
            All Products
        </a>
    </div>

    <div class="mt-12 bg-surface-container-lowest border border-surface-container-high">
        <div class="px-8 py-6 border-b border-surface-container-high">
            <h2 class="text-lg font-bold font-headline">Pending Product Approvals</h2>
            <p class="text-sm text-secondary">Products awaiting admin approval</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Product</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Shop</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Description</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest">Price / Stock</th>
                        <th class="px-8 py-4 text-[10px] font-bold text-secondary uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-low">
                    @forelse($pendingProducts as $product)
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-8 py-6">
                                <div class="font-bold text-on-surface">{{ $product->product_name }}</div>
                                <div class="text-xs text-secondary">#PRD-{{ $product->product_id }}</div>
                            </td>
                            <td class="px-8 py-6 text-sm">{{ $product->shop_name }}</td>
                            <td class="px-8 py-6 text-sm text-secondary">
                                {{ $product->description ?? 'No description.' }}
                            </td>
                            <td class="px-8 py-6 text-sm">
                                &pound;{{ number_format($product->price, 2) }} · Stock: {{ $product->stock }}
                            </td>
                            <td class="px-8 py-6 text-right">
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-8 text-center text-secondary">No pending products.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
