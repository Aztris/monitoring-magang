<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;
    public function definition()
    {
        $jurusan = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Teknik Elektro',
            'Manajemen',
            'Akuntansi',
            'Ilmu Komputer',
            'Teknik Sipil',
            'Desain Komunikasi Visual',
            'Pendidikan Guru Sekolah Dasar',
            'Psikologi',
        ];
        return [
            'nama' => $this->faker->unique()->randomElement($jurusan), // Menghasilkan nama jurusan dari array
            'kode' => $this->faker->unique()->lexify('??'), // Menghasilkan kode jurusan
            'deskripsi' => $this->faker->sentence(10, true), // Menghasilkan deskripsi dalam bahasa Indonesia
        ];
    }

}
