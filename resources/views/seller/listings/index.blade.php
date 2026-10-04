<x-app-layout>
    <x-slot name="title">My Listings</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Listings</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $listings->total() }} total listings</p>
            </div>
            <a href="{{ route('seller.listings.create') }}"
                class="bg-brand-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
                + New Listing
            </a>
        </div>

        {{-- Status filter tabs --}}
       {{-- Status filter tabs --}}
<div class="flex gap-2 mb-6 overflow-x-auto pb-1">
    @foreach(['' => 'All', 'active' => 'Active', 'draft' => 'Draft', 'paused' => 'Paused', 'sold' => 'Sold', 'deleted' => 'Deleted'] as $value => $label)
    <a href="{{ route('seller.listings.index', ['status' => $value]) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition-colors
              {{ request('status', '') === $value ? 'bg-brand-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:border-brand-400' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            @if ($listings->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Listing
                                </th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Price</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status
                                </th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Views</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($listings as $listing)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            {{-- Thumbnail --}}
                                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex-shrink-0 overflow-hidden">
                                                @if ($listing->images->count())
                                                    <img src="{{ Storage::url($listing->images->first()->path) }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <div
                                                        class="w-full h-full flex items-center justify-center text-gray-300">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('listings.show', $listing->slug) }}"
                                                    class="font-semibold text-gray-900 hover:text-brand-600 line-clamp-1">
                                                    {{ $listing->title }}
                                                </a>
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    {{ $listing->created_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">
                                        ${{ number_format($listing->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-status-badge :status="$listing->status" />
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $listing->views_count }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            @if (!$listing->trashed())
                                                {{-- Edit --}}
                                                <a href="{{ route('seller.listings.edit', $listing) }}"
                                                    class="px-3 py-1.5 text-xs font-semibold text-brand-600 border border-brand-300 rounded-lg hover:bg-brand-50 transition-colors">
                                                    Edit
                                                </a>

                                                {{-- Toggle status --}}
                                                <form method="POST"
                                                    action="{{ route('seller.listings.toggle-status', $listing) }}">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                        class="px-3 py-1.5 text-xs font-semibold border rounded-lg transition-colors
                           {{ $listing->status === 'active'
                               ? 'text-amber-600 border-amber-300 hover:bg-amber-50'
                               : 'text-green-600 border-green-300 hover:bg-green-50' }}">
                                                        {{ $listing->status === 'active' ? 'Pause' : 'Activate' }}
                                                    </button>
                                                </form>

                                                {{-- Delete --}}
                                                <form method="POST"
                                                    action="{{ route('seller.listings.destroy', $listing) }}"
                                                    onsubmit="return confirm('Delete this listing?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="px-3 py-1.5 text-xs font-semibold text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                                                        Delete
                                                    </button>
                                                </form>
                                            @else
                                                {{-- Deleted listing label --}}
                                                <span
                                                    class="px-3 py-1.5 text-xs font-semibold text-gray-400 border border-gray-200 rounded-lg">
                                                    Deleted
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $listings->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M4 6h16M4 10h16M4 14h16M4 18h7" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">No listings yet</h3>
                    <p class="text-gray-500 text-sm mb-4">Create your first listing to start selling.</p>
                    <a href="{{ route('seller.listings.create') }}"
                        class="bg-brand-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
                        Create Listing
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
