@extends('layouts.dashboard')

@section('title', $isEdit ? 'Edit FAQ' : 'Tambah FAQ')
@section('header', $isEdit ? 'Edit Pertanyaan FAQ' : 'Tambah Pertanyaan FAQ')

@section('content')
    <div class="mb-4">
        <a href="{{ route('dashboard.faqs.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand hover:underline">
            &larr; Kembali ke Daftar FAQ
        </a>
    </div>

    <form method="POST"
          action="{{ $isEdit ? route('dashboard.faqs.update', $faq) : route('dashboard.faqs.store') }}"
          class="mx-auto max-w-2xl space-y-5 rounded-2xl border border-brand/10 bg-white p-6 shadow-xs sm:p-8">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-brand">Pertanyaan <span class="text-[#c5221f]">*</span></label>
            <input type="text" name="question" value="{{ old('question', $faq->question) }}" required maxlength="255" placeholder="Contoh: Usia berapa anak bisa mulai bergabung?" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-brand">Jawaban Lengkap <span class="text-[#c5221f]">*</span></label>
            <textarea name="answer" rows="5" required maxlength="3000" placeholder="Tuliskan penjelasan jawaban yang jelas dan informatif..." class="mt-1.5 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] p-4 text-sm leading-relaxed text-navy-950 outline-none focus:border-brand focus:bg-white">{{ old('answer', $faq->answer) }}</textarea>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Urutan Tampil <span class="text-[#c5221f]">*</span></label>
                <input type="number" name="sort_order" min="0" max="999" value="{{ old('sort_order', $faq->sort_order) }}" required class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
            </div>

            <div class="flex items-end">
                <label class="flex min-h-11 w-full cursor-pointer items-center gap-3 rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $faq->is_active) ? 'checked' : '' }} class="size-4 accent-brand">
                    <span class="text-xs font-semibold text-navy-950">Tampilkan di Beranda</span>
                </label>
            </div>
        </div>

        <button type="submit" class="min-h-12 w-full cursor-pointer rounded-xl bg-gold py-3.5 font-oswald text-sm font-bold uppercase tracking-wider text-brand transition hover:brightness-95">
            {{ $isEdit ? 'Simpan Perubahan FAQ' : 'Simpan Pertanyaan FAQ' }}
        </button>
    </form>
@endsection

