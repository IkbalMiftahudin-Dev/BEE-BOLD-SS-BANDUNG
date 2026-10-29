<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database. Every seeder is idempotent and safe to re-run.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            NewsCategorySeeder::class,
            NewsSeeder::class,
            GallerySeeder::class,
            AgeProgramSeeder::class,
            TrainingScheduleSeeder::class,
            FaqSeeder::class,
            RegistrationFeeSeeder::class,
        ]);
    }
}
