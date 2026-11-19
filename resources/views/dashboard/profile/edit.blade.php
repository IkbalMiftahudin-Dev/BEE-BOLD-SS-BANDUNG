@extends('layouts.dashboard')

@section('title', 'Pengaturan Profil')
@section('header', 'Pengaturan Profil & Keamanan')

@section('content')
    <div class="grid grid-cols-1 gap-5 sm:gap-6 lg:grid-cols-12">
        {{-- Card 1: Informasi Profil & Email --}}
        <div class="rounded-2xl border border-brand/10 bg-white p-4 shadow-xs sm:p-6 lg:col-span-6">
            <div class="flex items-center gap-3.5 border-b border-brand/10 pb-4">
                <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-brand font-oswald text-base font-bold text-gold">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <h2 class="font-oswald text-lg font-bold uppercase tracking-wide text-brand">Informasi Akun &amp; Email</h2>
                    <p class="truncate text-xs text-navy-900/55">Role Aktif: <span class="font-bold uppercase text-brand">{{ $user->role->value }}</span></p>
                </div>
            </div>

            <form method="POST" action="{{ route('dashboard.profile.update') }}" data-edit-form data-confirm-title="Konfirmasi Perubahan Profil" data-confirm-message="Apakah Anda yakin ingin menyimpan perubahan nama tampilan dan alamat email Admin?" class="mt-5 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand">Nama Tampilan <span class="text-[#c5221f]">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="100" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand">Alamat Email Login <span class="text-[#c5221f]">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="150" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
                    <p class="mt-1 text-[11px] text-navy-900/50">Email ini digunakan saat masuk di halaman `/login`.</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="min-h-11 w-full cursor-pointer rounded-xl bg-gold px-6 py-2.5 font-oswald text-xs font-bold uppercase tracking-wider text-brand shadow-xs transition hover:brightness-95 sm:w-auto">
                        Simpan Profil &amp; Email
                    </button>
                </div>
            </form>
        </div>

        {{-- Card 2: Proteksi Kata Sandi (Backup Code) --}}
        <div class="flex flex-col justify-between rounded-2xl border border-brand/10 bg-white p-4 shadow-xs sm:p-6 lg:col-span-6">
            <div>
                <div class="flex items-center gap-3.5 border-b border-brand/10 pb-4">
                    <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-[#eaf2fb] text-brand">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h2 class="font-oswald text-lg font-bold uppercase tracking-wide text-brand">Proteksi Kata Sandi (Backup Code)</h2>
                        <p class="mt-0.5 text-xs text-navy-900/55">Pengamanan akun Admin pasca serah-terima mitra.</p>
                    </div>
                </div>

                <div class="mt-5 space-y-3 text-xs leading-relaxed text-navy-900/75 sm:text-sm">
                    <p>
                        Fitur ubah kata sandi langsung di dalam dashboard sengaja <strong class="text-brand">dinonaktifkan</strong> untuk mencegah risiko pengelola lupa kata sandi baru dan terkunci dari sistem setelah proses serah-terima.
                    </p>
                    <div class="rounded-xl border border-brand/10 bg-[#f4f7fb] p-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-brand">Prosedur Reset Kata Sandi Admin</p>
                        <ol class="mt-2 list-decimal space-y-1.5 pl-4 text-xs leading-relaxed text-navy-900/75">
                            <li>Keluar dari sesi dashboard saat ini menuju halaman <span class="font-semibold text-brand">/login</span>.</li>
                            <li>Klik tombol <span class="font-semibold text-brand">Lupa Kata Sandi?</span> di bawah kolom kata sandi.</li>
                            <li>Masukkan <span class="font-semibold text-brand">Backup Code permanen</span> untuk memverifikasi otoritas Admin dan menetapkan kata sandi baru.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

