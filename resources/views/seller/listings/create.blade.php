<x-app-layout>
<x-slot name="title">Create Listing</x-slot>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <a href="{{ route('seller.listings.index') }}"
           class="text-sm text-brand-600 hover:text-brand-700 font-semibold">← Back to listings</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Create New Listing</h1>
    </div>

    <form method="POST" action="{{ route('seller.listings.store') }}"
          enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Title --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="font-bold text-gray-900 mb-4">Basic Info</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           placeholder="e.g. iPhone 15 Pro Max 256GB"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none transition-all
                                  {{ $errors->has('title') ? 'border-red-400' : 'border-gray-300 focus:border-brand-500 focus:ring-2 focus:ring-brand-100' }}">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="5"
                        placeholder="Describe your item in detail..."
                        class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none transition-all resize-none
                               {{ $errors->has('description') ? 'border-red-400' : 'border-gray-300 focus:border-brand-500 focus:ring-2 focus:ring-brand-100' }}">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id"
                            class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none
                                   {{ $errors->has('category_id') ? 'border-red-400' : 'border-gray-300 focus:border-brand-500' }}">
                            <option value="">Select category</option>
                            @foreach($categories as $parent)
                                <optgroup label="{{ $parent->name }}">
                                    @foreach($parent->children as $child)
                                        <option value="{{ $child->id }}"
                                            {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                            {{ $child->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Condition <span class="text-red-500">*</span>
                        </label>
                        <select name="condition"
                            class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none
                                   {{ $errors->has('condition') ? 'border-red-400' : 'border-gray-300 focus:border-brand-500' }}">
                            <option value="">Select condition</option>
                            @foreach(['new' => 'New', 'like_new' => 'Like New', 'good' => 'Good', 'fair' => 'Fair'] as $val => $label)
                                <option value="{{ $val }}" {{ old('condition') === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('condition')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Price ($) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="price" value="{{ old('price') }}"
                               min="1" step="0.01" placeholder="0.00"
                               class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none
                                      {{ $errors->has('price') ? 'border-red-400' : 'border-gray-300 focus:border-brand-500' }}">
                        @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Quantity <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="quantity" value="{{ old('quantity', 1) }}"
                               min="1" placeholder="1"
                               class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none
                                      {{ $errors->has('quantity') ? 'border-red-400' : 'border-gray-300 focus:border-brand-500' }}">
                        @error('quantity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Location <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="location" value="{{ old('location') }}"
                               placeholder="e.g. Karachi, Pakistan"
                               class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none
                                      {{ $errors->has('location') ? 'border-red-400' : 'border-gray-300 focus:border-brand-500' }}">
                        @error('location')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status"
                            class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none border-gray-300 focus:border-brand-500">
                            <option value="draft"  {{ old('status') === 'draft'  ? 'selected' : '' }}>Draft (hidden)</option>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active (visible)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Images --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="font-bold text-gray-900 mb-1">Images</h2>
            <p class="text-xs text-gray-500 mb-4">Upload 1–10 images. First image will be the featured image. Max 5MB each.</p>

            <input type="file" name="images[]" multiple accept="image/jpg,image/jpeg,image/png,image/webp"
                   onchange="previewImages(this, 'image-preview')"
                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl
                          file:border-0 file:text-sm file:font-semibold file:bg-brand-50 file:text-brand-700
                          hover:file:bg-brand-100 cursor-pointer">
            @error('images')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            @error('images.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

            <div id="image-preview" class="flex flex-wrap gap-3 mt-4"></div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-4">
            <button type="submit"
                class="bg-brand-600 text-white px-8 py-3 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
                Create Listing
            </button>
            <a href="{{ route('seller.listings.index') }}"
               class="text-sm text-gray-600 hover:text-gray-900 font-medium">Cancel</a>
        </div>
    </form>
</div>
</x-app-layout>