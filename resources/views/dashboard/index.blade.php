@extends('layouts.dashboard')

@section('title', 'Ringkasan Dashboard')
@section('header', 'Ringkasan Dashboard')

@section('content')
    {{-- Banner Sambutan --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-brand via-[#004080] to-brand p-5 text-white shadow-md sm:p-8">
        <div class="relative z-10 flex flex-col justify-between gap-5 lg:flex-row lg:items-center">
            <div>
                <span class="inline-block rounded-full bg-gold px-3 py-1 font-oswald text-[11px] font-bold uppercase tracking-wider text-brand">
                    Akses {{ auth()->user()->role->label() }}
                </span>
                <h2 class="mt-2.5 font-oswald text-xl font-bold uppercase tracking-wide sm:text-3xl">
                    Selamat Datang, {{ auth()->user()->name }}
                </h2>
                <p class="mt-1.5 max-w-xl text-xs leading-relaxed text-white/80 sm:text-sm">
                    Seluruh perubahan yang Anda simpan di panel ini langsung diperbarui secara otomatis pada halaman publik Bee Bold Soccer School.
                </p>
            </div>
            <div class="flex flex-col gap-2.5 sm:flex-row sm:flex-wrap">
                <a href="{{ route('dashboard.news.create') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-gold px-5 py-2.5 text-center font-oswald text-xs font-bold uppercase tracking-wider text-brand transition hover:brightness-95">
                    + Tulis Berita Baru
                </a>
                <a href="{{ route('dashboard.galleries.create') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-white/25 bg-white/10 px-5 py-2.5 text-center font-oswald text-xs font-bold uppercase tracking-wider text-white transition hover:bg-white/20">
                    + Unggah Foto Galeri
                </a>
            </div>
        </div>
    </div>

    {{-- Statistik Kartu --}}
    <div class="mt-5 grid grid-cols-2 gap-3 sm:mt-6 sm:gap-4 {{ auth()->user()->isAdmin() ? 'lg:grid-cols-4' : 'sm:grid-cols-3' }}">
        <a href="{{ route('dashboard.news.index') }}" class="group rounded-2xl border border-brand/10 bg-white p-4 shadow-xs transition hover:border-brand/30 hover:shadow-md sm:p-5">
            <div class="flex flex-wrap items-center justify-between gap-1.5">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-navy-900/55 sm:text-xs">Total Berita</p>
                <span class="rounded-lg bg-gold/25 px-2 py-0.5 font-oswald text-[10px] font-bold text-brand sm:text-xs">{{ $stats['news_published'] }} Terbit</span>
            </div>
            <p class="mt-2.5 font-oswald text-3xl font-bold text-brand sm:mt-3 sm:text-4xl">{{ $stats['news_total'] }}</p>
            <p class="mt-1.5 text-[11px] font-medium text-brand/70 group-hover:text-brand sm:mt-2 sm:text-xs">Kelola berita &rarr;</p>
        </a>

        <a href="{{ route('dashboard.categories.index') }}" class="group rounded-2xl border border-brand/10 bg-white p-4 shadow-xs transition hover:border-brand/30 hover:shadow-md sm:p-5">
            <div class="flex flex-wrap items-center justify-between gap-1.5">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-navy-900/55 sm:text-xs">Kategori</p>
                <span class="rounded-lg bg-sky/25 px-2 py-0.5 font-oswald text-[10px] font-bold text-brand sm:text-xs">Aktif</span>
            </div>
            <p class="mt-2.5 font-oswald text-3xl font-bold text-brand sm:mt-3 sm:text-4xl">{{ $stats['categories'] }}</p>
            <p class="mt-1.5 text-[11px] font-medium text-brand/70 group-hover:text-brand sm:mt-2 sm:text-xs">Kelola kategori &rarr;</p>
        </a>

        <a href="{{ route('dashboard.galleries.index') }}" class="group rounded-2xl border border-brand/10 bg-white p-4 shadow-xs transition hover:border-brand/30 hover:shadow-md sm:p-5 {{ auth()->user()->isAdmin() ? '' : 'col-span-2 sm:col-span-1' }}">
            <div class="flex flex-wrap items-center justify-between gap-1.5">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-navy-900/55 sm:text-xs">Foto Galeri</p>
                <span class="rounded-lg bg-gold/25 px-2 py-0.5 font-oswald text-[10px] font-bold text-brand sm:text-xs">{{ $stats['galleries_home'] }}/6 Beranda</span>
            </div>
            <p class="mt-2.5 font-oswald text-3xl font-bold text-brand sm:mt-3 sm:text-4xl">{{ $stats['galleries'] }}</p>
            <p class="mt-1.5 text-[11px] font-medium text-brand/70 group-hover:text-brand sm:mt-2 sm:text-xs">Kelola galeri &rarr;</p>
        </a>

        @if (auth()->user()->isAdmin())
            <a href="{{ route('dashboard.editors.index') }}" class="group rounded-2xl border border-brand/10 bg-white p-4 shadow-xs transition hover:border-brand/30 hover:shadow-md sm:p-5">
                <div class="flex flex-wrap items-center justify-between gap-1.5">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-navy-900/55 sm:text-xs">Akun Editor</p>
                    <span class="rounded-lg bg-sky/25 px-2 py-0.5 font-oswald text-[10px] font-bold text-brand sm:text-xs">Tim Redaksi</span>
                </div>
                <p class="mt-2.5 font-oswald text-3xl font-bold text-brand sm:mt-3 sm:text-4xl">{{ $stats['editors'] }}</p>
                <p class="mt-1.5 text-[11px] font-medium text-brand/70 group-hover:text-brand sm:mt-2 sm:text-xs">Kelola editor &rarr;</p>
            </a>
        @endif
    </div>

    @if (auth()->user()->isAdmin())
        <div class="mt-4 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            <a href="{{ route('dashboard.programs.index') }}" class="flex items-center justify-between gap-2 rounded-2xl border border-brand/10 bg-white p-4 shadow-xs transition hover:border-brand/30 sm:p-5">
                <div class="min-w-0">
                    <p class="truncate text-[11px] font-semibold uppercase tracking-wider text-navy-900/55">Program Usia</p>
                    <p class="mt-1 font-oswald text-lg font-bold text-brand sm:text-2xl">{{ $stats['programs'] }} Program</p>
                </div>
                <span class="shrink-0 rounded-xl bg-[#eaf2fb] px-2.5 py-1.5 text-xs font-bold text-brand">&rarr;</span>
            </a>

            <a href="{{ route('dashboard.schedules.index') }}" class="flex items-center justify-between gap-2 rounded-2xl border border-brand/10 bg-white p-4 shadow-xs transition hover:border-brand/30 sm:p-5">
                <div class="min-w-0">
                    <p class="truncate text-[11px] font-semibold uppercase tracking-wider text-navy-900/55">Jadwal Latihan</p>
                    <p class="mt-1 font-oswald text-lg font-bold text-brand sm:text-2xl">{{ $stats['schedules'] }} Sesi</p>
                </div>
                <span class="shrink-0 rounded-xl bg-[#eaf2fb] px-2.5 py-1.5 text-xs font-bold text-brand">&rarr;</span>
            </a>

            <a href="{{ route('dashboard.faqs.index') }}" class="flex items-center justify-between gap-2 rounded-2xl border border-brand/10 bg-white p-4 shadow-xs transition hover:border-brand/30 sm:p-5">
                <div class="min-w-0">
                    <p class="truncate text-[11px] font-semibold uppercase tracking-wider text-navy-900/55">Daftar FAQ</p>
                    <p class="mt-1 font-oswald text-lg font-bold text-brand sm:text-2xl">{{ $stats['faqs'] }} Aktif</p>
                </div>
                <span class="shrink-0 rounded-xl bg-[#eaf2fb] px-2.5 py-1.5 text-xs font-bold text-brand">&rarr;</span>
            </a>

            <a href="{{ route('dashboard.fees.index') }}" class="flex items-center justify-between gap-2 rounded-2xl border border-brand/10 bg-white p-4 shadow-xs transition hover:border-brand/30 sm:p-5">
                <div class="min-w-0">
                    <p class="truncate text-[11px] font-semibold uppercase tracking-wider text-navy-900/55">Biaya Daftar</p>
                    <p class="mt-1 font-oswald text-lg font-bold text-brand sm:text-2xl">{{ $stats['fees'] }} Paket</p>
                </div>
                <span class="shrink-0 rounded-xl bg-[#eaf2fb] px-2.5 py-1.5 text-xs font-bold text-brand">&rarr;</span>
            </a>
        </div>
    @endif

    {{-- Berita & Galeri Terbaru --}}
    <div class="mt-6 grid grid-cols-1 gap-6 sm:mt-8 lg:grid-cols-12">
        <div class="rounded-2xl border border-brand/10 bg-white p-4 shadow-xs sm:p-6 lg:col-span-7">
            <div class="flex items-center justify-between gap-3 border-b border-brand/10 pb-3.5">
                <div>
                    <h3 class="font-oswald text-base font-bold uppercase tracking-wide text-brand sm:text-lg">Berita Terbaru</h3>
                    <p class="text-xs text-navy-900/55">5 artikel terakhir yang diperbarui</p>
                </div>
                <a href="{{ route('dashboard.news.index') }}" class="shrink-0 text-xs font-semibold text-brand hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-brand/10">
                @forelse ($latestNews as $item)
                    <div class="flex items-start justify-between gap-3 py-3.5 sm:items-center">
                        <div class="flex min-w-0 flex-1 items-start gap-3 sm:items-center">
                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="h-12 w-16 shrink-0 rounded-lg border border-brand/10 object-cover">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="rounded bg-brand/10 px-2 py-0.5 text-[10px] font-bold uppercase text-brand">{{ $item->category->name }}</span>
                                    @if ($item->is_featured)
                                        <span class="rounded bg-gold px-2 py-0.5 text-[10px] font-bold uppercase text-brand">Utama</span>
                                    @endif
                                </div>
                                <p class="line-clamp-2 pt-1 text-xs font-semibold leading-snug text-navy-950 sm:line-clamp-1 sm:text-sm">{{ $item->title }}</p>
                                <p class="pt-0.5 text-[11px] text-navy-900/50">{{ $item->published_date_label ?: 'Draft' }} &middot; {{ $item->author->name }}</p>
                            </div>
                        </div>
                        <a href="{{ route('dashboard.news.edit', $item) }}" class="shrink-0 rounded-lg border border-brand/15 bg-[#f4f7fb] px-3 py-1.5 text-xs font-semibold text-brand hover:bg-[#eaf2fb]">Edit</a>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-navy-900/50">Belum ada berita.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-brand/10 bg-white p-4 shadow-xs sm:p-6 lg:col-span-5">
            <div class="flex items-center justify-between gap-3 border-b border-brand/10 pb-3.5">
                <div>
                    <h3 class="font-oswald text-base font-bold uppercase tracking-wide text-brand sm:text-lg">Galeri Terbaru</h3>
                    <p class="text-xs text-navy-900/55">6 dokumentasi terakhir</p>
                </div>
                <a href="{{ route('dashboard.galleries.index') }}" class="shrink-0 text-xs font-semibold text-brand hover:underline">Lihat Semua</a>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-2.5 sm:grid-cols-3 sm:gap-3">
                @forelse ($latestGalleries as $photo)
                    <a href="{{ route('dashboard.galleries.edit', $photo) }}" class="group relative block h-24 overflow-hidden rounded-xl border border-brand/10">
                        <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="size-full object-cover transition duration-300 group-hover:scale-105">
                        @if ($photo->is_highlighted_on_home)
                            <span class="absolute right-1.5 top-1.5 rounded bg-gold px-1.5 py-0.5 text-[9px] font-bold uppercase text-brand">Beranda</span>
                        @endif
                    </a>
                @empty
                    <p class="col-span-full py-8 text-center text-sm text-navy-900/50">Belum ada foto galeri.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
