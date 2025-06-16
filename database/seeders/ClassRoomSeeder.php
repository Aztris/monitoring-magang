<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Menggunakan factory untuk membuat 10 data class room
        ClassRoom::factory()->count(5)->create();
    }
}
