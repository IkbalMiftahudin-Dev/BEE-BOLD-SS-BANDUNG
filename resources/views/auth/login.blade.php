<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Panel Pengelola - Bee Bold Soccer School</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bbss.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Oswald:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-navy-950 font-inter text-white antialiased">

<main class="relative flex min-h-screen flex-col justify-center overflow-hidden px-4 py-12 sm:px-6 lg:px-8">
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-[#061020] via-[#002b5c]/60 to-navy-950"></div>
    <div class="pointer-events-none absolute -top-32 left-1/2 size-[520px] -translate-x-1/2 rounded-full bg-brand/40 blur-[120px]"></div>

    <div class="relative mx-auto w-full max-w-md">
        <div class="mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-medium text-white/50 transition hover:text-gold">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 12.67 5.33 8 10 3.33" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Kembali ke Beranda
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-white/10 bg-[#071326]/90 p-6 shadow-2xl backdrop-blur-xl sm:p-8 md:p-10">
            <div class="flex flex-col items-center text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <img src="{{ asset('images/logo-bbss.png') }}" alt="Logo Bee Bold Soccer School" class="size-14 rounded-full object-contain shadow-[0_2px_16px_rgba(255,204,0,.35)]">
                </a>
                <p class="mt-4 text-[11px] font-medium uppercase tracking-[1.4px] text-sky">— Portal Internal</p>
                <h1 class="mt-1 font-oswald text-3xl font-bold uppercase tracking-wide text-white sm:text-4xl">
                    Masuk <span class="text-gold">Pengelola</span>
                </h1>
                <p class="mt-2 text-xs leading-relaxed text-white/50 sm:text-sm">
                    Khusus Admin &amp; Editor Bee Bold Soccer School Bandung
                </p>
            </div>

            @if (session('status'))
                <div role="status" class="mt-6 rounded-xl border border-[#4ade80]/30 bg-[#4ade80]/10 px-4 py-3 text-xs leading-relaxed text-[#86efac]">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div role="alert" class="mt-6 rounded-xl border border-[#f87171]/30 bg-[#f87171]/10 px-4 py-3 text-xs leading-relaxed text-[#fca5a5]">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="mt-6 space-y-5" novalidate>
                @csrf

                <div>
                    <label for="email" class="block text-xs font-medium text-white/70">Alamat Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="admin@beeboldss.local"
                        class="mt-1.5 min-h-[46px] w-full rounded-xl border border-white/15 bg-white/5 px-4 py-3 text-sm text-white outline-none transition placeholder:text-white/25 focus:border-gold"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-medium text-white/70">Kata Sandi</label>
                    <div class="relative mt-1.5">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            data-password-input
                            class="min-h-[46px] w-full rounded-xl border border-white/15 bg-white/5 py-3 pl-4 pr-11 text-sm text-white outline-none transition placeholder:text-white/25 focus:border-gold"
                        >
                        <button
                            type="button"
                            data-password-toggle
                            aria-label="Tampilkan kata sandi"
                            class="absolute right-2 top-1/2 flex size-9 -translate-y-1/2 cursor-pointer items-center justify-center rounded-lg text-white/40 transition hover:text-white"
                        >
                            <svg data-eye-open width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg data-eye-closed class="hidden" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                <line x1="2" x2="22" y1="2" y2="22"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
                    <label class="inline-flex cursor-pointer items-center gap-2.5 text-xs text-white/60 select-none">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            {{ old('remember') ? 'checked' : '' }}
                            class="size-4 rounded border-white/20 bg-white/5 text-gold focus:ring-0"
                        >
                        <span>Ingat sesi saya</span>
                    </label>

                    <button
                        type="button"
                        data-open-recovery-modal
                        class="cursor-pointer text-xs font-semibold text-gold transition hover:text-sky hover:underline"
                    >
                        Lupa Kata Sandi?
                    </button>
                </div>

                <button
                    type="submit"
                    class="min-h-[48px] w-full cursor-pointer rounded-xl bg-gold py-3.5 font-oswald text-sm font-bold uppercase tracking-wider text-brand transition hover:brightness-105"
                >
                    Masuk ke Dashboard
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-white/30">
            &copy; {{ date('Y') }} Bee Bold Soccer School Bandung. Hak cipta dilindungi.
        </p>
    </div>
</main>

@php
    $showVerifyModal = session('recovery_step') === 'verify' || $errors->recovery->any();
    $showResetModal = ($isRecoveryVerified ?? false) && (session('recovery_step') === 'reset' || $errors->resetPassword->any());
@endphp

{{-- Modal 1: Verifikasi Permanent Backup Code Admin --}}
<div
    data-recovery-verify-modal
    role="dialog"
    aria-modal="true"
    aria-labelledby="recovery-verify-title"
    aria-hidden="{{ $showVerifyModal ? 'false' : 'true' }}"
    class="fixed inset-0 z-50 {{ $showVerifyModal ? 'flex' : 'hidden' }} items-center justify-center overflow-y-auto bg-navy-950/80 p-4 backdrop-blur-sm"
>
    <div class="relative w-full max-w-md rounded-2xl border border-white/10 bg-[#071326] p-6 shadow-2xl sm:p-8">
        <div class="flex items-start justify-between gap-4 border-b border-white/10 pb-4">
            <div>
                <p class="text-[11px] font-medium uppercase tracking-[1.4px] text-sky">Pemulihan Akses</p>
                <h2 id="recovery-verify-title" class="mt-1 font-oswald text-2xl font-bold uppercase tracking-wide text-white">
                    Verifikasi <span class="text-gold">Backup Code</span>
                </h2>
            </div>
            <button
                type="button"
                data-close-recovery-modal
                aria-label="Tutup modal verifikasi"
                class="flex size-9 shrink-0 cursor-pointer items-center justify-center rounded-lg border border-white/10 bg-white/5 text-white/60 transition hover:bg-white/10 hover:text-white"
            >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <p class="mt-4 text-xs leading-relaxed text-white/60 sm:text-sm">
            Masukkan <strong class="text-white">Backup Code permanen Admin</strong> yang tersimpan saat serah terima sistem untuk mengatur ulang kata sandi Admin.
        </p>

        @if ($errors->recovery->any())
            <div role="alert" class="mt-4 rounded-xl border border-[#f87171]/30 bg-[#f87171]/10 px-4 py-3 text-xs leading-relaxed text-[#fca5a5]">
                {{ $errors->recovery->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.recovery.verify') }}" class="mt-5 space-y-5">
            @csrf

            <div>
                <label for="backup_code" class="block text-xs font-medium text-white/70">Backup Code Permanen</label>
                <input
                    id="backup_code"
                    name="backup_code"
                    type="text"
                    required
                    maxlength="64"
                    autocomplete="off"
                    spellcheck="false"
                    placeholder="BBSS-XXXX-XXXX-XXXX"
                    class="mt-1.5 min-h-[46px] w-full rounded-xl border border-white/15 bg-white/5 px-4 py-3 font-mono text-sm uppercase tracking-wider text-white outline-none transition placeholder:text-white/25 focus:border-gold"
                >
            </div>

            <div class="flex flex-col-reverse gap-3 pt-1 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    data-close-recovery-modal
                    class="min-h-[44px] w-full cursor-pointer rounded-xl border border-white/15 bg-white/5 px-5 py-2.5 text-xs font-semibold text-white/70 transition hover:bg-white/10 hover:text-white sm:w-auto"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="min-h-[44px] w-full cursor-pointer rounded-xl bg-gold px-6 py-2.5 font-oswald text-sm font-bold uppercase tracking-wider text-brand transition hover:brightness-105 sm:w-auto"
                >
                    Verifikasi Backup Code
                </button>
            </div>
        </form>
    </div>
</div>

@if ($isRecoveryVerified ?? false)
    {{-- Modal 2: Atur Kata Sandi Baru Admin --}}
    <div
        data-recovery-reset-modal
        role="dialog"
        aria-modal="true"
        aria-labelledby="recovery-reset-title"
        aria-hidden="{{ $showResetModal ? 'false' : 'true' }}"
        class="fixed inset-0 z-50 {{ $showResetModal ? 'flex' : 'hidden' }} items-center justify-center overflow-y-auto bg-navy-950/80 p-4 backdrop-blur-sm"
    >
        <div class="relative w-full max-w-md rounded-2xl border border-white/10 bg-[#071326] p-6 shadow-2xl sm:p-8">
            <div class="flex items-start justify-between gap-4 border-b border-white/10 pb-4">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-[1.4px] text-[#86efac]">Backup Code Terverifikasi</p>
                    <h2 id="recovery-reset-title" class="mt-1 font-oswald text-2xl font-bold uppercase tracking-wide text-white">
                        Atur <span class="text-gold">Kata Sandi Baru</span>
                    </h2>
                </div>
                <button
                    type="button"
                    data-close-reset-modal
                    aria-label="Tutup modal kata sandi baru"
                    class="flex size-9 shrink-0 cursor-pointer items-center justify-center rounded-lg border border-white/10 bg-white/5 text-white/60 transition hover:bg-white/10 hover:text-white"
                >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <p class="mt-4 text-xs leading-relaxed text-white/60 sm:text-sm">
                Masukkan kata sandi baru untuk akun <strong class="text-white">Admin</strong>. Sesi verifikasi berlaku selama 5 menit.
            </p>

            @if ($errors->resetPassword->any())
                <div role="alert" class="mt-4 rounded-xl border border-[#f87171]/30 bg-[#f87171]/10 px-4 py-3 text-xs leading-relaxed text-[#fca5a5]">
                    {{ $errors->resetPassword->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.recovery.reset') }}" class="mt-5 space-y-5">
                @csrf

                <div>
                    <label for="new_admin_password" class="block text-xs font-medium text-white/70">Kata Sandi Baru</label>
                    <div class="relative mt-1.5">
                        <input
                            id="new_admin_password"
                            name="password"
                            type="password"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            data-password-input
                            class="min-h-[46px] w-full rounded-xl border border-white/15 bg-white/5 py-3 pl-4 pr-11 text-sm text-white outline-none transition placeholder:text-white/25 focus:border-gold"
                        >
                        <button
                            type="button"
                            data-password-toggle
                            aria-label="Tampilkan kata sandi"
                            class="absolute right-2 top-1/2 flex size-9 -translate-y-1/2 cursor-pointer items-center justify-center rounded-lg text-white/40 transition hover:text-white"
                        >
                            <svg data-eye-open width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg data-eye-closed class="hidden" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                <line x1="2" x2="22" y1="2" y2="22"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="new_admin_password_confirmation" class="block text-xs font-medium text-white/70">Konfirmasi Kata Sandi Baru</label>
                    <div class="relative mt-1.5">
                        <input
                            id="new_admin_password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            placeholder="Ketik ulang kata sandi baru"
                            data-password-input
                            class="min-h-[46px] w-full rounded-xl border border-white/15 bg-white/5 py-3 pl-4 pr-11 text-sm text-white outline-none transition placeholder:text-white/25 focus:border-gold"
                        >
                        <button
                            type="button"
                            data-password-toggle
                            aria-label="Tampilkan kata sandi"
                            class="absolute right-2 top-1/2 flex size-9 -translate-y-1/2 cursor-pointer items-center justify-center rounded-lg text-white/40 transition hover:text-white"
                        >
                            <svg data-eye-open width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg data-eye-closed class="hidden" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                <line x1="2" x2="22" y1="2" y2="22"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 pt-1 sm:flex-row sm:justify-end">
                    <button
                        type="button"
                        data-close-reset-modal
                        class="min-h-[44px] w-full cursor-pointer rounded-xl border border-white/15 bg-white/5 px-5 py-2.5 text-xs font-semibold text-white/70 transition hover:bg-white/10 hover:text-white sm:w-auto"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="min-h-[44px] w-full cursor-pointer rounded-xl bg-gold px-6 py-2.5 font-oswald text-sm font-bold uppercase tracking-wider text-brand transition hover:brightness-105 sm:w-auto"
                    >
                        Simpan Kata Sandi Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

</body>
</html>

