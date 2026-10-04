<x-app-layout>
<x-slot name="title">Server Error</x-slot>
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-red-200">500</h1>
        <h2 class="text-2xl font-bold text-gray-900 mt-4">Server Error</h2>
        <p class="text-gray-500 mt-2 mb-8">Something went wrong on our end. Please try again later.</p>
        <a href="{{ route('home') }}"
           class="bg-brand-600 text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-brand-700 transition-colors">
            Go Home
        </a>
    </div>
</div>
</x-app-layout>