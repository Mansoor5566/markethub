<x-app-layout>
<x-slot name="title">Create Account</x-slot>

<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                <div class="w-10 h-10 bg-brand-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-gray-900">MarketHub</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-6">Create your account</h1>
            <p class="text-gray-500 text-sm mt-1">Join thousands of buyers and sellers</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Full Name --}}
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           required autofocus autocomplete="name"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none transition-all
                                  {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300 bg-gray-50 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100' }}">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           required autocomplete="email"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none transition-all
                                  {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300 bg-gray-50 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100' }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role --}}
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">I want to</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="buyer"
                                   {{ old('role', 'buyer') === 'buyer' ? 'checked' : '' }}
                                   class="peer sr-only">
                            <div class="flex flex-col items-center gap-2 p-4 border-2 rounded-xl transition-all
                                        peer-checked:border-brand-500 peer-checked:bg-brand-50 border-gray-200 hover:border-gray-300">
                                <svg class="w-6 h-6 text-gray-400 peer-checked:text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">Buy</span>
                                <span class="text-xs text-gray-400">Shop listings</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="seller"
                                   {{ old('role') === 'seller' ? 'checked' : '' }}
                                   class="peer sr-only">
                            <div class="flex flex-col items-center gap-2 p-4 border-2 rounded-xl transition-all
                                        peer-checked:border-brand-500 peer-checked:bg-brand-50 border-gray-200 hover:border-gray-300">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">Sell</span>
                                <span class="text-xs text-gray-400">List products</span>
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password"
                           required autocomplete="new-password"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none transition-all
                                  {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300 bg-gray-50 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100' }}">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                           required autocomplete="new-password"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none transition-all
                                  border-gray-300 bg-gray-50 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-brand-600 text-white py-2.5 rounded-xl text-sm font-semibold
                               hover:bg-brand-700 transition-colors">
                    Create Account
                </button>
            </form>
        </div>

        {{-- Login link --}}
        <p class="text-center text-sm text-gray-500 mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-brand-600 font-semibold hover:text-brand-700">
                Sign in
            </a>
        </p>

    </div>
</div>
</x-app-layout>