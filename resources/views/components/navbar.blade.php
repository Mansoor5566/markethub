<nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center h-16 gap-4">

    {{-- Logo --}}
    <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0">
        <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
        </div>
        <span class="font-bold text-gray-900 text-lg hidden sm:inline">MarketHub</span>
    </a>

    {{-- Search --}}
    <form action="{{ route('search') }}" method="GET" class="flex-1 max-w-xl hidden md:flex">
        <div class="relative w-full">
            <input type="search" name="q" value="{{ request('q') }}"
                placeholder="Search listings..."
                class="w-full pl-4 pr-10 py-2 text-sm border border-gray-300 rounded-xl bg-gray-50
                       focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-brand-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
            </button>
        </div>
    </form>

    {{-- Right side --}}
    <div class="flex items-center gap-2 ml-auto">
        <a href="{{ route('listings.index') }}" class="hidden lg:flex items-center gap-1 text-sm font-medium text-gray-600 hover:text-brand-600 px-3 py-2 rounded-lg hover:bg-gray-50">
            Browse
        </a>

        @auth
        {{-- Notifications --}}
        <a href="{{ route('notifications.index') }}" class="relative p-2 text-gray-500 hover:text-brand-600 rounded-lg hover:bg-gray-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            @php $unreadNotifs = auth()->user()->unreadNotifications()->count(); @endphp
            @if($unreadNotifs > 0)
            <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">
                {{ $unreadNotifs > 9 ? '9+' : $unreadNotifs }}
            </span>
            @endif
        </a>

        {{-- Messages --}}
        <a href="{{ route('messages.index') }}" class="relative p-2 text-gray-500 hover:text-brand-600 rounded-lg hover:bg-gray-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
            @php $unreadMsgs = auth()->user()->receivedMessages()->where('is_read', false)->count(); @endphp
            @if($unreadMsgs > 0)
            <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-brand-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">
                {{ $unreadMsgs > 9 ? '9+' : $unreadMsgs }}
            </span>
            @endif
        </a>

        {{-- User dropdown --}}
        <div class="relative" id="user-dropdown-wrap">
            <button onclick="document.getElementById('user-menu').classList.toggle('hidden')"
                class="flex items-center gap-2 pl-1 pr-3 py-1.5 rounded-xl hover:bg-gray-100 text-sm font-medium text-gray-700">
                @if(auth()->user()->avatar)
                    <img src="{{ Storage::url(auth()->user()->avatar) }}" class="w-7 h-7 rounded-full object-cover">
                @else
                    <div class="w-7 h-7 rounded-full bg-brand-500 flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <span class="hidden sm:inline max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div id="user-menu" class="hidden absolute right-0 mt-1 w-52 bg-white border border-gray-200 rounded-xl shadow-lg py-1 z-50">
                <div class="px-4 py-2.5 border-b border-gray-100">
                    <p class="text-xs text-gray-500">Signed in as</p>
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->email }}</p>
                </div>

                @if(auth()->user()->hasRole('seller'))
                <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Seller Dashboard</a>
                <a href="{{ route('seller.listings.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">My Listings</a>
                @endif

                @if(auth()->user()->hasRole('buyer'))
                <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">My Orders</a>
                <a href="{{ route('favorites.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Favorites</a>
                @endif

                @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-purple-700 font-medium hover:bg-purple-50">Admin Panel</a>
                @endif

                <div class="border-t border-gray-100 mt-1">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Edit Profile</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Sign Out</button>
                    </form>
                </div>
            </div>
        </div>

        @else
        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-brand-600 px-3 py-2">Log in</a>
        <a href="{{ route('register') }}" class="text-sm font-medium bg-brand-600 text-white px-4 py-2 rounded-xl hover:bg-brand-700">Sign up</a>
        @endauth

        {{-- Mobile menu button --}}
        <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
            class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
</div>

{{-- Mobile menu --}}
<div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 py-3 space-y-1">
    <form action="{{ route('search') }}" method="GET" class="px-2 mb-3">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search listings..."
            class="w-full px-4 py-2 text-sm border border-gray-300 rounded-xl bg-gray-50 outline-none">
    </form>
    <a href="{{ route('listings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">Browse Listings</a>
    @auth
    <a href="{{ route('messages.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">Messages</a>
    <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg">Notifications</a>
    @endauth
</div>

</div>
</nav>

<script>
// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('user-dropdown-wrap');
    const menu = document.getElementById('user-menu');
    if (wrap && menu && !wrap.contains(e.target)) {
        menu.classList.add('hidden');
    }
});
</script>