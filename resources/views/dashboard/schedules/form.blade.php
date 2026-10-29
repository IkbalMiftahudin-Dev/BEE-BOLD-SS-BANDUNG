@extends('layouts.dashboard')

@section('title', $isEdit ? 'Edit Jadwal Latihan' : 'Tambah Jadwal Latihan')
@section('header', $isEdit ? 'Edit Jadwal Latihan' : 'Tambah Jadwal Latihan')

@section('content')
    <div class="mb-4">
        <a href="{{ route('dashboard.schedules.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand hover:underline">
            &larr; Kembali ke Daftar Jadwal
        </a>
    </div>

    <form method="POST"
          action="{{ $isEdit ? route('dashboard.schedules.update', $schedule) : route('dashboard.schedules.store') }}"
          class="mx-auto max-w-2xl space-y-5 rounded-2xl border border-brand/10 bg-white p-6 shadow-xs sm:p-8">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Hari Latihan <span class="text-[#c5221f]">*</span></label>
                <select name="day_of_week" required class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-3.5 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
                    @foreach ($days as $day)
                        <option value="{{ $day }}" {{ old('day_of_week', $schedule->day_of_week) === $day ? 'selected' : '' }}>{{ $day }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Slot Waktu <span class="text-[#c5221f]">*</span></label>
                <input type="text" name="time_slot" value="{{ old('time_slot', $schedule->time_slot) }}" required maxlength="50" placeholder="Contoh: 16:00 – 18:00 atau On Schedule" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Kelompok Usia (Target) <span class="text-[#c5221f]">*</span></label>
                <input type="text" name="target_category" value="{{ old('target_category', $schedule->target_category) }}" required maxlength="100" placeholder="Contoh: U-7 – U-9" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Tipe Aktivitas (Badge) <span class="text-[#c5221f]">*</span></label>
                <select name="activity_type" required class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-3.5 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
                    @foreach ($activityTypes as $type)
                        <option value="{{ $type }}" {{ old('activity_type', $schedule->activity_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Nama Pelatih <span class="text-[#c5221f]">*</span></label>
                <input type="text" name="coach_name" value="{{ old('coach_name', $schedule->coach_name) }}" required maxlength="150" placeholder="Contoh: Coach Iwan" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-brand">Relasi Program Usia (Opsional)</label>
                <select name="age_program_id" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-3.5 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
                    <option value="">-- Gabungan / Tanpa Relasi Spesifik --</option>
                    @foreach ($programs as $prog)
                        <option value="{{ $prog->id }}" {{ (int) old('age_program_id', $schedule->age_program_id) === $prog->id ? 'selected' : '' }}>{{ $prog->title }} ({{ $prog->age_range }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="min-h-12 w-full cursor-pointer rounded-xl bg-gold py-3.5 font-oswald text-sm font-bold uppercase tracking-wider text-brand transition hover:brightness-95">
            {{ $isEdit ? 'Simpan Perubahan Jadwal' : 'Simpan Jadwal Latihan' }}
        </button>
    </form>
@endsection

