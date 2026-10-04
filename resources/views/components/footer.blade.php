<footer class="bg-white border-t border-gray-200 mt-16">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="col-span-2 md:col-span-1">
            <a href="{{ route('home') }}" class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <span class="font-bold text-gray-900">MarketHub</span>
            </a>
            <p class="text-sm text-gray-500">Buy and sell amazing products from sellers near you.</p>
        </div>
        <div>
            <h4 class="text-sm font-semibold text-gray-900 mb-3">Explore</h4>
            <ul class="space-y-2">
                <li><a href="{{ route('listings.index') }}" class="text-sm text-gray-500 hover:text-brand-600">Browse Listings</a></li>
                <li><a href="{{ route('search') }}" class="text-sm text-gray-500 hover:text-brand-600">Search</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-sm font-semibold text-gray-900 mb-3">Account</h4>
            <ul class="space-y-2">
                @auth
                <li><a href="{{ route('profile.edit') }}" class="text-sm text-gray-500 hover:text-brand-600">Profile</a></li>
                <li><a href="{{ route('orders.index') }}" class="text-sm text-gray-500 hover:text-brand-600">Orders</a></li>
                @else
                <li><a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-brand-600">Log in</a></li>
                <li><a href="{{ route('register') }}" class="text-sm text-gray-500 hover:text-brand-600">Sign up</a></li>
                @endauth
            </ul>
        </div>
        <div>
            <h4 class="text-sm font-semibold text-gray-900 mb-3">Selling</h4>
            <ul class="space-y-2">
                @auth
                @if(auth()->user()->hasRole('seller'))
                <li><a href="{{ route('seller.dashboard') }}" class="text-sm text-gray-500 hover:text-brand-600">Dashboard</a></li>
                <li><a href="{{ route('seller.listings.create') }}" class="text-sm text-gray-500 hover:text-brand-600">Create Listing</a></li>
                @endif
                @else
                <li><a href="{{ route('register') }}" class="text-sm text-gray-500 hover:text-brand-600">Start Selling</a></li>
                @endauth
            </ul>
        </div>
    </div>
    <div class="mt-8 pt-6 border-t border-gray-100 text-center">
        <p class="text-xs text-gray-400">&copy; {{ date('Y') }} MarketHub. Laravel 12 Training Project.</p>
    </div>
</div>
</footer>