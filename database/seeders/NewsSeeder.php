<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $image = fn (string $id): string => "https://api.builder.io/api/v1/image/assets/TEMP/{$id}?width=1600";

        $categoryIds = NewsCategory::pluck('id', 'slug');
        $adminId = User::where('role', UserRole::Admin)->orderBy('id')->value('id');
        $editorId = User::where('role', UserRole::Editor)->orderBy('id')->value('id') ?? $adminId;

        $articles = [
            [
                'slug' => 'bee-bold-raih-juara-1-turnamen-u-14-tingkat-kota-bandung-2025',
                'category' => 'prestasi',
                'author_id' => $editorId,
                'published_at' => '2025-08-15 09:00:00',
                'is_featured' => true,
                'title' => 'Bee Bold Raih Juara 1 Turnamen U-14 Tingkat Kota Bandung 2025',
                'lead_paragraph' => 'Tim U-14 Bee Bold Soccer School berhasil mengalahkan 24 tim peserta dan meraih gelar juara dalam turnamen bergengsi di Kota Bandung.',
                'thumbnail_path' => $image('146bc9db22dcc38d1f7deabb1520308374e2a79e'),
                'paragraphs' => [
                    'Tim U-14 Bee Bold Soccer School menorehkan prestasi gemilang dengan meraih gelar Juara 1 pada Turnamen Sepak Bola Antar-Akademi Tingkat Kota Bandung 2025 yang digelar di Stadion Siliwangi, Sabtu (15/8).',
                    'Dalam laga final yang berlangsung dramatis, skuat asuhan Coach Vian berhasil mengalahkan tim tuan rumah Persab Junior dengan skor tipis 2-1. Gol kemenangan dicetak oleh Muhammad Farhan melalui tendangan bebas spektakuler di menit ke-87.',
                    '"Ini bukan sekadar trofi. Ini adalah bukti nyata kerja keras anak-anak selama enam bulan terakhir. Saya sangat bangga," ujar jajaran pelatih usai pertandingan.',
                    'Turnamen yang diikuti 24 tim ini merupakan yang paling bergengsi di tingkat kota. Bee Bold melewati babak grup, perempat final, dan semifinal tanpa sekalipun kebobolan — catatan clean sheet yang luar biasa.',
                    'Striker muda Rizki Pratama dinobatkan sebagai Top Scorer dengan torehan 9 gol sepanjang turnamen, sementara kiper Daffa Ardiansyah meraih penghargaan Best Goalkeeper.',
                    'Keberhasilan ini menjadi motivasi besar bagi akademi untuk melangkah ke kompetisi tingkat Jawa Barat yang dijadwalkan berlangsung Oktober mendatang.',
                ],
            ],
            [
                'slug' => 'open-recruitment-batch-2026-dibuka-mulai-september',
                'category' => 'rekrutmen',
                'author_id' => $adminId,
                'published_at' => '2025-08-10 09:00:00',
                'is_featured' => false,
                'title' => 'Open Recruitment Batch 2026 Dibuka Mulai September',
                'lead_paragraph' => 'Pendaftaran gelombang baru resmi dibuka untuk kelompok usia Grassroot (U-7 – U-12) dan Youth (U-13 – U-20).',
                'thumbnail_path' => $image('3b4e6d01dbe2882d9b1fcfda9f22ac159b7611c5'),
                'paragraphs' => [
                    'Bee Bold Soccer School resmi membuka pendaftaran peserta didik baru untuk musim latihan terbaru yang mencakup kategori Grassroot (U-7 hingga U-12) dan Youth (U-13 hingga U-20).',
                    'Setiap calon siswa yang mendaftar akan mengikuti sesi klasifikasi awal bersama tim pelatih berlisensi guna menentukan penempatan kelompok latihan yang sesuai dengan tahap perkembangan teknik dan fisik pemain.',
                    'Seluruh sesi latihan reguler dilaksanakan di Lapangan UIN 2, Kota Bandung pada pukul 16.00 – 18.00 WIB, dilengkapi dengan program uji coba (Try Out Match) berkala di akhir pekan.',
                    'Orang tua atau wali dapat melakukan pendaftaran langsung melalui formulir online di situs resmi Bee Bold Soccer School atau menghubungi tim administrasi via WhatsApp untuk menjadwalkan sesi trial.',
                ],
            ],
            [
                'slug' => 'workshop-taktik-bersama-mantan-pemain-timnas-indonesia',
                'category' => 'pelatihan',
                'author_id' => $editorId,
                'published_at' => '2025-08-05 09:00:00',
                'is_featured' => false,
                'title' => 'Workshop Taktik Bersama Mantan Pemain Timnas Indonesia',
                'lead_paragraph' => 'Pemain berpengalaman Eka Ramdani membagikan ilmu taktik langsung kepada para pemain akademi.',
                'thumbnail_path' => $image('3352b0bc053ce6060e52c0d1b1b4fe490b509e4d'),
                'paragraphs' => [
                    'Para siswa Bee Bold Soccer School mendapatkan kesempatan berharga mengikuti sesi klinik dan workshop taktik bersama Eka Ramdani di Lapangan UIN 2, Kota Bandung.',
                    'Materi pelatihan berfokus pada pengambilan keputusan cepat di lini tengah, visi bermain, serta transisi positif dari bertahan ke menyerang dalam situasi pertandingan nyata.',
                    'Selain praktik di lapangan, sesi ini juga diisi dengan diskusi interaktif mengenai kedisiplinan, mentalitas kompetitif, dan etika seorang pesepak bola profesional.',
                ],
            ],
            [
                'slug' => 'lapangan-uin-2-kota-bandung-kini-tersertifikasi-fifa-quality-pro',
                'category' => 'fasilitas',
                'author_id' => $adminId,
                'published_at' => '2025-07-28 09:00:00',
                'is_featured' => false,
                'title' => 'Peningkatan Kualitas Fasilitas Latihan di Lapangan UIN 2 Kota Bandung',
                'lead_paragraph' => 'Pembaruan fasilitas dan perlengkapan latihan menjamin kenyamanan serta keamanan seluruh pemain akademi.',
                'thumbnail_path' => $image('a1ef1117dca9a42f9727f994440d7983c1aa6841'),
                'paragraphs' => [
                    'Bee Bold Soccer School terus berkomitmen menghadirkan lingkungan latihan yang aman dan berkualitas tinggi bagi seluruh kelompok usia di Home Ground Lapangan UIN 2, Kota Bandung.',
                    'Perawatan permukaan lapangan serta penambahan alat bantu latihan modern dilakukan secara berkala untuk menunjang kurikulum teknik, koordinasi motorik, hingga simulasi taktik tim.',
                    'Dengan fasilitas yang terjaga, para pemain dapat berlatih secara optimal dan meminimalkan risiko cedera selama sesi latihan intensif.',
                ],
            ],
            [
                'slug' => 'pemain-bee-bold-dipanggil-seleksi-timnas-u-16-indonesia',
                'category' => 'prestasi',
                'author_id' => $editorId,
                'published_at' => '2025-07-20 09:00:00',
                'is_featured' => false,
                'title' => 'Pemain Bee Bold Dipanggil Seleksi Tingkat Nasional U-16',
                'lead_paragraph' => 'Tiga pemain muda berbakat dari kelompok Youth kami mendapat panggilan bersejarah untuk mengikuti seleksi tingkat nasional.',
                'thumbnail_path' => $image('e88ee710b8d9ed2b7bed4803b6c1a6bd8cecd2ad'),
                'paragraphs' => [
                    'Kebanggaan kembali diraih oleh keluarga besar Bee Bold Soccer School setelah tiga pemain binaan kategori Youth mendapat panggilan mengikuti seleksi tingkat nasional kelompok usia U-16.',
                    'Pencapaian ini merupakan buah dari konsistensi latihan reguler, evaluasi perkembangan berkala, serta jam terbang kompetisi yang ditempa bersama jajaran pelatih Bee Bold.',
                    'Manajemen dan seluruh tim pelatih memberikan dukungan penuh agar ketiga pemain dapat menampilkan performa terbaik serta mengharumkan nama Kota Bandung.',
                ],
            ],
            [
                'slug' => 'bee-bold-gelar-klinik-sepak-bola-gratis-untuk-200-anak-yatim',
                'category' => 'komunitas',
                'author_id' => $editorId,
                'published_at' => '2025-07-10 09:00:00',
                'is_featured' => false,
                'title' => 'Bee Bold Gelar Klinik Sepak Bola Gratis untuk 200 Anak Yatim',
                'lead_paragraph' => 'Program sosial Bee Bold mengadakan klinik sepak bola satu hari penuh untuk 200 anak yatim dari berbagai panti asuhan di Bandung.',
                'thumbnail_path' => $image('a1f1a1ca7428d451d67f3aa6bef0f8b0ad509930'),
                'paragraphs' => [
                    'Sebagai wujud kepedulian sosial dan komitmen membangun karakter melalui olahraga, Bee Bold Soccer School menyelenggarakan kegiatan coaching clinic gratis bagi 200 anak yatim di Kota Bandung.',
                    'Kegiatan ini dipandu langsung oleh para pelatih dan siswa senior Bee Bold dengan rangkaian fun games, pengenalan teknik dasar sepak bola, serta pembagian perlengkapan olahraga.',
                    'Melalui semangat "More Than Just Football", Bee Bold berharap sepak bola dapat terus menjadi sarana berbagi kebahagiaan dan inspirasi positif bagi masyarakat luas.',
                ],
            ],
        ];

        foreach ($articles as $article) {
            $news = News::firstOrNew(['slug' => $article['slug']]);
            $news->fill([
                'category_id' => $categoryIds[$article['category']],
                'title' => $article['title'],
                'lead_paragraph' => $article['lead_paragraph'],
                'content' => implode("\n\n", $article['paragraphs']),
                'thumbnail_path' => $article['thumbnail_path'],
                'is_featured' => $article['is_featured'],
                'is_published' => true,
                'published_at' => Carbon::parse($article['published_at']),
            ]);
            $news->author_id = $article['author_id'];
            $news->save();
        }
    }
}

