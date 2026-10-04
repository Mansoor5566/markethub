<x-admin-layout>
<x-slot name="title">Dashboard</x-slot>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-gray-200 p-5">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Users</p>
        <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
        <p class="text-xs text-gray-400 mt-1">
            {{ $totalBuyers }} buyers · {{ $totalSellers }} sellers · {{ $totalAdmins }} admins
        </p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-5">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Listings</p>
        <p class="text-2xl font-bold text-gray-900">{{ $totalListings }}</p>
        <p class="text-xs text-gray-400 mt-1">
            {{ $activeListings }} active · {{ $draftListings }} draft
        </p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-5">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Orders</p>
        <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
        <p class="text-xs text-gray-400 mt-1">
            {{ $pendingOrders }} pending · {{ $completedOrders }} completed
        </p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-5">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Revenue</p>
        <p class="text-2xl font-bold text-gray-900">${{ number_format($totalRevenue, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">From completed orders</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Recent Orders --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">Recent Orders</h2>
            <a href="{{ route('admin.orders.index') }}"
               class="text-sm text-brand-600 font-semibold hover:text-brand-700">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Order</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Buyer</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Amount</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">
                            <p class="font-medium text-gray-900 truncate max-w-[150px]">{{ $order->listing->title }}</p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->format('M d, Y') }}</p>
                        </td>
                        <td class="px-6 py-3 text-gray-700">{{ $order->buyer->name }}</td>
                        <td class="px-6 py-3 font-semibold">${{ number_format($order->total, 2) }}</td>
                        <td class="px-6 py-3"><x-status-badge :status="$order->status"/></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Revenue Chart --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="font-bold text-gray-900 mb-6">Revenue (Last 6 Months)</h2>
        @php $maxRevenue = max(array_column($monthlyRevenue, 'revenue')) ?: 1; @endphp
        <div class="flex items-end gap-2 h-40">
            @foreach($monthlyRevenue as $month)
            @php $height = max(4, round(($month['revenue'] / $maxRevenue) * 100)); @endphp
            <div class="flex-1 flex flex-col items-center gap-1">
                <span class="text-[10px] text-gray-500">${{ number_format($month['revenue']/1000, 1) }}k</span>
                <div class="w-full bg-brand-500 rounded-t-lg hover:bg-brand-600 transition-colors"
                     style="height: {{ $height }}%"></div>
                <span class="text-[10px] text-gray-500">{{ $month['month'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

</x-admin-layout>