@props(['type' => 'info', 'message'])
@php
$styles = match($type) {
    'success' => 'bg-green-50 border-green-300 text-green-800',
    'error'   => 'bg-red-50 border-red-300 text-red-800',
    'warning' => 'bg-amber-50 border-amber-300 text-amber-800',
    default   => 'bg-blue-50 border-blue-300 text-blue-800',
};
$uid = 'alert-'.uniqid();
@endphp
<div id="{{ $uid }}" class="flex items-center gap-3 px-4 py-3 rounded-xl border {{ $styles }} mb-3">
    <p class="text-sm font-medium flex-1">{{ $message }}</p>
    <button onclick="document.getElementById('{{ $uid }}').remove()" class="opacity-60 hover:opacity-100">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
<script>
setTimeout(() => {
    const el = document.getElementById('{{ $uid }}');
    if (el) { el.style.opacity='0'; el.style.transition='opacity .3s'; setTimeout(()=>el.remove(),300); }
}, 5000);
</script>