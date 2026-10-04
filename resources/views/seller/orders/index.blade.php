<x-app-layout>
<x-slot name="title">Manage Orders</x-slot>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manage Orders</h1>
        <p class="text-sm text-gray-500 mt-1">Orders for your listings</p>
    </div>

    {{-- Status tabs --}}
    <div class="flex gap-2 mb-6 overflow-x-auto pb-1">
        @foreach(['' => 'All', 'paid' => 'Paid', 'shipped' => 'Shipped', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $value => $label)
        <a href="{{ route('seller.orders.index', ['status' => $value]) }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition-colors
                  {{ request('status', '') === $value ? 'bg-brand-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:border-brand-400' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        @if($orders->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Listing</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Buyer</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Amount</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Date</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900 truncate max-w-[180px]">
                                {{ $order->listing->title }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $order->buyer->name }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-900">
                            ${{ number_format($order->total, 2) }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <x-status-badge :status="$order->status"/>
                        </td>
                        <td class="px-6 py-4">
                            @if($order->status === 'paid')
                                <form method="POST" action="{{ route('seller.orders.ship', $order) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-xs font-semibold bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                                        Mark Shipped
                                    </button>
                                </form>
                            @elseif($order->status === 'shipped')
                                <form method="POST" action="{{ route('seller.orders.complete', $order) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-xs font-semibold bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                        Mark Completed
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
        @else
        <div class="text-center py-16 text-gray-500 text-sm">No orders found.</div>
        @endif
    </div>
</div>
</x-app-layout>