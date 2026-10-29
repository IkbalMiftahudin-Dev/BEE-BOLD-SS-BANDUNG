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
    $base = 'https://api.builder.io/api/v1/image/assets/TEMP/';
    $columns = [
        [['ca988373b55ac23f44db243d9d2d2ebb111eb5c0', 'h-[210px]'], ['fc5cf8df347f8e58775363a38e8aaf6e3cb0fcac', 'h-[340px]']],
        [['4d0d9174101d8929152612eeb9a76a6f27df19c8', 'h-[210px]'], ['03b68dcbe8d9b2f4d3b8c779c8cfc2dc28cdd188', 'h-[340px]'], ['59bac4d554a087fbc8409be6440def659cf73e8b', 'h-[210px]']],
        [['b6ba53fbf5c21068cb8db1a906f99bbe1c27106d', 'h-[210px]'], ['f7c3b6ccc825fde8dec5887c0925968df2e62ea1', 'h-[340px]'], ['9e2ce44b10fc712c4d89673aac0f79aab40fd692', 'h-[210px]']],
        [['c69cb6556e940d7b3657d9eed2ccfce9273f3487', 'h-[210px]'], ['1248967f4012969993b859a3f21069f2c6d7f820', 'h-[210px]'], ['0cb0f9c1cb7ec62c8c21e7241da988337af9cef9', 'h-[210px]'], ['2c1eff11995749faac53a0b3f81321224abd1dbd', 'h-[340px]']],
    ];
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
        <div class="mx-auto grid max-w-[1280px] grid-cols-2 items-start gap-3 pt-6 lg:grid-cols-4">
            @foreach ($columns as $column)
                <div class="flex flex-col gap-3">
                    @foreach ($column as [$id, $height])
                        <button type="button" data-gallery-item="{{ $base.$id }}?width=1600" class="group block w-full cursor-pointer overflow-hidden rounded-xl focus:outline-none">
                            <img src="{{ $base.$id }}?width=622" alt="Dokumentasi Bee Bold Soccer School" loading="lazy" class="{{ $height }} w-full object-cover transition duration-500 group-hover:scale-105">
                        </button>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>
</main>

<div id="gallery-modal" data-gallery-modal aria-hidden="true" role="dialog" aria-modal="true" aria-label="Pratinjau Foto Galeri" class="fixed inset-0 z-[60] hidden items-center justify-center bg-navy-950/80 p-4 backdrop-blur-md">
    <button type="button" data-gallery-close aria-label="Tutup foto" class="fixed right-5 top-5 z-10 flex size-11 cursor-pointer items-center justify-center rounded-full border border-white/20 bg-navy-900/90 text-white transition hover:border-gold hover:text-gold">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18M6 6l12 12"/>
        </svg>
    </button>
    <img data-gallery-img src="" alt="Dokumentasi Bee Bold Soccer School" class="max-h-[85vh] w-auto max-w-full rounded-2xl object-contain shadow-2xl ring-1 ring-white/15">
</div>

@include('partials.footer')

</body>
</html>
