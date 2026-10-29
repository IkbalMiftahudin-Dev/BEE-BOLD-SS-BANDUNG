@extends('layouts.dashboard')

@section('title', 'Manajemen Galeri')
@section('header', 'Galeri Dokumentasi')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-brand/10 bg-white p-5 shadow-xs">
        <div>
            <p class="text-sm font-semibold text-brand">Daftar Koleksi Dokumentasi</p>
            <p class="text-xs text-navy-900/55">Foto dengan tanda <span class="font-bold text-brand">Tampil di Beranda</span> akan muncul di 6 grid Momen Terbaik homepage.</p>
        </div>
        <a href="{{ route('dashboard.galleries.create') }}" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-gold px-5 py-2.5 font-oswald text-xs font-bold uppercase tracking-wider text-brand transition hover:brightness-95">
            + Unggah Foto Baru
        </a>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($galleries as $photo)
            <div class="flex flex-col overflow-hidden rounded-2xl border border-brand/10 bg-white shadow-xs">
                <div class="relative h-52 overflow-hidden bg-slate-100">
                    <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="size-full object-cover">
                    <div class="absolute left-3 top-3 flex flex-wrap gap-1.5">
                        <span class="rounded-full bg-brand/90 px-2.5 py-1 font-oswald text-[10px] font-bold uppercase tracking-wider text-white">{{ $photo->grid_ratio }}</span>
                        @if ($photo->is_highlighted_on_home)
                            <span class="rounded-full bg-gold px-2.5 py-1 font-oswald text-[10px] font-bold uppercase tracking-wider text-brand">Tampil di Beranda</span>
                        @endif
                    </div>
                </div>
                <div class="flex flex-1 flex-col p-5">
                    <h3 class="font-oswald text-base font-bold uppercase text-navy-950">{{ $photo->title }}</h3>
                    <p class="mt-1 line-clamp-2 flex-1 text-xs leading-relaxed text-navy-900/60">{{ $photo->caption ?: 'Tanpa deskripsi singkat.' }}</p>
                    <div class="mt-4 flex items-center justify-between border-t border-brand/10 pt-3.5">
                        <span class="text-[11px] text-navy-900/45">Oleh: {{ $photo->uploader?->name ?? 'Admin' }}</span>
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('dashboard.galleries.edit', $photo) }}" class="rounded-lg bg-brand px-3 py-1.5 text-xs font-semibold text-white hover:bg-navy-900">Edit</a>
                            <form method="POST" action="{{ route('dashboard.galleries.destroy', $photo) }}" onsubmit="return confirm('Hapus foto dokumentasi ini dari galeri?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cursor-pointer rounded-lg border border-[#f87171]/40 bg-[#fce8e6] px-3 py-1.5 text-xs font-semibold text-[#c5221f] hover:bg-[#f87171]/25">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-2xl border border-brand/10 bg-white p-12 text-center text-sm text-navy-900/50">
                Belum ada foto dokumentasi di galeri.
            </div>
        @endforelse
    </div>

    @if ($galleries->hasPages())
        <div class="mt-6">
            {{ $galleries->links() }}
        </div>
    @endif
@endsection

