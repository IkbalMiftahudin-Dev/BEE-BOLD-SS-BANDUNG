@extends('layouts.dashboard')

@section('title', $isEdit ? 'Edit Berita' : 'Tulis Berita Baru')
@section('header', $isEdit ? 'Edit Berita' : 'Tulis Berita Baru')

@section('content')
    <div class="mb-4">
        <a href="{{ route('dashboard.news.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand hover:underline">
            &larr; Kembali ke Daftar Berita
        </a>
    </div>

    <form method="POST"
          action="{{ $isEdit ? route('dashboard.news.update', $article) : route('dashboard.news.store') }}"
          enctype="multipart/form-data"
          class="grid grid-cols-1 gap-5 sm:gap-6 lg:grid-cols-12">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="space-y-5 rounded-2xl border border-brand/10 bg-white p-4 shadow-xs sm:p-6 lg:col-span-8">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Judul Berita <span class="text-[#c5221f]">*</span></label>
                <input type="text" name="title" value="{{ old('title', $article->title) }}" required maxlength="200" placeholder="Contoh: Bee Bold Raih Juara 1 Turnamen U-14 Tingkat Kota Bandung" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Paragraf Pembuka / Ringkasan <span class="text-[#c5221f]">*</span></label>
                <textarea name="lead_paragraph" rows="3" required maxlength="1000" placeholder="Ringkasan pembuka yang tampil di kartu berita dan bagian atas halaman detail..." class="mt-1.5 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] p-4 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">{{ old('lead_paragraph', $article->lead_paragraph) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Isi Lengkap Berita <span class="text-[#c5221f]">*</span></label>
                <p class="mt-0.5 text-xs text-navy-900/55">Pisahkan antar paragraf dengan satu baris kosong (Enter 2x).</p>
                <textarea name="content" rows="12" required placeholder="Tulis isi berita lengkap di sini..." class="mt-2 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] p-4 text-sm leading-relaxed text-navy-950 outline-none focus:border-brand focus:bg-white">{{ old('content', $article->content) }}</textarea>
            </div>
        </div>

        <div class="space-y-5 lg:col-span-4">
            <div class="space-y-4 rounded-2xl border border-brand/10 bg-white p-4 shadow-xs sm:p-6">
                <h3 class="font-oswald text-base font-bold uppercase tracking-wide text-brand">Pengaturan Publikasi</h3>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand">Kategori <span class="text-[#c5221f]">*</span></label>
                    <select name="category_id" required class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-3.5 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (int) old('category_id', $article->category_id) === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="rounded-xl border border-brand/10 bg-[#eaf2fb]/60 p-3.5">
                    <span class="block text-[11px] font-bold uppercase tracking-wider text-brand">Waktu Publikasi (Real-Time)</span>
                    <p class="mt-1 text-xs font-semibold text-navy-950">
                        @if ($isEdit && $article->published_at)
                            {{ $article->published_at->locale('id')->translatedFormat('j F Y, H:i') }} WIB
                        @else
                            Otomatis dicatat saat berita diterbitkan (WIB)
                        @endif
                    </p>
                </div>

                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-brand/10 bg-[#f4f7fb] p-3.5">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }} class="size-4 shrink-0 accent-brand">
                    <span class="text-xs font-semibold text-navy-950">Publikasikan di Website</span>
                </label>

                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-brand/10 bg-[#f4f7fb] p-3.5">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $article->is_featured) ? 'checked' : '' }} class="size-4 shrink-0 accent-brand">
                    <span class="text-xs font-semibold text-navy-950">Jadikan Highlight Berita Utama</span>
                </label>
            </div>

            <div class="space-y-4 rounded-2xl border border-brand/10 bg-white p-4 shadow-xs sm:p-6">
                <h3 class="font-oswald text-base font-bold uppercase tracking-wide text-brand">Foto Utama (Thumbnail)</h3>

                @if ($isEdit && $article->thumbnail_url)
                    <div class="overflow-hidden rounded-xl border border-brand/10">
                        <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}" class="h-40 w-full object-cover">
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-semibold text-navy-900/75">Unggah File Gambar (JPG/PNG/WEBP, maks 4MB)</label>
                    <input type="file" name="thumbnail_file" accept="image/jpeg,image/png,image/webp" class="mt-1.5 block w-full text-xs text-navy-900 file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-brand file:px-3.5 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-navy-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-navy-900/75">Atau Gunakan URL Gambar</label>
                    <input type="text" name="thumbnail_url" value="{{ old('thumbnail_url', $isEdit ? $article->thumbnail_path : '') }}" placeholder="https://..." class="mt-1.5 min-h-10 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-3.5 py-2 text-xs text-navy-950 outline-none focus:border-brand focus:bg-white">
                </div>
            </div>

            <button type="submit" class="min-h-12 w-full cursor-pointer rounded-xl bg-gold py-3.5 font-oswald text-sm font-bold uppercase tracking-wider text-brand shadow-xs transition hover:brightness-95">
                {{ $isEdit ? 'Simpan Perubahan Berita' : 'Terbitkan Berita' }}
            </button>
        </div>
    </form>
@endsection
