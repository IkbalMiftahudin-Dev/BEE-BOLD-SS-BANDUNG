<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ResolvesMediaUrl
{
    /**
     * Seeded records keep remote URLs, while uploads from the dashboard live under public/images/uploads or the public disk.
     */
    protected function resolveMediaUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (Str::startsWith($path, ['https://', 'http://'])) {
            return $path;
        }

        if (Str::startsWith($path, ['images/', '/images/'])) {
            return asset(ltrim($path, '/'));
        }

        return Storage::disk('public')->url($path);
    }
}
