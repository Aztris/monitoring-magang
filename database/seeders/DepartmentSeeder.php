<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        // Menggunakan factory untuk membuat 10 data department
        Department::factory()->create();
    }
}
