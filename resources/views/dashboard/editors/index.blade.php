@extends('layouts.dashboard')

@section('title', 'Kelola Editor')
@section('header', 'Kelola Akun Editor')

@section('content')
    <div class="flex flex-col justify-between gap-3 rounded-2xl border border-brand/10 bg-white p-4 shadow-xs sm:flex-row sm:items-center sm:p-5">
        <div>
            <p class="text-sm font-semibold text-brand">Daftar Akun Editor Terdaftar</p>
            <p class="text-xs text-navy-900/55">Editor memiliki akses ke modul Berita, Kategori Berita, dan Galeri Dokumentasi.</p>
        </div>
        <a href="{{ route('dashboard.editors.create') }}" class="inline-flex min-h-10 w-full shrink-0 items-center justify-center gap-2 rounded-xl bg-gold px-5 py-2.5 font-oswald text-xs font-bold uppercase tracking-wider text-brand transition hover:brightness-95 sm:w-auto">
            + Tambah Akun Editor
        </a>
    </div>

    {{-- Mobile Stacked Cards (< 768px) --}}
    <div class="mt-5 space-y-3 md:hidden">
        @forelse ($editors as $editor)
            <div class="rounded-2xl border border-brand/10 bg-white p-4 shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-brand font-oswald text-sm font-bold text-gold">
                        {{ strtoupper(substr($editor->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <p class="truncate text-sm font-semibold text-navy-950">{{ $editor->name }}</p>
                            <span class="shrink-0 rounded-full bg-sky/30 px-2.5 py-0.5 font-oswald text-[10px] font-bold uppercase tracking-wider text-brand">Editor</span>
                        </div>
                        <p class="truncate text-xs text-navy-900/60">{{ $editor->email }}</p>
                    </div>
                </div>

                <div class="mt-3 grid grid-cols-2 gap-2 rounded-xl bg-[#f4f7fb] p-2.5 text-center text-xs">
                    <div>
                        <span class="block text-[10px] font-semibold uppercase text-navy-900/50">Artikel Berita</span>
                        <span class="font-oswald text-sm font-bold text-brand">{{ $editor->news_count }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-semibold uppercase text-navy-900/50">Foto Galeri</span>
                        <span class="font-oswald text-sm font-bold text-brand">{{ $editor->galleries_count }}</span>
                    </div>
                </div>

                <div class="mt-3 flex items-center justify-between gap-2 border-t border-brand/10 pt-3">
                    <span class="text-[11px] text-navy-900/45">Terdaftar {{ $editor->created_at?->locale('id')->translatedFormat('d M Y') }}</span>
                    <div class="inline-flex items-center gap-1.5">
                        <a href="{{ route('dashboard.editors.edit', $editor) }}" class="rounded-lg bg-brand px-3 py-1.5 text-xs font-semibold text-white hover:bg-navy-900">Edit</a>
                        <form method="POST" action="{{ route('dashboard.editors.destroy', $editor) }}" onsubmit="return confirm('Hapus akun editor {{ $editor->name }}? Seluruh berita dan foto galeri yang diunggah editor ini akan tetap aman dan dialihkan ke akun Admin Anda.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer rounded-lg border border-[#f87171]/40 bg-[#fce8e6] px-3 py-1.5 text-xs font-semibold text-[#c5221f] hover:bg-[#f87171]/25">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-brand/10 bg-white p-8 text-center text-sm text-navy-900/50">
                Belum ada akun Editor yang terdaftar.
            </div>
        @endforelse
    </div>

    {{-- Desktop Table (>= 768px) --}}
    <div class="mt-6 hidden overflow-hidden rounded-2xl border border-brand/10 bg-white shadow-xs md:block">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="border-b border-brand/10 bg-[#f4f7fb] text-xs font-bold uppercase tracking-wider text-brand">
                    <tr>
                        <th class="px-5 py-3.5">Nama Editor</th>
                        <th class="px-4 py-3.5">Alamat Email</th>
                        <th class="px-4 py-3.5">Kontribusi</th>
                        <th class="px-4 py-3.5">Tanggal Dibuat</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand/10">
                    @forelse ($editors as $editor)
                        <tr class="hover:bg-[#f8fafd]">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-brand font-oswald text-xs font-bold text-gold">
                                        {{ strtoupper(substr($editor->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-navy-950">{{ $editor->name }}</p>
                                        <span class="inline-block rounded-full bg-sky/30 px-2 py-0.5 font-oswald text-[10px] font-bold uppercase tracking-wider text-brand">Editor</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 font-medium text-navy-900/80">{{ $editor->email }}</td>
                            <td class="px-4 py-4 text-xs text-navy-900/75">
                                <span class="font-semibold text-brand">{{ $editor->news_count }}</span> Berita
                                <span class="mx-1 text-navy-900/30">&bull;</span>
                                <span class="font-semibold text-brand">{{ $editor->galleries_count }}</span> Foto Galeri
                            </td>
                            <td class="px-4 py-4 text-xs text-navy-900/60">
                                {{ $editor->created_at?->locale('id')->translatedFormat('j F Y') ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('dashboard.editors.edit', $editor) }}" class="rounded-lg bg-brand px-3 py-1.5 text-xs font-semibold text-white hover:bg-navy-900">Edit</a>
                                    <form method="POST" action="{{ route('dashboard.editors.destroy', $editor) }}" onsubmit="return confirm('Hapus akun editor {{ $editor->name }}? Seluruh berita dan foto galeri yang diunggah editor ini akan tetap aman dan dialihkan ke akun Admin Anda.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="cursor-pointer rounded-lg border border-[#f87171]/40 bg-[#fce8e6] px-3 py-1.5 text-xs font-semibold text-[#c5221f] hover:bg-[#f87171]/25">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-sm text-navy-900/50">Belum ada akun Editor yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

