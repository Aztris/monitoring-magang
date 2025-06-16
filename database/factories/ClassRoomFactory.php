<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassRoom>
 */
class ClassRoomFactory extends Factory
{
    protected $model = ClassRoom::class;
    public function definition()
    {
        return [
            'name' => 'Kelas ' . $this->faker->unique()->word, // Menghasilkan nama kelas
            'grade_level' => $this->faker->randomElement([ '10', '11', '12']), // Menghasilkan tingkat kelas
            'department_id' => Department::factory(), // Mengaitkan dengan department yang ada
        ];
    }
}
