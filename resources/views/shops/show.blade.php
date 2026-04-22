@extends('app')

@section('title', $shop->name . ' | Click&Collect')

@section('content')
<div class="space-y-8">
    <!-- Shop Header -->
    <div class="bg-gradient-to-r from-primary to-primary-fixed rounded-lg p-12 text-white">
        <div class="max-w-2xl">
            <p class="text-white/80 text-sm font-bold uppercase mb-2">Local Trader</p>
            <h1 class="text-5xl font-extrabold mb-4">{{ $shop->name }}</h1>
            <p class="text-lg opacity-90 mb-6">{{ $shop->description ?? 'Quality local products' }}</p>
            
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined">star</span>
                    <span class="font-bold">{{ number_format($shop->rating ?? 0, 1) }} Rating</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined">shopping_bag</span>
                    <span class="font-bold">{{ $shop->products->count() }} Products</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Shop Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-surface-container-lowest rounded-lg p-6">
            <h3 class="font-bold text-lg mb-4">Business Type</h3>
            <p class="text-on-surface-variant">{{ $shop->trader->business_type ?? 'Specialty Shop' }}</p>
        </div>

        <div class="bg-surface-container-lowest rounded-lg p-6">
            <h3 class="font-bold text-lg mb-4">Collection Slots Available</h3>
            <p class="text-on-surface-variant">{{ $shop->collectionSlots->count() }} slots</p>
        </div>

        <div class="bg-surface-container-lowest rounded-lg p-6">
            <h3 class="font-bold text-lg mb-4">Status</h3>
            <p class="text-green-600 font-bold">{{ $shop->is_active ? 'Open' : 'Closed' }}</p>
        </div>
    </div>

    <!-- Products Section -->
    <div class="space-y-6">
        <h2 class="text-2xl font-extrabold">Products from {{ $shop->name }}</h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            @forelse ($products as $product)
                @include('components.product-card', ['product' => $product])
            @empty
                <p class="col-span-full text-center text-on-surface-variant py-12">No products available</p>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
