<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Generate unique student email
        $email = $this->generateUniqueStudentEmail();

        // Create user first
        $user = User::create([
            'nama' => $this->faker->name,
            'email' => $email,
            'role' => 'student',
            'password' => Hash::make('123'),
            'email_verified_at' => now(),
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        return [
            'user_id' => $user->id,
            'nis' => $this->generateUniqueNis(),
            'nama' => $user->nama,
            'jenkel' => $this->faker->randomElement(['L', 'P']),
            'agama' => $this->faker->optional()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
            'class_room_id' => \App\Models\ClassRoom::inRandomOrder()->first()->id ?? 1,
            'department_id' => \App\Models\Department::inRandomOrder()->first()->id ?? 1,
            'no_hp' => substr($this->faker->phoneNumber, 0, 15),
            'alamat' => $this->faker->optional()->address,
            'tempat_lahir' => $this->faker->optional()->city,
            'tanggal_lahir' => $this->faker->optional()->date(),
            'nama_ayah' => $this->faker->optional()->name('male'),
            'nama_ibu' => $this->faker->optional()->name('female'),
            'no_hp_ortu' => substr($this->faker->phoneNumber, 0, 15),
        ];
    }

    /**
     * Generate unique student email
     */
    protected function generateUniqueStudentEmail(): string
    {
        $maxAttempts = 100;
        $attempt = 0;

        do {
            $email = 'student' . ($this->faker->unique()->numberBetween(1, 999999)) . '@example.com';
            $attempt++;

            if ($attempt >= $maxAttempts) {
                throw new \RuntimeException('Maximum attempts to generate unique email reached');
            }
        } while (User::where('email', $email)->exists());

        return $email;
    }

    /**
     * Generate unique NIS
     */
    protected function generateUniqueNis(): string
    {
        return $this->faker->unique()->numerify('##########');
    }
}
