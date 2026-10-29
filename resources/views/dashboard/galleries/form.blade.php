@extends('layouts.dashboard')

@section('title', $isEdit ? 'Edit Foto Galeri' : 'Unggah Foto Galeri')
@section('header', $isEdit ? 'Edit Foto Galeri' : 'Unggah Foto Galeri')

@section('content')
    <div class="mb-4">
        <a href="{{ route('dashboard.galleries.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand hover:underline">
            &larr; Kembali ke Daftar Galeri
        </a>
    </div>

    <form method="POST"
          action="{{ $isEdit ? route('dashboard.galleries.update', $gallery) : route('dashboard.galleries.store') }}"
          enctype="multipart/form-data"
          class="mx-auto max-w-3xl space-y-5 rounded-2xl border border-brand/10 bg-white p-6 shadow-xs sm:p-8">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-brand">Judul Momen Dokumentasi <span class="text-[#c5221f]">*</span></label>
            <input type="text" name="title" value="{{ old('title', $gallery->title) }}" required maxlength="150" placeholder="Contoh: Sesi Latihan Taktik Kelompok Youth U-15" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-brand">Deskripsi Singkat (Caption)</label>
            <textarea name="caption" rows="3" maxlength="255" placeholder="Deskripsi singkat yang muncul saat foto diklik di galeri..." class="mt-1.5 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] p-4 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">{{ old('caption', $gallery->caption) }}</textarea>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Rasio Grid Foto <span class="text-[#c5221f]">*</span></label>
                <select name="grid_ratio" required class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-3.5 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
                    @foreach (['landscape' => 'Landscape (Mendatar)', 'portrait' => 'Portrait (Tegak)', 'square' => 'Square (Persegi)'] as $val => $label)
                        <option value="{{ $val }}" {{ old('grid_ratio', $gallery->grid_ratio) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <label class="flex min-h-11 w-full cursor-pointer items-center gap-3 rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5">
                    <input type="checkbox" name="is_highlighted_on_home" value="1" {{ old('is_highlighted_on_home', $gallery->is_highlighted_on_home) ? 'checked' : '' }} class="size-4 accent-brand">
                    <span class="text-xs font-semibold text-navy-950">Tampilkan di 6 Foto Momen Terbaik Beranda</span>
                </label>
            </div>
        </div>

        @if ($isEdit && $gallery->image_url)
            <div class="overflow-hidden rounded-xl border border-brand/10 bg-slate-100 p-2">
                <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" class="max-h-64 w-full rounded-lg object-contain">
            </div>
        @endif

        <div class="grid grid-cols-1 gap-4 rounded-xl border border-brand/10 bg-[#f8fafd] p-4 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-semibold text-navy-900/80">Unggah File Foto (JPG/PNG/WEBP, maks 4MB)</label>
                <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp" class="mt-1.5 block w-full text-xs text-navy-900 file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-brand file:px-3.5 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-navy-900">
            </div>
            <div>
                <label class="block text-xs font-semibold text-navy-900/80">Atau Gunakan URL Gambar</label>
                <input type="text" name="image_url" value="{{ old('image_url', $isEdit ? $gallery->image_path : '') }}" placeholder="https://..." class="mt-1.5 min-h-10 w-full rounded-xl border border-brand/15 bg-white px-3.5 py-2 text-xs text-navy-950 outline-none focus:border-brand">
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="min-h-12 w-full cursor-pointer rounded-xl bg-gold py-3.5 font-oswald text-sm font-bold uppercase tracking-wider text-brand transition hover:brightness-95">
                {{ $isEdit ? 'Simpan Perubahan Foto' : 'Simpan Foto ke Galeri' }}
            </button>
        </div>
    </form>
@endsection

