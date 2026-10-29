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

            <form method="POST" action="{{ route('dashboard.profile.update') }}" class="mt-5 space-y-4">
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

        {{-- Card 2: Ubah Kata Sandi --}}
        <div class="rounded-2xl border border-brand/10 bg-white p-4 shadow-xs sm:p-6 lg:col-span-6">
            <div class="border-b border-brand/10 pb-4">
                <h2 class="font-oswald text-lg font-bold uppercase tracking-wide text-brand">Ubah Kata Sandi</h2>
                <p class="mt-0.5 text-xs text-navy-900/55">Masukkan kata sandi saat ini untuk memverifikasi perubahan password baru.</p>
            </div>

            <form method="POST" action="{{ route('dashboard.profile.password') }}" class="mt-5 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand">Kata Sandi Saat Ini <span class="text-[#c5221f]">*</span></label>
                    <input type="password" name="current_password" required autocomplete="current-password" placeholder="Masukkan kata sandi lama Anda" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand">Kata Sandi Baru <span class="text-[#c5221f]">*</span></label>
                    <input type="password" name="password" required minlength="8" autocomplete="new-password" placeholder="Minimal 8 karakter" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand">Konfirmasi Kata Sandi Baru <span class="text-[#c5221f]">*</span></label>
                    <input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder="Ketik ulang kata sandi baru" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
                </div>

                <div class="pt-2">
                    <button type="submit" class="min-h-11 w-full cursor-pointer rounded-xl bg-brand px-6 py-2.5 font-oswald text-xs font-bold uppercase tracking-wider text-white shadow-xs transition hover:bg-navy-900 sm:w-auto">
                        Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

