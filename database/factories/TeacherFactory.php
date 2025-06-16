<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition()
    {
        // Generate unique NIP
        $nip = $this->generateUniqueNip();

        // Generate unique teacher email
        $email = $this->generateUniqueTeacherEmail();

        // Create user first
        $user = User::create([
            'nama' => $this->faker->name,
            'email' => $email,
            'password' => Hash::make('123'),
            'role' => 'teacher',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        return [
            'user_id' => $user->id,
            'nip' => $nip,
            'nama' => $user->nama, // Ensure consistency with user's name
            'jenkel' => $this->faker->randomElement(['L', 'P']),
            'tanggal_lahir' => $this->faker->optional()->date(),
            'no_hp' => $this->faker->optional()->regexify('08[1-9][0-9]{8,10}'), // Indonesian phone number format
            'alamat' => $this->faker->optional()->address,
            'mata_pelajaran' => $this->faker->optional()->randomElement([
                'Matematika', 'Bahasa Indonesia', 'Bahasa Inggris',
                'IPA', 'IPS', 'Pendidikan Agama', 'Olahraga'
            ]),
        ];
    }

    /**
     * Generate unique teacher email
     */
    protected function generateUniqueTeacherEmail(): string
    {
        $maxAttempts = 100;
        $attempt = 0;

        do {
            $email = 'teacher' . ($this->faker->unique()->numberBetween(1, 999999)) . '@example.com';
            $attempt++;

            if ($attempt >= $maxAttempts) {
                throw new \RuntimeException('Maximum attempts to generate unique email reached');
            }
        } while (User::where('email', $email)->exists());

        return $email;
    }

    /**
     * Generate unique NIP
     */
    protected function generateUniqueNip(): string
    {
        return $this->faker->unique()->numerify('NIP########');
    }
}
