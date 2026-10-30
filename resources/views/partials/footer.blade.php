@php
    $logoImg = asset('images/logo-bbss.png');
    $isHome = request()->is('/');
    $footerNav = [
        'Beranda' => $isHome ? '#beranda' : url('/'),
        'Program' => $isHome ? '#program' : url('/#program'),
        'Jadwal' => $isHome ? '#jadwal' : url('/#jadwal'),
        'Berita' => $isHome ? '#berita' : url('/#berita'),
        'Galeri' => $isHome ? '#galeri' : url('/#galeri'),
        'Daftar' => $isHome ? '#daftar' : url('/#daftar'),
    ];
@endphp
<footer class="relative overflow-hidden bg-brand px-6 pb-8 pt-16">
    <div class="relative mx-auto max-w-[1280px]">
        <span aria-hidden="true" class="footer-watermark hidden md:block">BEE</span>
        <div class="relative grid gap-12 md:grid-cols-[1fr_auto_1fr]">
            <div class="max-w-xs">
                <a href="{{ $isHome ? '#beranda' : url('/') }}" class="flex items-center gap-3">
                    <img src="{{ $logoImg }}" alt="Logo Bee Bold Soccer School" class="size-11 rounded-full object-contain shadow-[0_2px_12px_rgba(255,204,0,.35)]">
                    <span>
                        <span class="block font-oswald text-lg font-bold leading-none tracking-wide">BEE BOLD</span>
                        <span class="block text-[10px] tracking-[1px] text-sky">SOCCER SCHOOL</span>
                    </span>
                </a>
                <p class="pt-4 text-sm leading-relaxed text-white/50">Membina bakat, mengasah kemampuan, dan membentuk karakter pemain muda Bandung.</p>
                <div class="pt-5">
                    <a href="https://www.instagram.com/bee_bold_soccerschool" target="_blank" rel="noopener" aria-label="Instagram Bee Bold Soccer School" class="flex size-11 items-center justify-center rounded-xl bg-white/10 transition hover:bg-white/20">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="white" fill-opacity="0.6"><path d="M8 1.44c2.14 0 2.39.01 3.23.05 2.17.1 3.18 1.13 3.28 3.28.04.84.05 1.1.05 3.23s-.01 2.39-.05 3.23c-.1 2.15-1.11 3.18-3.28 3.28-.84.04-1.1.05-3.23.05s-2.39-.01-3.23-.05C2.59 14.41 1.59 13.38 1.49 11.23 1.45 10.39 1.44 10.14 1.44 8s.01-2.39.05-3.23C1.59 2.62 2.6 1.59 4.77 1.49 5.61 1.45 5.86 1.44 8 1.44ZM8 0C5.83 0 5.56.01 4.7.05 1.8.18.18 1.79.05 4.7.01 5.56 0 5.83 0 8s.01 2.45.05 3.3c.13 2.9 1.74 4.52 4.65 4.65.86.04 1.13.05 3.3.05s2.45-.01 3.3-.05c2.9-.13 4.52-1.74 4.65-4.65.04-.85.05-1.13.05-3.3s-.01-2.45-.05-3.3C15.82 1.8 14.21.18 11.3.05 10.45.01 10.17 0 8 0Zm0 3.89a4.11 4.11 0 1 0 0 8.22 4.11 4.11 0 0 0 0-8.22Zm0 6.78a2.67 2.67 0 1 1 0-5.34 2.67 2.67 0 0 1 0 5.34Zm4.27-7.9a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92Z"/></svg>
                    </a>
                </div>
            </div>
            <div class="md:w-80">
                <h4 class="font-oswald text-sm font-bold uppercase tracking-[1.4px]">Navigasi</h4>
                <ul class="space-y-3 pt-6 text-sm text-white/50">
                    @foreach ($footerNav as $label => $href)
                        <li><a href="{{ $href }}" class="transition hover:text-gold">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="font-oswald text-sm font-bold uppercase tracking-[1.4px]">Kontak</h4>
                <dl class="space-y-4 pt-6 text-sm">
                    <div><dt class="text-xs text-sky">Lapangan</dt><dd class="text-white/50">Lapangan UIN 2, Kota Bandung, Jawa Barat</dd></div>
                    <div><dt class="text-xs text-sky">WhatsApp</dt><dd class="text-white/50">081394555113</dd></div>
                    <div><dt class="text-xs text-sky">Email</dt><dd class="text-white/50">beeboldss.bdg@gmail.com</dd></div>
                </dl>
            </div>
        </div>

        <div class="mt-12 overflow-hidden rounded-2xl border border-white/10 bg-white/5">
            <div class="grid md:grid-cols-[320px_1fr]">
                <div class="flex flex-col justify-center p-8">
                    <p class="pb-2 text-xs font-medium uppercase tracking-[1.2px] text-sky">Home Ground</p>
                    <h4 class="font-oswald text-2xl font-bold uppercase">Temukan Kami</h4>
                    <p class="pt-2 text-sm leading-relaxed text-white/45">Lapangan UIN 2, Kota Bandung, Jawa Barat</p>
                    <a href="https://maps.google.com/?q=Lapangan+UIN+2+Bandung" target="_blank" rel="noopener" class="pt-5 text-sm font-medium text-gold">Buka di Google Maps ↗</a>
                </div>
                <iframe title="Peta Lapangan UIN 2" src="https://maps.google.com/maps?q=Lapangan%20UIN%20Bandung&output=embed" class="h-60 w-full opacity-80" loading="lazy"></iframe>
            </div>
        </div>

        <div class="mt-16 flex flex-wrap items-center justify-between gap-4 border-t border-white/10 pt-8 text-xs text-white/30">
            <p>© 2026 Bee Bold Soccer School. All rights reserved.</p>
            <div class="flex gap-6"><a href="#">Kebijakan Privasi</a><a href="#">Syarat &amp; Ketentuan</a></div>
        </div>
    </div>
</footer>
