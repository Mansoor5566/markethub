<x-app-layout>
<x-slot name="title">Home</x-slot>

{{-- Hero --}}
<section class="bg-gradient-to-br from-brand-600 to-brand-800 text-white py-16 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-3xl sm:text-5xl font-bold mb-4">Buy & Sell Anything</h1>
        <p class="text-brand-200 text-lg mb-8">Discover thousands of listings from trusted sellers near you.</p>
        <form action="{{ route('search') }}" method="GET" class="max-w-xl mx-auto">
            <div class="flex gap-2 bg-white rounded-2xl p-2 shadow-lg">
                <input type="search" name="q" placeholder="What are you looking for?"
                    class="flex-1 px-4 py-2 text-gray-900 text-sm outline-none rounded-xl bg-transparent">
                <button type="submit"
                    class="bg-brand-600 text-white px-6 py-2 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
                    Search
                </button>
            </div>
        </form>
    </div>
</section>

{{-- Categories --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Browse by Category</h2>
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4">
        @forelse($categories as $category)
        <a href="{{ route('categories.show', $category->slug) }}"
           class="flex flex-col items-center gap-2 p-4 bg-white rounded-2xl border border-gray-200
                  hover:border-brand-300 hover:shadow-sm transition-all text-center group">
            <div class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center
                        group-hover:bg-brand-200 transition-colors">
                <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-gray-700 group-hover:text-brand-600 leading-tight">
                {{ $category->name }}
            </span>
            <span class="text-xs text-gray-400">{{ $category->listings_count }}</span>
        </a>
        @empty
        <p class="text-gray-500 col-span-8">No categories yet.</p>
        @endforelse
    </div>
</section>

{{-- Featured Listings --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Featured Listings</h2>
        <a href="{{ route('listings.index') }}"
           class="text-sm text-brand-600 font-semibold hover:text-brand-700">
            View all →
        </a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($featuredListings as $listing)
            <x-listing-card :listing="$listing"/>
        @empty
            <p class="text-gray-500 col-span-4 text-center py-12">No listings yet.</p>
        @endforelse
    </div>
</section>

{{-- Recent Listings --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Recently Added</h2>
        <a href="{{ route('listings.index') }}"
           class="text-sm text-brand-600 font-semibold hover:text-brand-700">
            View all →
        </a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($recentListings as $listing)
            <x-listing-card :listing="$listing"/>
        @empty
            <p class="text-gray-500 col-span-4 text-center py-12">No listings yet.</p>
        @endforelse
    </div>
</section>

</x-app-layout>