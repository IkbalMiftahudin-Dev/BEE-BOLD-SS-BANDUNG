<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'Usia berapa anak bisa mulai bergabung?',
                'Program kami terbuka untuk anak usia 5–20 tahun. Setelah mendaftar, peserta akan melalui proses klasifikasi terlebih dahulu. Peserta yang sudah berpengalaman akan bergabung dengan Tim 1 (Kelas Elite), sedangkan peserta baru akan masuk ke Tim 2 (Kelas Pengenalan).',
            ],
            [
                'Apakah anak harus sudah mahir bermain sepak bola?',
                'Tidak. Peserta baru akan masuk kelas pengenalan dan dibimbing dari dasar.',
            ],
            [
                'Di mana dan kapan latihan dilaksanakan?',
                'Latihan dilaksanakan di Lapangan UIN 2, Kota Bandung. Lihat jadwal lengkap di bagian Jadwal Mingguan.',
            ],
            [
                'Apa saja yang termasuk dalam biaya pendaftaran?',
                'Biaya pendaftaran mencakup administrasi dan perlengkapan awal peserta.',
            ],
            [
                'Apakah tersedia sesi trial sebelum mendaftar?',
                'Ya, hubungi kami via WhatsApp untuk menjadwalkan sesi trial.',
            ],
            [
                'Bagaimana sistem keamanan dan pendampingan pemain?',
                'Setiap sesi didampingi pelatih berlisensi dan tim pendamping di lapangan.',
            ],
        ];

        foreach ($faqs as $index => [$question, $answer]) {
            Faq::updateOrCreate(
                ['question' => $question],
                ['answer' => $answer, 'sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }
}

