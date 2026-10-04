<x-app-layout>
<x-slot name="title">Order Confirmed</x-slot>

<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md text-center">

        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
        <p class="text-gray-500 text-sm mb-8">
            Thank you for your purchase. Your order has been placed successfully.
        </p>

        @if($order)
        <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6 text-left">
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Order ID</span>
                    <span class="font-semibold text-gray-900">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Item</span>
                    <span class="font-semibold text-gray-900 text-right max-w-[200px] truncate">
                        {{ $order->listing->title }}
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Amount Paid</span>
                    <span class="font-bold text-green-600">${{ number_format($order->total, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Status</span>
                    <x-status-badge :status="$order->status"/>
                </div>
            </div>
        </div>
        @endif

        <div class="space-y-3">
            <a href="{{ route('orders.index') }}"
               class="block w-full bg-brand-600 text-white py-3 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
                View My Orders
            </a>
            <a href="{{ route('listings.index') }}"
               class="block w-full bg-gray-100 text-gray-700 py-3 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
</x-app-layout>