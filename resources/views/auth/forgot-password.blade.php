<x-app-layout>
<x-slot name="title">Forgot Password</x-slot>

<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">

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
            <h1 class="text-2xl font-bold text-gray-900 mt-6">Reset your password</h1>
            <p class="text-gray-500 text-sm mt-1">
                Enter your email and we'll send you a reset link
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">

            @if(session('status'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <p class="text-sm text-green-700 font-medium">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           required autofocus
                           class="w-full px-4 py-2.5 text-sm border rounded-xl outline-none transition-all
                                  {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300 bg-gray-50 focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100' }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-brand-600 text-white py-2.5 rounded-xl text-sm font-semibold
                               hover:bg-brand-700 transition-colors">
                    Send Reset Link
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            Remember your password?
            <a href="{{ route('login') }}" class="text-brand-600 font-semibold hover:text-brand-700">
                Sign in
            </a>
        </p>
    </div>
</div>
</x-app-layout>