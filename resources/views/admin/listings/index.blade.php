<x-admin-layout>
<x-slot name="title">Listings</x-slot>

{{-- Filters --}}
<div class="bg-white rounded-2xl border border-gray-200 p-4 mb-6">
    <form method="GET" action="{{ route('admin.listings.index') }}" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search listings..."
               class="flex-1 min-w-[200px] px-4 py-2 text-sm border border-gray-300 rounded-xl outline-none focus:border-brand-500">
        <select name="status"
            class="px-4 py-2 text-sm border border-gray-300 rounded-xl outline-none focus:border-brand-500">
            <option value="">All Status</option>
            @foreach(['active','draft','paused','sold','deleted'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit"
            class="bg-brand-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
            Filter
        </button>
        <a href="{{ route('admin.listings.index') }}"
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
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Listing</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Seller</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Price</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($listings as $listing)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900 truncate max-w-[200px]">{{ $listing->title }}</p>
                        <p class="text-xs text-gray-400">{{ $listing->created_at->format('M d, Y') }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-700 text-sm">{{ $listing->seller->name }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-900">${{ number_format($listing->price, 2) }}</td>
                    <td class="px-6 py-4">
                        <x-status-badge :status="$listing->trashed() ? 'cancelled' : $listing->status"/>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            @if(!$listing->trashed())
                                <a href="{{ route('listings.show', $listing->slug) }}" target="_blank"
                                   class="px-3 py-1.5 text-xs font-semibold text-brand-600 border border-brand-300 rounded-lg hover:bg-brand-50 transition-colors">
                                    View
                                </a>
                                @if($listing->status === 'draft')
                                <form method="POST" action="{{ route('admin.listings.approve', $listing) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-xs font-semibold text-green-600 border border-green-300 rounded-lg hover:bg-green-50 transition-colors">
                                        Approve
                                    </button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('admin.listings.force-delete', $listing) }}"
                                      onsubmit="return confirm('Permanently delete this listing?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-xs font-semibold text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No listings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $listings->links() }}
    </div>
</div>

</x-admin-layout>