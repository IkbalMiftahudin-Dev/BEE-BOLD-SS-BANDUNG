@extends('layouts.dashboard')

@section('title', 'Manajemen Program Usia')
@section('header', 'Program Usia')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-brand/10 bg-white p-5 shadow-xs">
        <div>
            <p class="text-sm font-semibold text-brand">Daftar Kelompok Program Usia</p>
            <p class="text-xs text-navy-900/55">Diurutkan berdasarkan kolom urutan tampil (`sort_order`).</p>
        </div>
        <a href="{{ route('dashboard.programs.create') }}" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-gold px-5 py-2.5 font-oswald text-xs font-bold uppercase tracking-wider text-brand transition hover:brightness-95">
            + Tambah Program Usia
        </a>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($programs as $program)
            <div class="flex flex-col overflow-hidden rounded-2xl border border-brand/10 bg-white shadow-xs">
                <div class="relative h-48 overflow-hidden bg-slate-100">
                    <img src="{{ $program->image_url }}" alt="{{ $program->title }}" class="size-full object-cover">
                    <div class="absolute left-3 top-3 flex flex-wrap gap-1.5">
                        <span class="rounded-full {{ $program->category_group === 'Grassroot' ? 'bg-gold text-brand' : 'bg-sky text-brand' }} px-3 py-1 font-oswald text-xs font-bold">{{ $program->age_range }}</span>
                        <span class="rounded-full bg-brand/90 px-2.5 py-1 font-oswald text-[10px] font-bold uppercase text-white">Urutan #{{ $program->sort_order }}</span>
                    </div>
                </div>
                <div class="flex flex-1 flex-col p-5">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-brand/60">{{ $program->category_group }}</span>
                        @if ($program->is_active)
                            <span class="rounded-full bg-[#e6f4ea] px-2.5 py-0.5 text-[10px] font-semibold text-[#137333]">Aktif</span>
                        @else
                            <span class="rounded-full bg-slate-200 px-2.5 py-0.5 text-[10px] font-semibold text-slate-700">Nonaktif</span>
                        @endif
                    </div>
                    <h3 class="mt-1 font-oswald text-lg font-bold uppercase text-navy-950">{{ $program->title }}</h3>
                    <p class="mt-1 text-xs font-semibold text-brand">{{ $program->coach_name }}</p>
                    @if (filled($program->description))
                        <p class="mt-1 flex-1 text-xs leading-relaxed text-navy-900/60">{{ $program->description }}</p>
                    @else
                        <p class="mt-1 flex-1 text-xs italic text-navy-900/40">Tanpa deskripsi kurikulum</p>
                    @endif
                    <div class="mt-4 flex items-center justify-end gap-2 border-t border-brand/10 pt-3.5">
                        <a href="{{ route('dashboard.programs.edit', $program) }}" class="rounded-lg bg-brand px-3 py-1.5 text-xs font-semibold text-white hover:bg-navy-900">Edit</a>
                        <form method="POST" action="{{ route('dashboard.programs.destroy', $program) }}" onsubmit="return confirm('Hapus program usia ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer rounded-lg border border-[#f87171]/40 bg-[#fce8e6] px-3 py-1.5 text-xs font-semibold text-[#c5221f] hover:bg-[#f87171]/25">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-2xl border border-brand/10 bg-white p-12 text-center text-sm text-navy-900/50">
                Belum ada program usia.
            </div>
        @endforelse
    </div>
@endsection

