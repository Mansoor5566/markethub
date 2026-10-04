<x-app-layout>
    <x-slot name="title">{{ $listing->title }}</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-brand-600">Home</a>
            <span>/</span>
            <a href="{{ route('listings.index') }}" class="hover:text-brand-600">Listings</a>
            <span>/</span>
            @if ($listing->category)
                <a href="{{ route('categories.show', $listing->category->slug) }}"
                    class="hover:text-brand-600">{{ $listing->category->name }}</a>
                <span>/</span>
            @endif
            <span class="text-gray-900 font-medium truncate">{{ $listing->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left: Images + Description --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Image Gallery --}}
                <x-image-gallery :images="$listing->images" />

                {{-- Description --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Description</h2>
                    <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $listing->description }}
                    </div>
                </div>

                {{-- Reviews --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-900">
                            Reviews ({{ $listing->reviews->count() }})
                        </h2>
                        @if ($listing->reviews->count())
                            <div class="flex items-center gap-2">
                                <x-star-rating :rating="$listing->averageRating()" />
                                <span class="text-sm font-semibold text-gray-700">
                                    {{ $listing->averageRating() }}/5
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Write review button --}}
                    @if ($canReview)
                        <div class="mb-6 p-4 bg-brand-50 border border-brand-200 rounded-xl">
                            <p class="text-sm text-brand-700 font-medium mb-3">
                                You purchased this item. Share your experience!
                            </p>
                            <button onclick="document.getElementById('review-form').classList.toggle('hidden')"
                                class="bg-brand-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
                                Write a Review
                            </button>

                            {{-- Review Form --}}
                            <div id="review-form" class="hidden mt-4">
                                @php
                                    $reviewableOrder = \App\Models\Order::where('buyer_id', auth()->id())
                                        ->where('listing_id', $listing->id)
                                        ->where('status', 'completed')
                                        ->whereDoesntHave('review')
                                        ->first();
                                @endphp
                                @if ($reviewableOrder)
                                    <form method="POST" action="{{ route('reviews.store', $reviewableOrder) }}">
                                        @csrf

                                        {{-- Star Rating --}}
                                        <div class="mb-4">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Rating</label>
                                            <div class="flex items-center gap-2" id="star-container">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <button type="button" data-star="{{ $i }}"
                                                        onclick="setRating({{ $i }})"
                                                        class="text-3xl text-gray-300 hover:text-amber-400 transition-colors star-btn">
                                                        ★
                                                    </button>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" id="rating-input" value="">
                                            @error('rating')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Title --}}
                                        <div class="mb-4">
                                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                                Title <span class="text-gray-400 font-normal">(optional)</span>
                                            </label>
                                            <input type="text" name="title" value="{{ old('title') }}"
                                                maxlength="100" placeholder="Summarize your experience"
                                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl outline-none focus:border-brand-500">
                                        </div>

                                        {{-- Body --}}
                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-semibold text-gray-700 mb-1.5">Review</label>
                                            <textarea name="body" rows="4" placeholder="Tell others about your experience..."
                                                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl outline-none focus:border-brand-500 resize-none">{{ old('body') }}</textarea>
                                            @error('body')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <button type="submit"
                                            class="bg-brand-600 text-white px-6 py-2 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
                                            Submit Review
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Reviews List --}}
                    @forelse($listing->reviews as $review)
                        <div class="border-b border-gray-100 last:border-0 pb-4 last:pb-0 mb-4 last:mb-0">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-brand-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($review->reviewer->name, 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="text-sm font-semibold text-gray-900">{{ $review->reviewer->name }}</span>
                                        <x-star-rating :rating="$review->rating" />
                                    </div>
                                    @if ($review->title)
                                        <p class="text-sm font-medium text-gray-800 mb-1">{{ $review->title }}</p>
                                    @endif
                                    <p class="text-sm text-gray-600">{{ $review->body }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm text-center py-8">No reviews yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Right: Buy box + Seller card --}}
            <div class="space-y-4">

                {{-- Price & Buy --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-20">
                    <div class="mb-4">
                        <span class="text-3xl font-bold text-gray-900">
                            ${{ number_format($listing->price, 2) }}
                        </span>
                        <div class="flex items-center gap-2 mt-2">
                            <x-status-badge :status="$listing->condition" />
                            @if ($listing->quantity > 0)
                                <span class="text-xs text-gray-500">{{ $listing->quantity }} available</span>
                            @endif
                        </div>
                    </div>

                    <h1 class="text-lg font-bold text-gray-900 mb-2">{{ $listing->title }}</h1>

                    <div class="flex items-center gap-1 text-xs text-gray-500 mb-4">
                        <span>📍</span>
                        <span>{{ $listing->location }}</span>
                    </div>

                    <div class="flex items-center gap-1 text-xs text-gray-500 mb-6">
                        <span>👁</span>
                        <span>{{ $listing->views_count }} views</span>
                    </div>

                    {{-- Buy Button (FR-DETAIL-03) --}}
                    @auth
                        @if (auth()->id() === $listing->user_id)
                            <a href="{{ route('seller.listings.edit', $listing) }}"
                                class="block w-full text-center bg-gray-100 text-gray-700 py-3 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
                                Edit Listing
                            </a>
                        @elseif($listing->quantity <= 0 || $listing->status !== 'active')
                            <button disabled
                                class="w-full bg-gray-200 text-gray-500 py-3 rounded-xl text-sm font-semibold cursor-not-allowed">
                                Sold Out
                            </button>
                        @else
                            <form method="POST" action="{{ route('checkout.create', $listing) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-brand-600 text-white py-3 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
                                    Buy Now
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="block w-full text-center bg-brand-600 text-white py-3 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
                            Login to Purchase
                        </a>
                    @endauth

                    {{-- Favorite --}}
                    @auth
                        <button onclick="toggleFavorite({{ $listing->id }})"
                            class="mt-3 w-full flex items-center justify-center gap-2 border border-gray-300 text-gray-700 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50 transition-colors">
                            <svg id="fav-icon-{{ $listing->id }}"
                                class="w-4 h-4 {{ auth()->user()->favorites()->where('listing_id', $listing->id)->exists() ? 'text-red-500' : 'text-gray-400' }}"
                                fill="{{ auth()->user()->favorites()->where('listing_id', $listing->id)->exists() ? 'currentColor' : 'none' }}"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Save to Favorites
                        </button>
                    @endauth
                </div>

                {{-- Seller Card (FR-DETAIL-02) --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Seller</h3>
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-brand-500 flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($listing->seller->name, 0, 1)) }}
                        </div>
                        <div>
                            <a href="{{ route('sellers.show', $listing->seller) }}"
                                class="font-semibold text-gray-900 hover:text-brand-600 transition-colors">
                                {{ $listing->seller->name }}
                            </a>
                            <div class="flex items-center gap-1 mt-0.5">
                                <x-star-rating :rating="$listing->seller->averageRating()" size="sm" />
                                <span class="text-xs text-gray-500">
                                    ({{ $listing->seller->receivedReviews()->count() }} reviews)
                                </span>
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Member since {{ $listing->seller->created_at->format('M Y') }}
                            </p>
                        </div>
                    </div>

                    {{-- Message seller --}}
                    @auth
                        @if (auth()->id() !== $listing->user_id)
                            <a href="{{ route('messages.show', [$listing->seller, $listing]) }}"
                                class="block w-full text-center border border-brand-600 text-brand-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-50 transition-colors">
                                Message Seller
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function setRating(val) {
                document.getElementById('rating-input').value = val;
                document.querySelectorAll('.star-btn').forEach((btn, i) => {
                    btn.classList.toggle('text-amber-400', i < val);
                    btn.classList.toggle('text-gray-300', i >= val);
                });
            }
        </script>
    @endpush
</x-app-layout>
