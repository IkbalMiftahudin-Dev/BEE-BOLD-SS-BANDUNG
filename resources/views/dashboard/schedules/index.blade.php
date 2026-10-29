@extends('layouts.dashboard')

@section('title', 'Manajemen Jadwal Latihan')
@section('header', 'Jadwal Latihan Mingguan')

@section('content')
    <div class="flex flex-col justify-between gap-3 rounded-2xl border border-brand/10 bg-white p-4 shadow-xs sm:flex-row sm:items-center sm:p-5">
        <div>
            <p class="text-sm font-semibold text-brand">Jadwal Latihan per Hari</p>
            <p class="text-xs text-navy-900/55">Dikelompokkan sesuai 5 tab hari pada Beranda.</p>
        </div>
        <a href="{{ route('dashboard.schedules.create') }}" class="inline-flex min-h-10 w-full items-center justify-center gap-2 rounded-xl bg-gold px-5 py-2.5 font-oswald text-xs font-bold uppercase tracking-wider text-brand transition hover:brightness-95 sm:w-auto">
            + Tambah Jadwal Latihan
        </a>
    </div>

    <div class="mt-5 space-y-5 sm:mt-6 sm:space-y-6">
        @foreach ($days as $day)
            @php $items = $schedulesByDay->get($day, collect()); @endphp
            <div class="overflow-hidden rounded-2xl border border-brand/10 bg-white shadow-xs">
                <div class="flex items-center justify-between border-b border-brand/10 bg-[#f4f7fb] px-4 py-3 sm:px-5 sm:py-3.5">
                    <h3 class="font-oswald text-base font-bold uppercase tracking-wide text-brand">{{ $day }}</h3>
                    <span class="rounded-full bg-brand/10 px-2.5 py-0.5 text-xs font-semibold text-brand">{{ $items->count() }} Sesi</span>
                </div>

                @if ($items->isNotEmpty())
                    {{-- Mobile Cards (< 768px) --}}
                    <div class="divide-y divide-brand/10 md:hidden">
                        @foreach ($items as $item)
                            <div class="p-4">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-oswald text-sm font-bold text-brand">{{ $item->time_slot }}</span>
                                    <span class="rounded-full {{ $item->activity_type === 'Grassroot' ? 'bg-gold/30 text-brand' : ($item->activity_type === 'Youth' ? 'bg-sky/30 text-brand' : 'bg-brand/10 text-brand') }} px-2.5 py-0.5 text-[11px] font-bold">{{ $item->activity_type }}</span>
                                </div>
                                <p class="mt-1 text-sm font-semibold text-navy-950">{{ $item->target_category }}</p>
                                <div class="mt-2.5 flex items-center justify-between gap-2">
                                    <span class="text-xs font-medium text-navy-900/65">{{ $item->coach_name }}</span>
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('dashboard.schedules.edit', $item) }}" class="rounded-lg bg-brand px-3 py-1.5 text-xs font-semibold text-white hover:bg-navy-900">Edit</a>
                                        <form method="POST" action="{{ route('dashboard.schedules.destroy', $item) }}" onsubmit="return confirm('Hapus baris jadwal latihan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="cursor-pointer rounded-lg border border-[#f87171]/40 bg-[#fce8e6] px-3 py-1.5 text-xs font-semibold text-[#c5221f] hover:bg-[#f87171]/25">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Desktop Table (>= 768px) --}}
                    <div class="hidden overflow-x-auto md:block">
                        <table class="w-full min-w-[640px] text-left text-sm">
                            <thead class="border-b border-brand/10 text-xs font-semibold uppercase text-navy-900/55">
                                <tr>
                                    <th class="px-5 py-3">Jam Latihan</th>
                                    <th class="px-4 py-3">Kelompok Target</th>
                                    <th class="px-4 py-3">Tipe Aktivitas</th>
                                    <th class="px-4 py-3">Pelatih</th>
                                    <th class="px-5 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-brand/10">
                                @foreach ($items as $item)
                                    <tr class="hover:bg-[#f8fafd]">
                                        <td class="px-5 py-3.5 font-oswald font-bold text-brand">{{ $item->time_slot }}</td>
                                        <td class="px-4 py-3.5 font-semibold text-navy-950">{{ $item->target_category }}</td>
                                        <td class="px-4 py-3.5">
                                            <span class="rounded-full {{ $item->activity_type === 'Grassroot' ? 'bg-gold/30 text-brand' : ($item->activity_type === 'Youth' ? 'bg-sky/30 text-brand' : 'bg-brand/10 text-brand') }} px-2.5 py-1 text-xs font-bold">{{ $item->activity_type }}</span>
                                        </td>
                                        <td class="px-4 py-3.5 text-xs font-medium text-navy-900/75">{{ $item->coach_name }}</td>
                                        <td class="px-5 py-3.5 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <a href="{{ route('dashboard.schedules.edit', $item) }}" class="rounded-lg bg-brand px-3 py-1.5 text-xs font-semibold text-white hover:bg-navy-900">Edit</a>
                                                <form method="POST" action="{{ route('dashboard.schedules.destroy', $item) }}" onsubmit="return confirm('Hapus baris jadwal latihan ini?');">
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
                @else
                    <p class="px-4 py-5 text-xs text-navy-900/50 sm:px-5 sm:py-6">Belum ada jadwal pada hari {{ $day }}.</p>
                @endif
            </div>
        @endforeach
    </div>
@endsection
