<x-app-layout>
<x-slot name="title">{{ $category->name }}</x-slot>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-3">
            <a href="{{ route('home') }}" class="hover:text-brand-600">Home</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">{{ $category->name }}</span>
        </nav>
        <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
        @if($category->description)
            <p class="text-gray-500 text-sm mt-1">{{ $category->description }}</p>
        @endif
    </div>

    {{-- Subcategory pills --}}
    @if($category->children->count())
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ route('categories.show', $category->slug) }}"
           class="px-4 py-1.5 rounded-full text-sm font-semibold transition-colors
                  {{ !request('sub') ? 'bg-brand-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:border-brand-400' }}">
            All
        </a>
        @foreach($category->children as $child)
        <a href="{{ route('categories.show', $category->slug) }}?sub={{ $child->id }}"
           class="px-4 py-1.5 rounded-full text-sm font-semibold transition-colors
                  {{ request('sub') == $child->id ? 'bg-brand-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:border-brand-400' }}">
            {{ $child->name }}
        </a>
        @endforeach
    </div>
    @endif

    {{-- Listings grid --}}
    @if($listings->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($listings as $listing)
                <x-listing-card :listing="$listing"/>
            @endforeach
        </div>
        <div class="mt-8">{{ $listings->links() }}</div>
    @else
        <div class="text-center py-24 bg-white rounded-2xl border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">No listings in this category</h3>
            <p class="text-gray-500 text-sm mb-4">Be the first to list something here.</p>
            <a href="{{ route('listings.index') }}"
               class="text-brand-600 font-semibold text-sm hover:text-brand-700">
                Browse all listings →
            </a>
        </div>
    @endif
</div>
</x-app-layout>