@extends('app')

@section('title', 'Local Shops | Click&Collect')

@section('content')
<div class="space-y-8">
    <h1 class="text-3xl font-extrabold">Discover Our Local Traders</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($shops as $shop)
            <a href="{{ route('shops.show', $shop) }}" class="bg-surface-container-lowest rounded-lg overflow-hidden hover:shadow-lg transition-all group">
                <div class="aspect-video bg-gradient-to-br from-primary to-primary-fixed flex items-center justify-center text-white text-center p-6">
                    <h3 class="text-2xl font-bold">{{ $shop->name }}</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-primary font-bold mb-2">{{ $shop->trader->business_type ?? 'Shop' }}</p>
                    <p class="text-on-surface-variant text-sm mb-4">{{ $shop->description ?? 'Quality local products' }}</p>
                    
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-orange-500 fill-current">star</span>
                            <span class="font-bold">{{ number_format($shop->rating ?? 0, 1) }}</span>
                        </div>
                        <span class="text-sm text-on-surface-variant">{{ $shop->products->count() }} Products</span>
                    </div>
                </div>
            </a>
        @empty
            <p class="col-span-full text-center text-on-surface-variant py-12">No shops available</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="flex justify-center py-8">
        {{ $shops->links() }}
    </div>
</div>
@endsection
