@props(['listing'])
@php
$isFav = auth()->check()
    ? auth()->user()->favorites()->where('listing_id',$listing->id)->exists()
    : false;
$conditionColors = [
    'new'      => 'bg-green-100 text-green-700',
    'like_new' => 'bg-emerald-100 text-emerald-700',
    'good'     => 'bg-blue-100 text-blue-700',
    'fair'     => 'bg-amber-100 text-amber-700',
];
@endphp
<article class="group bg-white rounded-2xl border border-gray-200 overflow-hidden hover:border-brand-300 hover:shadow-md transition-all flex flex-col">
    <div class="relative aspect-video overflow-hidden bg-gray-100">
        <a href="{{ route('listings.show', $listing->slug) }}">
            @if($listing->images->count())
                <img src="{{ Storage::url($listing->images->first()->path) }}"
                     alt="{{ $listing->title }}"
                     loading="lazy"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-300">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
        </a>
        <span class="absolute top-2 left-2 text-xs font-semibold px-2 py-1 rounded-lg {{ $conditionColors[$listing->condition] ?? 'bg-gray-100 text-gray-700' }}">
            {{ ucfirst(str_replace('_',' ',$listing->condition)) }}
        </span>
        @auth
        <button onclick="toggleFavorite({{ $listing->id }})"
            class="absolute top-2 right-2 w-8 h-8 rounded-full bg-white/90 flex items-center justify-center shadow hover:scale-110 transition-transform">
            <svg id="fav-icon-{{ $listing->id }}" class="w-4 h-4 {{ $isFav ? 'text-red-500' : 'text-gray-300' }}"
                 fill="{{ $isFav ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </button>
        @endauth
    </div>

    <div class="p-4 flex flex-col flex-1 gap-2">
        <div class="flex items-center justify-between">
            <span class="text-xl font-bold text-gray-900">${{ number_format($listing->price, 2) }}</span>
            @if($listing->quantity <= 3 && $listing->quantity > 0)
                <span class="text-xs text-amber-600 font-medium">Only {{ $listing->quantity }} left</span>
            @endif
        </div>
        <a href="{{ route('listings.show', $listing->slug) }}"
           class="text-sm font-semibold text-gray-900 hover:text-brand-600 line-clamp-2 leading-snug">
            {{ $listing->title }}
        </a>
        <p class="text-xs text-gray-500">📍 {{ $listing->location }}</p>
        <div class="mt-auto pt-3 border-t border-gray-100 flex items-center gap-2">
            <div class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-white text-[10px] font-bold">
                {{ strtoupper(substr($listing->seller->name, 0, 1)) }}
            </div>
            <span class="text-xs text-gray-500 truncate">{{ $listing->seller->name }}</span>
        </div>
    </div>
</article>