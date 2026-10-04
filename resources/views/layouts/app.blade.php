<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MarketHub') }} — {{ $title ?? 'Marketplace' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: {
                            50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',
                            300:'#a5b4fc',400:'#818cf8',500:'#6366f1',
                            600:'#4f46e5',700:'#4338ca',800:'#3730a3',900:'#312e81',
                        },
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .line-clamp-2 { display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; }
        .line-clamp-3 { display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

{{-- Navbar --}}
@include('components.navbar')

{{-- Flash Messages --}}
@if(session()->hasAny(['success','error','info','warning']))
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
    @foreach(['success','error','info','warning'] as $type)
        @if(session($type))
            <x-alert :type="$type" :message="session($type)"/>
        @endif
    @endforeach
</div>
@endif

{{-- Main Content --}}
<main class="min-h-screen">
    {{ $slot }}
</main>

{{-- Footer --}}
@include('components.footer')

<script>
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]').content;
}
function toggleFavorite(listingId) {
    fetch('/favorites/' + listingId + '/toggle', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': getCsrfToken(), 'Accept': 'application/json' },
    })
    .then(r => r.json())
    .then(data => {
        const icon = document.getElementById('fav-icon-' + listingId);
        if (!icon) return;
        if (data.favorited) {
            icon.classList.add('text-red-500');
            icon.classList.remove('text-gray-300');
        } else {
            icon.classList.remove('text-red-500');
            icon.classList.add('text-gray-300');
        }
    });
}
function previewAvatar(input, previewId) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const el = document.getElementById(previewId);
        if (el) el.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
}
function previewImages(input, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    container.innerHTML = '';
    Array.from(input.files).forEach((file, i) => {
        if (!file.type.startsWith('image/')) return;
        const reader = new FileReader();
        reader.onload = e => {
            const wrap = document.createElement('div');
            wrap.className = 'relative';
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-20 h-20 object-cover rounded-lg border border-gray-200';
            if (i === 0) {
                const badge = document.createElement('span');
                badge.className = 'absolute -top-1 -right-1 bg-brand-600 text-white text-[9px] px-1 rounded';
                badge.textContent = 'Main';
                wrap.appendChild(badge);
            }
            wrap.appendChild(img);
            container.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@stack('scripts')
</body>
</html>