@extends('layouts.trader')

@section('title', 'Profile | Trader Portal')

@section('header-title', 'Profile')

@section('content')
@php
    $shopCount = $shops->count();
@endphp

<div class="space-y-10">
    <section class="bg-surface-container-lowest border border-surface-container-high rounded-xl overflow-hidden">
        <div class="px-8 py-8 bg-gradient-to-r from-surface-container-low to-surface-container-lowest">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-center gap-5">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->full_name }}" class="w-16 h-16 rounded-xl object-cover ring-4 ring-surface-container-high" />
                    @else
                        <div class="w-16 h-16 rounded-xl bg-surface-container-high flex items-center justify-center ring-4 ring-surface-container-high">
                            <span class="material-symbols-outlined text-3xl text-secondary">store</span>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs font-bold text-secondary uppercase tracking-widest">Trader Account</p>
                        <h1 class="text-3xl font-bold font-headline text-on-surface">{{ $user->full_name }}</h1>
                        <p class="text-sm text-secondary">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="px-3 py-1.5 text-[11px] font-bold uppercase rounded-full bg-primary/10 text-primary">User: {{ $user->status }}</span>
                    <span class="px-3 py-1.5 text-[11px] font-bold uppercase rounded-full bg-surface-container-high text-secondary">Trader: {{ $trader->is_active ? 'ACTIVE' : 'INACTIVE' }}</span>
                    <div class="px-4 py-3 bg-white rounded-lg border border-surface-container-high flex items-center gap-3">
                        <span class="text-2xl font-bold text-primary">{{ $shopCount }}</span>
                        <span class="text-xs font-bold uppercase tracking-widest text-secondary">Shops</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1 bg-surface-container-low rounded-xl p-6 border border-surface-container-high">
                <h2 class="text-sm font-bold uppercase tracking-widest text-secondary">Account</h2>
                <h3 class="text-lg font-bold font-headline mt-2">Personal Details</h3>
                <div class="mt-6 space-y-4 text-sm">
                    <div>
                        <p class="text-xs uppercase tracking-widest text-secondary font-bold">Full Name</p>
                        <p class="text-on-surface font-semibold mt-1">{{ $user->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-widest text-secondary font-bold">Email Address</p>
                        <p class="text-on-surface font-semibold mt-1">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-widest text-secondary font-bold">Phone</p>
                        <p class="text-on-surface font-semibold mt-1">{{ $user->phone_no ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-widest text-secondary font-bold">Address</p>
                        <p class="text-on-surface font-semibold mt-1">{{ $user->address ?? 'Not provided' }}</p>
                    </div>
                </div>
                <a href="{{ route('trader.settings') }}" class="mt-6 inline-flex items-center gap-2 bg-primary text-on-primary px-4 py-2.5 text-xs font-bold uppercase tracking-widest hover:opacity-90">
                    <span class="material-symbols-outlined text-sm">edit</span>
                    Edit Details
                </a>
            </div>

            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-secondary">Shopfronts</p>
                        <h2 class="text-2xl font-bold font-headline">Business Profiles</h2>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest text-secondary bg-surface-container-high px-3 py-1 rounded-full">{{ $shopCount }} assigned</span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @forelse($shops as $shop)
                        <div class="bg-white border border-surface-container-high rounded-xl overflow-hidden">
                            <div class="px-5 py-4 bg-surface-container-low flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    @if($shop->shop_image)
                                        <img src="{{ $shop->shop_image }}" alt="{{ $shop->shop_name }}" class="w-10 h-10 rounded-lg object-cover" />
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center">
                                            <span class="material-symbols-outlined text-secondary">store</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-secondary">Shop #{{ $shop->shop_id }}</p>
                                        <p class="text-sm font-bold text-on-surface">{{ $shop->shop_name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($currentShop && $currentShop->shop_id === $shop->shop_id)
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-primary">Active</span>
                                    @endif
                                    <button type="button" onclick="openShopModal({{ $shop->shop_id }})" class="text-[10px] font-bold uppercase tracking-widest text-secondary hover:text-primary transition-colors">
                                        Edit
                                    </button>
                                </div>
                            </div>
                            <div class="p-5 space-y-4 text-sm">
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-secondary font-bold">Location / Address</p>
                                    <p class="text-on-surface mt-1">{{ $shop->shop_address ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-widest text-secondary font-bold">Description</p>
                                    <p class="text-on-surface mt-1">{{ $shop->description ?? 'No description yet' }}</p>
                                </div>
                                <div class="flex items-center justify-between text-xs uppercase tracking-widest text-secondary font-bold">
                                    <span>Products</span>
                                    <span class="text-primary">{{ $shop->products_count }}</span>
                                </div>
                            </div>
                        </div>

                        <div id="shop-modal-{{ $shop->shop_id }}" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-[60]">
                            <div class="bg-white w-full max-w-lg mx-4 rounded-2xl shadow-2xl border border-surface-container-high overflow-hidden">
                                <div class="px-6 py-4 bg-surface-container-low flex items-center justify-between">
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-secondary">Edit Shop</p>
                                        <h3 class="text-lg font-bold font-headline">{{ $shop->shop_name }}</h3>
                                    </div>
                                    <button type="button" onclick="closeShopModal({{ $shop->shop_id }})" class="text-zinc-500 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined">close</span>
                                    </button>
                                </div>
                                <form method="POST" action="{{ route('trader.shops.update', $shop) }}" enctype="multipart/form-data" class="p-6 space-y-4">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-1">Shop Name</label>
                                        <input type="text" name="shop_name" value="{{ $shop->shop_name }}" required
                                            class="w-full px-4 py-2.5 bg-surface border border-surface-container-high text-sm focus:ring-2 focus:ring-primary/20">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-1">Location / Address</label>
                                        <input type="text" name="shop_address" value="{{ $shop->shop_address }}"
                                            class="w-full px-4 py-2.5 bg-surface border border-surface-container-high text-sm focus:ring-2 focus:ring-primary/20">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-1">Description</label>
                                        <textarea name="description" rows="3"
                                            class="w-full px-4 py-2.5 bg-surface border border-surface-container-high text-sm focus:ring-2 focus:ring-primary/20">{{ $shop->description }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-1">Shop Image (optional)</label>
                                        <input type="file" name="shop_image" accept="image/jpeg,image/png,image/gif,image/webp"
                                            class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-primary file:text-on-primary file:font-bold file:text-sm">
                                    </div>
                                    <div class="flex items-center justify-end gap-3 pt-2">
                                        <button type="button" onclick="closeShopModal({{ $shop->shop_id }})" class="px-4 py-2 text-xs font-bold uppercase tracking-widest text-secondary border border-surface-container-high hover:bg-surface-container-low">
                                            Cancel
                                        </button>
                                        <button type="submit" class="px-5 py-2 text-xs font-bold uppercase tracking-widest bg-primary text-on-primary hover:opacity-90">
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white border border-surface-container-high rounded-xl p-6 text-sm text-secondary">
                            No shops found yet. Create your first shop to see it here.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function openShopModal(shopId) {
    const modal = document.getElementById(`shop-modal-${shopId}`);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeShopModal(shopId) {
    const modal = document.getElementById(`shop-modal-${shopId}`);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}
</script>
@endsection
