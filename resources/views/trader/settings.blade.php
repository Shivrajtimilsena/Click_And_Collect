@extends('layouts.trader')

@section('title', 'Settings | Trader Portal')

@section('header-title', 'Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('trader.settings.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PATCH')

        <!-- Profile Section -->
        <div class="bg-surface-container-lowest border border-surface-container-high rounded-lg overflow-hidden">
            <div class="px-8 py-6 border-b border-surface-container-high">
                <h3 class="text-lg font-bold font-headline">Profile</h3>
                <p class="text-sm text-secondary">Your shop name, photo, and details shown to customers</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="flex items-center gap-6">
                    <div class="relative shrink-0">
                        @if($shop->shop_image)
                            <img src="{{ $shop->shop_image }}" alt="{{ $shop->shop_name }}" class="w-24 h-24 rounded-xl object-cover ring-4 ring-surface-container-high">
                        @else
                            <div class="w-24 h-24 rounded-xl bg-surface-container-high flex items-center justify-center ring-4 ring-surface-container-high">
                                <span class="material-symbols-outlined text-3xl text-secondary">store</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Shop Photo</label>
                        <input type="file" name="shop_image" id="shop_image" accept="image/jpeg,image/png,image/gif,image/webp"
                               class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-primary file:text-on-primary file:font-bold file:text-sm"/>
                        @error('shop_image')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-secondary mt-2" id="shop_image_hint">Upload a photo (max 5MB, jpg/png/gif/webp)</p>
                        <p class="text-error text-sm mt-1 hidden" id="shop_image_error">File size exceeds the 5MB limit. Please choose a smaller file.</p>
                    </div>
                </div>

                <hr class="border-surface-container-high">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Name</label>
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

                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20">{{ old('description', $shop->description) }}</textarea>
                    @error('description')
                        <p class="text-error text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-primary text-on-primary px-10 py-3 font-bold rounded-lg hover:opacity-90 active:scale-95 transition-all">
                        Save Changes
                    </button>
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

    <!-- Change Password -->
    <div class="bg-surface-container-lowest border border-surface-container-high rounded-lg overflow-hidden">
        <div class="px-8 py-6 border-b border-surface-container-high">
            <h3 class="text-lg font-bold font-headline">Change Password</h3>
            <p class="text-sm text-secondary">Update your trader portal login password</p>
        </div>
        <form method="POST" action="{{ route('trader.settings.change-password') }}" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Current Password</label>
                <div class="relative">
                    <input type="password" name="current_password" id="cp_current"
                           class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20 pr-14 @error('current_password') ring-2 ring-error @enderror"/>
                    <button type="button" onclick="togglePassword('cp_current', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-xl">visibility</span>
                    </button>
                </div>
                @error('current_password')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">New Password</label>
                    <div class="relative">
                        <input type="password" name="new_password" id="cp_new"
                               class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20 pr-14 @error('new_password') ring-2 ring-error @enderror"/>
                        <button type="button" onclick="togglePassword('cp_new', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer">
                            <span class="material-symbols-outlined text-xl">visibility</span>
                        </button>
                    </div>
                    @error('new_password')
                        <p class="text-error text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" name="new_password_confirmation" id="cp_confirm"
                               class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20 pr-14"/>
                        <button type="button" onclick="togglePassword('cp_confirm', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer">
                            <span class="material-symbols-outlined text-xl">visibility</span>
                        </button>
                    </div>
                </div>
            </div>
            <button type="submit" class="bg-primary text-on-primary px-8 py-3 font-bold rounded-lg hover:opacity-90 active:scale-95 transition-all">
                Update Password
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var fileInput = document.getElementById('shop_image');
    if (fileInput) {
        var maxSize = 5 * 1024 * 1024;
        var errorEl = document.getElementById('shop_image_error');
        var hintEl = document.getElementById('shop_image_hint');

        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0] && this.files[0].size > maxSize) {
                errorEl.classList.remove('hidden');
                hintEl.classList.add('hidden');
                this.value = '';
            } else {
                errorEl.classList.add('hidden');
                hintEl.classList.remove('hidden');
            }
        });
    }
});

function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('.material-symbols-outlined');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}
</script>
@endsection
