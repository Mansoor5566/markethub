<x-app-layout>
<x-slot name="title">Payment Cancelled</x-slot>

<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md text-center">

        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment Cancelled</h1>
        <p class="text-gray-500 text-sm mb-8">
            Your payment was cancelled. No charges were made.
        </p>

        <div class="space-y-3">
            @if(isset($listing) && $listing)
            <a href="{{ route('listings.show', $listing->slug) }}"
               class="block w-full bg-brand-600 text-white py-3 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
                Return to Listing
            </a>
            @endif
            <a href="{{ route('listings.index') }}"
               class="block w-full bg-gray-100 text-gray-700 py-3 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
                Browse Listings
            </a>
        </div>
    </div>
</div>
</x-app-layout>