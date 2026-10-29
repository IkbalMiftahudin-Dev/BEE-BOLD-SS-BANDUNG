<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ResolvesMediaUrl
{
    /**
     * Seeded records keep remote URLs, while uploads from the dashboard live on the public disk.
     */
    protected function resolveMediaUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (Str::startsWith($path, ['https://', 'http://'])) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}

