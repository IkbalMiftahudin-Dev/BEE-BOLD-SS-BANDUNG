<?php

namespace Database\Seeders;

use App\Models\AgeProgram;
use App\Models\TrainingSchedule;
use Illuminate\Database\Seeder;

class TrainingScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $programIds = AgeProgram::pluck('id', 'age_range');
        $slot = '16:00 – 18:00';

        $grassroot = [
            ['U-7 – U-9', 'Grassroot', 'Coach Iwan'],
            ['U-10 – U-12', 'Grassroot', 'Coach Asep'],
        ];
        $youth = [
            ['U-13', 'Youth', 'Coach Viali & Coach Barte'],
            ['U-14', 'Youth', 'Coach Vian'],
            ['U-15 – U-16', 'Youth', 'Coach Firman'],
            ['U-17 – U-20', 'Youth', 'Coach Viali'],
        ];

        $schedule = [
            'Senin' => array_merge($grassroot, $youth),
            'Selasa' => $youth,
            'Rabu' => $grassroot,
            'Kamis' => array_merge($grassroot, $youth),
        ];

        foreach ($schedule as $day => $rows) {
            foreach ($rows as [$ageRange, $activity, $coach]) {
                TrainingSchedule::updateOrCreate(
                    ['day_of_week' => $day, 'target_category' => $ageRange],
                    [
                        'age_program_id' => $programIds[$ageRange] ?? null,
                        'time_slot' => $slot,
                        'activity_type' => $activity,
                        'coach_name' => $coach,
                    ],
                );
            }
        }

        $weekend = [
            ['Grassroot (U-7 – U-12)', 'Coach Iwan & Coach Asep'],
            ['Youth (U-13 – U-20)', 'Coach Viali, Coach Barte, Coach Vian & Coach Firman'],
        ];

        foreach ($weekend as [$target, $coach]) {
            TrainingSchedule::updateOrCreate(
                ['day_of_week' => 'Sabtu/Minggu', 'target_category' => $target],
                [
                    'age_program_id' => null,
                    'time_slot' => 'On Schedule',
                    'activity_type' => 'Try Out Match / Turnamen',
                    'coach_name' => $coach,
                ],
            );
        }
    }
}

