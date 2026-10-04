<x-app-layout>
<x-slot name="title">Chat with {{ $otherUser->name }}</x-slot>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('messages.index') }}"
           class="text-sm text-brand-600 hover:text-brand-700 font-semibold">← Back</a>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-brand-500 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
            </div>
            <div>
                <p class="font-semibold text-gray-900">{{ $otherUser->name }}</p>
                <a href="{{ route('listings.show', $listing->slug) }}"
                   class="text-xs text-brand-600 hover:text-brand-700">
                    Re: {{ $listing->title }}
                </a>
            </div>
        </div>
    </div>

    {{-- Messages --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-4 space-y-4 min-h-[300px]">
        @forelse($messages as $message)
        @php $isOwn = $message->sender_id === auth()->id(); @endphp
        <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-[75%]">
                <div class="px-4 py-3 rounded-2xl text-sm
                            {{ $isOwn
                               ? 'bg-brand-600 text-white rounded-br-sm'
                               : 'bg-gray-100 text-gray-900 rounded-bl-sm' }}">
                    {{ $message->body }}
                </div>
                <p class="text-xs text-gray-400 mt-1 {{ $isOwn ? 'text-right' : 'text-left' }}">
                    {{ $message->created_at->format('M d, h:i A') }}
                </p>
            </div>
        </div>
        @empty
        <div class="text-center text-gray-400 text-sm py-8">
            No messages yet. Start the conversation!
        </div>
        @endforelse
    </div>

    {{-- Send Message --}}
    <form method="POST" action="{{ route('messages.send', [$otherUser, $listing]) }}">
        @csrf
        <div class="flex gap-3">
            <textarea name="body" rows="2"
                placeholder="Type your message... (max 5000 characters)"
                class="flex-1 px-4 py-3 text-sm border border-gray-300 rounded-2xl outline-none
                       focus:border-brand-500 focus:ring-2 focus:ring-brand-100 resize-none">{{ old('body') }}</textarea>
            <button type="submit"
                class="flex-shrink-0 bg-brand-600 text-white px-6 py-3 rounded-2xl text-sm font-semibold
                       hover:bg-brand-700 transition-colors self-end">
                Send
            </button>
        </div>
        @error('body')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </form>
</div>
</x-app-layout>