@extends('layouts.dashboard')

@section('title', $isEdit ? 'Edit Akun Editor' : 'Tambah Akun Editor')
@section('header', $isEdit ? 'Edit Akun Editor' : 'Tambah Akun Editor Baru')

@section('content')
    <div class="mb-4">
        <a href="{{ route('dashboard.editors.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand hover:underline">
            &larr; Kembali ke Daftar Editor
        </a>
    </div>

    <form method="POST"
          action="{{ $isEdit ? route('dashboard.editors.update', $editor) : route('dashboard.editors.store') }}"
          class="max-w-2xl space-y-5 rounded-2xl border border-brand/10 bg-white p-4 shadow-xs sm:p-6">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-brand">Nama Lengkap Editor <span class="text-[#c5221f]">*</span></label>
            <input type="text" name="name" value="{{ old('name', $editor->name) }}" required maxlength="100" placeholder="Contoh: Editor Media Bee Bold" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-brand">Alamat Email Login <span class="text-[#c5221f]">*</span></label>
            <input type="email" name="email" value="{{ old('email', $editor->email) }}" required maxlength="150" placeholder="Contoh: editor2@beebold.id" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
        </div>

        <div class="border-t border-brand/10 pt-4">
            <h3 class="font-oswald text-sm font-bold uppercase tracking-wide text-brand">
                {{ $isEdit ? 'Ubah Kata Sandi (Opsional)' : 'Kata Sandi Akun' }}
            </h3>
            <p class="mt-0.5 text-xs text-navy-900/55">
                {{ $isEdit ? 'Kosongkan kolom kata sandi jika tidak ingin mengubah password editor ini.' : 'Minimal 8 karakter.' }}
            </p>

            <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand">
                        Kata Sandi {{ $isEdit ? 'Baru' : '' }} @if (! $isEdit)<span class="text-[#c5221f]">*</span>@endif
                    </label>
                    <input type="password" name="password" {{ $isEdit ? '' : 'required' }} minlength="8" autocomplete="new-password" placeholder="Minimal 8 karakter" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand">
                        Konfirmasi Kata Sandi @if (! $isEdit)<span class="text-[#c5221f]">*</span>@endif
                    </label>
                    <input type="password" name="password_confirmation" {{ $isEdit ? '' : 'required' }} minlength="8" autocomplete="new-password" placeholder="Ulangi kata sandi" class="mt-1.5 min-h-11 w-full rounded-xl border border-brand/15 bg-[#f4f7fb] px-4 py-2.5 text-sm text-navy-950 outline-none focus:border-brand focus:bg-white">
                </div>
            </div>
        </div>

        <div class="flex flex-col-reverse justify-end gap-2.5 pt-2 sm:flex-row">
            <a href="{{ route('dashboard.editors.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-brand/15 bg-white px-5 py-2.5 text-xs font-semibold text-navy-900 hover:bg-[#f4f7fb]">
                Batal
            </a>
            <button type="submit" class="min-h-11 cursor-pointer rounded-xl bg-gold px-6 py-2.5 font-oswald text-xs font-bold uppercase tracking-wider text-brand shadow-xs transition hover:brightness-95">
                {{ $isEdit ? 'Simpan Perubahan Editor' : 'Buat Akun Editor' }}
            </button>
        </div>
    </form>
@endsection

