<!-- Edit Profile Modal -->
<div id="edit-profile-modal" class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4" style="display: none;">
    <div class="absolute inset-0 bg-black/50" onclick="closeEditProfileModal()"></div>

    <div class="relative bg-surface rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto z-10" onclick="event.stopPropagation()">
        <button onclick="closeEditProfileModal()" class="absolute top-4 right-4 p-2 hover:bg-surface-container rounded-full transition-colors z-10">
            <span class="material-symbols-outlined">close</span>
        </button>

        <div class="p-8 lg:p-10">
            <header class="mb-8">
                <h2 class="font-headline text-3xl font-bold text-on-background">Edit Profile</h2>
                <p class="text-on-surface-variant mt-2 text-sm">Update your personal information.</p>
            </header>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="space-y-6">
                    <!-- Full Name -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1">Full Name</label>
                        <input type="text" name="full_name" value="{{ old('full_name', Auth::user()->full_name) }}" class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 placeholder:text-outline/50 font-medium" required/>
                        @error('full_name')
                            <p class="text-error text-sm ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 placeholder:text-outline/50 font-medium" required/>
                        @error('email')
                            <p class="text-error text-sm ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1">Phone Number</label>
                        <input type="tel" name="phone_no" value="{{ old('phone_no', Auth::user()->phone_no ?? '') }}" placeholder="Optional" class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 placeholder:text-outline/50 font-medium"/>
                        @error('phone_no')
                            <p class="text-error text-sm ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Avatar Upload -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1">Profile Picture</label>
                        <input type="file" name="avatar" accept="image/*" class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-primary file:text-on-primary cursor-pointer"/>
                        @error('avatar')
                            <p class="text-error text-sm ml-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-on-surface-variant ml-1">PNG, JPG or GIF (max. 50MB)</p>
                    </div>

                    <!-- Address Field -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1">Address</label>
                        <input type="text" name="address" value="{{ old('address', Auth::user()->address ?? '') }}" placeholder="Optional" class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 placeholder:text-outline/50 font-medium"/>
                    </div>

                    <!-- City -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-on-surface-variant ml-1">City</label>
                        <input type="text" name="city" value="{{ old('city', Auth::user()->city ?? '') }}" placeholder="Optional" class="w-full px-6 py-4 bg-surface-container-high rounded-lg border-2 border-transparent focus:ring-2 focus:ring-primary/20 focus:bg-surface-container-lowest focus:border-primary transition-all duration-300 placeholder:text-outline/50 font-medium"/>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-5 rounded-full bg-gradient-to-r from-primary to-primary-fixed text-on-primary font-bold text-lg shadow-[0_10px_30px_rgba(177,34,9,0.15)] hover:shadow-[0_15px_35px_rgba(177,34,9,0.25)] active:scale-[0.98] transition-all duration-300 flex items-center justify-center group mt-8">
                    Save Changes
                    <span class="material-symbols-outlined ml-2 group-hover:translate-x-1 transition-transform">check</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function openEditProfileModal() {
    document.getElementById('edit-profile-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeEditProfileModal() {
    document.getElementById('edit-profile-modal').style.display = 'none';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeEditProfileModal();
    }
});
</script>