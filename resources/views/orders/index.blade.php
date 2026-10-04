<x-app-layout>
<x-slot name="title">My Orders</x-slot>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $orders->total() }} total orders</p>
    </div>

    {{-- Status tabs --}}
    <div class="flex gap-2 mb-6 overflow-x-auto pb-1">
        @foreach(['' => 'All', 'pending' => 'Pending', 'paid' => 'Paid', 'shipped' => 'Shipped', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $value => $label)
        <a href="{{ route('orders.index', ['status' => $value]) }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition-colors
                  {{ request('status', '') === $value ? 'bg-brand-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:border-brand-400' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Orders list --}}
    @if($orders->count())
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-white rounded-2xl border border-gray-200 p-5 hover:border-brand-300 transition-colors">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">

                {{-- Listing image --}}
                <div class="w-16 h-16 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden">
                    @if($order->listing->images->count())
                        <img src="{{ Storage::url($order->listing->images->first()->path) }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <a href="{{ route('orders.show', $order) }}"
                       class="font-semibold text-gray-900 hover:text-brand-600 transition-colors line-clamp-1">
                        {{ $order->listing->title }}
                    </a>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Seller: {{ $order->listing->seller->name }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $order->created_at->format('M d, Y') }}
                    </p>
                </div>

                {{-- Amount + Status --}}
                <div class="flex sm:flex-col items-center sm:items-end gap-3 sm:gap-1">
                    <span class="text-lg font-bold text-gray-900">
                        ${{ number_format($order->total, 2) }}
                    </span>
                    <x-status-badge :status="$order->status"/>
                </div>

                {{-- View button --}}
                <a href="{{ route('orders.show', $order) }}"
                   class="flex-shrink-0 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold
                          rounded-xl hover:bg-gray-50 transition-colors">
                    View
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>

    @else
    <div class="text-center py-24 bg-white rounded-2xl border border-gray-200">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <h3 class="text-lg font-semibold text-gray-900 mb-1">No orders yet</h3>
        <p class="text-gray-500 text-sm mb-4">Start shopping to see your orders here.</p>
        <a href="{{ route('listings.index') }}"
           class="bg-brand-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
            Browse Listings
        </a>
    </div>
    @endif
</div>
</x-app-layout>