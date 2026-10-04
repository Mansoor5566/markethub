<x-app-layout>
<x-slot name="title">Browse Listings</x-slot>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Browse Listings</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $listings->total() }} listings found</p>
        </div>
        {{-- Sort --}}
        <form method="GET" action="{{ route('listings.index') }}" id="sort-form">
            @foreach(request()->except('sort') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <select name="sort" onchange="document.getElementById('sort-form').submit()"
                class="text-sm border border-gray-300 rounded-xl px-3 py-2 outline-none
                       focus:border-brand-500 bg-white">
                <option value="newest"     {{ request('sort','newest') === 'newest'     ? 'selected' : '' }}>Newest First</option>
                <option value="price_low"  {{ request('sort') === 'price_low'           ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_high" {{ request('sort') === 'price_high'          ? 'selected' : '' }}>Price: High to Low</option>
                <option value="popular"    {{ request('sort') === 'popular'             ? 'selected' : '' }}>Most Popular</option>
            </select>
        </form>
    </div>

    <div class="flex gap-8">

        {{-- Sidebar Filters --}}
        <aside class="hidden lg:block w-64 flex-shrink-0">
            <form method="GET" action="{{ route('listings.index') }}" id="filter-form">
                <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}">

                <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-6">

                    <h3 class="font-semibold text-gray-900">Filters</h3>

                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                        <select name="category"
                            class="w-full text-sm border border-gray-300 rounded-xl px-3 py-2 outline-none focus:border-brand-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $parent)
                                <optgroup label="{{ $parent->name }}">
                                    @foreach($parent->children as $child)
                                        <option value="{{ $child->id }}"
                                            {{ request('category') == $child->id ? 'selected' : '' }}>
                                            {{ $child->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    {{-- Condition --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Condition</label>
                        <div class="space-y-2">
                            @foreach(['new' => 'New', 'like_new' => 'Like New', 'good' => 'Good', 'fair' => 'Fair'] as $value => $label)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="condition" value="{{ $value }}"
                                    {{ request('condition') === $value ? 'checked' : '' }}
                                    class="text-brand-600">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="condition" value=""
                                    {{ !request('condition') ? 'checked' : '' }}
                                    class="text-brand-600">
                                <span class="text-sm text-gray-700">Any</span>
                            </label>
                        </div>
                    </div>

                    {{-- Price --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Price Range</label>
                        <div class="flex gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}"
                                placeholder="Min"
                                class="w-full text-sm border border-gray-300 rounded-xl px-3 py-2 outline-none focus:border-brand-500">
                            <input type="number" name="max_price" value="{{ request('max_price') }}"
                                placeholder="Max"
                                class="w-full text-sm border border-gray-300 rounded-xl px-3 py-2 outline-none focus:border-brand-500">
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="space-y-2">
                        <button type="submit"
                            class="w-full bg-brand-600 text-white py-2 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
                            Apply Filters
                        </button>
                        <a href="{{ route('listings.index') }}"
                            class="block w-full text-center bg-gray-100 text-gray-700 py-2 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
                            Clear All
                        </a>
                    </div>
                </div>
            </form>
        </aside>

        {{-- Listings Grid --}}
        <div class="flex-1 min-w-0">

            {{-- Active filter pills --}}
            @if(request()->hasAny(['category', 'condition', 'min_price', 'max_price']))
            <div class="flex flex-wrap gap-2 mb-4">
                @if(request('category'))
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-brand-100 text-brand-700 text-xs font-semibold rounded-lg">
                        Category filter
                        <a href="{{ request()->fullUrlWithoutQuery('category') }}" class="hover:text-brand-900">×</a>
                    </span>
                @endif
                @if(request('condition'))
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-brand-100 text-brand-700 text-xs font-semibold rounded-lg">
                        {{ ucfirst(str_replace('_',' ',request('condition'))) }}
                        <a href="{{ request()->fullUrlWithoutQuery('condition') }}" class="hover:text-brand-900">×</a>
                    </span>
                @endif
                @if(request('min_price') || request('max_price'))
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-brand-100 text-brand-700 text-xs font-semibold rounded-lg">
                        ${{ request('min_price', '0') }} – ${{ request('max_price', '∞') }}
                        <a href="{{ request()->fullUrlWithoutQuery(['min_price','max_price']) }}" class="hover:text-brand-900">×</a>
                    </span>
                @endif
            </div>
            @endif

            {{-- Grid --}}
            @if($listings->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($listings as $listing)
                        <x-listing-card :listing="$listing"/>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $listings->links() }}
                </div>
            @else
                <div class="text-center py-24 bg-white rounded-2xl border border-gray-200">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">No listings found</h3>
                    <p class="text-gray-500 text-sm mb-4">Try adjusting your filters or search terms.</p>
                    <a href="{{ route('listings.index') }}"
                       class="text-brand-600 font-semibold text-sm hover:text-brand-700">
                        Clear all filters
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>