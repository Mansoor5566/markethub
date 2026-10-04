<x-admin-layout>
<x-slot name="title">Reviews</x-slot>

{{-- Search --}}
<div class="bg-white rounded-2xl border border-gray-200 p-4 mb-6">
    <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search by reviewer or content..."
               class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded-xl outline-none focus:border-brand-500">
        <button type="submit"
            class="bg-brand-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
            Search
        </button>
        <a href="{{ route('admin.reviews.index') }}"
           class="bg-gray-100 text-gray-700 px-5 py-2 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
            Clear
        </a>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Reviewer</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Listing</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Rating</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Review</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">{{ $review->reviewer->name }}</p>
                        <p class="text-xs text-gray-400">{{ $review->created_at->format('M d, Y') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('listings.show', $review->listing->slug) }}" target="_blank"
                           class="text-sm text-brand-600 hover:text-brand-700 truncate max-w-[150px] block">
                            {{ $review->listing->title }}
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <x-star-rating :rating="$review->rating"/>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-600 truncate max-w-[200px]">{{ $review->body }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                              onsubmit="return confirm('Delete this review?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="px-3 py-1.5 text-xs font-semibold text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No reviews found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $reviews->links() }}
    </div>
</div>

</x-admin-layout>