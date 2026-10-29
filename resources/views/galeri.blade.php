<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Semua Galeri - Bee Bold Soccer School</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bbss.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Oswald:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-navy-950 font-inter text-white antialiased">

@include('partials.navbar')

@php
    $columns = [[], [], [], []];
    foreach ($galleries as $index => $photo) {
        $columns[$index % 4][] = $photo;
    }
    $ratioHeight = fn (string $ratio): string => match ($ratio) {
        'portrait' => 'h-[340px]',
        'square' => 'h-[260px]',
        default => 'h-[210px]',
    };
@endphp

<main>
    <section class="relative overflow-hidden px-6 pb-12 pt-32">
        <div class="pointer-events-none absolute -top-40 left-0 size-[500px] rounded-full bg-sky/5 blur-[64px]"></div>
        <div class="relative mx-auto max-w-[1280px]">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm text-white/40 transition hover:text-white">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 12.66 5.33 8 10 3.33" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Kembali ke Beranda
            </a>
            <p class="pt-8 text-xs font-medium uppercase tracking-[1.2px] text-gold">— Dokumentasi</p>
            <h1 class="pt-3 font-oswald text-6xl font-bold uppercase leading-none md:text-[96px]">Semua<br><span class="text-sky">Galeri</span></h1>
            <p class="max-w-lg pt-4 text-base leading-6 text-white/50">Koleksi foto terbaik dari setiap momen latihan, pertandingan, dan kegiatan Bee Bold Soccer School.</p>
        </div>
    </section>

    <section class="px-6 pb-24">
        <div class="mx-auto max-w-[1280px]">
            @if ($galleries->isNotEmpty())
                <div class="grid grid-cols-2 items-start gap-3 pt-6 lg:grid-cols-4">
                    @foreach ($columns as $column)
                        <div class="flex flex-col gap-3">
                            @foreach ($column as $photo)
                                <button type="button"
                                    data-gallery-item="{{ $photo->image_url }}"
                                    data-gallery-title="{{ $photo->title }}"
                                    data-gallery-caption="{{ $photo->caption }}"
                                    class="group relative block w-full cursor-pointer overflow-hidden rounded-xl text-left focus:outline-none">
                                    <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" loading="lazy" class="{{ $ratioHeight($photo->grid_ratio) }} w-full object-cover transition duration-500 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-t from-navy-950/90 via-navy-950/20 to-transparent opacity-0 transition duration-300 group-hover:opacity-100"></div>
                                    <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 transition duration-300 group-hover:opacity-100">
                                        <p class="font-oswald text-sm font-bold uppercase tracking-wide text-white">{{ $photo->title }}</p>
                                        @if ($photo->caption)
                                            <p class="line-clamp-2 pt-1 text-xs leading-relaxed text-white/70">{{ $photo->caption }}</p>
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                @if ($galleries->hasPages())
                    <div class="mt-10">
                        {{ $galleries->links() }}
                    </div>
                @endif
            @else
                <p class="py-20 text-center text-base text-white/50">Belum ada foto di galeri.</p>
            @endif
        </div>
    </section>
</main>

@include('partials.gallery-modal')
@include('partials.footer')

</body>
</html>
