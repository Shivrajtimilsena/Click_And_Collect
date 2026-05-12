@extends('layouts.trader')

@section('title', 'Edit Product | Trader Portal')

@section('header-title', 'Edit Product')

@section('content')
<div class="max-w-6xl">
    <div class="mb-8">
        <div class="text-sm text-secondary uppercase tracking-widest font-bold">
            <a href="{{ route('trader.inventory.index') }}" class="hover:text-on-surface">Inventory</a>
            <span class="mx-2">&gt;</span>
            <span class="text-primary">EDIT PRODUCT</span>
        </div>
        <h1 class="text-4xl font-bold font-headline text-on-background mt-4">Edit Artisan<br/>Listing</h1>
    </div>

    <form id="product-form" action="{{ route('trader.product.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Form Sections -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Product Fundamentals Section -->
                <div class="bg-surface-container-lowest border border-surface-container-high rounded-lg p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-5 h-5 rounded-full bg-primary flex items-center justify-center">
                            <span class="text-white text-xs font-bold">📦</span>
                        </div>
                        <h3 class="text-lg font-bold text-on-background">Product Fundamentals</h3>
                    </div>

                    <div class="space-y-6">
                        <!-- Product Name -->
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">Product Name</label>
                            <input 
                                type="text" 
                                name="product_name"
                                value="{{ old('product_name', $product->product_name) }}"
                                class="w-full bg-surface-container-high border-0 rounded-md px-4 py-3 focus:ring-2 focus:ring-primary/20 transition-all @error('product_name') ring-2 ring-error @enderror"
                                placeholder="e.g. Traditional Sourdough Boule"
                                required
                            />
                            @error('product_name')
                                <p class="text-xs text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category & Price Row -->
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">Category</label>
                                <select 
                                    name="product_category_id"
                                    class="w-full bg-surface-container-high border-0 rounded-md px-4 py-3 focus:ring-2 focus:ring-primary/20 transition-all appearance-none @error('product_category_id') ring-2 ring-error @enderror"
                                    required
                                >
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->product_category_id }}" {{ old('product_category_id', $product->product_category_id) == $category->product_category_id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_category_id')
                                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">Base Price (£)</label>
                                <input 
                                    type="number" 
                                    name="price"
                                    value="{{ old('price', $product->price) }}"
                                    step="0.01"
                                    min="0"
                                    class="w-full bg-surface-container-high border-0 rounded-md px-4 py-3 focus:ring-2 focus:ring-primary/20 transition-all @error('price') ring-2 ring-error @enderror"
                                    placeholder="0.00"
                                    required
                                />
                                @error('price')
                                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- The Craftsmanship Section -->
                <div class="bg-surface-container-lowest border border-surface-container-high rounded-lg p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-5 h-5 rounded-full bg-primary flex items-center justify-center">
                            <span class="text-white text-xs font-bold">✨</span>
                        </div>
                        <h3 class="text-lg font-bold text-on-background">The Craftsmanship</h3>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">The Story Behind The Product</label>
                        <textarea 
                            name="description"
                            class="w-full bg-surface-container-high border-0 rounded-md px-4 py-3 focus:ring-2 focus:ring-primary/20 transition-all resize-none @error('description') ring-2 ring-error @enderror"
                            rows="5"
                            placeholder="Describe the heritage, ingredients, and the hands that crafted this item..."
                            required
                        >{{ old('description', $product->description) }}</textarea>
                        <p class="text-xs text-secondary mt-2">This will appear as the primary product copy on the customer storefront.</p>
                        @error('description')
                            <p class="text-xs text-error mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Inventory & Safety Section -->
                <div class="bg-surface-container-lowest border border-surface-container-high rounded-lg p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-5 h-5 rounded-full bg-primary flex items-center justify-center">
                            <span class="text-white text-xs font-bold">📊</span>
                        </div>
                        <h3 class="text-lg font-bold text-on-background">Inventory & Safety</h3>
                    </div>

                    <div class="space-y-6">
                        <!-- Stock Levels -->
                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">Stock Level</label>
                                <input 
                                    type="number" 
                                    name="stock"
                                    value="{{ old('stock', $product->stock) }}"
                                    min="0"
                                    class="w-full bg-surface-container-high border-0 rounded-md px-4 py-3 focus:ring-2 focus:ring-primary/20 transition-all @error('stock') ring-2 ring-error @enderror"
                                    required
                                />
                                @error('stock')
                                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">Min. Order</label>
                                <input 
                                    type="number" 
                                    name="min_order"
                                    value="{{ old('min_order', $product->min_order) }}"
                                    min="1"
                                    class="w-full bg-surface-container-high border-0 rounded-md px-4 py-3 focus:ring-2 focus:ring-primary/20 transition-all @error('min_order') ring-2 ring-error @enderror"
                                />
                                @error('min_order')
                                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-2">Max. Order</label>
                                <input 
                                    type="number" 
                                    name="max_order"
                                    value="{{ old('max_order', $product->max_order) }}"
                                    min="1"
                                    class="w-full bg-surface-container-high border-0 rounded-md px-4 py-3 focus:ring-2 focus:ring-primary/20 transition-all @error('max_order') ring-2 ring-error @enderror"
                                />
                                @error('max_order')
                                    <p class="text-xs text-error mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Allergy Information -->
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-4">Allergy Information</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="allergens[]" value="gluten" {{ in_array('gluten', old('allergens', [])) ? 'checked' : '' }} class="rounded border-outline text-primary"/>
                                    <span class="text-sm text-on-surface">Gluten</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="allergens[]" value="dairy" {{ in_array('dairy', old('allergens', [])) ? 'checked' : '' }} class="rounded border-outline text-primary"/>
                                    <span class="text-sm text-on-surface">Dairy</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="allergens[]" value="nuts" {{ in_array('nuts', old('allergens', [])) ? 'checked' : '' }} class="rounded border-outline text-primary"/>
                                    <span class="text-sm text-on-surface">Nuts</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="allergens[]" value="soya" {{ in_array('soya', old('allergens', [])) ? 'checked' : '' }} class="rounded border-outline text-primary"/>
                                    <span class="text-sm text-on-surface">Soya</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Image Upload Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Product Image Section -->
                <div class="bg-surface-container-lowest border border-surface-container-high rounded-lg p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-5 h-5 rounded-full bg-primary flex items-center justify-center">
                            <span class="text-white text-xs font-bold">📸</span>
                        </div>
                        <h3 class="text-lg font-bold text-on-background">Product Image</h3>
                    </div>

                    <div id="dropZone" class="bg-surface-container-low border-2 border-dashed border-surface-container-high rounded-lg p-8 text-center mb-6 cursor-pointer hover:bg-surface-container transition-all">
                        <input type="file" id="imageInput" name="image" accept="image/*" class="hidden" />
                        <div id="uploadPrompt" class="flex flex-col items-center justify-center gap-4">
                            <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center">
                                <span class="material-symbols-outlined text-secondary text-2xl">image</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-on-surface">Upload Product Images</p>
                                <p class="text-xs text-secondary">Drag & drop or click to upload</p>
                            </div>
                            <button type="button" class="text-primary text-sm font-bold hover:underline">Browse Files</button>
                        </div>
                        <div id="imagePreview" class="hidden flex flex-col items-center gap-4">
                            <img id="previewImg" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                            <button type="button" class="text-error text-sm font-bold hover:underline">Remove Image</button>
                        </div>
                    </div>

                    @if($product->image_url)
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-xs text-green-700 font-bold mb-2">Current Image</p>
                            <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" class="w-full h-24 object-cover rounded"/>
                        </div>
                    @endif

                    @error('image')
                        <p class="text-xs text-error mb-4">{{ $message }}</p>
                    @enderror

                    <div class="flex gap-2">
                        <button type="button" class="flex-1 h-10 bg-surface-container-high rounded-md hover:bg-surface-container-high transition-all flex items-center justify-center">
                            <span class="material-symbols-outlined text-sm">add</span>
                        </button>
                        <button type="button" class="flex-1 h-10 bg-surface-container-high rounded-md hover:bg-surface-container-high transition-all flex items-center justify-center">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </button>
                        <button type="button" class="flex-1 h-10 bg-surface-container-high rounded-md hover:bg-surface-container-high transition-all flex items-center justify-center">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </div>
                </div>

                <!-- Ready to Publish Section -->
                <div class="bg-primary text-on-primary rounded-xl p-8 space-y-4">
                    <h4 class="text-lg font-bold">Ready to Save Changes?</h4>
                    <p class="text-sm opacity-90">Review your updated product details before saving.</p>
                    
                    <button type="submit" class="w-full bg-white text-primary font-bold py-3 px-4 rounded-full hover:opacity-90 transition-all uppercase tracking-widest text-sm">
                        Save Changes
                    </button>
                    
                    <a href="{{ route('trader.inventory.index') }}" class="w-full bg-primary-container text-on-primary font-bold py-3 px-4 rounded-full hover:opacity-90 transition-all text-center block">
                        Cancel
                    </a>
                </div>

                <!-- Visibility Section -->
                <div class="bg-surface-container-low rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-on-background">Visibility</h4>
                        <button type="button" class="relative inline-flex h-6 w-11 items-center rounded-full bg-primary">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white ml-1 transition"></span>
                        </button>
                    </div>
                    <p class="text-xs text-secondary">Currently in Public - customers will see this on the Click and Collect marketplace.</p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('dropZone');
        const imageInput = document.getElementById('imageInput');
        const uploadPrompt = document.getElementById('uploadPrompt');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        // Click to upload
        dropZone.addEventListener('click', () => imageInput.click());

        // File input change
        imageInput.addEventListener('change', function(e) {
            handleFile(this.files[0]);
        });

        // Drag and drop
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('bg-surface-container');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('bg-surface-container');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('bg-surface-container');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                imageInput.files = files;
                handleFile(files[0]);
            }
        });

        // Handle file selection
        function handleFile(file) {
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                uploadPrompt.classList.add('hidden');
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        // Remove image button
        document.querySelectorAll('[type="button"]').forEach(btn => {
            if (btn.textContent.includes('Remove')) {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    imageInput.value = '';
                    uploadPrompt.classList.remove('hidden');
                    imagePreview.classList.add('hidden');
                });
            }
        });
    });
</script>
@endsection
