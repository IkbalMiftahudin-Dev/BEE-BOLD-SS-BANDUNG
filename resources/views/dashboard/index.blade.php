<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Pengelola - Bee Bold Soccer School</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bbss.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Oswald:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-navy-950 font-inter text-white antialiased">
    <header class="border-b border-white/10 bg-[#001433]/80 px-6 py-4 backdrop-blur-xl">
        <div class="mx-auto flex max-w-[1280px] flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-bbss.png') }}" alt="Logo Bee Bold" class="size-10 rounded-full object-contain">
                <div>
                    <p class="font-oswald text-base font-bold uppercase tracking-wide">Dashboard Pengelola</p>
                    <p class="text-xs text-sky">Login sebagai: {{ auth()->user()->name }} ({{ auth()->user()->role->label() }})</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="rounded-lg border border-white/15 bg-white/5 px-4 py-2 text-xs font-medium text-white/80 transition hover:text-white">Lihat Beranda</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="cursor-pointer rounded-lg bg-[#f87171]/20 px-4 py-2 text-xs font-semibold text-[#fca5a5] transition hover:bg-[#f87171]/30">Keluar</button>
                </form>
            </div>
        </div>
    </header>
    <main class="mx-auto max-w-[1280px] px-6 py-12">
        <div class="rounded-2xl border border-white/10 bg-white/5 p-8">
            <p class="text-xs font-medium uppercase tracking-[1.2px] text-gold">— Status Autentikasi Aktif</p>
            <h1 class="mt-2 font-oswald text-3xl font-bold uppercase">Selamat Datang, {{ auth()->user()->name }}</h1>
            <p class="mt-2 text-sm text-white/60">Otorisasi role <span class="font-semibold text-sky">{{ auth()->user()->role->label() }}</span> terverifikasi melalui <code class="rounded bg-white/10 px-1.5 py-0.5 text-xs">RoleMiddleware</code>.</p>
        </div>
    </main>
</body>
</html>

