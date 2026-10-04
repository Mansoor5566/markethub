<x-app-layout>
<x-slot name="title">Order #{{ $order->id }}</x-slot>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <a href="{{ route('orders.index') }}"
           class="text-sm text-brand-600 hover:text-brand-700 font-semibold">← Back to orders</a>
        <div class="flex items-center justify-between mt-2">
            <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
            <x-status-badge :status="$order->status"/>
        </div>
        <p class="text-sm text-gray-500 mt-1">Placed on {{ $order->created_at->format('F d, Y') }}</p>
    </div>

    {{-- Status Timeline --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <h2 class="font-bold text-gray-900 mb-6">Order Status</h2>
        @php
            $steps = ['pending' => 'Pending', 'paid' => 'Paid', 'shipped' => 'Shipped', 'completed' => 'Completed'];
            $stepKeys = array_keys($steps);
            $currentIndex = array_search($order->status, $stepKeys) ?? 0;
            if($order->status === 'cancelled') $currentIndex = -1;
        @endphp

        @if($order->status === 'cancelled')
            <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <p class="text-sm text-red-700 font-medium">This order was cancelled.</p>
            </div>
        @else
        <div class="flex items-center">
            @foreach($steps as $key => $label)
            @php $index = array_search($key, $stepKeys); @endphp
            <div class="flex-1 flex flex-col items-center relative">
                {{-- Connector line --}}
                @if(!$loop->last)
                <div class="absolute top-4 left-1/2 w-full h-0.5 {{ $index < $currentIndex ? 'bg-brand-500' : 'bg-gray-200' }}"></div>
                @endif

                {{-- Dot --}}
                <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center relative z-10
                            {{ $index < $currentIndex ? 'bg-brand-500 border-brand-500' :
                               ($index === $currentIndex ? 'bg-white border-brand-500' : 'bg-white border-gray-300') }}">
                    @if($index < $currentIndex)
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    @elseif($index === $currentIndex)
                        <div class="w-3 h-3 rounded-full bg-brand-500"></div>
                    @endif
                </div>
                <span class="text-xs mt-2 font-medium text-center
                             {{ $index <= $currentIndex ? 'text-brand-600' : 'text-gray-400' }}">
                    {{ $label }}
                </span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Order Items --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <h2 class="font-bold text-gray-900 mb-4">Order Items</h2>
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden">
                @if($order->listing->images->count())
                    <img src="{{ Storage::url($order->listing->images->first()->path) }}"
                         class="w-full h-full object-cover">
                @endif
            </div>
            <div class="flex-1">
                <a href="{{ route('listings.show', $order->listing->slug) }}"
                   class="font-semibold text-gray-900 hover:text-brand-600">
                    {{ $order->listing->title }}
                </a>
                <p class="text-sm text-gray-500 mt-0.5">
                    Qty: {{ $order->quantity }} × ${{ number_format($order->unit_price, 2) }}
                </p>
            </div>
            <span class="font-bold text-gray-900 text-lg">
                ${{ number_format($order->total, 2) }}
            </span>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex justify-between text-sm text-gray-600 mb-1">
                <span>Subtotal</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>
            <div class="flex justify-between font-bold text-gray-900">
                <span>Total</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Shipping Address --}}
    @if($order->shipping_address)
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <h2 class="font-bold text-gray-900 mb-2">Shipping Address</h2>
        <p class="text-sm text-gray-600">{{ $order->shipping_address }}</p>
    </div>
    @endif

    {{-- Seller Info --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
        <h2 class="font-bold text-gray-900 mb-4">Seller</h2>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-brand-500 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr($order->listing->seller->name, 0, 1)) }}
            </div>
            <div>
                <a href="{{ route('sellers.show', $order->listing->seller) }}"
                   class="font-semibold text-gray-900 hover:text-brand-600">
                    {{ $order->listing->seller->name }}
                </a>
            </div>
            <a href="{{ route('messages.show', [$order->listing->seller, $order->listing]) }}"
               class="ml-auto text-sm text-brand-600 font-semibold border border-brand-300 px-4 py-2 rounded-xl hover:bg-brand-50 transition-colors">
                Message Seller
            </a>
        </div>
    </div>

    {{-- Write Review --}}
    @if($order->canBeReviewed())
    <div class="bg-brand-50 border border-brand-200 rounded-2xl p-6">
        <h2 class="font-bold text-gray-900 mb-1">Leave a Review</h2>
        <p class="text-sm text-gray-600 mb-4">Share your experience with this purchase.</p>

        <form method="POST" action="{{ route('reviews.store', $order) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Rating</label>
                <div class="flex items-center gap-2">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" data-star="{{ $i }}"
                        onclick="setRating({{ $i }})"
                        class="text-3xl text-gray-300 hover:text-amber-400 transition-colors star-btn">
                        ★
                    </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-input">
                @error('rating')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Title <span class="text-gray-400 font-normal">(optional)</span>
                </label>
                <input type="text" name="title" maxlength="100"
                       placeholder="Summarize your experience"
                       class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl outline-none focus:border-brand-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Review</label>
                <textarea name="body" rows="3"
                    placeholder="Tell others about your experience..."
                    class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-xl outline-none focus:border-brand-500 resize-none"></textarea>
                @error('body')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                class="bg-brand-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
                Submit Review
            </button>
        </form>
    </div>
    @elseif($order->review)
    <div class="bg-green-50 border border-green-200 rounded-2xl p-6">
        <h2 class="font-bold text-gray-900 mb-2">Your Review</h2>
        <div class="flex items-center gap-2 mb-2">
            <x-star-rating :rating="$order->review->rating"/>
            <span class="text-sm font-semibold text-gray-700">{{ $order->review->rating }}/5</span>
        </div>
        @if($order->review->title)
            <p class="text-sm font-semibold text-gray-800 mb-1">{{ $order->review->title }}</p>
        @endif
        <p class="text-sm text-gray-600">{{ $order->review->body }}</p>
    </div>
    @endif
</div>

@push('scripts')
<script>
function setRating(val) {
    document.getElementById('rating-input').value = val;
    document.querySelectorAll('.star-btn').forEach((btn, i) => {
        btn.classList.toggle('text-amber-400', i < val);
        btn.classList.toggle('text-gray-300', i >= val);
    });
}
</script>
@endpush
</x-app-layout>