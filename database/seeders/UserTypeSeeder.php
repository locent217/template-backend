<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserType::create([
            'name' => 'Super Administrator',
            'code' => 'SUPER_ADMIN',
        ]);

        UserType::create([
            'name' => 'Administrator',
            'code' => 'ADMIN',
        ]);

        UserType::create([
            'name' => 'Manager',
            'code' => 'MANAGER',
        ]);

        UserType::create([
            'name' => 'Director',
            'code' => 'DIRECTOR',
        ]);

        UserType::create([
            'name' => 'Staff',
            'code' => 'STAFF',
        ]);

        UserType::create([
            'name' => 'Client',
            'code' => 'CLIENT',
        ]);
    }
}
