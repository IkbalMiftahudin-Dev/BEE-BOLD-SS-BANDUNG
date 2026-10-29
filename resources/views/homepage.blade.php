<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bee Bold Soccer School</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bbss.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat&family=Inter:wght@400;500;600;700&family=Montserrat:ital,wght@0,600;0,900;1,900&family=Oswald:wght@400;600;700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-navy-950 font-inter text-white antialiased">

    @php
        $img = fn($id) => "https://api.builder.io/api/v1/image/assets/TEMP/$id";
        $heroImg = $img('1a60366866c23e33f00ba54592976d7b5fc9b63d?width=2879');
        $logoImg = asset('images/logo-bbss.png');
        $activeProgramsCount = $programsCount ?? $programs->count();
        $experienceYears =
            $yearsOfExperience ??
            max(
                1,
                (int) floor(
                    \Illuminate\Support\Carbon::create(2024, 5, 6, 0, 0, 0, 'Asia/Jakarta')->diffInYears(
                        now('Asia/Jakarta'),
                    ),
                ),
            );
        $stats = [
            ['500+', 'Siswa Aktif'],
            [(string) $activeProgramsCount, 'Program Usia'],
            ['15+', 'Pelatih Berlisensi'],
            [$experienceYears . ' Tahun', 'Pengalaman'],
        ];
        $marquee = [
            'Pelatihan Profesional',
            'Pengembangan Karakter',
            'Fasilitas Lengkap',
            'Kurikulum Terstruktur',
            'Pelatih Berlisensi',
        ];
        $plus =
            '<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 3.33v9.33M3.33 8h9.33" stroke="currentColor" stroke-width="1.33" stroke-linecap="round"/></svg>';
    @endphp

    @include('partials.navbar')

    {{-- Hero (Full 100vh Viewport) --}}
    <section id="beranda" class="relative flex min-h-screen flex-col justify-center overflow-hidden bg-[#001228]">
        <img src="{{ $heroImg }}" alt="" class="absolute inset-0 size-full object-cover opacity-[.28]">
        <div class="absolute inset-0 bg-gradient-to-b from-[#001228]/60 via-[#001f40]/30 to-[#001228]/85"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-brand/90 to-transparent to-60%"></div>
        <div class="absolute inset-y-0 left-0 w-1 bg-gradient-to-b from-gold to-sky"></div>

        <div class="relative mx-auto flex w-full max-w-[1280px] flex-1 items-center justify-between px-6 pb-16 pt-28 sm:pb-20 sm:pt-32">
            <div class="max-w-[640px]">
                <p class="font-inter text-xs font-medium uppercase tracking-[1.2px] text-sky">— Sekolah Sepak Bola
                    Bandung</p>
                <h1 class="mt-3 font-montserrat font-black leading-[.98] tracking-[-2.2px] sm:mt-4">
                    <span class="block text-4xl text-white sm:text-5xl lg:text-[52px] xl:text-[56px]">SELAMAT DATANG DI</span>
                    <span class="block text-5xl italic text-gold sm:text-6xl lg:text-[70px] xl:text-[77px]">BEE BOLD</span>
                    <span class="block text-4xl text-white sm:text-5xl lg:text-[64px] xl:text-[72px]">SOCCER SCHOOL</span>
                </h1>
                <p class="mt-5 max-w-xl text-base leading-[1.625] text-white/70 sm:mt-6 sm:text-lg">Sekolah sepak bola di Bandung yang
                    membina bakat, mengasah kemampuan, dan membentuk karakter pemain muda untuk meraih prestasi di
                    lapangan.</p>
                <div class="mt-6 flex flex-wrap gap-3 sm:mt-8 sm:gap-4">
                    <a href="#daftar"
                        class="flex min-h-12 items-center gap-3 rounded-xl bg-gold px-6 py-3.5 font-oswald text-base font-bold tracking-wide text-brand shadow-[0_8px_32px_rgba(255,204,0,.4)] sm:px-8 sm:py-4 sm:text-lg">
                        DAFTAR SEKARANG
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M14.16 13.33 17.49 10l-3.33-3.33M17.49 10H2.5" stroke="#036" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    <a href="#program"
                        class="flex min-h-12 items-center rounded-xl border border-white/30 px-6 py-3.5 font-oswald text-base font-semibold tracking-wide sm:px-8 sm:py-4 sm:text-lg">LIHAT
                        PROGRAM</a>
                </div>
            </div>
            <div class="hidden flex-1 flex-col items-center justify-center lg:flex lg:-translate-y-20">
                <div class="-rotate-6 text-center font-caveat text-4xl leading-[1.05] xl:text-5xl">
                    <p>More</p>
                    <p>Than Just</p>
                    <p>Football</p>
                </div>
                <div class="mt-1.5 h-[7px] w-[170px] -rotate-[4deg] rounded-md bg-gold xl:w-[195px]"></div>
            </div>
        </div>
    </section>

    {{-- Statistik & Marquee (Terpisah dari Hero) --}}
    <section aria-label="Statistik dan Keunggulan Bee Bold" class="relative z-10 border-t border-white/10 bg-[#050d1a]">
        <div class="mx-auto grid max-w-[1312px] grid-cols-2 divide-y divide-white/10 px-4 sm:px-6 md:grid-cols-4 md:divide-x md:divide-y-0 md:px-16">
            @foreach ($stats as [$value, $label])
                <div class="px-4 py-6 text-center sm:px-6 md:py-8">
                    <div class="font-montserrat text-3xl font-black tracking-[-.8px] text-gold sm:text-4xl md:text-[40px]">
                        {{ $value }}</div>
                    <div
                        class="pt-1.5 font-montserrat text-[10px] font-semibold uppercase tracking-[1.1px] text-white/60 sm:text-[11px]">
                        {{ $label }}</div>
                </div>
            @endforeach
        </div>

        <div class="overflow-hidden bg-gold py-3.5 shadow-md">
            <div class="flex w-max animate-[marquee_30s_linear_infinite] items-center gap-16 pl-6">
                @for ($i = 0; $i < 3; $i++)
                    @foreach ($marquee as $m)
                        <div class="flex items-center gap-8">
                            <span
                                class="font-oswald text-sm font-bold uppercase tracking-[1.4px] text-brand">{{ $m }}</span>
                            <span class="text-xl text-brand/50">◆</span>
                        </div>
                    @endforeach
                @endfor
            </div>
        </div>
    </section>

    {{-- Tentang --}}
    <section id="tentang"
        class="relative overflow-hidden bg-gradient-to-b from-[#061020] via-[#002b5c] to-[#061020] px-6 py-24">
        <div
            class="pointer-events-none absolute left-1/2 top-[38%] h-[520px] w-[960px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#004080]/45 blur-[130px]">
        </div>
        <div class="relative mx-auto max-w-[1280px]">
            <div class="grid items-center gap-12 lg:grid-cols-[1fr_minmax(320px,460px)_280px] lg:items-start">
                <div class="lg:pt-4">
                    <p class="text-xs font-medium uppercase tracking-[1.2px] text-gold">— Tentang Kami</p>
                    <h2 class="mt-3 font-oswald text-6xl font-bold uppercase leading-[0.95] md:text-7xl">
                        <span class="block text-white">Lebih Dari</span>
                        <span class="block text-sky">Akademi</span>
                    </h2>
                </div>

                <div class="relative mx-auto w-full max-w-[460px] -rotate-2 rounded-sm bg-white p-3 pb-10 shadow-2xl">
                    <div
                        class="pointer-events-none absolute -right-6 -top-3 z-10 h-7 w-24 rotate-[35deg] bg-gold shadow-md">
                    </div>
                    <img src="{{ $img('fdcf4ca5342f0ccc2a2c6d6e1ac9c475da09755d?width=1264') }}" alt="Tim Bee Bold"
                        class="aspect-[4/3] w-full object-cover">
                </div>

                <div
                    class="mx-auto w-full max-w-[280px] rounded-2xl border border-white/5 bg-[#001428]/90 p-7 shadow-xl lg:mt-10">
                    <img src="{{ $logoImg }}" alt="Logo Bee Bold Soccer School"
                        class="mx-auto size-14 rounded-full object-contain shadow-[0_2px_12px_rgba(255,204,0,.25)]">
                    <blockquote class="mt-6 text-sm italic leading-relaxed text-white/80">
                        “Disiplin hari ini, adalah kemenangan di masa depan.”
                    </blockquote>
                    <p class="mt-4 text-xs text-white/40">— Bee Bold SS</p>
                </div>
            </div>

            <div
                class="mt-16 rounded-2xl border border-white/5 bg-[#050d1a]/90 px-8 py-12 shadow-2xl backdrop-blur-sm md:px-14">
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ([['Pelatihan Profesional', 'Program latihan terstruktur bersama pelatih berpengalaman.', 'logo-pelatihan.svg'], ['Pengembangan Karakter', 'Menanamkan nilai disiplin, kerja sama, dan sportivitas.', 'logo-pengembangan.svg'], ['Fasilitas Lengkap', 'Selain latihan dilengkapi dengan program psikologi, fisiologi, nutrisi, dan pengenalan latihan beban untuk usia tertentu.', 'logo-fasilitas.svg'], ['Kesempatan Berkompetisi', 'Mengikuti turnamen dan uji coba untuk mengasah mental bertanding.', 'logo-kesempatan.svg']] as [$t, $d, $icon])
                        <div>
                            <img src="{{ asset('images/' . $icon) }}" alt="{{ $t }}"
                                class="mb-4 size-9 object-contain lg:mb-5 lg:size-11">
                            <h3 class="font-inter text-base font-bold leading-snug text-white lg:text-lg">
                                {{ $t }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-white/65 lg:mt-2.5 lg:text-[15px]">
                                {{ $d }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-20 text-center">
                <div class="mx-auto mb-3 h-0.5 w-8 bg-gold"></div>
                <p class="text-xs font-medium uppercase tracking-[1.2px] text-gold">Pendiri</p>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                @foreach ([['Boy Jati Asmara', 'Direktur Pengembangan Bee Bold SS', 'Eks Persib'], ['Eka Ramdani', 'Direktur Teknik Bee Bold SS', 'Mantan Pemain Timnas Indonesia & Persib'], ['Gilang Jalu', 'Direktur PT Gikari Jaya Sentosa', 'Pengusaha sekaligus Pemerhati Sepak Bola']] as [$n, $r, $sub])
                    <div
                        class="relative h-[320px] overflow-hidden rounded-2xl border border-white/5 bg-gradient-to-br from-brand to-navy-900">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#001428] via-[#001428]/40 to-transparent">
                        </div>
                        <div class="absolute bottom-0 p-6">
                            <p class="font-oswald text-xl font-bold uppercase tracking-wide text-white">
                                {{ $n }}</p>
                            <p class="pt-0.5 text-xs font-medium text-gold">{{ $r }}</p>
                            <p class="pt-0.5 text-[11px] text-white/45">{{ $sub }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Program --}}
    <section id="program" class="bg-navy-950 px-6 py-24">
        <div class="mx-auto max-w-[1280px]">
            <p class="text-xs font-medium uppercase tracking-[1.2px] text-gold">— Program</p>
            <div class="flex flex-wrap items-end justify-between gap-6">
                <h2 class="pt-3 font-oswald text-6xl font-bold uppercase leading-none md:text-7xl">Jalur Menuju <span
                        class="text-gold">Puncak</span></h2>
                <p class="max-w-sm text-left text-base leading-6 text-white/50">Program terstruktur untuk setiap
                    kelompok usia, dirancang bersama pelatih berlisensi AFC/UEFA.</p>
            </div>
            @if (count($programs) > 4)
                <div class="mt-10 flex justify-end gap-2">
                    <button type="button" data-program-prev aria-label="Geser ke kiri"
                        class="flex size-11 cursor-pointer items-center justify-center rounded-xl border border-white/15 bg-white/5 text-white transition hover:border-gold hover:bg-gold hover:text-brand">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m15 18-6-6 6-6" />
                        </svg>
                    </button>
                    <button type="button" data-program-next aria-label="Geser ke kanan"
                        class="flex size-11 cursor-pointer items-center justify-center rounded-xl border border-white/15 bg-white/5 text-white transition hover:border-gold hover:bg-gold hover:text-brand">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </button>
                </div>
            @endif
            <div data-program-slider
                class="no-scrollbar {{ count($programs) > 4 ? 'mt-4' : 'mt-14' }} flex snap-x snap-mandatory gap-4 overflow-x-auto scroll-smooth pb-4">
                @forelse ($programs as $program)
                    <div data-program-card
                        class="relative h-[480px] w-[85%] shrink-0 snap-start overflow-hidden rounded-2xl bg-navy-900 bg-cover bg-center sm:w-[calc((100%-1rem)/2)] lg:w-[calc((100%-3rem)/4)] {{ $loop->even ? 'lg:mt-8' : '' }}"
                        style="background-image: linear-gradient(0deg,#001428 0%,rgba(0,20,40,.4) 50%,rgba(0,20,40,0) 100%), url('{{ addcslashes((string) $program->image_url, "'\\\r\n()") }}')">
                        {{-- Badge colour alternates in the view layer; there is no badge_color column. --}}
                        <span
                            class="absolute left-4 top-4 rounded-full {{ $loop->odd ? 'bg-gold' : 'bg-sky' }} px-3 py-1 font-oswald text-xs font-bold tracking-[1.2px] text-brand">{{ $program->age_range }}</span>
                        <div class="absolute inset-x-0 bottom-0 p-6">
                            <h3 class="font-oswald text-2xl font-bold uppercase">{{ $program->title }}</h3>
                            <p class="pt-2 text-sm leading-[1.625] text-white/60">{{ $program->coach_name }}{{ filled($program->description) ? ' • ' . $program->description : '' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="w-full py-16 text-center text-base text-white/50">Program latihan akan segera diumumkan.
                    </p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Berita --}}
    <section id="berita" class="bg-navy-950 px-6 py-24">
        <div class="mx-auto max-w-[1280px]">
            <p class="text-xs font-medium uppercase tracking-[1.2px] text-gold">— Kabar & Cerita</p>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <h2 class="pt-3 font-oswald text-6xl font-bold uppercase leading-none md:text-7xl">Terkini Dari <span
                        class="text-sky">Lapangan</span></h2>
                <a href="{{ route('berita.index') }}"
                    class="flex items-center gap-2 text-sm font-medium tracking-[.35px] text-gold transition hover:opacity-80">Semua
                    Berita
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="m11.33 10.67 2.67-2.67-2.67-2.67M14 8H2" stroke="#FFCC00" stroke-width="1.33"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
            @if ($featuredNews)
                <div class="mt-14 grid gap-4 lg:grid-cols-2">
                    <a href="{{ route('berita.show', $featuredNews->slug) }}"
                        class="group relative block min-h-[480px] overflow-hidden rounded-2xl bg-[#001A33]">
                        <img src="{{ $featuredNews->thumbnail_url }}" alt="{{ $featuredNews->title }}"
                            class="absolute inset-0 size-full object-cover opacity-70 transition duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#001428] via-[#001428]/50 to-transparent">
                        </div>
                        <x-category-badge :slug="$featuredNews->category->slug"
                            class="absolute left-4 top-[18px] px-3 py-1 text-xs font-bold">{{ $featuredNews->category->name }}</x-category-badge>
                        <div class="absolute inset-x-0 bottom-0 p-8">
                            <p class="text-xs text-white/40">{{ $featuredNews->published_date_label }}</p>
                            <h3
                                class="pt-3 font-oswald text-3xl font-bold uppercase leading-tight transition group-hover:text-gold">
                                {{ $featuredNews->title }}</h3>
                            <p class="pt-3 text-sm leading-[1.625] text-white/60">{{ $featuredNews->lead_paragraph }}
                            </p>
                        </div>
                    </a>
                    <div class="flex flex-col gap-4">
                        @foreach ($sideNews as $item)
                            <a href="{{ route('berita.show', $item->slug) }}"
                                class="group flex min-h-36 overflow-hidden rounded-2xl bg-[#001A33] transition hover:ring-1 hover:ring-sky/30">
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" loading="lazy"
                                    class="w-32 shrink-0 object-cover transition duration-500 group-hover:scale-105 sm:w-40">
                                <div class="flex flex-1 flex-col justify-center px-5 py-4">
                                    <div class="pb-2"><span
                                            class="rounded bg-sky/15 px-2 py-0.5 text-xs font-medium text-sky">{{ $item->category->name }}</span>
                                    </div>
                                    <h3
                                        class="font-oswald text-lg font-bold uppercase leading-snug transition group-hover:text-gold">
                                        {{ $item->title }}</h3>
                                    <p class="pt-1 text-xs text-white/40">{{ $item->published_date_label }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="mt-14 py-16 text-center text-base text-white/50">Belum ada berita yang dipublikasikan.</p>
            @endif
        </div>
    </section>

    {{-- Jadwal --}}
    <section id="jadwal" class="bg-navy-950 px-6 py-24">
        <div class="mx-auto max-w-[1280px]">
            <p class="text-xs font-medium uppercase tracking-[1.2px] text-gold">— Jadwal</p>
            <h2 class="pt-3 font-oswald text-6xl font-bold uppercase leading-none md:text-7xl">Jadwal <span
                    class="text-gold">Mingguan</span></h2>
            <div class="flex items-center gap-3 pt-4 text-sm text-white/50">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="#FFCC00">
                    <path fill-rule="evenodd"
                        d="M4.04 3.24a5.6 5.6 0 0 1 7.92 7.92L8 15.12l-3.96-3.96a5.6 5.6 0 0 1 0-7.92ZM8 8.8a1.6 1.6 0 1 0 0-3.2 1.6 1.6 0 0 0 0 3.2Z"
                        clip-rule="evenodd" />
                </svg>
                Lapangan UIN 2, Kota Bandung
            </div>

            <div class="flex flex-wrap gap-2 pt-14" data-tabs>
                @foreach ($schedulesByDay as $dayData)
                    <button type="button" data-tab="{{ $dayData['key'] }}"
                        class="min-h-12 rounded-xl border px-5 py-2.5 font-oswald text-sm font-semibold tracking-[.35px] transition {{ $loop->first ? 'border-gold bg-gold text-brand' : 'border-white/10 bg-white/5 text-white/60' }}">{{ $dayData['label'] }}</button>
                @endforeach
            </div>

            @forelse ($schedulesByDay as $dayData)
                <div data-schedule-panel="{{ $dayData['key'] }}"
                    class="mt-8 flex flex-col gap-3 {{ $loop->first ? '' : 'hidden' }}">
                    <div class="mb-1 flex items-center gap-2 text-xs font-medium uppercase tracking-[1px] text-sky">
                        <span class="size-2 rounded-full bg-gold"></span>
                        <span>{{ $dayData['subtitle'] }}</span>
                    </div>
                    @foreach ($dayData['items'] as $schedule)
                        <div
                            class="flex flex-col justify-between gap-4 rounded-xl border border-white/10 bg-white/[.08] p-5 backdrop-blur-xl sm:flex-row sm:items-center">
                            <div class="min-w-[160px] font-oswald text-lg font-bold text-gold">
                                {{ $schedule->time_slot }}
                            </div>
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <p class="font-oswald text-lg font-bold">{{ $schedule->target_category }}</p>
                                    <span
                                        class="rounded-full {{ $schedule->activity_type === 'Grassroot' ? 'bg-gold/20 text-gold' : ($schedule->activity_type === 'Youth' ? 'bg-sky/20 text-sky' : 'bg-white/15 text-white') }} px-2.5 py-0.5 text-xs font-semibold">{{ $schedule->activity_type }}</span>
                                </div>
                                <p class="pt-1 text-sm font-medium text-white/80">{{ $schedule->coach_name }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <p class="mt-8 py-10 text-center text-base text-white/50">Jadwal latihan akan segera diumumkan.</p>
            @endforelse

            <div
                class="mt-3 flex items-center gap-4 rounded-xl border border-white/10 bg-white/[.08] p-5 text-sm text-white/60">
                <svg class="shrink-0" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <circle cx="10" cy="10" r="7.5" stroke="#FFCC00" stroke-width="1.67" />
                    <path d="M10.83 13.33H10V10h-.83M10 6.67h.01" stroke="#FFCC00" stroke-width="1.67"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>Jadwal dapat berubah sewaktu-waktu. Untuk hari Sabtu/Minggu merupakan jadwal uji coba (<em>Try Out
                        Match</em>) atau turnamen dengan lokasi tentatif. Konfirmasi melalui WhatsApp grup sebelum sesi
                    berlangsung.</span>
            </div>
        </div>
    </section>

    {{-- Galeri --}}
    <section id="galeri" class="bg-navy-950 px-6 py-24">
        <div class="mx-auto max-w-[1280px]">
            <p class="text-xs font-medium uppercase tracking-[1.2px] text-gold">— Galeri</p>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <h2 class="pt-3 font-oswald text-6xl font-bold uppercase leading-none md:text-7xl">Momen <span
                        class="text-sky">Terbaik</span></h2>
                <a href="{{ route('galeri.index') }}"
                    class="flex items-center gap-2 text-sm font-medium tracking-[.35px] text-gold transition hover:opacity-80">Semua
                    Galeri
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="m11.33 10.67 2.67-2.67-2.67-2.67M14 8H2" stroke="#FFCC00" stroke-width="1.33"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
            @if ($highlightedGalleries->isNotEmpty())
                <div class="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($highlightedGalleries as $photo)
                        <button type="button" data-gallery-item="{{ $photo->image_url }}"
                            data-gallery-title="{{ $photo->title }}" data-gallery-caption="{{ $photo->caption }}"
                            class="group relative h-[260px] w-full cursor-pointer overflow-hidden rounded-2xl text-left focus:outline-none">
                            <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" loading="lazy"
                                class="size-full object-cover transition duration-500 group-hover:scale-105">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-navy-950/90 via-navy-950/20 to-transparent opacity-0 transition duration-300 group-hover:opacity-100">
                            </div>
                            <div
                                class="absolute inset-x-0 bottom-0 p-5 opacity-0 transition duration-300 group-hover:opacity-100">
                                <p class="font-oswald text-base font-bold uppercase tracking-wide text-white">
                                    {{ $photo->title }}</p>
                                @if ($photo->caption)
                                    <p class="line-clamp-2 pt-1 text-xs leading-relaxed text-white/70">
                                        {{ $photo->caption }}</p>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            @else
                <p class="mt-14 py-16 text-center text-base text-white/50">Belum ada dokumentasi galeri.</p>
            @endif
        </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="relative overflow-hidden bg-[#001428] px-6 py-24">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-sky/30 to-transparent"></div>
        <div class="pointer-events-none absolute -top-32 right-0 size-96 rounded-full bg-brand/40 blur-[64px]"></div>

        <div class="relative mx-auto grid max-w-[1280px] gap-20 lg:grid-cols-12">
            <span
                class="pointer-events-none absolute -left-4 -top-12 font-oswald text-[180px] font-bold leading-none text-white/[.03]">?</span>
            <div class="lg:col-span-5">
                <p class="text-xs font-medium uppercase tracking-[1.2px] text-sky">— Frequently Asked Questions</p>
                <h2 class="pt-3 font-oswald text-6xl font-bold uppercase leading-none md:text-7xl">Masih Ada <span
                        class="text-gold">Pertanyaan?</span></h2>
                <p class="max-w-md pt-6 text-base leading-[1.625] text-white/50">Temukan jawaban seputar program,
                    pendaftaran, dan pengalaman berlatih di Bee Bold Soccer School.</p>
            </div>
            <div class="lg:col-span-7">
                <div class="border-t border-white/10" data-faq>
                    @foreach ($faqs as $i => $faq)
                        <div class="border-b border-white/10">
                            <button type="button" data-faq-btn
                                class="flex min-h-[72px] w-full items-center gap-5 py-6 text-left">
                                <span class="font-oswald text-xs tracking-[1.2px] text-white/25"
                                    data-faq-num>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <span
                                    class="flex-1 font-oswald text-xl font-semibold uppercase tracking-[.5px] text-white/70"
                                    data-faq-q>{{ $faq->question }}</span>
                                <span data-faq-icon
                                    class="flex size-10 shrink-0 items-center justify-center rounded-full border border-white/15 text-white/50 transition-transform">{!! $plus !!}</span>
                            </button>
                            <div data-faq-a class="hidden pb-7 pl-10 pr-16 text-[15px] leading-[26px] text-white/55">
                                {{ $faq->answer }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 flex items-center gap-3 text-xs text-white/30">
                    <span class="size-2 rounded-full bg-sky/50"></span>
                    Informasi diperbarui untuk musim latihan 2025/2026
                </div>
            </div>
        </div>
    </section>

    {{-- Pendaftaran --}}
    <section id="daftar" class="grid lg:grid-cols-2">
        <div class="relative overflow-hidden bg-brand px-6 py-16 lg:py-24">
            <img src="{{ asset('images/lapangan.jpg') }}" alt="Lapangan Bee Bold Soccer School"
                class="absolute inset-0 size-full object-cover object-center">
            <div class="absolute inset-0 bg-brand/70 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-brand/80 via-[#002855]/75 to-[#001428]/90"></div>
            <div class="relative mx-auto w-full max-w-[592px] lg:ml-auto lg:mr-0 lg:max-w-[640px] lg:pr-12">
                <p class="text-xs font-medium uppercase tracking-[1.2px] text-sky">— Pendaftaran</p>
                <h2 class="pt-4 font-oswald text-6xl font-bold uppercase leading-none">Bergabung <span
                        class="text-gold">Bersama</span> Kami</h2>

                <div class="py-8">
                    @foreach ($oneTimeFees as $fee)
                        <div class="{{ $loop->first ? '' : 'mt-4' }}">
                            <p class="text-xs uppercase tracking-[.3px] text-white/40">{{ $fee->label }}</p>
                            <p class="font-semibold">{{ $fee->amount_text }}</p>
                        </div>
                    @endforeach
                    @if ($oneTimeFees->isNotEmpty() && $monthlyFees->isNotEmpty())
                        <hr class="mt-2 border-white/10">
                    @endif
                    @if ($monthlyFees->isNotEmpty())
                        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 sm:gap-12">
                            @foreach ($monthlyFees as $fee)
                                <div>
                                    <p class="text-xs uppercase tracking-[.3px] text-white/40">{{ $fee->label }}</p>
                                    <p class="pt-1 font-semibold">{{ $fee->amount_text }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-white/10 bg-white/5 p-5 text-sm text-white/70">
                        <p class="font-oswald font-semibold uppercase tracking-wide text-white">Persyaratan</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            <li>Usia 7 - 20 tahun</li>
                            <li>Fotokopi Akte Kelahiran</li>
                            <li>KK</li>
                            <li>NISN (Bila Masih Sekolah)</li>
                            <li>Surat Keterangan Sehat dari Dokter</li>
                            <li>Pasfoto 3x4 (2 lembar)</li>
                        </ul>
                    </div>

                    <div class="rounded-xl border border-white/10 bg-white/5 p-5 text-sm text-white/70">
                        <p class="font-oswald font-semibold uppercase tracking-wide text-white">Sudah Termasuk</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            <li>Jersey latihan</li>
                            <li>Pendampingan pelatih berlisensi</li>
                            <li>Laporan perkembangan berkala</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center bg-navy-950 px-6 py-16 lg:py-24">
            <div class="mx-auto w-full max-w-[560px] lg:ml-0 lg:mr-auto lg:max-w-[640px] lg:pl-12">
                <form id="reg-form" method="POST" action="{{ route('registration.whatsapp') }}"
                    class="w-full max-w-[560px] space-y-4 rounded-2xl border border-white/10 bg-white/5 p-8">
                    @csrf
                    <h3 class="font-oswald text-xl font-bold uppercase tracking-wide">Formulir Pendaftaran</h3>
                    @foreach ([['name', 'Nama Lengkap Calon Pemain', 'text'], ['age', 'Usia', 'number'], ['parent', 'Nama Orang Tua / Wali', 'text'], ['phone', 'Nomor WhatsApp', 'tel']] as [$name, $label, $type])
                        <label class="block">
                            <span class="text-xs text-white/50">{{ $label }}</span>
                            <input name="{{ $name }}" type="{{ $type }}"
                                value="{{ old($name) }}" @if ($name === 'age') min="7" max="20" @endif
                                @if ($name === 'phone') inputmode="numeric" pattern="[0-9]+" @endif required
                                class="mt-1 w-full rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none placeholder:text-white/30 focus:border-gold">
                            @error($name)
                                <span class="mt-1 block text-xs text-[#f87171]">{{ $message }}</span>
                            @enderror
                        </label>
                    @endforeach
                    <button type="submit" data-reg-submit disabled
                        class="w-full cursor-not-allowed rounded-lg bg-white/15 py-3.5 font-oswald font-bold uppercase tracking-wide text-white/40 transition">Lanjutkan
                        ke WhatsApp</button>
                </form>
            </div>
        </div>
    </section>

    @include('partials.gallery-modal')
    @include('partials.footer')

</body>

</html>
