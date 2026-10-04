<x-app-layout>
<x-slot name="title">Search Results</x-slot>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
            @if($q)
                Search results for "{{ $q }}"
            @else
                All Listings
            @endif
        </h1>
        <p class="text-sm text-gray-500 mt-1">{{ $listings->total() }} results found</p>
    </div>

    {{-- Search bar --}}
    <form method="GET" action="{{ route('search') }}" class="mb-8">
        <div class="flex gap-3 max-w-xl">
            <input type="search" name="q" value="{{ $q }}"
                placeholder="Search listings..."
                class="flex-1 px-4 py-2.5 text-sm border border-gray-300 rounded-xl outline-none
                       focus:border-brand-500 focus:ring-2 focus:ring-brand-100 bg-gray-50 focus:bg-white">
            <button type="submit"
                class="bg-brand-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
                Search
            </button>
        </div>
    </form>

    @if($listings->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($listings as $listing)
                <x-listing-card :listing="$listing"/>
            @endforeach
        </div>
        <div class="mt-8">{{ $listings->links() }}</div>
    @else
        <div class="text-center py-24 bg-white rounded-2xl border border-gray-200">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">No results found</h3>
            <p class="text-gray-500 text-sm mb-4">Try different keywords or browse all listings.</p>
            <a href="{{ route('listings.index') }}"
               class="text-brand-600 font-semibold text-sm hover:text-brand-700">
                Browse all listings →
            </a>
        </div>
    @endif
</div>
</x-app-layout>