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

                <div class="flex items-center justify-between pt-1">
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

</body>
</html>

