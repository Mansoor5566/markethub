<x-admin-layout>
<x-slot name="title">User: {{ $user->name }}</x-slot>

<div class="max-w-4xl">

    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}"
           class="text-sm text-brand-600 hover:text-brand-700 font-semibold">← Back to users</a>
    </div>

    {{-- User Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-brand-500 flex items-center justify-center text-white text-2xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                <div class="flex items-center gap-3 mt-2">
                    <span class="text-xs px-2.5 py-1 rounded-lg font-semibold
                                 {{ $user->hasRole('admin') ? 'bg-purple-100 text-purple-700' :
                                    ($user->hasRole('seller') ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                        {{ ucfirst($user->getRoleNames()->first() ?? 'user') }}
                    </span>
                    @if($user->is_active)
                        <span class="text-xs px-2.5 py-1 rounded-lg font-semibold bg-green-100 text-green-700">Active</span>
                    @else
                        <span class="text-xs px-2.5 py-1 rounded-lg font-semibold bg-red-100 text-red-700">Banned</span>
                    @endif
                    <span class="text-xs text-gray-400">Joined {{ $user->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            {{-- Ban/Unban --}}
            @if($user->is_active)
                <form method="POST" action="{{ route('admin.users.ban', $user) }}"
                      onsubmit="return confirm('Ban this user?')">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="px-4 py-2 text-sm font-semibold text-red-600 border border-red-300 rounded-xl hover:bg-red-50 transition-colors">
                        Ban User
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="px-4 py-2 text-sm font-semibold text-green-600 border border-green-300 rounded-xl hover:bg-green-50 transition-colors">
                        Unban User
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Recent Listings --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4">Recent Listings</h3>
        @forelse($listings as $listing)
        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
            <a href="{{ route('listings.show', $listing->slug) }}"
               class="text-sm font-medium text-gray-900 hover:text-brand-600">{{ $listing->title }}</a>
            <x-status-badge :status="$listing->status"/>
        </div>
        @empty
        <p class="text-sm text-gray-400">No listings.</p>
        @endforelse
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4">Recent Orders</h3>
        @forelse($orders as $order)
        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
            <span class="text-sm text-gray-900">{{ $order->listing->title }}</span>
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold">${{ number_format($order->total, 2) }}</span>
                <x-status-badge :status="$order->status"/>
            </div>
        </div>
        @empty
        <p class="text-sm text-gray-400">No orders.</p>
        @endforelse
    </div>

    {{-- Recent Reviews --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Recent Reviews</h3>
        @forelse($reviews as $review)
        <div class="py-2 border-b border-gray-100 last:border-0">
            <div class="flex items-center gap-2 mb-1">
                <x-star-rating :rating="$review->rating"/>
                <span class="text-xs text-gray-500">{{ $review->listing->title }}</span>
            </div>
            <p class="text-sm text-gray-600">{{ $review->body }}</p>
        </div>
        @empty
        <p class="text-sm text-gray-400">No reviews.</p>
        @endforelse
    </div>
</div>

</x-admin-layout>