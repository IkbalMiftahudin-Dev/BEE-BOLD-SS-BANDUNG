@extends('layouts.dashboard')

@section('title', $isEdit ? 'Edit Program Usia' : 'Tambah Program Usia')
@section('header', $isEdit ? 'Edit Program Usia' : 'Tambah Program Usia')

@section('content')
    <div class="mb-4">
        <a href="{{ route('dashboard.programs.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand hover:underline">
            &larr; Kembali ke Daftar Program Usia
        </a>
    </div>

    <form method="POST"
          action="{{ $isEdit ? route('dashboard.programs.update', $program) : route('dashboard.programs.store') }}"
          enctype="multipart/form-data"
          class="mx-auto max-w-3xl space-y-5 rounded-2xl border border-brand/10 bg-white p-6 shadow-xs sm:p-8">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Judul Program <span class="text-[#c5221f]">*</span></label>
                <input type="text" name="title" value="{{ old('title', $program->title) }}" required maxlength="100" placeholder="Contoh: Grassroot (U7 – U9)" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Rentang Usia (Badge) <span class="text-[#c5221f]">*</span></label>
                <input type="text" name="age_range" value="{{ old('age_range', $program->age_range) }}" required maxlength="50" placeholder="Contoh: U-7 – U-9" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Kategori Kelompok <span class="text-[#c5221f]">*</span></label>
                <select name="category_group" required class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-3.5 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
                    @foreach (['Grassroot', 'Youth'] as $grp)
                        <option value="{{ $grp }}" {{ old('category_group', $program->category_group) === $grp ? 'selected' : '' }}>{{ $grp }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Nama Pelatih <span class="text-[#c5221f]">*</span></label>
                <input type="text" name="coach_name" value="{{ old('coach_name', $program->coach_name) }}" required maxlength="100" placeholder="Contoh: Coach Iwan" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Urutan Tampil <span class="text-[#c5221f]">*</span></label>
                <input type="number" name="sort_order" min="0" max="999" value="{{ old('sort_order', $program->sort_order) }}" required class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-brand">Deskripsi Kurikulum &amp; Fokus Pembinaan <span class="font-normal normal-case text-navy-900/50">(Opsional)</span></label>
            <textarea name="description" rows="3" maxlength="1000" placeholder="Contoh: Pengenalan & teknik dasar (boleh dikosongkan)" class="mt-1.5 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] p-4 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">{{ old('description', $program->description) }}</textarea>
        </div>

        @if ($isEdit && $program->image_url)
            <div class="overflow-hidden rounded-xl border border-brand/10 bg-slate-100 p-2">
                <img src="{{ $program->image_url }}" alt="{{ $program->title }}" class="max-h-56 w-full rounded-lg object-contain">
            </div>
        @endif

        <div class="grid grid-cols-1 gap-4 rounded-xl border border-brand/10 bg-[#f8fafd] p-4 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-semibold text-navy-900/80">Unggah Foto Kartu Program (JPG/PNG/WEBP)</label>
                <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp" class="mt-1.5 block w-full text-xs text-navy-900 file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-brand file:px-3.5 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-navy-900">
            </div>
            <div>
                <label class="block text-xs font-semibold text-navy-900/80">Atau Gunakan URL Gambar</label>
                <input type="text" name="image_url" value="{{ old('image_url', $isEdit ? $program->image_path : '') }}" placeholder="https://..." class="mt-1.5 min-h-10 w-full rounded-xl border border-brand/15 bg-white px-3.5 py-2 text-xs text-navy-950 outline-none focus:border-brand">
            </div>
        </div>

        <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-3">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $program->is_active) ? 'checked' : '' }} class="size-4 accent-brand">
            <span class="text-xs font-semibold text-navy-950">Tampilkan Program Ini di Beranda</span>
        </label>

        <button type="submit" class="min-h-12 w-full cursor-pointer rounded-xl bg-gold py-3.5 font-oswald text-sm font-bold uppercase tracking-wider text-brand transition hover:brightness-95">
            {{ $isEdit ? 'Simpan Perubahan Program' : 'Simpan Program Usia' }}
        </button>
    </form>
@endsection

