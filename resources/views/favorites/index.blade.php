<x-app-layout>
<x-slot name="title">My Favorites</x-slot>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Favorites</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $favorites->count() }} saved listings</p>
    </div>

    @if($favorites->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($favorites as $favorite)
            @if($favorite->listing)
                <x-listing-card :listing="$favorite->listing"/>
            @endif
        @endforeach
    </div>
    @else
    <div class="text-center py-24 bg-white rounded-2xl border border-gray-200">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
        <h3 class="text-lg font-semibold text-gray-900 mb-1">No favorites yet</h3>
        <p class="text-gray-500 text-sm mb-4">Save listings you love by clicking the heart icon.</p>
        <a href="{{ route('listings.index') }}"
           class="bg-brand-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
            Browse Listings
        </a>
    </div>
    @endif
</div>
</x-app-layout>