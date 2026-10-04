<x-app-layout>
<x-slot name="title">Messages</x-slot>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Messages</h1>
    </div>

    @if($conversations->count())
    <div class="space-y-3">
        @foreach($conversations as $conversation)
        @php
            $otherUser = $conversation->sender_id === auth()->id()
                ? $conversation->receiver
                : $conversation->sender;
            $isUnread = $conversation->receiver_id === auth()->id() && !$conversation->is_read;
        @endphp
        <a href="{{ route('messages.show', [$otherUser, $conversation->listing]) }}"
           class="flex items-center gap-4 bg-white rounded-2xl border p-4 hover:border-brand-300 transition-colors
                  {{ $isUnread ? 'border-brand-200' : 'border-gray-200' }}">

            {{-- Avatar --}}
            <div class="w-12 h-12 rounded-full bg-brand-500 flex-shrink-0 flex items-center justify-center text-white font-bold text-lg">
                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-gray-900 {{ $isUnread ? 'text-brand-700' : '' }}">
                        {{ $otherUser->name }}
                    </span>
                    <span class="text-xs text-gray-400">
                        {{ $conversation->created_at->diffForHumans() }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5 truncate">
                    Re: {{ $conversation->listing->title }}
                </p>
                <p class="text-sm text-gray-600 truncate mt-0.5 {{ $isUnread ? 'font-medium text-gray-900' : '' }}">
                    {{ $conversation->body }}
                </p>
            </div>

            {{-- Unread dot --}}
            @if($isUnread)
            <div class="w-2.5 h-2.5 bg-brand-500 rounded-full flex-shrink-0"></div>
            @endif
        </a>
        @endforeach
    </div>
    @else
    <div class="text-center py-24 bg-white rounded-2xl border border-gray-200">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                  d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
        <h3 class="text-lg font-semibold text-gray-900 mb-1">No messages yet</h3>
        <p class="text-gray-500 text-sm">Start a conversation from any listing page.</p>
    </div>
    @endif
</div>
</x-app-layout>