<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        \App\Models\User::where('role', 'student')->delete();
        Student::query()->delete();

        // Create 50 students
        Student::factory()
            ->count(15)
            ->create();
    }
}
