@extends('layouts.dashboard')

@section('title', 'Biaya Pendaftaran')
@section('header', 'Biaya Pendaftaran')

@section('content')
    <div class="flex flex-col justify-between gap-3 rounded-2xl border border-brand/10 bg-white p-4 shadow-xs sm:flex-row sm:items-center sm:p-5">
        <div>
            <p class="text-sm font-semibold text-brand">Komponen Biaya Pendaftaran &amp; Iuran Bulanan</p>
            <p class="text-xs text-navy-900/55">Tampil pada kolom kiri section Pendaftaran (`#daftar`) di Beranda.</p>
        </div>
        <a href="{{ route('dashboard.fees.create') }}" class="inline-flex min-h-10 w-full items-center justify-center gap-2 rounded-xl bg-gold px-5 py-2.5 font-oswald text-xs font-bold uppercase tracking-wider text-brand transition hover:brightness-95 sm:w-auto">
            + Tambah Komponen Biaya
        </a>
    </div>

    {{-- Mobile Cards (< 768px) --}}
    <div class="mt-5 space-y-3 md:hidden">
        @forelse ($fees as $fee)
            <div class="rounded-2xl border border-brand/10 bg-white p-4 shadow-xs">
                <div class="flex items-center justify-between gap-2">
                    <span class="font-oswald text-xs font-bold text-brand">Urutan #{{ $fee->sort_order }}</span>
                    <div class="flex items-center gap-1.5">
                        <span class="rounded-full {{ $fee->fee_type === 'one_time' ? 'bg-gold/30 text-brand' : 'bg-sky/30 text-brand' }} px-2.5 py-0.5 text-[10px] font-bold">
                            {{ $fee->fee_type === 'one_time' ? 'Sekali Bayar' : 'Iuran Bulanan' }}
                        </span>
                        @if ($fee->is_active)
                            <span class="rounded-full bg-[#e6f4ea] px-2 py-0.5 text-[10px] font-semibold text-[#137333]">Aktif</span>
                        @else
                            <span class="rounded-full bg-slate-200 px-2 py-0.5 text-[10px] font-semibold text-slate-700">Nonaktif</span>
                        @endif
                    </div>
                </div>
                <p class="mt-2 text-sm font-semibold text-navy-950">{{ $fee->label }}</p>
                <div class="mt-2.5 flex items-center justify-between gap-2 border-t border-brand/10 pt-2.5">
                    <span class="font-oswald text-base font-bold text-brand">{{ $fee->amount_text }}</span>
                    <div class="inline-flex items-center gap-1.5">
                        <a href="{{ route('dashboard.fees.edit', $fee) }}" class="rounded-lg bg-brand px-3 py-1.5 text-xs font-semibold text-white hover:bg-navy-900">Edit</a>
                        <form method="POST" action="{{ route('dashboard.fees.destroy', $fee) }}" onsubmit="return confirm('Hapus komponen biaya ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer rounded-lg border border-[#f87171]/40 bg-[#fce8e6] px-3 py-1.5 text-xs font-semibold text-[#c5221f] hover:bg-[#f87171]/25">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-brand/10 bg-white p-8 text-center text-sm text-navy-900/50">
                Belum ada komponen biaya pendaftaran.
            </div>
        @endforelse
    </div>

    {{-- Desktop Table (>= 768px) --}}
    <div class="mt-6 hidden overflow-hidden rounded-2xl border border-brand/10 bg-white shadow-xs md:block">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] text-left text-sm">
                <thead class="border-b border-brand/10 bg-[#f4f7fb] text-xs font-bold uppercase tracking-wider text-brand">
                    <tr>
                        <th class="px-5 py-3.5">Urutan</th>
                        <th class="px-4 py-3.5">Label Komponen</th>
                        <th class="px-4 py-3.5">Nominal Tampilan</th>
                        <th class="px-4 py-3.5">Tipe</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand/10">
                    @forelse ($fees as $fee)
                        <tr class="hover:bg-[#f8fafd]">
                            <td class="px-5 py-4 font-oswald font-bold text-brand">#{{ $fee->sort_order }}</td>
                            <td class="px-4 py-4 font-semibold text-navy-950">{{ $fee->label }}</td>
                            <td class="px-4 py-4 font-bold text-brand">{{ $fee->amount_text }}</td>
                            <td class="px-4 py-4">
                                <span class="rounded-full {{ $fee->fee_type === 'one_time' ? 'bg-gold/30 text-brand' : 'bg-sky/30 text-brand' }} px-3 py-1 text-xs font-bold">
                                    {{ $fee->fee_type === 'one_time' ? 'Sekali Bayar' : 'Iuran Bulanan' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @if ($fee->is_active)
                                    <span class="rounded-full bg-[#e6f4ea] px-2.5 py-0.5 text-[11px] font-semibold text-[#137333]">Aktif</span>
                                @else
                                    <span class="rounded-full bg-slate-200 px-2.5 py-0.5 text-[11px] font-semibold text-slate-700">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('dashboard.fees.edit', $fee) }}" class="rounded-lg bg-brand px-3 py-1.5 text-xs font-semibold text-white hover:bg-navy-900">Edit</a>
                                    <form method="POST" action="{{ route('dashboard.fees.destroy', $fee) }}" onsubmit="return confirm('Hapus komponen biaya ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="cursor-pointer rounded-lg border border-[#f87171]/40 bg-[#fce8e6] px-3 py-1.5 text-xs font-semibold text-[#c5221f] hover:bg-[#f87171]/25">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-navy-900/50">Belum ada komponen biaya pendaftaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
