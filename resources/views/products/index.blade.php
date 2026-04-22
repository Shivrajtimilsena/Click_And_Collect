@extends('app')

@section('title', 'Products | Click&Collect')

@section('content')
<div class="flex flex-col lg:flex-row gap-6">
    @include('components.category-sidebar', ['categories' => $categories])

    <div class="flex-grow space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-extrabold">Products</h1>
            <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
                <select name="sort" onchange="this.form.submit()" class="px-4 py-2 rounded-full border-none bg-surface-container text-on-surface">
                    <option value="trending" {{ request('sort') === 'trending' ? 'selected' : '' }}>Trending</option>
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating</option>
                </select>
            </form>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-3">
            @forelse ($products as $product)
                @include('components.product-card', ['product' => $product])
            @empty
                <p class="col-span-full text-center text-on-surface-variant py-12">No products found</p>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="flex justify-center py-8">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
