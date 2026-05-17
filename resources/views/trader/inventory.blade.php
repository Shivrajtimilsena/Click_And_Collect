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
                                @if($product->discount)
                                    <form action="{{ route('trader.product.flash-deal.remove', $product) }}" method="POST" class="inline" onsubmit="return confirm('Remove flash deal?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-green-600 text-sm font-bold hover:underline whitespace-nowrap">Remove Flash Deal</button>
                                    </form>
                                @else
                                    <button onclick="openFlashDealModal({{ $product->product_id }}, '{{ $product->product_name }}', {{ $product->price }})" class="text-green-600 text-sm font-bold hover:underline whitespace-nowrap">
                                        Add to Flash Deal
                                    </button>
                                @endif
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

<!-- Flash Deal Modal -->
<div id="flashDealModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold">Set Flash Deal</h3>
            <button onclick="closeFlashDealModal()" class="text-zinc-400 hover:text-zinc-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="mb-4">
            <p class="text-sm text-zinc-600">Product: <strong id="modalProductName"></strong></p>
            <p class="text-sm text-zinc-600">Original Price: <strong>&pound;<span id="modalOriginalPrice"></span></strong></p>
        </div>
        <form id="flashDealForm" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold mb-1">Discounted Price (&pound;)</label>
                    <input type="number" name="discount_price" id="discountPrice" step="0.01" min="0.01" required
                        class="w-full px-4 py-3 bg-zinc-50 border border-zinc-200 text-sm focus:ring-2 focus:ring-primary/20"
                        placeholder="Enter discounted price">
                    <p class="text-xs text-zinc-500 mt-1">Must be lower than original price</p>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">End Date</label>
                    <input type="date" name="end_date" id="endDate" required
                        class="w-full px-4 py-3 bg-zinc-50 border border-zinc-200 text-sm focus:ring-2 focus:ring-primary/20"
                        value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                </div>
                <div id="pricePreview" class="hidden bg-zinc-50 p-4">
                    <div class="flex items-baseline gap-3">
                        <span class="text-lg font-bold text-green-600" id="previewNewPrice"></span>
                        <span class="text-sm text-zinc-400 line-through" id="previewOldPrice"></span>
                        <span class="text-xs font-bold text-red-600" id="previewDiscountPercent"></span>
                    </div>
                </div>
                <button type="submit" class="w-full bg-primary text-on-primary py-3 font-bold hover:opacity-90 transition-all">
                    Set Flash Deal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openFlashDealModal(productId, productName, originalPrice) {
    document.getElementById('flashDealForm').action = '/trader/product/' + productId + '/flash-deal';
    document.getElementById('modalProductName').textContent = productName;
    document.getElementById('modalOriginalPrice').textContent = originalPrice.toFixed(2);
    document.getElementById('flashDealModal').classList.remove('hidden');
    document.getElementById('pricePreview').classList.add('hidden');
}

function closeFlashDealModal() {
    document.getElementById('flashDealModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('discountPrice');
    if (input) {
        input.addEventListener('input', function() {
            var original = parseFloat(document.getElementById('modalOriginalPrice').textContent);
            var discounted = parseFloat(this.value);
            var preview = document.getElementById('pricePreview');
            var previewNew = document.getElementById('previewNewPrice');
            var previewOld = document.getElementById('previewOldPrice');
            var previewPct = document.getElementById('previewDiscountPercent');

            if (discounted > 0 && discounted < original) {
                var pct = ((original - discounted) / original * 100).toFixed(1);
                previewNew.textContent = '\u00a3' + discounted.toFixed(2);
                previewOld.textContent = '\u00a3' + original.toFixed(2);
                previewPct.textContent = '-' + pct + '%';
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        });
    }

    var modal = document.getElementById('flashDealModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) closeFlashDealModal();
        });
    }
});
</script>
@endsection
