<?php

namespace Database\Seeders;

use App\Models\RegistrationFee;
use Illuminate\Database\Seeder;

class RegistrationFeeSeeder extends Seeder
{
    public function run(): void
    {
        $fees = [
            ['Biaya Pendaftaran', 'Rp 2.000.000 (sekali)', RegistrationFee::TYPE_ONE_TIME],
            ['Biaya Bulanan Grassroot (U7 - U12)', 'Mulai Rp 300.000 / bulan', RegistrationFee::TYPE_MONTHLY],
            ['Biaya Bulanan Youth (U13 - U20)', 'Mulai Rp 350.000 / bulan', RegistrationFee::TYPE_MONTHLY],
        ];

        foreach ($fees as $index => [$label, $amount, $type]) {
            RegistrationFee::updateOrCreate(
                ['label' => $label],
                ['amount_text' => $amount, 'fee_type' => $type, 'sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }
}

