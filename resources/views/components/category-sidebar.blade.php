<div class="space-y-1">
    <h3 class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant px-4 mb-4">Categories</h3>
    <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container {{ !request('category') ? 'bg-surface-container' : 'text-on-surface-variant' }} transition-colors">
        All
    </a>
    @foreach ($categories as $category)
        @php $categorySlug = \Illuminate\Support\Str::slug($category->category_name); @endphp
        <a href="{{ route('products.index', ['category' => $categorySlug]) }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container {{ request('category') === $categorySlug ? 'bg-surface-container' : 'text-on-surface-variant' }} transition-colors">
            {{ $category->category_name }}
        </a>
    @endforeach
</div>

<div class="space-y-1">
    <h3 class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant px-4 mb-4">Shop by Price</h3>
    <form action="{{ route('products.index') }}" method="GET" class="space-y-2">
        @if (request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
        <label class="flex items-center gap-2 px-4 py-2 text-sm font-medium cursor-pointer hover:bg-surface-container">
            <input type="radio" name="price_range" value="0-5" {{ request('price_range') === '0-5' ? 'checked' : '' }}>
            Under $5.00
        </label>
        <label class="flex items-center gap-2 px-4 py-2 text-sm font-medium cursor-pointer hover:bg-surface-container">
            <input type="radio" name="price_range" value="5-10" {{ request('price_range') === '5-10' ? 'checked' : '' }}>
            $5.00 - $10.00
        </label>
        <label class="flex items-center gap-2 px-4 py-2 text-sm font-medium cursor-pointer hover:bg-surface-container">
            <input type="radio" name="price_range" value="10-20" {{ request('price_range') === '10-20' ? 'checked' : '' }}>
            $10.00 - $20.00
        </label>
        <label class="flex items-center gap-2 px-4 py-2 text-sm font-medium cursor-pointer hover:bg-surface-container">
            <input type="radio" name="price_range" value="20+" {{ request('price_range') === '20+' ? 'checked' : '' }}>
            $20.00 and more
        </label>
        <button type="submit" class="w-full mt-2 bg-primary text-on-primary px-4 py-2 text-xs font-bold">Filter</button>
    </form>
</div>
