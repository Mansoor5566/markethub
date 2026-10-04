@props(['status'])
@php
$styles = match($status) {
    'active','completed','paid' => 'bg-green-100 text-green-700',
    'pending','draft'           => 'bg-gray-100 text-gray-600',
    'shipped','paused'          => 'bg-amber-100 text-amber-700',
    'cancelled'                 => 'bg-red-100 text-red-700',
    'sold'                      => 'bg-purple-100 text-purple-700',
    default                     => 'bg-gray-100 text-gray-600',
};
@endphp
<span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $styles }}">
    {{ ucfirst($status) }}
</span>