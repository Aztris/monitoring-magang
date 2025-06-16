<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssessmentCriteria;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AssessmentCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel sebelum mengisi untuk menghindari duplikasi
        // DB::table('assessment_criterias')->truncate();

        $criteria = [
            [
                'nama' => 'Disiplin',
                'deskripsi' => 'Penilaian terhadap kedisiplinan siswa, termasuk ketepatan waktu dan ketaatan pada peraturan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tanggung Jawab',
                'deskripsi' => 'Penilaian terhadap kemampuan siswa dalam menyelesaikan tugas dan tanggung jawab yang diberikan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Inisiatif dan Kreativitas',
                'deskripsi' => 'Penilaian terhadap kemampuan siswa dalam memberikan ide-ide baru dan mengambil inisiatif dalam pekerjaan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kerjasama Tim',
                'deskripsi' => 'Penilaian terhadap kemampuan siswa untuk bekerja sama secara efektif dengan rekan kerja lain.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kemampuan Teknis',
                'deskripsi' => 'Penilaian terhadap penguasaan keterampilan teknis yang relevan dengan bidang magang.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Komunikasi',
                'deskripsi' => 'Penilaian terhadap kemampuan berkomunikasi secara lisan dan tulisan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Sikap dan Perilaku (Attitude)',
                'deskripsi' => 'Penilaian terhadap sikap profesional, etika kerja, dan perilaku siswa selama magang.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Masukkan data ke dalam tabel
        AssessmentCriteria::insert($criteria);
    }
}
