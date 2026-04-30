@extends('layouts.profile')

@section('profile-content')
<div>
    <h3 class="font-headline text-3xl font-extrabold tracking-tight text-on-surface mb-8">
        Settings
    </h3>

    <!-- Edit Profile Form -->
    <div class="bg-surface-container-lowest p-8 rounded-lg shadow-[0_10px_30px_rgba(45,47,47,0.04)]">
        <h4 class="font-headline text-2xl font-bold text-on-surface mb-8">Edit Profile Information</h4>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Profile Picture Upload -->
            <div>
                <label class="block text-sm font-bold text-on-surface mb-4">Profile Picture</label>
                <div class="flex items-center space-x-6">
                    <!-- Current Avatar Preview -->
                    <div class="w-32 h-32 rounded-full overflow-hidden bg-surface-container">
                        <img 
                            alt="Profile picture" 
                            id="avatarPreview"
                            class="w-full h-full object-cover" 
                            src="{{ Auth::user()->avatar_url ?? 'https://via.placeholder.com/128' }}"
                        />
                    </div>

                    <!-- Upload Input -->
                    <div class="flex-1">
                        <div class="relative border-2 border-dashed border-outline-variant p-6 rounded-lg text-center cursor-pointer hover:bg-surface-container-low transition-colors" id="uploadArea">
                            <input 
                                type="file" 
                                name="avatar" 
                                id="avatarInput"
                                accept="image/*"
                                class="hidden"
                            />
                            <div class="space-y-2">
                                <span class="material-symbols-outlined text-4xl text-on-surface-variant block">cloud_upload</span>
                                <p class="text-sm font-bold text-on-surface">Click to upload or drag and drop</p>
                                <p class="text-xs text-on-surface-variant">PNG, JPG, GIF (Max 3MB)</p>
                            </div>
                        </div>
                        @error('avatar')
                        <p class="text-error text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Full Name -->
            <div>
                <label class="block text-sm font-bold text-on-surface mb-2">Full Name</label>
                <input 
                    type="text" 
                    name="full_name" 
                    value="{{ Auth::user()->full_name }}"
                    class="w-full px-4 py-3 border border-outline-variant text-on-surface placeholder-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                    required
                />
                @error('full_name')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-bold text-on-surface mb-2">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ Auth::user()->email }}"
                    class="w-full px-4 py-3 border border-outline-variant text-on-surface placeholder-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                    required
                />
                @error('email')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-bold text-on-surface mb-2">Phone Number</label>
                <input 
                    type="tel" 
                    name="phone_no" 
                    value="{{ Auth::user()->phone_no ?? '' }}"
                    class="w-full px-4 py-3 border border-outline-variant text-on-surface placeholder-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                />
                @error('phone_no')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address -->
            <div>
                <label class="block text-sm font-bold text-on-surface mb-2">Address</label>
                <input 
                    type="text" 
                    name="address" 
                    value="{{ Auth::user()->address ?? '' }}"
                    class="w-full px-4 py-3 border border-outline-variant text-on-surface placeholder-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                />
                @error('address')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- City -->
            <div>
                <label class="block text-sm font-bold text-on-surface mb-2">City</label>
                <input 
                    type="text" 
                    name="city" 
                    value="{{ Auth::user()->city ?? '' }}"
                    class="w-full px-4 py-3 border border-outline-variant text-on-surface placeholder-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                />
                @error('city')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Postal Code -->
            <div>
                <label class="block text-sm font-bold text-on-surface mb-2">Postal Code</label>
                <input 
                    type="text" 
                    name="postal_code" 
                    value="{{ Auth::user()->postal_code ?? '' }}"
                    class="w-full px-4 py-3 border border-outline-variant text-on-surface placeholder-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                />
                @error('postal_code')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex gap-3 pt-6">
                <button 
                    type="submit"
                    class="py-3 px-8 bg-primary text-on-primary font-bold text-sm uppercase tracking-wide hover:opacity-90 transition-opacity"
                >
                    Save Changes
                </button>
                <a 
                    href="{{ route('profile.dashboard') }}"
                    class="py-3 px-8 bg-surface-container text-on-surface font-bold text-sm uppercase tracking-wide hover:bg-surface-container-low transition-colors inline-block"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const uploadArea = document.getElementById('uploadArea');
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');

    // Click to upload
    uploadArea.addEventListener('click', () => avatarInput.click());

    // File input change
    avatarInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            handleFile(file);
        }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('bg-surface-container-low', 'border-primary');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('bg-surface-container-low', 'border-primary');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('bg-surface-container-low', 'border-primary');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            avatarInput.files = files;
            handleFile(files[0]);
        }
    });

    function handleFile(file) {
        // Check file size (3MB = 3072KB)
        if (file.size > 3072 * 1024) {
            alert('File size must be less than 3MB');
            avatarInput.value = '';
            return;
        }

        // Check file type
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file');
            avatarInput.value = '';
            return;
        }

        // Preview the image
        const reader = new FileReader();
        reader.onload = (e) => {
            avatarPreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
</script>
@endsection
