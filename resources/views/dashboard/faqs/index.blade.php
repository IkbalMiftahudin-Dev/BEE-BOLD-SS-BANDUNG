@extends('layouts.dashboard')

@section('title', 'Manajemen FAQ')
@section('header', 'Daftar Pertanyaan (FAQ)')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-brand/10 bg-white p-5 shadow-xs">
        <div>
            <p class="text-sm font-semibold text-brand">Daftar Pertanyaan Umum (FAQ)</p>
            <p class="text-xs text-navy-900/55">Diurutkan berdasarkan nomor urutan tampil di Beranda.</p>
        </div>
        <a href="{{ route('dashboard.faqs.create') }}" class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-gold px-5 py-2.5 font-oswald text-xs font-bold uppercase tracking-wider text-brand transition hover:brightness-95">
            + Tambah Pertanyaan FAQ
        </a>
    </div>

    <div class="mt-6 space-y-4">
        @forelse ($faqs as $faq)
            <div class="flex flex-col justify-between gap-4 rounded-2xl border border-brand/10 bg-white p-5 shadow-xs sm:flex-row sm:items-start">
                <div class="flex items-start gap-4">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-brand font-oswald text-xs font-bold text-gold">
                        {{ str_pad((string) $faq->sort_order, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-oswald text-base font-bold uppercase text-navy-950">{{ $faq->question }}</h3>
                            @if ($faq->is_active)
                                <span class="rounded-full bg-[#e6f4ea] px-2.5 py-0.5 text-[10px] font-semibold text-[#137333]">Aktif</span>
                            @else
                                <span class="rounded-full bg-slate-200 px-2.5 py-0.5 text-[10px] font-semibold text-slate-700">Nonaktif</span>
                            @endif
                        </div>
                        <p class="mt-1.5 text-xs leading-relaxed text-navy-900/65">{{ $faq->answer }}</p>
                    </div>
                </div>
                <div class="flex shrink-0 items-center gap-2 self-end sm:self-center">
                    <a href="{{ route('dashboard.faqs.edit', $faq) }}" class="rounded-lg bg-brand px-3 py-1.5 text-xs font-semibold text-white hover:bg-navy-900">Edit</a>
                    <form method="POST" action="{{ route('dashboard.faqs.destroy', $faq) }}" onsubmit="return confirm('Hapus item FAQ ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cursor-pointer rounded-lg border border-[#f87171]/40 bg-[#fce8e6] px-3 py-1.5 text-xs font-semibold text-[#c5221f] hover:bg-[#f87171]/25">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-brand/10 bg-white p-12 text-center text-sm text-navy-900/50">
                Belum ada data FAQ.
            </div>
        @endforelse
    </div>
@endsection

