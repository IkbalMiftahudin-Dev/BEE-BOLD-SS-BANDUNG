@extends('layouts.dashboard')

@section('title', 'Kategori Berita')
@section('header', 'Kategori Berita')

@section('content')
    <div class="grid grid-cols-1 gap-5 sm:gap-6 lg:grid-cols-12">
        <div class="rounded-2xl border border-brand/10 bg-white p-4 shadow-xs sm:p-6 lg:col-span-4">
            <h2 class="font-oswald text-lg font-bold uppercase tracking-wide text-brand">Tambah Kategori Baru</h2>
            <p class="mt-1 text-xs text-navy-900/55">Slug URL dibuat secara otomatis dari nama kategori.</p>

            <form method="POST" action="{{ route('dashboard.categories.store') }}" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand">Nama Kategori <span class="text-[#c5221f]">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required maxlength="50" placeholder="Contoh: Prestasi" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
                </div>
                <button type="submit" class="min-h-11 w-full cursor-pointer rounded-xl bg-gold py-2.5 font-oswald text-xs font-bold uppercase tracking-wider text-brand transition hover:brightness-95">
                    + Simpan Kategori
                </button>
            </form>
        </div>

        <div class="lg:col-span-8">
            {{-- Mobile Cards (< 768px) --}}
            <div class="space-y-3 md:hidden">
                @foreach ($categories as $cat)
                    <div class="rounded-2xl border border-brand/10 bg-white p-4 shadow-xs">
                        <div class="flex items-center justify-between gap-2">
                            <code class="rounded bg-[#eaf2fb] px-2 py-0.5 text-[11px] text-brand">{{ $cat->slug }}</code>
                            <span class="text-xs font-semibold text-navy-900/70">{{ $cat->news_count }} artikel</span>
                        </div>
                        <form id="cat-mobile-form-{{ $cat->id }}" method="POST" action="{{ route('dashboard.categories.update', $cat) }}" class="mt-3">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $cat->name }}" required maxlength="50" class="min-h-10 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-3.5 py-2 text-xs font-semibold text-navy-950 outline-none focus:border-brand focus:bg-white">
                        </form>
                        <div class="mt-3 flex items-center justify-end gap-2">
                            <button type="submit" form="cat-mobile-form-{{ $cat->id }}" class="cursor-pointer rounded-lg bg-brand px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-navy-900">Simpan</button>
                            <form method="POST" action="{{ route('dashboard.categories.destroy', $cat) }}" onsubmit="return confirm('Hapus kategori berita ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cursor-pointer rounded-lg border border-[#f87171]/40 bg-[#fce8e6] px-3.5 py-1.5 text-xs font-semibold text-[#c5221f] hover:bg-[#f87171]/25">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Desktop Table (>= 768px) --}}
            <div class="hidden overflow-hidden rounded-2xl border border-brand/10 bg-white shadow-xs md:block">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[540px] text-left text-sm">
                        <thead class="border-b border-brand/10 bg-[#f4f7fb] text-xs font-bold uppercase tracking-wider text-brand">
                            <tr>
                                <th class="px-5 py-3.5">Nama Kategori</th>
                                <th class="px-4 py-3.5">Slug</th>
                                <th class="px-4 py-3.5">Jumlah Berita</th>
                                <th class="px-5 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-brand/10">
                            @foreach ($categories as $cat)
                                <tr class="hover:bg-[#f8fafd]">
                                    <td class="px-5 py-3.5">
                                        <form id="cat-form-{{ $cat->id }}" method="POST" action="{{ route('dashboard.categories.update', $cat) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="name" value="{{ $cat->name }}" required maxlength="50" class="min-h-9 w-full max-w-[200px] rounded-lg border border-brand/15 bg-[#f4f7fb] px-3 py-1.5 text-xs font-semibold text-navy-950 outline-none focus:border-brand focus:bg-white">
                                        </form>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <code class="rounded bg-[#eaf2fb] px-2 py-1 text-xs text-brand">{{ $cat->slug }}</code>
                                    </td>
                                    <td class="px-4 py-3.5 text-xs font-semibold text-navy-900/70">
                                        {{ $cat->news_count }} artikel
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <button type="submit" form="cat-form-{{ $cat->id }}" class="cursor-pointer rounded-lg bg-brand px-3 py-1.5 text-xs font-semibold text-white hover:bg-navy-900">Simpan</button>
                                            <form method="POST" action="{{ route('dashboard.categories.destroy', $cat) }}" onsubmit="return confirm('Hapus kategori berita ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="cursor-pointer rounded-lg border border-[#f87171]/40 bg-[#fce8e6] px-3 py-1.5 text-xs font-semibold text-[#c5221f] hover:bg-[#f87171]/25">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
