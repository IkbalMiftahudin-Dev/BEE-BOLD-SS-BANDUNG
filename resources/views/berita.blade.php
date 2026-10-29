<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Semua Berita - Bee Bold Soccer School</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bbss.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Oswald:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-navy-950 font-inter text-white antialiased">

@include('partials.navbar')

<main>
    <section class="relative overflow-hidden bg-gradient-to-b from-brand/40 to-transparent px-6 pb-12 pt-32">
        <div class="pointer-events-none absolute -top-40 left-[58%] size-[600px] rounded-full bg-brand/20 blur-[64px]"></div>
        <div class="relative mx-auto max-w-[1280px]">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm text-white/40 transition hover:text-white">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 12.67 5.33 8 10 3.33" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Kembali ke Beranda
            </a>
            <p class="pt-8 text-xs font-medium uppercase tracking-[1.2px] text-gold">— Berita &amp; Update</p>
            <h1 class="pt-3 font-oswald text-6xl font-bold uppercase leading-none md:text-[96px]">Semua<br><span class="text-sky">Berita</span></h1>
            <p class="max-w-lg pt-4 text-base leading-6 text-white/50">Ikuti terus perkembangan terbaru dari Bee Bold Soccer School — prestasi, program, dan kegiatan kami.</p>
        </div>
    </section>

    <section class="px-6 pb-8">
        <div class="mx-auto flex max-w-[1280px] flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap gap-2" data-news-categories>
                <a href="{{ route('berita.index', array_filter(['q' => $search])) }}"
                   data-news-cat="Semua"
                   class="inline-flex min-h-9 items-center rounded-full px-4 py-2 font-oswald text-xs font-semibold tracking-[0.3px] transition {{ $activeCategory === null ? 'bg-gold text-brand' : 'border border-white/10 bg-white/5 text-white/60 hover:text-white' }}">Semua</a>
                @foreach ($categories as $cat)
                    <a href="{{ route('berita.index', array_filter(['kategori' => $cat->slug, 'q' => $search])) }}"
                       data-news-cat="{{ $cat->name }}"
                       class="inline-flex min-h-9 items-center rounded-full px-4 py-2 font-oswald text-xs font-semibold tracking-[0.3px] transition {{ $activeCategory === $cat->slug ? 'bg-gold text-brand' : 'border border-white/10 bg-white/5 text-white/60 hover:text-white' }}">{{ $cat->name }}</a>
                @endforeach
            </div>
            <form method="GET" action="{{ route('berita.index') }}" class="relative block w-full sm:w-64">
                @if ($activeCategory)
                    <input type="hidden" name="kategori" value="{{ $activeCategory }}">
                @endif
                <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-white/30" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="m14 14-3.33-3.33M11.33 6.67a4.67 4.67 0 1 1-9.33 0 4.67 4.67 0 0 1 9.33 0Z" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <input type="search" name="q" value="{{ $search }}" data-news-search placeholder="Cari berita..." class="h-[42px] w-full rounded-xl border border-white/15 bg-white/5 pl-9 pr-4 text-sm text-white outline-none placeholder:text-white/25 focus:border-gold">
            </form>
        </div>
    </section>

    <section class="px-6 pb-24">
        <div class="mx-auto max-w-[1280px]">
            @if ($featured)
                <a href="{{ route('berita.show', $featured->slug) }}"
                   data-news-card
                   data-category="{{ $featured->category->name }}"
                   data-title="{{ strtolower($featured->title.' '.$featured->lead_paragraph) }}"
                   class="group relative block h-[480px] overflow-hidden rounded-2xl">
                    <img src="{{ $featured->thumbnail_url }}" alt="{{ $featured->title }}" class="absolute inset-0 size-full object-cover transition duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-navy-950/40 to-transparent"></div>
                    <x-category-badge :slug="$featured->category->slug" class="absolute left-6 top-[22px] px-3 py-1.5 font-oswald text-xs font-bold tracking-[1.2px]">{{ $featured->category->name }}</x-category-badge>
                    <div class="absolute inset-x-0 bottom-0 p-6 md:p-12">
                        <div class="max-w-2xl">
                            <div class="flex items-center gap-3 text-xs text-white/40"><span>{{ $featured->published_date_label }}</span><span>·</span><span>{{ $featured->author->name }}</span></div>
                            <h2 class="pt-3 font-oswald text-3xl font-bold uppercase leading-tight transition group-hover:text-gold md:text-4xl md:leading-[45px]">{{ $featured->title }}</h2>
                            <p class="pt-3 text-base leading-[26px] text-white/60">{{ $featured->lead_paragraph }}</p>
                        </div>
                    </div>
                </a>
            @endif

            @if ($news->isNotEmpty())
                <div class="grid gap-6 {{ $featured ? 'pt-6' : '' }} md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($news as $item)
                        <a href="{{ route('berita.show', $item->slug) }}"
                           data-news-card
                           data-category="{{ $item->category->name }}"
                           data-title="{{ strtolower($item->title.' '.$item->lead_paragraph) }}"
                           class="group flex flex-col overflow-hidden rounded-2xl border border-white/5 bg-[#0d1a2d] transition hover:border-sky/30">
                            <div class="relative h-[200px] overflow-hidden">
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" loading="lazy" class="size-full object-cover transition duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0d1a2d]/60 to-transparent"></div>
                                <x-category-badge :slug="$item->category->slug" class="absolute left-3 top-3 px-2.5 py-1 font-oswald text-[10px] font-bold tracking-[1px]">{{ $item->category->name }}</x-category-badge>
                            </div>
                            <div class="flex flex-1 flex-col p-6">
                                <div class="flex items-center gap-2 text-xs text-white/30"><span>{{ $item->published_date_label }}</span><span>·</span><span>{{ $item->author->name }}</span></div>
                                <h3 class="line-clamp-2 pt-2 font-oswald text-lg font-bold uppercase leading-[22.5px]">{{ $item->title }}</h3>
                                <p class="line-clamp-2 pt-3 text-sm leading-[22.75px] text-white/50">{{ $item->lead_paragraph }}</p>
                                <span class="mt-auto inline-flex items-center gap-2 pt-4 text-xs font-medium text-sky">
                                    Baca Selengkapnya
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="m8.49 8 2-2-2-2M10.49 6h-9" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if ($news->hasPages())
                    <div class="mt-10">
                        {{ $news->links() }}
                    </div>
                @endif
            @endif

            <p data-news-empty class="{{ ($featured === null && $news->isEmpty()) ? '' : 'hidden' }} py-20 text-center text-base text-white/50">Berita yang Anda cari tidak ditemukan.</p>
        </div>
    </section>
</main>

@include('partials.footer')

</body>
</html>
