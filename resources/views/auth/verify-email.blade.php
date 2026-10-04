<x-app-layout>
<x-slot name="title">Verify Email</x-slot>

<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md text-center">

        <div class="w-16 h-16 bg-brand-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-2">Check your email</h1>
        <p class="text-gray-500 text-sm mb-8">
            We sent a verification link to your email address. Click the link to activate your account.
        </p>

        @if(session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <p class="text-sm text-green-700 font-medium">A new verification link has been sent.</p>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                        class="w-full bg-brand-600 text-white py-2.5 rounded-xl text-sm font-semibold
                               hover:bg-brand-700 transition-colors">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full bg-gray-100 text-gray-700 py-2.5 rounded-xl text-sm font-semibold
                               hover:bg-gray-200 transition-colors">
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</div>
</x-app-layout>