<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use Illuminate\Database\Seeder;

class NewsCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'prestasi' => 'Prestasi',
            'rekrutmen' => 'Rekrutmen',
            'pelatihan' => 'Pelatihan',
            'fasilitas' => 'Fasilitas',
            'komunitas' => 'Komunitas',
        ];

        foreach ($categories as $slug => $name) {
            NewsCategory::updateOrCreate(['slug' => $slug], ['name' => $name]);
        }
    }
}

