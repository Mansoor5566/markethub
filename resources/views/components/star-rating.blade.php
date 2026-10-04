@props(['rating' => 0, 'max' => 5])
@php $rating = (float) $rating; @endphp
<div class="flex items-center gap-0.5">
    @for($i = 1; $i <= $max; $i++)
        @php $diff = $rating - ($i - 1); @endphp
        <svg class="w-4 h-4" viewBox="0 0 24 24">
            <path fill="{{ $diff >= 1 ? '#F59E0B' : ($diff > 0 ? '#FCD34D' : '#D1D5DB') }}"
                  d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
    @endfor
</div>