<x-app-layout>
<x-slot name="title">{{ $user->name }}</x-slot>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Seller Header --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-brand-500 flex-shrink-0 flex items-center justify-center text-white text-3xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <x-star-rating :rating="$user->averageRating()"/>
                    <span class="text-sm text-gray-600 font-medium">
                        {{ $user->averageRating() }}/5
                        ({{ $user->receivedReviews()->count() }} reviews)
                    </span>
                </div>
                <p class="text-sm text-gray-500 mt-1">
                    Member since {{ $user->created_at->format('F Y') }}
                </p>
                @if($user->bio)
                    <p class="text-sm text-gray-700 mt-3">{{ $user->bio }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Listings --}}
        <div class="lg:col-span-2">
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                Active Listings ({{ $listings->total() }})
            </h2>
            @if($listings->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($listings as $listing)
                    <x-listing-card :listing="$listing"/>
                @endforeach
            </div>
            <div class="mt-6">{{ $listings->links() }}</div>
            @else
            <div class="text-center py-12 bg-white rounded-2xl border border-gray-200">
                <p class="text-gray-500 text-sm">No active listings.</p>
            </div>
            @endif
        </div>

        {{-- Reviews --}}
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Reviews</h2>
            @if($reviews->count())
            <div class="space-y-4">
                @foreach($reviews as $review)
                <div class="bg-white rounded-2xl border border-gray-200 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-7 h-7 rounded-full bg-brand-500 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr($review->reviewer->name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $review->reviewer->name }}</span>
                    </div>
                    <x-star-rating :rating="$review->rating"/>
                    @if($review->title)
                        <p class="text-sm font-medium text-gray-800 mt-2">{{ $review->title }}</p>
                    @endif
                    <p class="text-sm text-gray-600 mt-1">{{ $review->body }}</p>
                    <p class="text-xs text-gray-400 mt-2">{{ $review->created_at->diffForHumans() }}</p>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 bg-white rounded-2xl border border-gray-200">
                <p class="text-gray-500 text-sm">No reviews yet.</p>
            </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>