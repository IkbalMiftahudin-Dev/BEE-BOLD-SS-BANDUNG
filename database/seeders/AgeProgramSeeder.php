<?php

namespace Database\Seeders;

use App\Models\AgeProgram;
use Illuminate\Database\Seeder;

class AgeProgramSeeder extends Seeder
{
    public function run(): void
    {
        $image = fn (string $id): string => "https://api.builder.io/api/v1/image/assets/TEMP/{$id}?width=616";
        $imageA = $image('99a8ab9d1ae26e0c798bad520b970b46c230f5fc');
        $imageB = $image('526a306a7a65c484546dcd4f6bfdfec24f447542');

        $programs = [
            ['Grassroot (U7 – U9)', 'Grassroot', 'U-7 – U-9', 'Coach Iwan', 'Pengenalan & teknik dasar', $imageA],
            ['Grassroot (U10 – U12)', 'Grassroot', 'U-10 – U-12', 'Coach Asep', 'Penguatan teknik & koordinasi', $imageB],
            ['Youth (U13)', 'Youth', 'U-13', 'Coach Viali & Coach Barte', 'Transisi taktik', $imageA],
            ['Youth (U14)', 'Youth', 'U-14', 'Coach Vian', 'Pemantapan taktik & fisik', $imageB],
            ['Youth (U15 – U16)', 'Youth', 'U-15 – U-16', 'Coach Firman', 'Persiapan kompetisi', $imageA],
            ['Youth (U17 – U20)', 'Youth', 'U-17 – U-20', 'Coach Viali', 'Pematangan performa elit', $imageB],
        ];

        foreach ($programs as $index => [$title, $group, $ageRange, $coach, $description, $imagePath]) {
            AgeProgram::updateOrCreate(
                ['age_range' => $ageRange],
                [
                    'title' => $title,
                    'category_group' => $group,
                    'coach_name' => $coach,
                    'description' => $description,
                    'image_path' => $imagePath,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}

