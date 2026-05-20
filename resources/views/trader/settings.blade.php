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

    <!-- Your Shops -->
    <div class="bg-surface-container-lowest border border-surface-container-high rounded-lg overflow-hidden">
        <div class="px-8 py-6 border-b border-surface-container-high flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold font-headline">Your Shops</h3>
                <p class="text-sm text-secondary">Switch between shops or create a new one</p>
            </div>
        </div>
        <div class="p-8">
            @if($shops->isNotEmpty())
            <div class="flex flex-wrap gap-4">
                @foreach($shops as $s)
                @php $activeShopId = $shop?->shop_id ?? $shops->first()->shop_id; @endphp
                <div class="flex items-center gap-3 px-4 py-3 border {{ $activeShopId == $s->shop_id ? 'border-primary bg-primary/5' : 'border-surface-container-high' }}">
                    @if($s->shop_image)
                        <img src="{{ $s->shop_image }}" alt="" class="w-10 h-10 rounded object-cover">
                    @else
                        <div class="w-10 h-10 bg-surface-container-high flex items-center justify-center">
                            <span class="material-symbols-outlined text-secondary text-sm">store</span>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-bold {{ $activeShopId == $s->shop_id ? 'text-primary' : '' }}">{{ $s->shop_name }}</p>
                        <p class="text-xs text-secondary">{{ $s->products_count ?? $s->products?->count() ?? 0 }} products</p>
                    </div>
                    @if($activeShopId != $s->shop_id)
                    <form method="POST" action="{{ route('trader.shops.switch', $s) }}" class="ml-2">
                        @csrf
                        <button type="submit" class="text-xs text-primary font-bold hover:underline">Switch</button>
                    </form>
                    @else
                    <span class="ml-2 text-[10px] font-bold uppercase text-primary tracking-widest">Active</span>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-secondary mb-4">You haven't created any shops yet. Create your first one below.</p>
            @endif
            <button onclick="document.getElementById('add-shop-settings').classList.toggle('hidden')" class="mt-4 text-sm text-primary font-bold hover:underline flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">add</span>
                Add New Shop
            </button>
            <form id="add-shop-settings" method="POST" action="{{ route('trader.shops.store') }}" enctype="multipart/form-data" class="hidden mt-4 space-y-3 bg-surface-container-low p-4 border border-surface-container-high">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-1">Shop Name</label>
                        <input type="text" name="shop_name" required
                            class="w-full px-4 py-2.5 bg-surface border border-surface-container-high text-sm focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-1">Address</label>
                        <input type="text" name="shop_address"
                            class="w-full px-4 py-2.5 bg-surface border border-surface-container-high text-sm focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-1">Description</label>
                    <textarea name="description" rows="2"
                        class="w-full px-4 py-2.5 bg-surface border border-surface-container-high text-sm focus:ring-2 focus:ring-primary/20"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-1">Shop Photo (optional)</label>
                    <input type="file" name="shop_image" accept="image/jpeg,image/png,image/gif,image/webp"
                        class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-primary file:text-on-primary file:font-bold file:text-sm">
                </div>
                <button type="submit" class="bg-primary text-on-primary px-6 py-2.5 text-sm font-bold hover:opacity-90">
                    Create Shop
                </button>
            </form>
        </div>
    </div>

    <form method="POST" action="{{ route('trader.settings.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PATCH')

        <!-- Trader Profile -->
        <div class="bg-surface-container-lowest border border-surface-container-high rounded-lg overflow-hidden">
            <div class="px-8 py-6 border-b border-surface-container-high">
                <h3 class="text-lg font-bold font-headline">Trader Profile</h3>
                <p class="text-sm text-secondary">Your personal details and profile image</p>
            </div>
            <div class="p-8 space-y-6">
                <div class="flex items-center gap-6">
                    <div class="relative shrink-0">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->full_name }}" class="w-24 h-24 rounded-xl object-cover ring-4 ring-surface-container-high">
                        @else
                            <div class="w-24 h-24 rounded-xl bg-surface-container-high flex items-center justify-center ring-4 ring-surface-container-high">
                                <span class="material-symbols-outlined text-3xl text-secondary">person</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Profile Photo</label>
                        <input type="file" name="avatar_image" id="avatar_image" accept="image/jpeg,image/png,image/gif,image/webp"
                               class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-primary file:text-on-primary file:font-bold file:text-sm"/>
                        @error('avatar_image')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-secondary mt-2">Upload a photo (max 5MB, jpg/png/gif/webp)</p>
                    </div>
                </div>

                <hr class="border-surface-container-high">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Full Name</label>
                        <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}"
                               class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20 @error('full_name') ring-2 ring-error @enderror"/>
                        @error('full_name')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Email</label>
                        <input type="email" value="{{ $user->email }}" disabled
                               class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low text-secondary"/>
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Phone</label>
                        <input type="text" name="phone_no" value="{{ old('phone_no', $user->phone_no) }}"
                               class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Logo URL</label>
                        <input type="url" name="logo_url" value="{{ old('logo_url', $trader->logo_url) }}"
                               placeholder="https://example.com/logo.png"
                               class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20"/>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-secondary uppercase tracking-widest mb-2">Address</label>
                    <input type="text" name="address" value="{{ old('address', $user->address) }}"
                           class="w-full px-4 py-3 bg-surface-container-high border border-surface-container-low focus:ring-2 focus:ring-primary/20"/>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-primary text-on-primary px-10 py-3 font-bold rounded-lg hover:opacity-90 active:scale-95 transition-all">
                        Save Changes
                    </button>
                </div>
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
