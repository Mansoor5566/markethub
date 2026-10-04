<x-app-layout>
<x-slot name="title">Notifications</x-slot>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ auth()->user()->unreadNotifications()->count() }} unread
            </p>
        </div>
        @if(auth()->user()->unreadNotifications()->count() > 0)
        <form method="POST" action="{{ route('notifications.read-all') }}">
            @csrf
            <button type="submit"
                class="text-sm text-brand-600 font-semibold border border-brand-300 px-4 py-2 rounded-xl hover:bg-brand-50 transition-colors">
                Mark all as read
            </button>
        </form>
        @endif
    </div>

    <div class="space-y-3">
        @forelse($notifications as $notification)
        <div class="bg-white rounded-2xl border p-5 transition-colors
                    {{ is_null($notification->read_at) ? 'border-brand-200 bg-brand-50' : 'border-gray-200' }}">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center
                            {{ is_null($notification->read_at) ? 'bg-brand-500' : 'bg-gray-300' }}">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900 font-medium">
                        {{ $notification->data['message'] ?? 'You have a new notification.' }}
                    </p>
                    @if(isset($notification->data['url']))
                    <a href="{{ $notification->data['url'] }}"
                       class="text-xs text-brand-600 font-semibold hover:text-brand-700 mt-1 inline-block">
                        View details →
                    </a>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $notification->created_at->diffForHumans() }}
                    </p>
                </div>
                @if(is_null($notification->read_at))
                    <span class="w-2 h-2 bg-brand-500 rounded-full flex-shrink-0 mt-1"></span>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-24 bg-white rounded-2xl border border-gray-200">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">No notifications</h3>
            <p class="text-gray-500 text-sm">You're all caught up!</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $notifications->links() }}</div>
</div>
</x-app-layout>