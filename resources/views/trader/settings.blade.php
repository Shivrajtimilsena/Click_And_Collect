@extends('layouts.trader')

@section('title', 'Settings | Trader Portal')

@section('header-title', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('trader.settings.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PATCH')

        <!-- Shop Profile Image -->
        <div class="bg-surface-container-lowest border border-surface-container-high rounded-lg overflow-hidden">
            <div class="px-8 py-6 border-b border-surface-container-high">
                <h3 class="text-lg font-bold font-headline">Shop Profile</h3>
                <p class="text-sm text-secondary">Your shop's public image and basic info</p>
            </div>
            <div class="p-8">
                <div class="flex items-center gap-8 mb-8">
                    <div class="relative">
                        @if($shop->shop_image)
                            <img src="{{ $shop->shop_image }}" alt="{{ $shop->shop_name }}" class="w-28 h-28 rounded-xl object-cover ring-4 ring-surface-container-high">
                        @else
                            <div class="w-28 h-28 rounded-xl bg-surface-container-high flex items-center justify-center ring-4 ring-surface-container-high">
                                <span class="material-symbols-outlined text-4xl text-secondary">store</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Shop Image</label>
                        <input type="file" name="shop_image" accept="image/jpeg,image/png,image/gif,image/webp"
                               class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-primary file:text-on-primary file:font-bold file:text-sm"/>
                        @error('shop_image')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-secondary mt-2">Upload a new image (max 5MB, jpg/png/gif/webp)</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Shop Name</label>
                        <input type="text" name="shop_name" value="{{ old('shop_name', $shop->shop_name) }}"
                               class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20 @error('shop_name') ring-2 ring-error @enderror"/>
                        @error('shop_name')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Shop Type</label>
                        <select name="shop_type" class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20">
                            <option value="BAKERY" {{ old('shop_type', $trader->shop_type) === 'BAKERY' ? 'selected' : '' }}>Bakery</option>
                            <option value="GROCERY" {{ old('shop_type', $trader->shop_type) === 'GROCERY' ? 'selected' : '' }}>Grocery</option>
                            <option value="BUTCHERY" {{ old('shop_type', $trader->shop_type) === 'BUTCHERY' ? 'selected' : '' }}>Butchery</option>
                            <option value="FARM_SHOP" {{ old('shop_type', $trader->shop_type) === 'FARM_SHOP' ? 'selected' : '' }}>Farm Shop</option>
                            <option value="OTHER" {{ old('shop_type', $trader->shop_type) === 'OTHER' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Short Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20">{{ old('description', $shop->description) }}</textarea>
                    @error('description')
                        <p class="text-error text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Shop Address & Details -->
        <div class="bg-surface-container-lowest border border-surface-container-high rounded-lg overflow-hidden">
            <div class="px-8 py-6 border-b border-surface-container-high">
                <h3 class="text-lg font-bold font-headline">Location & Contact</h3>
                <p class="text-sm text-secondary">Where customers can collect their orders</p>
            </div>
            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Shop Address</label>
                    <input type="text" name="shop_address" value="{{ old('shop_address', $shop->shop_address) }}"
                           placeholder="Street, city, postcode"
                           class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20 @error('shop_address') ring-2 ring-error @enderror"/>
                    @error('shop_address')
                        <p class="text-error text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Logo URL</label>
                    <input type="url" name="logo_url" value="{{ old('logo_url', $trader->logo_url) }}"
                           placeholder="https://example.com/logo.png"
                           class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20"/>
                </div>
            </div>
        </div>

        <!-- Status & Save -->
        <div class="bg-surface-container-lowest border border-surface-container-high rounded-lg overflow-hidden">
            <div class="px-8 py-6 border-b border-surface-container-high">
                <h3 class="text-lg font-bold font-headline">Shop Status</h3>
            </div>
            <div class="p-8 flex items-center justify-between">
                <label class="flex items-center gap-4 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $shop->is_active) ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-surface-container-low text-primary focus:ring-primary/20"/>
                    <div>
                        <span class="font-bold text-on-surface">Shop Active</span>
                        <p class="text-xs text-secondary">Toggle to show/hide your shop from customers</p>
                    </div>
                </label>
                <button type="submit" class="bg-primary text-on-primary px-8 py-3 font-bold rounded-lg hover:opacity-90 active:scale-95 transition-all">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
