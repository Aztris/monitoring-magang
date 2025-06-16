<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing company data
        \App\Models\User::where('role', 'company')->delete();
        Company::query()->delete();

        // Create 20 companies
        Company::factory()
            ->count(9)
            ->create();
    }
}
