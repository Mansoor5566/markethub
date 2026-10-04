<x-admin-layout>
<x-slot name="title">Orders</x-slot>

{{-- Revenue summary --}}
<div class="bg-white rounded-2xl border border-gray-200 p-5 mb-6">
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
        Total Revenue (filtered)
    </p>
    <p class="text-2xl font-bold text-gray-900">${{ number_format($totalRevenue, 2) }}</p>
</div>

{{-- Status tabs --}}
<div class="flex gap-2 mb-6 overflow-x-auto pb-1">
    @foreach(['' => 'All', 'pending' => 'Pending', 'paid' => 'Paid', 'shipped' => 'Shipped', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $value => $label)
    <a href="{{ route('admin.orders.index', ['status' => $value]) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition-colors
              {{ request('status', '') === $value ? 'bg-brand-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:border-brand-400' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Order</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Buyer</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Amount</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Date</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900 truncate max-w-[180px]">
                            {{ $order->listing->title }}
                        </p>
                        <p class="text-xs text-gray-400">#{{ $order->id }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-700">{{ $order->buyer->name }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-900">${{ number_format($order->total, 2) }}</td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4"><x-status-badge :status="$order->status"/></td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $orders->links() }}
    </div>
</div>

</x-admin-layout>