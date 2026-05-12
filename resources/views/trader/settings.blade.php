@extends('layouts.trader')

@section('title', 'Settings | Trader Portal')

@section('header-title', 'Settings')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-surface-container-lowest border border-surface-container-high">
        <div class="px-8 py-6 border-b border-surface-container-high">
            <h3 class="text-lg font-bold font-headline">Trader Profile</h3>
            <p class="text-sm text-secondary">Update your trader information</p>
        </div>
        <form class="p-8 space-y-6" action="#" method="POST">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Shop Type</label>
                <select name="shop_type" class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20">
                    <option value="BAKERY" {{ ($trader->shop_type ?? '') === 'BAKERY' ? 'selected' : '' }}>Bakery</option>
                    <option value="GROCERY" {{ ($trader->shop_type ?? '') === 'GROCERY' ? 'selected' : '' }}>Grocery</option>
                    <option value="BUTCHERY" {{ ($trader->shop_type ?? '') === 'BUTCHERY' ? 'selected' : '' }}>Butchery</option>
                    <option value="FARM_SHOP" {{ ($trader->shop_type ?? '') === 'FARM_SHOP' ? 'selected' : '' }}>Farm Shop</option>
                    <option value="OTHER" {{ ($trader->shop_type ?? '') === 'OTHER' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Logo URL</label>
                <input type="url" name="logo_url" value="{{ $trader->logo_url ?? '' }}" class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20" placeholder="https://..."/>
            </div>
            <div class="flex items-center gap-4">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ ($trader->is_active ?? true) ? 'checked' : '' }}/>
                <label for="is_active" class="text-sm font-bold">Shop Active</label>
            </div>
            <button type="submit" class="bg-primary text-on-primary px-6 py-3 font-bold">Save Changes</button>
        </form>
    </div>

    <div class="bg-surface-container-lowest border border-surface-container-high">
        <div class="px-8 py-6 border-b border-surface-container-high">
            <h3 class="text-lg font-bold font-headline">Your Shops</h3>
            <p class="text-sm text-secondary">Manage your shop locations</p>
        </div>
        <div class="p-8">
            @forelse($trader->shops as $shop)
                <div class="flex items-center justify-between py-4 border-b border-surface-container-low">
                    <div>
                        <p class="font-bold text-on-surface">{{ $shop->shop_name }}</p>
                        <p class="text-xs text-secondary">{{ $shop->description ?? 'No description' }}</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-bold uppercase {{ $shop->is_active ? 'bg-green-100 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                        {{ $shop->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            @empty
                <p class="text-center text-secondary py-8">No shops yet</p>
            @endforelse
            <button class="mt-4 w-full py-4 border-2 border-dashed border-surface-container-high text-secondary text-sm font-bold uppercase tracking-widest hover:border-primary/40 hover:text-primary transition-all">
                Add New Shop
            </button>
        </div>
    </div>
</div>
@endsection
