@props(['slug' => null])

@php
    // Badge colours live in the view layer (no badge_color column), keyed by category slug.
    $badgeColor = match ($slug) {
        'prestasi' => 'bg-gold',
        'rekrutmen' => 'bg-sky',
        'pelatihan' => 'bg-[#4ade80]',
        'fasilitas' => 'bg-[#f87171]',
        'komunitas' => 'bg-[#c084fc]',
        default => 'bg-white/80',
    };
@endphp

<span {{ $attributes->class([$badgeColor, 'rounded-full text-brand']) }}>{{ $slot }}</span>

