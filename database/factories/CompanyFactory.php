<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition()
    {
        // Generate unique company email
        $email = $this->generateUniqueCompanyEmail();

        // Create user first
        $user = User::create([
            'nama' => $this->faker->company,
            'email' => $email,
            'password' => Hash::make('123'),
            'role' => 'company',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        return [
            'user_id' => $user->id,
            'nama' => $user->nama,
            'email' => $email,
            'logo' => $this->faker->optional()->imageUrl(200, 200, 'business'),
            'alamat' => $this->faker->address,
            'no_hp' => $this->faker->optional()->regexify('08[1-9][0-9]{8,10}'),
            'nama_pimpinan' => $this->faker->optional()->name,
            'bidang_usaha' => $this->faker->optional()->randomElement([
                'Teknologi Informasi', 'Manufaktur', 'Perdagangan',
                'Jasa Konsultan', 'Pendidikan', 'Kesehatan'
            ]),
            'deskripsi' => $this->faker->optional()->paragraph,
            'pic_nama' => $this->faker->optional()->name,
            'pic_phone' => $this->faker->optional()->phoneNumber,
            'pic_email' => $this->faker->optional()->safeEmail,
        ];
    }

    /**
     * Generate unique company email
     */
    protected function generateUniqueCompanyEmail(): string
    {
        $maxAttempts = 100;
        $attempt = 0;

        do {
            $email = 'company' . ($this->faker->unique()->numberBetween(1, 999999)) . '@example.com';
            $attempt++;

            if ($attempt >= $maxAttempts) {
                throw new \RuntimeException('Maximum attempts to generate unique email reached');
            }
        } while (User::where('email', $email)->exists());

        return $email;
    }
}
