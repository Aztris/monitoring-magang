<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing teacher data
        \App\Models\User::where('role', 'teacher')->delete();
        Teacher::query()->delete();

        // Create 30 teachers
        Teacher::factory()
            ->count(7)
            ->create();
    }
}
