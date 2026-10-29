<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $article->title }} - Bee Bold Soccer School</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bbss.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Oswald:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-navy-950 font-inter text-white antialiased">

@include('partials.navbar')

@php
    $shareText = urlencode($article->title.' '.url()->current());
@endphp

<main>
    <section class="relative flex min-h-[480px] items-end overflow-hidden px-6 pb-12 pt-32">
        <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}" class="absolute inset-0 size-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-navy-950/60 to-navy-950/20"></div>
        <div class="relative mx-auto w-full max-w-[1280px]">
            <nav aria-label="Breadcrumb" class="mb-6 text-xs text-white/50">
                <a href="{{ url('/') }}" class="transition hover:text-gold">Beranda</a> <span class="px-1.5">/</span>
                <a href="{{ route('berita.index') }}" class="transition hover:text-gold">Berita</a> <span class="px-1.5">/</span>
                <span class="text-white/70">{{ $article->title }}</span>
            </nav>
            <x-category-badge :slug="$article->category->slug" class="inline-block px-3 py-1 font-oswald text-xs font-bold">{{ $article->category->name }}</x-category-badge>
            <h1 class="max-w-3xl pt-4 font-oswald text-3xl font-bold uppercase leading-tight md:text-5xl md:leading-[1.2]">{{ $article->title }}</h1>
            <div class="flex items-center gap-3 pt-5 text-xs text-white/50">
                <span class="flex size-5 items-center justify-center rounded-full bg-gold text-[8px] font-bold text-brand">BB</span>
                <span>{{ $article->author->name }}</span><span>·</span><span>{{ $article->published_date_label }}</span>
            </div>
        </div>
    </section>

    <section class="px-6 py-12">
        <div class="mx-auto grid max-w-[1280px] gap-12 lg:grid-cols-[1fr_320px]">
            <article class="max-w-[600px]">
                <p class="border-l-2 border-gold py-1 pl-5 text-lg leading-8 text-white/80">{{ $article->lead_paragraph }}</p>
                <div class="space-y-6 pt-10 text-[15px] leading-7 text-white/70">
                    @foreach ($article->paragraphs as $p)
                        <p>{{ $p }}</p>
                    @endforeach
                </div>

                <div class="mt-12 flex flex-wrap items-center gap-3 border-t border-white/10 pt-6 text-xs text-white/40">
                    <span>Bagikan:</span>
                    <a href="https://wa.me/?text={{ $shareText }}" target="_blank" rel="noopener" class="rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-white/60 transition hover:text-white">WhatsApp</a>
                    <a href="https://www.instagram.com/beeboldss.bdg" target="_blank" rel="noopener" class="rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-white/60 transition hover:text-white">Instagram</a>
                </div>
            </article>

            <aside class="space-y-8">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 text-center">
                    <p class="font-oswald text-4xl font-bold text-gold">{{ $total }}</p>
                    <p class="pt-1 text-xs text-white/40">Total Berita Dipublikasikan</p>
                    <a href="{{ route('berita.index') }}" class="mt-5 block rounded-lg border border-sky/40 bg-sky/10 py-2.5 text-xs text-sky transition hover:bg-sky/20">Lihat Semua Berita</a>
                </div>

                @if ($relatedPicks->isNotEmpty())
                    <div>
                        <h3 class="font-oswald text-xs font-bold uppercase tracking-[1.4px]">Berita Terkait</h3>
                        <div class="space-y-3 pt-4">
                            @foreach ($relatedPicks as $item)
                                <a href="{{ route('berita.show', $item->slug) }}" class="flex gap-3 rounded-xl border border-white/5 bg-white/5 p-3 transition hover:border-sky/30">
                                    <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" loading="lazy" class="h-12 w-[60px] shrink-0 rounded-md object-cover">
                                    <span>
                                        <span class="block text-[10px] text-sky">{{ $item->category->name }}</span>
                                        <span class="block pt-1 font-oswald text-[10px] font-bold uppercase leading-tight">{{ $item->title }}</span>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($moreNews->isNotEmpty())
                    <div>
                        <h3 class="font-oswald text-xs font-bold uppercase tracking-[1.4px]">Berita Lainnya</h3>
                        <ul class="pt-4">
                            @foreach ($moreNews as $item)
                                <li class="border-b border-white/5 py-3 last:border-0">
                                    <a href="{{ route('berita.show', $item->slug) }}" class="flex items-start gap-2 font-oswald text-[10px] font-bold uppercase leading-tight text-white/70 transition hover:text-gold">
                                        <span class="mt-0.5 size-1.5 shrink-0 rotate-45 bg-gold"></span>{{ $item->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>

        <div class="mx-auto mt-16 flex max-w-[1280px] flex-wrap justify-center gap-3">
            <a href="{{ route('berita.index') }}" class="flex w-[220px] items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-5 py-4 text-xs text-white/50 transition hover:text-white">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 12.67 5.33 8 10 3.33" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Semua Berita
            </a>
            <a href="{{ url('/#daftar') }}" class="flex w-[220px] items-center justify-center gap-2 rounded-xl bg-gold p-4 font-oswald text-xs font-bold tracking-[0.3px] text-brand">
                DAFTAR SEKARANG
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="m8.49 8 2-2-2-2M10.49 6h-9" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </section>
</main>

@include('partials.footer')

</body>
</html>
