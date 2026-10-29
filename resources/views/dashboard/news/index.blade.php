@extends('layouts.dashboard')

@section('title', 'Manajemen Berita')
@section('header', 'Berita & Artikel')

@section('content')
    <div class="flex flex-col justify-between gap-3 rounded-2xl border border-brand/10 bg-white p-4 shadow-xs sm:flex-row sm:items-center sm:p-5">
        <form method="GET" action="{{ route('dashboard.news.index') }}" class="flex w-full flex-col gap-2.5 sm:flex-1 sm:flex-row sm:flex-wrap sm:items-center">
            <select name="category_id" onchange="this.form.submit()" class="min-h-10 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-3.5 py-2 text-xs font-medium text-navy-950 outline-none focus:border-brand sm:w-auto">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $activeCategoryId === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <div class="flex w-full gap-2 sm:flex-1 sm:max-w-sm">
                <input type="search" name="q" value="{{ $search }}" placeholder="Cari judul atau isi berita..." class="min-h-10 w-full flex-1 rounded-xl border border-brand/15 bg-[#f4f7fb] px-3.5 py-2 text-xs text-navy-950 outline-none placeholder:text-navy-900/40 focus:border-brand">
                <button type="submit" class="min-h-10 shrink-0 cursor-pointer rounded-xl bg-[#eaf2fb] px-4 py-2 text-xs font-semibold text-brand hover:bg-sky/30">Cari</button>
            </div>
        </form>

        <a href="{{ route('dashboard.news.create') }}" class="inline-flex min-h-10 w-full shrink-0 items-center justify-center gap-2 rounded-xl bg-gold px-5 py-2.5 font-oswald text-xs font-bold uppercase tracking-wider text-brand transition hover:brightness-95 sm:w-auto">
            + Tulis Berita Baru
        </a>
    </div>

    {{-- Mobile Stacked Cards (< 768px) --}}
    <div class="mt-5 space-y-3.5 md:hidden">
        @forelse ($news as $item)
            <div class="overflow-hidden rounded-2xl border border-brand/10 bg-white p-4 shadow-xs">
                <div class="flex items-start gap-3">
                    <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="h-16 w-20 shrink-0 rounded-xl border border-brand/10 object-cover">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <span class="rounded-full bg-brand/10 px-2.5 py-0.5 font-oswald text-[10px] font-bold uppercase text-brand">{{ $item->category->name }}</span>
                            @if ($item->is_featured)
                                <span class="rounded bg-gold px-2 py-0.5 font-oswald text-[10px] font-bold uppercase text-brand">Highlight</span>
                            @endif
                            @if ($item->is_published)
                                <span class="rounded-full bg-[#e6f4ea] px-2 py-0.5 text-[10px] font-semibold text-[#137333]">Publik</span>
                            @else
                                <span class="rounded-full bg-slate-200 px-2 py-0.5 text-[10px] font-semibold text-slate-700">Draft</span>
                            @endif
                        </div>
                        <p class="mt-1.5 line-clamp-2 text-sm font-semibold leading-snug text-navy-950">{{ $item->title }}</p>
                    </div>
                </div>

                <div class="mt-3 flex flex-wrap items-center justify-between gap-2 border-t border-brand/10 pt-3 text-[11px] text-navy-900/60">
                    <div class="min-w-0 truncate">
                        <span class="font-medium text-navy-950">{{ $item->author->name }}</span>
                        <span class="mx-1">&bull;</span>
                        <span>{{ $item->published_date_label ?: 'Real-time' }}</span>
                    </div>
                    <div class="flex shrink-0 items-center gap-1.5">
                        <a href="{{ route('berita.show', $item->slug) }}" target="_blank" rel="noopener" class="rounded-lg border border-brand/15 bg-white px-2.5 py-1.5 text-xs font-medium text-brand hover:bg-[#eaf2fb]">Lihat</a>
                        <a href="{{ route('dashboard.news.edit', $item) }}" class="rounded-lg bg-brand px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-navy-900">Edit</a>
                        <form method="POST" action="{{ route('dashboard.news.destroy', $item) }}" onsubmit="return confirm('Hapus artikel berita ini secara permanen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer rounded-lg border border-[#f87171]/40 bg-[#fce8e6] px-2.5 py-1.5 text-xs font-semibold text-[#c5221f] hover:bg-[#f87171]/25">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-brand/10 bg-white p-8 text-center text-sm text-navy-900/50">
                Tidak ada artikel berita yang ditemukan.
            </div>
        @endforelse
    </div>

    {{-- Tablet & Desktop Table (>= 768px) --}}
    <div class="mt-6 hidden overflow-hidden rounded-2xl border border-brand/10 bg-white shadow-xs md:block">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="border-b border-brand/10 bg-[#f4f7fb] text-xs font-bold uppercase tracking-wider text-brand">
                    <tr>
                        <th class="px-5 py-3.5">Artikel</th>
                        <th class="px-4 py-3.5">Kategori</th>
                        <th class="px-4 py-3.5">Penulis &amp; Waktu</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand/10">
                    @forelse ($news as $item)
                        <tr class="hover:bg-[#f8fafd]">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3.5">
                                    <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="h-14 w-20 shrink-0 rounded-lg border border-brand/10 object-cover">
                                    <div class="max-w-md">
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            @if ($item->is_featured)
                                                <span class="rounded bg-gold px-2 py-0.5 font-oswald text-[10px] font-bold uppercase text-brand">Highlight Utama</span>
                                            @endif
                                        </div>
                                        <p class="line-clamp-1 font-semibold text-navy-950">{{ $item->title }}</p>
                                        <p class="line-clamp-1 pt-0.5 text-xs text-navy-900/55">{{ $item->lead_paragraph }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-block rounded-full bg-brand/10 px-3 py-1 font-oswald text-xs font-bold uppercase text-brand">{{ $item->category->name }}</span>
                            </td>
                            <td class="px-4 py-4 text-xs text-navy-900/70">
                                <p class="font-medium text-navy-950">{{ $item->author->name }}</p>
                                <p class="text-navy-900/50">{{ $item->published_date_label }}</p>
                            </td>
                            <td class="px-4 py-4">
                                @if ($item->is_published)
                                    <span class="inline-block rounded-full bg-[#e6f4ea] px-2.5 py-1 text-[11px] font-semibold text-[#137333]">Dipublikasikan</span>
                                @else
                                    <span class="inline-block rounded-full bg-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-700">Draft</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('berita.show', $item->slug) }}" target="_blank" rel="noopener" class="rounded-lg border border-brand/15 bg-white px-3 py-1.5 text-xs font-medium text-brand hover:bg-[#eaf2fb]">Lihat</a>
                                    <a href="{{ route('dashboard.news.edit', $item) }}" class="rounded-lg bg-brand px-3 py-1.5 text-xs font-semibold text-white hover:bg-navy-900">Edit</a>
                                    <form method="POST" action="{{ route('dashboard.news.destroy', $item) }}" onsubmit="return confirm('Hapus artikel berita ini secara permanen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="cursor-pointer rounded-lg border border-[#f87171]/40 bg-[#fce8e6] px-3 py-1.5 text-xs font-semibold text-[#c5221f] hover:bg-[#f87171]/25">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-sm text-navy-900/50">Tidak ada artikel berita yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($news->hasPages())
        <div class="mt-4 rounded-2xl border border-brand/10 bg-white px-4 py-3 shadow-xs sm:px-5 sm:py-4">
            {{ $news->links() }}
        </div>
    @endif
@endsection
