<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'age_program_id',
    'day_of_week',
    'time_slot',
    'target_category',
    'activity_type',
    'coach_name',
])]
class TrainingSchedule extends Model
{
    /**
     * Display order, tab key, label and subtitle for each schedule day used by the homepage tabs.
     *
     * @var array<string, array{key: string, label: string, subtitle: string}>
     */
    public const DAYS = [
        'Senin' => [
            'key' => 'senin',
            'label' => 'Senin',
            'subtitle' => 'Latihan Gabungan — Grassroot (U7–U12) & Youth (U13–U20)',
        ],
        'Selasa' => [
            'key' => 'selasa',
            'label' => 'Selasa',
            'subtitle' => 'Khusus Youth (U13–U20)',
        ],
        'Rabu' => [
            'key' => 'rabu',
            'label' => 'Rabu',
            'subtitle' => 'Khusus Grassroot (U7–U12)',
        ],
        'Kamis' => [
            'key' => 'kamis',
            'label' => 'Kamis',
            'subtitle' => 'Latihan Gabungan — Grassroot (U7–U12) & Youth (U13–U20)',
        ],
        'Sabtu/Minggu' => [
            'key' => 'akhir-pekan',
            'label' => 'Sabtu / Minggu (Try Out Match / On Schedule)',
            'subtitle' => 'Jadwal Uji Coba (Try Out Match) / Turnamen — Lokasi Tentatif',
        ],
    ];

    public const ACTIVITY_TYPES = ['Grassroot', 'Youth', 'Try Out Match / Turnamen'];

    /**
     * @return BelongsTo<AgeProgram, $this>
     */
    public function ageProgram(): BelongsTo
    {
        return $this->belongsTo(AgeProgram::class, 'age_program_id');
    }
}

