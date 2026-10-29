@php
    $logoImg = asset('images/logo-bbss.png');
    $isHome = request()->is('/');
    $navItems = [
        ['label' => 'Beranda', 'anchor' => '#beranda', 'href' => $isHome ? '#beranda' : url('/'), 'active' => $isHome],
        ['label' => 'Program', 'anchor' => '#program', 'href' => $isHome ? '#program' : url('/#program'), 'active' => false],
        ['label' => 'Berita', 'anchor' => '#berita', 'href' => $isHome ? '#berita' : url('/#berita'), 'active' => request()->routeIs('berita.*')],
        ['label' => 'Jadwal', 'anchor' => '#jadwal', 'href' => $isHome ? '#jadwal' : url('/#jadwal'), 'active' => false],
        ['label' => 'Galeri', 'anchor' => '#galeri', 'href' => $isHome ? '#galeri' : url('/#galeri'), 'active' => request()->routeIs('galeri.*')],
    ];
    $daftarHref = $isHome ? '#daftar' : url('/#daftar');
@endphp
<header class="fixed inset-x-0 top-0 z-50 border border-sky/15 bg-[#001433]/60 backdrop-blur-xl">
    <div class="mx-auto flex max-w-[1280px] items-center justify-between px-6 py-4">
        <a href="{{ $isHome ? '#beranda' : url('/') }}" class="flex items-center gap-3">
            <img src="{{ $logoImg }}" alt="Logo Bee Bold Soccer School" class="size-11 rounded-full object-contain shadow-[0_2px_12px_rgba(255,204,0,.35)]">
            <span>
                <span class="block font-oswald text-lg font-bold leading-none tracking-wide">BEE BOLD</span>
                <span class="block text-[10px] tracking-[1px] text-sky">SOCCER SCHOOL</span>
            </span>
        </a>
        <nav class="hidden items-center gap-8 text-sm font-medium text-white/70 md:flex">
            @foreach ($navItems as $item)
                <a href="{{ $item['href'] }}"
                   data-nav-item="{{ $item['anchor'] }}"
                   class="transition hover:text-gold {{ $item['active'] ? 'font-semibold text-gold' : '' }}">{{ $item['label'] }}</a>
            @endforeach
            <a href="{{ $daftarHref }}" class="rounded-lg bg-gold px-5 py-2.5 font-oswald font-semibold text-brand">DAFTAR</a>
        </nav>
        <button type="button" data-nav-toggle aria-controls="mobile-menu" aria-expanded="false" aria-label="Buka menu navigasi" class="flex size-11 items-center justify-center rounded-lg border border-white/15 bg-white/5 text-white transition hover:border-gold hover:text-gold md:hidden">
            <svg data-nav-icon="open" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg data-nav-icon="close" class="hidden" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <nav id="mobile-menu" data-nav-menu class="hidden border-t border-sky/15 bg-[#001433]/95 px-6 py-4 backdrop-blur-xl md:hidden">
        <div class="flex flex-col space-y-1 text-sm font-medium text-white/80">
            @foreach ($navItems as $item)
                <a href="{{ $item['href'] }}"
                   data-nav-link
                   data-nav-item="{{ $item['anchor'] }}"
                   class="rounded-lg px-3 py-3 transition hover:bg-white/5 hover:text-gold {{ $item['active'] ? 'bg-white/5 font-semibold text-gold' : '' }}">{{ $item['label'] }}</a>
            @endforeach
            <div class="pt-2">
                <a href="{{ $daftarHref }}" data-nav-link class="block w-full rounded-lg bg-gold px-5 py-3 text-center font-oswald font-semibold tracking-wide text-brand">DAFTAR</a>
            </div>
        </div>
    </nav>
</header>
