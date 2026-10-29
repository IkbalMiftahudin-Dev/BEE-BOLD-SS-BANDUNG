@extends('layouts.dashboard')

@section('title', $isEdit ? 'Edit Biaya Pendaftaran' : 'Tambah Biaya Pendaftaran')
@section('header', $isEdit ? 'Edit Biaya Pendaftaran' : 'Tambah Biaya Pendaftaran')

@section('content')
    <div class="mb-4">
        <a href="{{ route('dashboard.fees.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand hover:underline">
            &larr; Kembali ke Daftar Biaya Pendaftaran
        </a>
    </div>

    <form method="POST"
          action="{{ $isEdit ? route('dashboard.fees.update', $fee) : route('dashboard.fees.store') }}"
          class="mx-auto max-w-2xl space-y-5 rounded-2xl border border-brand/10 bg-white p-6 shadow-xs sm:p-8">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-brand">Label Komponen Biaya <span class="text-[#c5221f]">*</span></label>
            <input type="text" name="label" value="{{ old('label', $fee->label) }}" required maxlength="100" placeholder="Contoh: Biaya Bulanan Grassroot (U7 - U12)" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-brand">Nominal Tampilan <span class="text-[#c5221f]">*</span></label>
            <input type="text" name="amount_text" value="{{ old('amount_text', $fee->amount_text) }}" required maxlength="100" placeholder="Contoh: Mulai Rp 300.000 / bulan" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Jenis Biaya <span class="text-[#c5221f]">*</span></label>
                <select name="fee_type" required class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-3.5 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
                    <option value="one_time" {{ old('fee_type', $fee->fee_type) === 'one_time' ? 'selected' : '' }}>Sekali Bayar (Atas)</option>
                    <option value="monthly" {{ old('fee_type', $fee->fee_type) === 'monthly' ? 'selected' : '' }}>Iuran Bulanan (Grid)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Urutan Tampil <span class="text-[#c5221f]">*</span></label>
                <input type="number" name="sort_order" min="0" max="999" value="{{ old('sort_order', $fee->sort_order) }}" required class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
            </div>

            <div class="flex items-end">
                <label class="flex min-h-11 w-full cursor-pointer items-center gap-3 rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $fee->is_active) ? 'checked' : '' }} class="size-4 accent-brand">
                    <span class="text-xs font-semibold text-navy-950">Aktif</span>
                </label>
            </div>
        </div>

        <button type="submit" class="min-h-12 w-full cursor-pointer rounded-xl bg-gold py-3.5 font-oswald text-sm font-bold uppercase tracking-wider text-brand transition hover:brightness-95">
            {{ $isEdit ? 'Simpan Perubahan Biaya' : 'Simpan Komponen Biaya' }}
        </button>
    </form>
@endsection

