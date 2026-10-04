@props(['images'])
<div class="space-y-3">
    <div class="aspect-video bg-gray-100 rounded-2xl overflow-hidden">
        @if($images->count())
            <img id="gallery-main" src="{{ Storage::url($images->first()->path) }}"
                 alt="Product image" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-300">
                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
    </div>
    @if($images->count() > 1)
    <div class="flex gap-2 overflow-x-auto pb-1">
        @foreach($images as $i => $image)
        <button onclick="switchGalleryImage('{{ Storage::url($image->path) }}', this)"
            class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden border-2 transition-colors
                   {{ $i === 0 ? 'border-brand-500' : 'border-transparent hover:border-gray-300' }}">
            <img src="{{ Storage::url($image->path) }}" loading="lazy" class="w-full h-full object-cover">
        </button>
        @endforeach
    </div>
    @endif
</div>
<script>
function switchGalleryImage(src, btn) {
    const main = document.getElementById('gallery-main');
    if (main) {
        main.style.opacity = '0';
        main.style.transition = 'opacity .15s';
        setTimeout(() => { main.src = src; main.style.opacity = '1'; }, 150);
    }
    btn.closest('div').querySelectorAll('button').forEach(b => {
        b.classList.remove('border-brand-500');
        b.classList.add('border-transparent');
    });
    btn.classList.add('border-brand-500');
    btn.classList.remove('border-transparent');
}
</script>