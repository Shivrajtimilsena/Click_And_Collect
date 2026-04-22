<aside class="w-full lg:w-64 flex-shrink-0">
    <div class="p-4 space-y-1">
        <h3 class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant px-4 mb-4">Categories</h3>
        <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container {{ !request('category') ? 'bg-surface-container' : 'text-on-surface-variant' }} transition-colors">
            All
        </a>
        <a href="{{ route('products.index', ['category' => 'artisan-bakery']) }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container {{ request('category') === 'artisan-bakery' ? 'bg-surface-container' : 'text-on-surface-variant' }} transition-colors">
            Artisan Bakery
        </a>
        <a href="{{ route('products.index', ['category' => 'local-butcher']) }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container {{ request('category') === 'local-butcher' ? 'bg-surface-container' : 'text-on-surface-variant' }} transition-colors">
            Local Butcher
        </a>
        <a href="{{ route('products.index', ['category' => 'daily-greens']) }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container {{ request('category') === 'daily-greens' ? 'bg-surface-container' : 'text-on-surface-variant' }} transition-colors">
            Daily Greens
        </a>
        <a href="{{ route('products.index', ['category' => 'dairy-eggs']) }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container {{ request('category') === 'dairy-eggs' ? 'bg-surface-container' : 'text-on-surface-variant' }} transition-colors">
            Dairy & Eggs
        </a>
        <a href="{{ route('products.index', ['category' => 'fresh-catch']) }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container {{ request('category') === 'fresh-catch' ? 'bg-surface-container' : 'text-on-surface-variant' }} transition-colors">
            Fresh Catch
        </a>
        <a href="{{ route('products.index', ['category' => 'coffee-tea']) }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container {{ request('category') === 'coffee-tea' ? 'bg-surface-container' : 'text-on-surface-variant' }} transition-colors">
            Coffee & Tea
        </a>
        <a href="{{ route('products.index', ['category' => 'wine-spirits']) }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container {{ request('category') === 'wine-spirits' ? 'bg-surface-container' : 'text-on-surface-variant' }} transition-colors">
            Wine & Spirits
        </a>
        <a href="{{ route('products.index', ['category' => 'vegetables']) }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container {{ request('category') === 'vegetables' ? 'bg-surface-container' : 'text-on-surface-variant' }} transition-colors">
            Vegetables
        </a>
        <a href="{{ route('products.index', ['category' => 'seafood']) }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container {{ request('category') === 'seafood' ? 'bg-surface-container' : 'text-on-surface-variant' }} transition-colors">
            Seafood
        </a>
        <a href="{{ route('products.index', ['category' => 'bakery']) }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container {{ request('category') === 'bakery' ? 'bg-surface-container' : 'text-on-surface-variant' }} transition-colors">
            Bakery
        </a>
        <a href="{{ route('products.index', ['category' => 'delicatessen']) }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium hover:bg-surface-container {{ request('category') === 'delicatessen' ? 'bg-surface-container' : 'text-on-surface-variant' }} transition-colors">
            Delicatessen
        </a>
    </div>

    <div class="p-4 space-y-1">
        <h3 class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant px-4 mb-4">Shop by Price</h3>
        <form action="{{ route('products.index') }}" method="GET" class="space-y-2">
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
            <button type="submit" class="w-full mt-2 bg-primary text-on-primary px-4 py-2 text-xs font-bold">Filter</button>
        </form>
    </div>
</aside>
