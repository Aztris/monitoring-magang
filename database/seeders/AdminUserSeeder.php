<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah admin sudah ada
        if (User::where('email', 'admin')->doesntExist()) {
            // Buat user admin
            $adminUser = User::create([
                'nama' => 'Administrator',
                // 'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('123'), // Ganti dengan password yang aman
                'role' => 'admin'
            ]);

            // Buat data profil admin
            Admin::create([
                'user_id' => $adminUser->id,
                'jenkel' => 'L'
            ]);

            $this->command->info('Admin user created successfully!');
            $this->command->info('email: admin@example.com');
            $this->command->info('Password: 123');
        } else {
            $this->command->info('Admin user already exists!');
        }
    }
}
