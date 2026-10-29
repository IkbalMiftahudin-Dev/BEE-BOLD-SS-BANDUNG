<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $uploaderId = User::where('role', UserRole::Admin)->orderBy('id')->value('id');

        // Placeholder title/caption; replace through the dashboard with the real event descriptions.
        $photos = [
            ['ca988373b55ac23f44db243d9d2d2ebb111eb5c0', 'landscape'],
            ['fc5cf8df347f8e58775363a38e8aaf6e3cb0fcac', 'portrait'],
            ['4d0d9174101d8929152612eeb9a76a6f27df19c8', 'landscape'],
            ['03b68dcbe8d9b2f4d3b8c779c8cfc2dc28cdd188', 'portrait'],
            ['59bac4d554a087fbc8409be6440def659cf73e8b', 'landscape'],
            ['b6ba53fbf5c21068cb8db1a906f99bbe1c27106d', 'landscape'],
            ['f7c3b6ccc825fde8dec5887c0925968df2e62ea1', 'portrait'],
            ['9e2ce44b10fc712c4d89673aac0f79aab40fd692', 'landscape'],
            ['c69cb6556e940d7b3657d9eed2ccfce9273f3487', 'landscape'],
            ['1248967f4012969993b859a3f21069f2c6d7f820', 'landscape'],
            ['0cb0f9c1cb7ec62c8c21e7241da988337af9cef9', 'landscape'],
            ['2c1eff11995749faac53a0b3f81321224abd1dbd', 'portrait'],
        ];

        foreach ($photos as $index => [$assetId, $ratio]) {
            $number = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
            $imagePath = "https://api.builder.io/api/v1/image/assets/TEMP/{$assetId}?width=1600";

            $gallery = Gallery::firstOrNew(['image_path' => $imagePath]);
            $gallery->fill([
                'title' => "Dokumentasi Bee Bold #{$number}",
                'caption' => 'Momen kegiatan Bee Bold Soccer School di Lapangan UIN 2, Kota Bandung.',
                'grid_ratio' => $ratio,
                'is_highlighted_on_home' => $index < 6,
            ]);
            $gallery->uploaded_by = $uploaderId;
            $gallery->save();
        }
    }
}

