<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - Bee Bold Soccer School</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bbss.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Oswald:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f4f7fb] font-inter text-navy-950 antialiased">

@php
    $user = auth()->user();
    $isAdmin = $user->isAdmin();

    $contentLinks = [
        ['label' => 'Ringkasan', 'route' => 'dashboard', 'match' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['label' => 'Berita & Artikel', 'route' => 'dashboard.news.index', 'match' => 'dashboard.news.*', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
        ['label' => 'Kategori Berita', 'route' => 'dashboard.categories.index', 'match' => 'dashboard.categories.*', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
        ['label' => 'Galeri Dokumentasi', 'route' => 'dashboard.galleries.index', 'match' => 'dashboard.galleries.*', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
    ];

    $adminLinks = [
        ['label' => 'Program Usia', 'route' => 'dashboard.programs.index', 'match' => 'dashboard.programs.*', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        ['label' => 'Jadwal Latihan', 'route' => 'dashboard.schedules.index', 'match' => 'dashboard.schedules.*', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['label' => 'Daftar FAQ', 'route' => 'dashboard.faqs.index', 'match' => 'dashboard.faqs.*', 'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => 'Biaya Pendaftaran', 'route' => 'dashboard.fees.index', 'match' => 'dashboard.fees.*', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label' => 'Kelola Editor', 'route' => 'dashboard.editors.index', 'match' => 'dashboard.editors.*', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
    ];

    $profileActive = request()->routeIs('dashboard.profile.*');
@endphp

<div class="flex min-h-screen">
    {{-- Mobile Backdrop --}}
    <div data-dash-backdrop class="fixed inset-0 z-40 hidden bg-navy-950/40 backdrop-blur-xs lg:hidden"></div>

    {{-- Sidebar: Fixed full-screen on mobile drawer, Sticky full-screen (h-dvh) on desktop so profile stays pinned at bottom --}}
    <aside data-dash-sidebar class="fixed inset-y-0 left-0 z-50 flex h-dvh w-72 shrink-0 -translate-x-full flex-col border-r border-brand/10 bg-white shadow-xl transition-transform duration-200 lg:sticky lg:top-0 lg:z-30 lg:translate-x-0 lg:shadow-none">
        <div class="flex shrink-0 items-center justify-between border-b border-brand/10 bg-brand px-5 py-4 text-white">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo-bbss.png') }}" alt="Logo Bee Bold" class="size-10 rounded-full object-contain shadow-[0_2px_10px_rgba(255,204,0,.4)]">
                <div>
                    <span class="block font-oswald text-base font-bold leading-none tracking-wide text-white">BEE BOLD</span>
                    <span class="block pt-0.5 text-[10px] font-semibold tracking-[1px] text-gold">PANEL PENGELOLA</span>
                </div>
            </a>
            <button type="button" data-dash-close aria-label="Tutup menu" class="flex size-9 items-center justify-center rounded-lg text-white/70 hover:bg-white/10 hover:text-white lg:hidden">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="flex-1 space-y-5 overflow-y-auto px-4 py-5">
            <div>
                <p class="px-3 text-[11px] font-bold uppercase tracking-[1.2px] text-brand/45">Konten Publikasi</p>
                <div class="mt-2 space-y-1">
                    @foreach ($contentLinks as $link)
                        @php $active = request()->routeIs($link['match']); @endphp
                        <a href="{{ route($link['route']) }}"
                           class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition {{ $active ? 'bg-brand font-semibold text-white shadow-xs' : 'text-navy-900/75 hover:bg-[#eaf2fb] hover:text-brand' }}">
                            <svg class="size-5 shrink-0 {{ $active ? 'text-gold' : 'text-brand/60' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"/>
                            </svg>
                            <span class="truncate">{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            @if ($isAdmin)
                <div>
                    <p class="px-3 text-[11px] font-bold uppercase tracking-[1.2px] text-brand/45">Akademi &amp; Sistem</p>
                    <div class="mt-2 space-y-1">
                        @foreach ($adminLinks as $link)
                            @php $active = request()->routeIs($link['match']); @endphp
                            <a href="{{ route($link['route']) }}"
                               class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition {{ $active ? 'bg-brand font-semibold text-white shadow-xs' : 'text-navy-900/75 hover:bg-[#eaf2fb] hover:text-brand' }}">
                                <svg class="size-5 shrink-0 {{ $active ? 'text-gold' : 'text-brand/60' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"/>
                                </svg>
                                <span class="truncate">{{ $link['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="px-3 text-[11px] font-bold uppercase tracking-[1.2px] text-brand/45">Akun Saya</p>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('dashboard.profile.edit') }}"
                           class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition {{ $profileActive ? 'bg-brand font-semibold text-white shadow-xs' : 'text-navy-900/75 hover:bg-[#eaf2fb] hover:text-brand' }}">
                            <svg class="size-5 shrink-0 {{ $profileActive ? 'text-gold' : 'text-brand/60' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="truncate">Pengaturan Profil</span>
                        </a>
                    </div>
                </div>
            @endif
        </nav>

        {{-- Pinned Bottom User Profile Card (Non-Clickable Info Display) --}}
        <div class="shrink-0 border-t border-brand/10 bg-[#f8fafd] p-3.5">
            <div class="flex items-center gap-3 rounded-xl border border-brand/10 bg-white p-3">
                <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-brand font-oswald text-sm font-bold text-gold">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-bold text-navy-950">{{ $user->name }}</p>
                    <p class="truncate text-[11px] text-navy-900/55">{{ $user->email }}</p>
                    <span class="mt-1 inline-block rounded-full {{ $isAdmin ? 'bg-gold/35 text-brand' : 'bg-sky/35 text-brand' }} px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider">
                        {{ $user->role->label() }}
                    </span>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Content Area --}}
    <div class="flex min-w-0 flex-1 flex-col">
        {{-- Topbar --}}
        <header class="sticky top-0 z-20 border-b border-brand/10 bg-white/95 px-4 py-3 backdrop-blur-md sm:px-6 lg:px-8">
            <div class="mx-auto flex max-w-[1280px] items-center justify-between gap-3">
                <div class="flex min-w-0 flex-1 items-center gap-2.5 sm:gap-3">
                    <button type="button" data-dash-toggle aria-label="Buka menu navigasi" class="flex size-10 shrink-0 items-center justify-center rounded-xl border border-brand/15 bg-[#f4f7fb] text-brand hover:bg-[#eaf2fb] lg:hidden">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="min-w-0 flex-1">
                        <h1 class="truncate font-oswald text-lg font-bold uppercase tracking-wide text-brand sm:text-2xl">@yield('header', 'Dashboard')</h1>
                    </div>
                </div>

                <div class="flex shrink-0 items-center gap-2">
                    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="inline-flex min-h-9 items-center gap-1.5 rounded-xl border border-brand/15 bg-[#f4f7fb] px-3 py-2 text-xs font-semibold text-brand transition hover:border-brand/30 hover:bg-[#eaf2fb] sm:min-h-10 sm:px-3.5">
                        <span class="hidden sm:inline">Lihat Website</span>
                        <span class="sm:hidden">Web</span>
                        <svg width="13" height="13" viewBox="0 0 16 16" fill="none"><path d="M6 3H3v10h10v-3M9 3h4v4M13 3 7.5 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex min-h-9 cursor-pointer items-center rounded-xl bg-[#dc2626] px-3 py-2 font-oswald text-xs font-bold uppercase tracking-wider text-white shadow-xs transition hover:bg-[#b91c1c] sm:min-h-10 sm:px-4">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Page Body --}}
        <main class="mx-auto w-full max-w-[1280px] flex-1 px-4 py-5 sm:px-6 sm:py-8 lg:px-8">

            @if (session('status'))
                <div role="status" class="mb-5 flex items-center justify-between gap-3 rounded-xl border border-brand/20 bg-[#e6f4ea] px-4 py-3 text-xs font-medium text-[#137333] sm:text-sm">
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div role="alert" class="mb-5 flex items-center justify-between gap-3 rounded-xl border border-[#f87171]/40 bg-[#fce8e6] px-4 py-3 text-xs font-medium text-[#c5221f] sm:text-sm">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div role="alert" class="mb-5 rounded-xl border border-[#f87171]/40 bg-[#fce8e6] px-4 py-3 text-xs text-[#c5221f] sm:text-sm">
                    <p class="font-semibold">Terdapat kesalahan pada input Anda:</p>
                    <ul class="mt-1 list-disc space-y-0.5 pl-5 text-xs">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
